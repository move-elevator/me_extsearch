<?php

/**
 * Class HooksHandler
 */
class HooksHandler {
	/**
	 * @var TYPO3\CMS\IndexedSearch\Controller\SearchFormController
	 */
	public $pObj;

	/**
	 * initialize_postProc
	 *
	 * Hook to rebuild the values of indexed_search
	 * @return void
	 */
	public function initialize_postProc() {
		if (
			!$this->pObj->conf['show.']['addL3section.']
			|| !$this->pObj->conf['show.']['addL3section.']['active']
		) {
			return;
		}

		// unset default section array
		$temp = $this->pObj->optValues['sections'][0];
		unset($this->pObj->optValues['sections']);
		// reset option 'hole page'
		$this->pObj->optValues['sections'][0] = $temp;

		// copied and modified typo3 standard from indexed_search above hook

		// check for typoscript option show.L1sections
		if (!$this->pObj->conf['show.']['L1sections']) {
			return;
		}

		// get subpages from give page
		$firstLevelMenu = $this->pObj->getMenu($this->pObj->wholeSiteIdList);
		foreach ($firstLevelMenu as $optionName => $mR) {
			// check for nav_hide value
			if ($mR['nav_hide']) {
				unset($firstLevelMenu[$optionName]);
				continue;
			}

			$this->pObj->optValues['sections']['rl1_' . $mR['uid']] = trim(
				$this->pObj->pi_getLL('opt_RL1') . ' ' . $mR['title']
			);

			// check for typoscript option show.L2sections
			if (!$this->pObj->conf['show.']['L2sections']) {
				continue;
			}

			// get subpages from give page
			$secondLevelMenu = $this->pObj->getMenu($mR['uid']);
			foreach ($secondLevelMenu as $kk2 => $mR2) {
				// check for nav_hide value
				if ($mR2['nav_hide']) {
					unset($secondLevelMenu[$kk2]);
					continue;
				}

				$this->pObj->optValues['sections']['rl2_' . $mR2['uid']] = trim(
					$this->pObj->pi_getLL('opt_RL2') . ' ' . $mR2['title']
				);
			}
		}

		// add special option fields
		if ($this->pObj->conf['show.']['addL3section.']['pid']) {
			// read possible page ids
			$pidArray = explode(',', $this->pObj->conf['show.']['addL3section.']['pid']);
			foreach ($pidArray as $pid) {
				// get level of given page for option value
				$level = count($GLOBALS['TSFE']->sys_page->getRootLine($pid)) - 1;
				$pageTitle = $this->getPageTitle($pid);
				if (!$pageTitle || $pageTitle === '') {
					continue;
				}

				$this->pObj->optValues['sections']['tx_meextsearch_rl3_' . $pid] = trim(
					$this->pObj->pi_getLL('opt_RL1') . ' ' . $pageTitle
				);
			}
		}
	}

	/**
	 * @param integer $uid
	 * @return string|boolean
	 */
	public function getPageTitle($uid) {

		$output = $this->getPageTitleFromConfig($uid);
		if ($output !== '') {
			return $output;
		}

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'title',
			'pages',
			'uid=' . (int)$uid . $this->pObj->cObj->enableFields('pages'),
			'',
			'sorting'
		);

		$output = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		if (is_array($output)) {
			$output = array_pop($output);
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return $output;
	}

	/**
	 * @param integer $uid
	 * @return string
	 */
	public function getPageTitleFromConfig($uid) {
		$output = '';

		if (
			isset($this->pObj->conf['show.']['addL3section.']['language.'])
			&& isset($this->pObj->conf['show.']['addL3section.']['language.'][$uid])
			&& $this->pObj->conf['show.']['addL3section.']['language.'][$uid] != ''
		) {
			$output = $this->pObj->conf['show.']['addL3section.']['language.'][$uid];
		}

		if (\TYPO3\CMS\Core\Utility\GeneralUtility::isFirstPartOfStr($output, 'LLL:')) {
			$output = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($output, '');
		}

		return $output;
	}
}
