<?php

require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Utility/GeneralUtility.php';

/**
 * Class tx_meextsearch_tasks_delete
 */
class tx_meextsearch_tasks_delete extends tx_scheduler_Task {

    /**
     * @var array 
     */
    protected $extConf;

	/**
	 * @return bool
	 * @throws Exception if count of days not valid
	 */
	public function execute() {
        $extConf = tx_mesearch_utility_generalutility::getExtConfiguration();
        if (!is_array($extConf)) {
            throw new Exception("Invalid count of days! count of day must be number greater than or equal 3.");
        }
        $versionBranch = (int) substr($GLOBALS['TYPO_VERSION'], 0, 1).substr($GLOBALS['TYPO_VERSION'], 2, 1);
        switch (true) {
            case ($versionBranch <= 45):
                require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Service/ClearIndexSearchCache/ClearIndexSearchCacheTypo45Service.php';
                t3lib_div::makeInstance('ClearIndexSearchCacheTypo45Service', $extConf);
                break;
            case ($versionBranch <= 61):
                require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Service/ClearIndexSearchCache/ClearIndexSearchCacheTypo61Service.php';
                t3lib_div::makeInstance('ClearIndexSearchCacheTypo61Service', $extConf);
                break;
            default:
        }
        return TRUE;
    }

}
?>