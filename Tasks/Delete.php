<?php

require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Utility/GeneralUtility.php';

class tx_meextsearch_tasks_delete extends tx_scheduler_Task {

    /**
     * @var array 
     */
    protected $extConf;

    public function execute() {
        $this->extConf = tx_mesearch_utility_generalutility::getExtConfiguration();

        if (!is_array($this->extConf)) {
            throw new Exception("Invalid count of days! count of day must be number greater than or equal 3.");
        }

        $pHashArray = $this->getPhash();
        if (is_array($pHashArray)) {
            $hashListArray = array();
            $pageListArray = array();
            foreach ($pHashArray as $item) {
                $hashListArray[] = $item['phash'];  
                $pageListArray[] = $item['data_page_id'];  
            }
            $hashListString = implode(",", $hashListArray);
            $pageListString = implode(",", $pageListArray);
            $this->deleteRecordPhash($hashListString);
            $this->deleteRecordGrlist($hashListString);
            $this->deleteRecordFulltext($hashListString);
            $this->deleteCacheRecordPages($pageListString);
            $this->deleteCacheRecordPagesection($pageListString);
        }
        return TRUE;
    }

    /**
     * get Records to delete
     * 
     * @return array
     */
    public function getPhash($tstamp = '') {
        $dayCountHoures = (int) $this->extConf['countOfDays'] * 24;
        if ($tstamp == '') {
            $delTstmp = time() - (60 * 60 * $dayCountHoures);
        } else {
            $delTstmp = $tstamp;
        }
        $pHashArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('phash, data_page_id', 'index_phash', 'crdate < ' . $delTstmp);
        if (is_array($pHashArray) and count($pHashArray)) {
            return $pHashArray;
        } else {
            return FALSE;
        }
    }

    /**
     * Delete Records from table "index_phash"
     * 
     * @param string $pHash
     * @return mixed
     */
    public function deleteRecordPhash($pHash) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('index_phash', 'phash IN (' . $pHash . ')');
    }

    /**
     * Delete Records from table "index_grlist"
     * 
     * @param string $pHash
     * @return mixed
     */
    public function deleteRecordGrlist($pHash) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('index_grlist', 'phash IN (' . $pHash . ')');
    }

    /**
     * Delete Records from table "index_fulltext"
     * 
     * @param string $pHash
     * @return mixed
     */
    public function deleteRecordFulltext($pHash) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('index_fulltext', 'phash IN (' . $pHash . ')');
    }

    /**
     * Delete Record from table "cache_pages"
     * 
     * @param string $pages
     * @return mixed
     */    
    public function deleteCacheRecordPages($pages) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('cache_pages', 'page_id IN (' . $pages . ')');
    }
    
    /**
     * Delete Records from table "cache_pagesection"
     * 
     * @param string $pages
     * @return mixed
     */
    public function deleteCacheRecordPagesection($pages) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery('cache_pagesection', 'page_id IN (' . $pages . ')');
    }
}

?>
