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
        require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Service/ClearIndexSearchCacheTypoService.php';
        t3lib_div::makeInstance('ClearIndexSearchCacheTypoService', $extConf);

        return TRUE;
    }

}
?>