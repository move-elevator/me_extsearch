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
        if ((int) $configuration['countOfDays'] >= 3) {
            return $configuration;
        } else {
            return FALSE;
        }
    }

	/**
	 * @param string $table
	 * @param string $identifierColumn
	 * @param string $identifier
	 * @return bool
	 */
	public static function deleteRecordsByIdentifierColumn($table, $identifierColumn, $identifier) {
        return $GLOBALS['TYPO3_DB']->exec_DELETEquery($table, $identifierColumn . ' IN (' . self::implodeString(',',$identifier) . ')');
    }

	/**
	 * @param array $tableList
	 * @param string $identifierColumn
	 * @param array $identifierList
	 * @return array
	 */
	public static function deleteRecordsByTableList($tableList, $identifierColumn, $identifierList) {
		$resultList = array();
		foreach ($tableList as $table) {
			$resultList[] = self::deleteRecordsByIdentifierColumn($table, $identifierColumn, $identifierList);
		}
		return $resultList;
	}

    /**
     * @param string $glue
     * @param array $array
     * @return string|boolean
     */
    public static function implodeString($glue, $array) {
        if (!is_array($array)) {
            return FALSE;
        }
        $returnString = '';
        $i = 0;
        foreach ($array as $item) {
            if ($i > 0) {
                $returnString .= $glue;
            }
            $returnString .= '\'' . $item . '\'';
            $i++;
        }
        return $returnString;
    }

	/**
	 * @param int $countOfDays
	 * @param string $tstamp
	 * @return array|bool
	 */
	public static function getPhash($countOfDays, $tstamp = '') {
		$dayCountHoures = (int)$countOfDays * 24;
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
	 * @param integer $pageId
	 * @return string
	 */
	public static function getPageIdentifier($pageId) {
		$identifierRow = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('identifier', 'cf_cache_pages_tags', "tag = 'pageId_" . $pageId . "'");
		return $identifierRow['identifier'];
	}
}

?>