<?php

class HooksHandler {
	/**
	 * @var TYPO3\CMS\IndexedSearch\Controller\SearchFormController
	 */
	public $pObj;

	/**
	 * initialize_postProc
	 *
	 * Hook to rebuild the values of indexed_search
	 *
	 * @return void
	 */
	public function initialize_postProc() {
		if ($this->pObj->conf['show.']['specialSection.'] && $this->pObj->conf['show.']['specialSection.']['active']) {
			$temp = $this->pObj->optValues['sections'][0];
			// unset default
			unset($this->pObj->optValues['sections']);
			$this->pObj->optValues['sections'][0] = $temp;

			// copied and modified typo3 standard from indexed_search
			if ($this->pObj->conf['show.']['L1sections']) {
				$firstLevelMenu = $this->pObj->getMenu($this->pObj->wholeSiteIdList);
				foreach ($firstLevelMenu as $optionName => $mR) {
					if (!$mR['nav_hide']) {
						$this->pObj->optValues['sections']['rl1_' . $mR['uid']] = trim(
							$this->pObj->pi_getLL('opt_RL1') . ' ' . $mR['title']
						);
						if ($this->pObj->conf['show.']['L2sections']) {
							$secondLevelMenu = $this->pObj->getMenu($mR['uid']);
							foreach ($secondLevelMenu as $kk2 => $mR2) {
								if (!$mR2['nav_hide']) {
									$this->pObj->optValues['sections']['rl2_' . $mR2['uid']] = trim(
										$this->pObj->pi_getLL('opt_RL2') . ' ' . $mR2['title']
									);
								} else {
									unset($secondLevelMenu[$kk2]);
								}
							}
						}
					} else {
						unset($firstLevelMenu[$optionName]);
					}
				}
			}

			// add special option fields
			if ($this->pObj->conf['show.']['specialSection.']['pid']) {
				$pidArray = explode(',', $this->pObj->conf['show.']['specialSection.']['pid']);
				foreach($pidArray as $pid) {
					$level = count($GLOBALS['TSFE']->sys_page->getRootLine($pid)) - 1;
					$pageTitle = $this->getPageTitle($pid);
					if ($pageTitle != '') {
						$this->pObj->optValues['sections']['rl' . $level . '_' . $pid] = trim(
							$this->pObj->pi_getLL('opt_RL1') . ' ' . $pageTitle
						);
					}
				}
			}
		}

		return;
	}

	/**
	 * @param integer $uid
	 *
	 * @return string
	 */
	public function getPageTitle($uid){
		$output = array();

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('title', 'pages', 'uid=' . (int)$uid . $this->pObj->cObj->enableFields('pages'), '', 'sorting');
		$output = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		return array_pop($output);
	}
}
