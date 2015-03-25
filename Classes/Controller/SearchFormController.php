<?php

namespace MoveElevator\MeExtsearch\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Utility\MathUtility;
use \MoveElevator\MeExtsearch\Service\SettingsService;

/**
 * Class SearchFormController
 *
 * @package MoveElevator\MeExtsearch\Controller
 */
class SearchFormController extends \TYPO3\CMS\IndexedSearch\Controller\SearchFormController {

	/**
	 * @var \MoveElevator\MeExtsearch\Service\SettingsService
	 */
	protected $settingsService;

	/**
	 * @var array
	 */
	protected $links = array();

	/**
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');

		$this->settingsService = $objectManager->get('MoveElevator\MeExtsearch\Service\SettingsService');
	}

	/**
	 * Returns a results browser
	 * overwrite to use bootstrap for pagebrowser
	 *
	 * @param int $showResultCount Show result count
	 * @param string $addString String appended to "displaying results..." notice.
	 * @param string $addPart String appended after section "displaying results...
	 * @param int $freeIndexUid List of integers pointing to free indexing configurations to search.
	 *    -1 represents no filtering, 0 represents TYPO3 pages only,
	 *    any number above zero is a uid of an indexing configuration!
	 * @return string  HTML output
	 */
	public function pi_list_browseresults($showResultCount = 1, $addString = '', $addPart = '', $freeIndexUid = -1) {
		// Initializing variables:
		$pointer = intval($this->piVars['pointer']);
		$count = $this->internal['res_count'];
		$resultsAtaTime = MathUtility::forceIntegerInRange($this->internal['results_at_a_time'], 1, 1000);
		$maxPages = MathUtility::forceIntegerInRange($this->internal['maxPages'], 1, 100);
		$pageCount = ceil($count / $resultsAtaTime);
		$sTables = '';

		if ($pageCount > 1) {
			$this->addPrevLink($freeIndexUid, $pointer);
			$this->addPageLink($freeIndexUid, $pageCount, $pointer, $maxPages);
			$this->addNextLink($freeIndexUid, $pointer, $pageCount);

			if (is_array($this->links) && count($this->links) > 0) {
				$addPart .= $this->cObj->stdWrap(
					implode('', $this->links),
					$this->settingsService->getByPath('search_pagebrowser')
				);
				$this->links = array();
			}
		}

		$pR1 = $pointer * $resultsAtaTime + 1;
		$pR2 = $pointer * $resultsAtaTime + $resultsAtaTime;

		$label = $this->pi_getLL(
			'pi_list_browseresults_display',
			'Displaying results ###TAG_BEGIN###%s to %s###TAG_END### out of ###TAG_BEGIN###%s###TAG_END###'
		);
		$label = str_replace('###TAG_BEGIN###', '<strong>', $label);
		$label = str_replace('###TAG_END###', '</strong>', $label);
		$sTables = '<div' . $this->pi_classParam('browsebox') . '>';
		$sTables .= ($showResultCount ? '<p>' . sprintf(
					$label,
					$pR1,
					min(array($this->internal['res_count'], $pR2)),
					$this->internal['res_count']) . $addString . '</p>' : ''
			) . $addPart . '</div>';

		return $sTables;
	}

	/**
	 * @param int $freeIndexUid
	 * @param int $pageCount
	 * @param int $pointer
	 * @param int $maxPages
	 * @return void
	 */
	protected function addPageLink($freeIndexUid, $pageCount, $pointer, $maxPages) {
		for ($a = 0; $a < $pageCount; $a++) {
			$min = max(0, $pointer + 1 - ceil($maxPages / 2));
			$max = $min + $maxPages;
			if ($max > $pageCount) {
				$min = $min - ($max - $pageCount);
			}
			if ($a >= $min && $a < $max) {
				$link = $this->makePointerSelector_link(trim(($this->pi_getLL('pi_list_browseresults_page', 'Page', 1) . ' '
					. ($a + 1))), $a, $freeIndexUid);
				if ($a == $pointer) {
					$this->links[] = $this->cObj->stdWrap($link, $this->settingsService->getByPath('search_pagebrowser.CUR'));
				} else {
					$this->links[] = $this->cObj->stdWrap($link, $this->settingsService->getByPath('search_pagebrowser.NO'));
				}
			}
		}
	}

	/**
	 * @param int $freeIndexUid
	 * @param int $pointer
	 * @return void
	 */
	protected function addPrevLink($freeIndexUid, $pointer) {
		$llDirection = $this->pi_getLL('pi_list_browseresults_prev', '< Previous', 1);
		if ($pointer > 0) {
			$link = $this->makePointerSelector_link($llDirection, ($pointer - 1), $freeIndexUid);
			$this->links[] = $this->cObj->stdWrap($link, $this->settingsService->getByPath('search_pagebrowser.PREV'));
		} else {
			$alwaysDisplay = $this->settingsService->getByPath('search_pagebrowser.PREV.alwaysDisplay');
			if (intval($alwaysDisplay) === 1) {
				$this->links[] = $this->cObj->stdWrap(
					$llDirection,
					$this->settingsService->getByPath('search_pagebrowser.PREV.disabled')
				);
			}
		}
	}

	/**
	 * @param int $freeIndexUid
	 * @param int $pointer
	 * @param int $pageCount
	 * @return void
	 */
	protected function addNextLink($freeIndexUid, $pointer, $pageCount) {
		$llDirection = $this->pi_getLL('pi_list_browseresults_next', 'Next >', 1);
		if ($pointer + 1 < $pageCount) {
			$link = $this->makePointerSelector_link($llDirection, ($pointer + 1), $freeIndexUid);
			$this->links[] = $this->cObj->stdWrap($link, $this->settingsService->getByPath('search_pagebrowser.NEXT'));
		} else {
			$alwaysDisplay = $this->settingsService->getByPath('search_pagebrowser.NEXT.alwaysDisplay');
			if (intval($alwaysDisplay) === 1) {
				$this->links[] = $this->cObj->stdWrap(
					$llDirection,
					$this->settingsService->getByPath('search_pagebrowser.NEXT.disabled')
				);
			}
		}
	}

	/**
	 * Takes the array with resultrows as input and returns the result-HTML-code
	 * Takes the "group" var into account: Makes a "section" or "flat" display.
	 * Overwrite to fixed exact count bug so that only the desired number of
	 * records is displayed
	 *
	 * @param array  Result rows
	 * @param integer Pointer to which indexing configuration you want to search in. -1 means no
	 * filtering. 0 means only regular indexed content.
	 * @return string HTML
	 * @todo Define visibility
	 */
	public function compileResult($resultRows, $freeIndexUid = -1) {
		$content = '';
		// Transfer result rows to new variable, performing some mapping of sub-results etc.
		$newResultRows = array();
		foreach ($resultRows as $row) {
			$id = md5($row['phash_grouping']);
			if (is_array($newResultRows[$id])) {
				if (!$newResultRows[$id]['show_resume'] && $row['show_resume']) {
					// swapping:
					// Remove old
					$subrows = $newResultRows[$id]['_sub'];
					unset($newResultRows[$id]['_sub']);
					$subrows[] = $newResultRows[$id];
					// Insert new:
					$newResultRows[$id] = $row;
					$newResultRows[$id]['_sub'] = $subrows;
				} else {
					$newResultRows[$id]['_sub'][] = $row;
				}
			} else {
				$newResultRows[$id] = $row;
			}
		}
		$resultRows = $newResultRows;
		$this->resultSections = array();
		if ($freeIndexUid <= 0) {
			switch ($this->piVars['group']) {
				case 'sections':
					$rl2flag = substr($this->piVars['sections'], 0, 2) == 'rl';
					$sections = array();
					foreach ($resultRows as $row) {
						$id = $row['rl0'] . '-' . $row['rl1'] . ($rl2flag ? '-' . $row['rl2'] : '');
						$sections[$id][] = $row;
					}
					$this->resultSections = array();
					foreach ($sections as $id => $resultRows) {
						$rlParts = explode('-', $id);
						$theId = $rlParts[2] ? $rlParts[2] : ($rlParts[1] ? $rlParts[1] : $rlParts[0]);
						$theRLid = $rlParts[2] ? 'rl2_' . $rlParts[2] : ($rlParts[1] ? 'rl1_' . $rlParts[1] : '0');
						$sectionName = $this->getPathFromPageId($theId);
						if ($sectionName[0] == '/') {
							$sectionName = substr($sectionName, 1);
						}
						if (!trim($sectionName)) {
							$sectionTitleLinked = $this->pi_getLL('unnamedSection', '', 1) . ':';
						} else {
							$onclick = 'document.' . $this->prefixId . '[\'' . $this->prefixId . '[_sections]\'].value=\'' . $theRLid . '\';document.' . $this->prefixId . '.submit();return false;';
							$sectionTitleLinked = '<a href="#" onclick="' . htmlspecialchars($onclick) . '">' . htmlspecialchars($sectionName) . ':</a>';
						}
						$this->resultSections[$id] = array($sectionName, count($resultRows));
						// Add content header:
						$content .= $this->makeSectionHeader($id, $sectionTitleLinked, count($resultRows));
						// Render result rows:
						foreach ($resultRows as $row) {
							$content .= $this->printResultRow($row);
						}
					}
					break;
				default:
					// flat:
					$searchResultIndex = 0;
					foreach ($resultRows as $row) {
						$content .= $this->printResultRow($row);
						// add result record counter so that only the desired number is displayed
						$searchResultIndex++;
						if ($searchResultIndex >= $this->piVars['results']) {
							break;
						}
					}
					break;
			}
		} else {
			foreach ($resultRows as $row) {
				$content .= $this->printResultRow($row);
			}
		}
		return '<div' . $this->pi_classParam('res') . '>' . $content . '</div>';
	}

}