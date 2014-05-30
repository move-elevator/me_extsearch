<?php

namespace MoveElevator\MeExtsearch\Utility;

/**
 * Class IndexUtility
 *
 * @package MoveElevator\MeExtsearch\Utility
 */
class IndexUtility {

	/**
	 * @param string $table
	 * @param string $identifierColumn
	 * @param string $identifier
	 * @return bool
	 */
	static public function deleteRecordsByIdentifierColumn($table, $identifierColumn, $identifier) {
		return $GLOBALS['TYPO3_DB']->exec_DELETEquery($table, $identifierColumn . ' IN (\'' . implode("','", $identifier) . '\')');
	}

	/**
	 * @param array $tableList
	 * @param string $identifierColumn
	 * @param array $identifierList
	 * @return array
	 */
	static public function deleteRecordsByTableList(array $tableList, $identifierColumn, array $identifierList) {
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
	static public function getPhash($countOfDays, $tstamp = 0) {
		$dayCountHours = (int)$countOfDays * 24;
		if ($tstamp === 0) {
			$delTstmp = time() - (60 * 60 * $dayCountHours);
		} else {
			$delTstmp = $tstamp;
		}

		$pHashArray = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('phash, data_page_id', 'index_phash', 'tstamp < ' . $delTstmp);

		if (is_array($pHashArray) && count($pHashArray) > 0) {
			return $pHashArray;
		}
		return FALSE;
	}

	/**
	 * @param int $pageId
	 * @return string
	 */
	static public function getPageIdentifier($pageId) {
		$identifierRow = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('identifier', 'cf_cache_pages_tags', "tag = 'pageId_" . $pageId . "'");
		if (isset($identifierRow['identifier'])) {
			return $identifierRow['identifier'];
		}

		return NULL;
	}
}