<?php
/**
 * Class ClearIndexSearchCacheTypo45Service
 */
class ClearIndexSearchCacheTypo45Service {

    /**
     * @var array 
     */
    protected $extConf;

    /**
     * @var array
     */
    protected $indexTables = array(
        'index_phash',
        'index_grlist',
        'index_fulltext',
        'index_rel',
        'index_section'
    );

    /**
     * @var array 
     */
    protected $pageTables = array(
        'cache_pages',
        'cache_pagesection'
    );

    /**
     * @param array $extConf
     */
    public function __construct($extConf) {
        $this->extConf = $extConf;
        $this->execute();
    }

	/**
	 * @return bool
	 */
	public function execute() {
        $pHashRecords = tx_mesearch_utility_indexutility::getPhash($this->extConf['countOfDays']);
        $pageIdList = array();
        $phashList = array();
        if (is_array($pHashRecords)) {
            foreach ($pHashRecords as $item) {
                $pageIdList[] = $item['data_page_id'];
                $phashList[] = $item['phash'];
            }
			tx_mesearch_utility_generalutility::deleteRecordsByTableList($this->indexTables, 'phash', $phashList);
			tx_mesearch_utility_generalutility::deleteRecordsByTableList($this->pageTables, 'page_id', $pageIdList);
            return TRUE;
        }
    }
}
?>