<?php

namespace MoveElevator\MeExtsearch\Utility;

use \TYPO3\CMS\Extbase\Mvc\Exception\InvalidArgumentValueException;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ClearCacheUtility
 *
 * @package MoveElevator\MeExtsearch\Utility
 */
class ClearCacheUtility {

	/**
	 * @param int $pageId
	 * @return void
	 */
	static public function clearCacheByPage($pageId) {
		if ((int)$pageId === 0) {
			throw new InvalidArgumentValueException('Invalid page id!', 1428439087);
		}

		/** @var \TYPO3\CMS\Core\DataHandling\DataHandler $tce */
		$tce = GeneralUtility::makeInstance('TYPO3\CMS\Core\DataHandling\DataHandler');
		$tce->admin = 1;
		$tce->clear_cacheCmd((int)$pageId);
	}
}