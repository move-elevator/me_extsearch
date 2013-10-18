<?php
/**
 * Class ClearIndexSearchCacheTypo61Service
 */
class ClearIndexSearchCacheTypo61Service {

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
        'cf_cache_pages',
        'cf_cache_pages_tags'
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
        $pHashRecords = tx_mesearch_utility_generalutility::getPhash($this->extConf['countOfDays']);
        $identifierList = array();
        $phashList = array();
        if (is_array($pHashRecords)) {
            foreach ($pHashRecords as $item) {
                $identifierList[] = tx_mesearch_utility_generalutility::getPageIdentifier($item['data_page_id']);
                $phashList[] = $item['phash'];
            }
			tx_mesearch_utility_generalutility::deleteRecordsByTableList($this->indexTables, 'phash', $phashList);
			tx_mesearch_utility_generalutility::deleteRecordsByTableList($this->pageTables, 'identifier', $identifierList);
            return TRUE;
        }
    }
}
?>