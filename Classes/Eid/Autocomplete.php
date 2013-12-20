<?php
namespace MoveElevator\MeExtsearch\Eid;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Utility\EidUtility;

if (!(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_FE)) {
	die ();
} else {
	$object = GeneralUtility::makeInstance('MoveElevator\MeExtsearch\Eid\Autocomplete');
	$object->main();
}

/**
 * Class Autocomplete
 *
 * @package MoveElevator\MeExtsearch\Eid
 */
class Autocomplete {

	const EXTKEY = 'meextsearch';

	/**
	 * @return void
	 */
	public function main() {
		if (isset($_GET['term'])) {
			$query = $this->createQuery($_GET['term'], $this->getLanguage());
			$words = $this->getList($query);
			echo json_encode($words);
		}
	}

	/**
	 * @param string $query
	 * @return array
	 */
	protected function getList($query) {
		$res = $GLOBALS['TYPO3_DB']->sql_query($query);
		$results = array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$results[] = array(
				'id' => $row['baseword'],
				'label' => $row['baseword'],
				'value' => $row['baseword'],
			);
		}

		return $results;
	}

	/**
	 * @param $search
	 * @return string
	 */
	protected function createQuery($search) {
		$words = GeneralUtility::trimExplode(' ', $search, 1);

		$where_array = array();
		foreach ($words as $word) {
			$where_array[] = 'baseword LIKE ' . $GLOBALS['TYPO3_DB']->fullQuoteStr('%' . $word . '%', 'index_words');
		}

		$from = 'index_words';
		$where = '(' . implode(' AND ', $where_array) . ')';

		// Join with index_phash table
		$from .= ',index_rel,index_phash';
		$where .=
			' AND index_words.wid = index_rel.wid ' .
			' AND index_rel.phash = index_phash.phash' .
			' AND index_phash.gr_list = "0,-1"' .
			' AND index_phash.sys_language_uid=' . $this->getLanguage();

		$query =
			'SELECT DISTINCT baseword' .
			' FROM ' . $from .
			' WHERE ' . $where .
			' ORDER BY LENGTH(baseword)' .
			' LIMIT ' . $this->getLimit();

		return $query;
	}

	protected function getLanguage() {
		$languageId = intval($_GET['language']);
		if ($languageId > 0) {
			return $languageId;
		}

		return 0;
	}

	protected function getLimit() {
		$limit = intval($_GET['limit']);
		if ($limit > 0) {
			return $limit;
		}

		return 7;
	}
}

?>