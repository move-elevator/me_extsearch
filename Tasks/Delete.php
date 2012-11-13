<?php

require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Utility/GeneralUtility.php';

class tx_meextsearch_tasks_delete extends tx_scheduler_Task {

    /**
     * @var array 
     */
    protected $extConf;
    protected $tabArray = array('index_phash', 'index_grlist', 'index_fulltext');

    public function execute() {
        $this->extConf = tx_mesearch_utility_generalutility::getExtConfiguration();
        $pHashArray = $this->getPhash();
        if (is_array($pHashArray)) {
            foreach ($pHashArray as $pHash) {
                $this->deleteRecord($pHash['phash']);
            }
        }
        return TRUE;
    }

    /**
     * get Records to delete
     * @return array
     */
    protected function getPhash() {
        $dayCountHoures = (int)$this->extConf['countOfDays'] * 24;

        $delTstmp = time() - (60 * 60 * $dayCountHoures);
        $pHashArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('phash', $this->extConf['tabs'][0], 'crdate < ' . $delTstmp);
        if (is_array($pHashArray) and count($pHashArray)) {
            return $pHashArray;
        } else {
            return FALSE;
        }
    }

    /**
     * Delte one Record
     * @param string $table
     * @param string $pHash
     * @return mixed
     */
    protected function deleteRecord($pHash) {
        foreach($tabArray as $table) {
            $GLOBALS['TYPO3_DB']->exec_DELETEquery($table, 'phash =' . $pHash);
        }
        
    }

}

?>
