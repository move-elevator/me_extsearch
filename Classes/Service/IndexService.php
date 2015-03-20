<?php

namespace MoveElevator\MeExtsearch\Service;

use \TYPO3\CMS\Core\Resource\Exception\InvalidConfigurationException;
use \TYPO3\CMS\Core\SingletonInterface;
use \MoveElevator\MeExtsearch\Utility\IndexUtility;
use \MoveElevator\MeLibrary\Utility\ExtensionSettingsUtility;

/**
 * Class IndexService
 *
 * @package MoveElevator\MeExtsearch\Service
 */
class IndexService implements SingletonInterface {

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
	 * Remove search index records which are older than 3 days
	 *
	 * @return bool
	 */
	public function clearOlderEntries() {
		$countOfDays = $this->getCountOfDays();
		$pageHashRecords = IndexUtility::getPhash($countOfDays);
		$identifiers = array();
		$pageHashesDeleted = array();
		if (is_array($pageHashRecords)) {
			foreach ($pageHashRecords as $item) {
				$identifiers[] = IndexUtility::getPageIdentifier($item['data_page_id']);
				$pageHashesDeleted[] = $item['phash'];
			}
			IndexUtility::deleteRecordsByTableList($this->indexTables, 'phash', $pageHashesDeleted);
			IndexUtility::deleteRecordsByTableList($this->pageTables, 'identifier', $identifiers);

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Get age of removable records in days
	 *
	 * @return int
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidConfigurationException
	 */
	protected function getCountOfDays() {
		$countOfDays = ExtensionSettingsUtility::getSinglePropertyByName('me_extsearch', 'countOfDays');
		if (!is_numeric($countOfDays) || intval($countOfDays) < 3) {
			throw new InvalidConfigurationException('Invalid count of days! count of day must be number greater than or equal 3.');
		}

		return intval($countOfDays);
	}
}

?>