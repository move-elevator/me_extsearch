<?php

namespace MoveElevator\MeExtsearch\Service;

use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Core\Resource\Exception\InvalidConfigurationException;
use \TYPO3\CMS\Core\SingletonInterface;
use \MoveElevator\MeExtsearch\Utility\IndexUtility;
use \MoveElevator\MeExtsearch\Utility\ClearCacheUtility;
use \MoveElevator\MeExtsearch\Utility\ExtensionSettingsUtility;

/**
 * Class IndexService
 *
 * @package MoveElevator\MeExtsearch\Service
 */
class IndexService implements SingletonInterface {

	/**
	 * Remove search index records which are older than x days
	 *
	 * @return bool
	 */
	public function clearOlderEntries() {
		/** @var \TYPO3\CMS\IndexedSearch\Indexer $indexer */
		$indexer = GeneralUtility::makeInstance('TYPO3\CMS\IndexedSearch\Indexer');
		$countOfDays = $this->getCountOfDays();
		$pageHashRecords = IndexUtility::getPhash($countOfDays);

		if (!is_array($pageHashRecords) || count($pageHashRecords) === 0) {
			return FALSE;
		}

		foreach ($pageHashRecords as $item) {
			$indexer->removeOldIndexedPages($item['phash']);
			if ((int)$item['data_page_id'] > 0) {
				ClearCacheUtility::clearCacheByPage((int)$item['data_page_id']);
			}
		}

		return TRUE;
	}

	/**
	 * Get age of removable records in days
	 *
	 * @return int
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidConfigurationException
	 */
	protected function getCountOfDays() {
		$countOfDays = ExtensionSettingsUtility::getSinglePropertyByName('countOfDays');
		if (!is_numeric($countOfDays) || intval($countOfDays) < 0) {
			throw new InvalidConfigurationException('Invalid count of days! count of day must be number greater than or equal 3.');
		}

		return intval($countOfDays);
	}
}