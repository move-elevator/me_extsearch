<?php

namespace MoveElevator\MeExtsearch\Command;

use \TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Class IndexCommandController
 *
 * @package MoveElevator\MeExtsearch\Command
 */
class IndexCommandController extends CommandController {

	const CLEAR_SERVICE_NAME = 'MoveElevator\MeExtsearch\Service\IndexService';

	/**
	 * @return bool
	 */
	public function clearCommand() {
		/** @var \MoveElevator\MeExtsearch\Service\IndexService $indexService */
		$indexService = $this->objectManager->get(self::CLEAR_SERVICE_NAME);

		return $indexService->clearOlderEntries();
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
}