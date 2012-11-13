<?php

require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Utility/GeneralUtility.php';

class tx_meextsearch_tasks_delete extends tx_scheduler_Task {

    /**
     * @var array 
     */
    protected $extConf;

    public function execute() {
        $this->extConf = tx_mesearch_utility_generalutility::getExtConfiguration();
        $pHashArray = $this->getPhash();
        if (is_array($pHashArray)) {
            foreach ($pHashArray as $pHash) {
                $this->deleteRecordPhash($pHash['phash']);
                $this->deleteRecordGrlist($pHash['phash']);
                $this->deleteRecordFulltext($pHash['phash']);
            }
        }
        return TRUE;
    }

    /**
     * get Records to delete
     * @return array
     */
    public function getPhash($tstamp = '') {
        $dayCountHoures = (int) $this->extConf['countOfDays'] * 24;
        if($tstamp == '') {
            $delTstmp = time() - (60 * 60 * $dayCountHoures);
        } else {
            $delTstmp = $tstamp;
        }
        $pHashArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('phash', 'index_phash', 'crdate < ' . $delTstmp);
        if (is_array($pHashArray) and count($pHashArray)) {
            return $pHashArray;
        } else {
            return FALSE;
        }
    }

    /**
     * Delte one Record from table "index_phash"
     * @param string $pHash
     * @return mixed
     */
    public function deleteRecordPhash($pHash) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('index_phash', 'phash =' . $pHash);
    }

    /**
     * Delte one Record from table "index_grlist"
     * @param string $pHash
     * @return mixed
     */
    public function deleteRecordGrlist($pHash) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('index_grlist', 'phash =' . $pHash);
    }

    /**
     * Delte one Record from table "index_fulltext"
     * @param string $pHash
     * @return mixed
     */
    public function deleteRecordFulltext($pHash) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('index_fulltext', 'phash =' . $pHash);
    }

}

?>
