<?php

namespace MoveElevator\MeExtsearch\Controller;

use MoveElevator\MeExtsearch\Service\SettingsService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Class SearchFormController
 *
 * @package MoveElevator\MeExtsearch\Controller
 */
class SearchFormController extends \TYPO3\CMS\IndexedSearch\Controller\SearchFormController {

	/**@var  \MoveElevator\MeExtsearch\Service\SettingsService */
	protected $settingsService;

	/** @var array */
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
		$resultsAtaTime = MathUtility::forceIntegerInRange($this->internal['resultsAtaTime'], 1, 1000);
		$maxPages = MathUtility::forceIntegerInRange($this->internal['maxPages'], 1, 100);
		$pageCount = ceil($count / $resultsAtaTime);
		$sTables = '';

		if ($pageCount > 1) {
			$this->addPrevLink($freeIndexUid, $pointer);
			$this->addPageLink($freeIndexUid, $pageCount, $pointer, $maxPages);
			$this->addNextLink($freeIndexUid, $pointer, $pageCount);

			if (is_array($this->links) && count($this->links) > 0) {
				$addPart .= $this->cObj->stdWrap(implode('', $this->links), $this->settingsService->getByPath('search_pagebrowser'));
				$this->links = array();
			}
		}

		$pR1 = $pointer * $resultsAtaTime + 1;
		$pR2 = $pointer * $resultsAtaTime + $resultsAtaTime;

		$label = $this->pi_getLL('pi_list_browseresults_display', 'Displaying results ###TAG_BEGIN###%s to %s###TAG_END### out of ###TAG_BEGIN###%s###TAG_END###');
		$label = str_replace('###TAG_BEGIN###', '<strong>', $label);
		$label = str_replace('###TAG_END###', '</strong>', $label);
		$sTables = '<div' . $this->pi_classParam('browsebox') . '>';
		$sTables .= ($showResultCount ? '<p>' . sprintf($label, $pR1, min(array($this->internal['res_count'], $pR2)), $this->internal['res_count']) . $addString . '</p>' : '') . $addPart . '</div>';
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
				$link = $this->makePointerSelector_link(trim(($this->pi_getLL('pi_list_browseresults_page', 'Page', 1) . ' ' . ($a + 1))), $a, $freeIndexUid);
				if ($a == $pointer) {
					$this->links[] = $this->cObj->stdWrap($link, $this->settingsService->getByPath('search_pagebrowser.CUR'));
				} else {
					$this->links[] = $this->cObj->stdWrap($link, $this->settingsService->getByPath('search_pagebrowser.NO'));
				}
			}
		}
	}

	/**
	 * @param $freeIndexUid
	 * @param $pointer
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
				$this->links[] = $this->cObj->stdWrap($llDirection, $this->settingsService->getByPath('search_pagebrowser.PREV.disabled'));
			}
		}
	}

	/**
	 * @param $freeIndexUid
	 * @param $pointer
	 * @param $pageCount
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
				$this->links[] = $this->cObj->stdWrap($llDirection, $this->settingsService->getByPath('search_pagebrowser.NEXT.disabled'));
			}
		}
	}
}

?>