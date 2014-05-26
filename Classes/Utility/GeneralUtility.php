<?php

/**
 * Class tx_mesearch_utility_generalutility
 */
class tx_mesearch_utility_generalutility {

	/**
	 * @return array|bool
	 */
	public static function getExtConfiguration() {
		$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['me_extsearch']);
		return self::valid($configuration);
	}

	/**
	 * @param array $configuration
	 * @return array|bool
	 */
	public static function valid(array $configuration) {
		if ((int)$configuration['countOfDays'] >= 3) {
			return $configuration;
		}
		return FALSE;
	}

	/**
	 * @param string $table
	 * @param string $identifierColumn
	 * @param string $identifier
	 * @return bool
	 */
	public static function deleteRecordsByIdentifierColumn($table, $identifierColumn, $identifier) {
		/** @var $TYPO3_DB t3lib_DB */
		return $TYPO3_DB->exec_DELETEquery($table, $identifierColumn . ' IN (' . implode("','", $identifier) . ')');
	}

	/**
	 * @param array $tableList
	 * @param string $identifierColumn
	 * @param array $identifierList
	 * @return array
	 */
	public static function deleteRecordsByTableList(array $tableList, $identifierColumn, array $identifierList) {
		$resultList = array();
		foreach ($tableList as $table) {
			$resultList[] = self::deleteRecordsByIdentifierColumn($table, $identifierColumn, $identifierList);
		}
		return $resultList;
	}

	/**
	 * @param int $countOfDays
	 * @param int $tstamp
	 * @return array|bool
	 */
	public static function getPhash($countOfDays, $tstamp = 0) {
		$dayCountHours = (int)$countOfDays * 24;
		if ($tstamp === 0) {
			$delTstmp = time() - (60 * 60 * $dayCountHours);
		} else {
			$delTstmp = $tstamp;
		}
		/** @var $TYPO3_DB t3lib_DB */
		$pHashArray = $TYPO3_DB->exec_SELECTgetRows('phash, data_page_id', 'index_phash', 'tstamp < ' . $delTstmp);

		if (is_array($pHashArray) && count($pHashArray) > 0) {
			return $pHashArray;
		}
		return FALSE;
	}

	/**
	 * @param int $pageId
	 * @return string
	 */
	public static function getPageIdentifier($pageId) {
		/** @var $TYPO3_DB t3lib_DB */
		$identifierRow = $TYPO3_DB->exec_SELECTgetSingleRow('identifier', 'cf_cache_pages_tags', "tag = 'pageId_" . $pageId . "'");
		if (isset($identifierRow['identifier'])) {
			return $identifierRow['identifier'];
		}
		return NULL;
	}
}