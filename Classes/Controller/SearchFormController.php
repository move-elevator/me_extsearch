<?php

namespace MoveElevator\MeExtsearch\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2001-2013 Kasper Skårhøj (kasperYYYY@typo3.com)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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

	public function initialize() {
		parent::initialize();

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');

		$this->settingsService = $objectManager->get('MoveElevator\MeExtsearch\Service\SettingsService');
	}

	/**
	 * Returns a results browser
	 *
	 * @param 	boolean		Show result count
	 * @param 	string		String appended to "displaying results..." notice.
	 * @param 	string		String appended after section "displaying results...
	 * @param 	string		List of integers pointing to free indexing configurations to search. -1 represents no filtering, 0 represents TYPO3 pages only, any number above zero is a uid of an indexing configuration!
	 * @return 	string		HTML output
	 */
	public function pi_list_browseresults($showResultCount = 1, $addString = '', $addPart = '', $freeIndexUid = -1) {
		// Initializing variables:
		$pointer = intval($this->piVars['pointer']);
		$count = $this->internal['res_count'];
		$results_at_a_time = MathUtility::forceIntegerInRange($this->internal['results_at_a_time'], 1, 1000);
		$maxPages = MathUtility::forceIntegerInRange($this->internal['maxPages'], 1, 100);
		$pageCount = ceil($count / $results_at_a_time);
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

		$pR1 = $pointer * $results_at_a_time + 1;
		$pR2 = $pointer * $results_at_a_time + $results_at_a_time;

		$label = $this->pi_getLL('pi_list_browseresults_display', 'Displaying results ###TAG_BEGIN###%s to %s###TAG_END### out of ###TAG_BEGIN###%s###TAG_END###');
		$label = str_replace('###TAG_BEGIN###', '<strong>', $label);
		$label = str_replace('###TAG_END###', '</strong>', $label);
		$sTables = '<div' . $this->pi_classParam('browsebox') . '>' . ($showResultCount ? '<p>' . sprintf($label, $pR1, min(array($this->internal['res_count'], $pR2)), $this->internal['res_count']) . $addString . '</p>' : '') . $addPart . '</div>';
		return $sTables;
	}

	/**
	 * @param integer $freeIndexUid
	 * @param integer $pageCount
	 * @param integer $pointer
	 * @param integer $maxPages
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
	 */
	protected function addPrevLink ($freeIndexUid, $pointer) {
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