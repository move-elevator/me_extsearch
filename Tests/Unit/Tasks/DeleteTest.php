<?php

require_once t3lib_extMgm::extPath('me_extsearch') . 'Classes/Utility/GeneralUtility.php';

class Tx_MeExtSearch_Tasks_DeleteTest extends Tx_Extbase_Tests_Unit_BaseTestCase {

    /**
     * @var tx_meextsearch_tasks_delete 
     */
    protected $object;

    /**
     * @var Tx_Phpunit_Framework
     */
    protected $testingFramework;

    public function setUp() {
        $this->testingFramework = new Tx_Phpunit_Framework('tx_meextsearch', array('index', 'index', 'index'));
        $this->object = $this->objectManager->get('tx_meextsearch_tasks_delete');
    }

    public function tearDown() {
        $this->testingFramework->cleanUp();
        unset($this->object);
    }

    /**
     * Testing delete record 'index_phash'
     */
    public function testDeleteRecordFromIndexPhashTable() {
        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010000, 'item_title' => 'my_test_phash'
        ));
        $result = $this->object->deleteRecordPhash(9010000);

        $this->assertEquals(TRUE, $result);
    }

    /**
     * Testing delete record 'index_grlist'
     */
    public function testDeleteRecordFromIndexGrlistTable() {
        $this->testingFramework->createRecord('index_grlist', array(
            'phash' => 9010001
        ));
        $result = $this->object->deleteRecordGrlist(9010001);
        $this->assertEquals(TRUE, $result);
    }

    /**
     * Testing delete 'index_fulltext'
     */
    public function testDeleteRecordFromIndexFulltextTable() {
        $this->testingFramework->createRecord('index_fulltext', array(
            'phash' => 9010002
        ));
        $result = $this->object->deleteRecordFulltext(9010002);
        $this->assertEquals(TRUE, $result);
    }

    /**
     * Testing get record from table index_phash
     */
    public function testGetPhashRecordFromTableIndexPhash() {
        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010003,
            'crdate' => 1351784588
        ));
        $result = $this->object->getPhash(1351784589);
        $this->assertEquals(TRUE, is_array($result));
    }
    
    /**
     * Testing valid count of days, count = 3
     */
    public function testValidConfCountOfDaysIsThree() {
        $configuration = array();
        $configuration['countOfDays'] = 3;
        $result1 = tx_mesearch_utility_generalutility::valid($configuration);
        $this->assertEquals($configuration, $result1);
    }
    
    /**
     * Testing valid count of days, count > 3
     */
    public function testValidConfCountOfDaysGreaterThanThree() {
        $configuration = array();
        $configuration['countOfDays'] = rand(4, 999);
        $result2 = tx_mesearch_utility_generalutility::valid($configuration);
        $this->assertEquals($configuration, $result2);
    }
    
    /**
     * Testing valid count of days, count < 2
     */
    public function testValidConfCountOfDaysLessThanThree() {
        $configuration = array();
        $configuration['countOfDays'] = rand(0, 2);
        $result3 = tx_mesearch_utility_generalutility::valid($configuration);
        $this->assertEquals(FALSE, $result3);
    }
    
    /**
     * Testing valid count of days, count = sting
     */
    public function testValidConfCountOfDaysIsString() {
        $configuration = array();
        $configuration['countOfDays'] = 'string';
        $result4 = tx_mesearch_utility_generalutility::valid($configuration);
        $this->assertEquals(FALSE, $result4);
    }

}

?>
