<?php

require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Utility/GeneralUtility.php';

class tx_meextsearch_tasks_delete extends tx_scheduler_Task {

    /**
     * @var array 
     */
    protected $extConf;

    public function __construct() {
        $this->extConf = tx_mesearch_utility_generalutility::getExtConfiguration();
    }

    public function execute() {

        // Query
        $query = '';
        $GLOBALS['TYPO3_DB']->sql_query($query);

        return $res;
    }

}

?>
