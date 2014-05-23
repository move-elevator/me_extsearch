<?php

namespace MoveElevator\MeExtsearch\Tests\Unit\Tasks;

use \TYPO3\CMS\Core\Tests\BaseTestCase;
use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class DeleteTest
 *
 * @package MoveElevator\MeExtsearch\Tests\Unit\Tasks
 */
class DeleteTest extends BaseTestCase {

	/**
	 * @var Tx_Phpunit_Framework
	 */
	protected $testingFramework;

	public function setUp() {
		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		$this->testingFramework = new \Tx_Phpunit_Framework('tx_meglossary', array('index', 'cache'));
	}

	public function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->object);
	}

	/**
	 * Testing delete record 'index_phash'
	 */
	public function testDeleteRecordsByIdentifierColumn() {
		$this->markTestSkipped('test me please');
	}
}
