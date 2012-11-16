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
        $this->testingFramework = new Tx_Phpunit_Framework('tx_meextsearch', array('index', 'cache'));
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
        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010001, 'item_title' => 'my_test_phash2'
        ));
        $this->object->result = $this->object->deleteRecordPhash('9010000, 9010001');

        $this->assertEquals(TRUE, $this->object->result);
    }

    /**
     * Testing delete record 'index_grlist'
     */
    public function testDeleteRecordFromIndexGrlistTable() {
        $this->testingFramework->createRecord('index_grlist', array(
            'phash' => 9010001
        ));
        $this->testingFramework->createRecord('index_grlist', array(
            'phash' => 9010002
        ));
        $this->object->result = $this->object->deleteRecordGrlist('9010001, 9010002');
        $this->assertEquals(TRUE, $this->object->result);
    }

    /**
     * Testing delete 'index_fulltext'
     */
    public function testDeleteRecordFromIndexFulltextTable() {
        $this->testingFramework->createRecord('index_fulltext', array(
            'phash' => 9010021
        ));
        $this->testingFramework->createRecord('index_fulltext', array(
            'phash' => 9010022
        ));

        $this->object->result = $this->object->deleteRecordFulltext('9010021, 9010022');    
        $this->assertEquals(TRUE, $this->object->result);
    }

    /**
     * Testing delete 'cache_pages'
     */
    public function testDeleteRecordFromCachePagesTable() {
        $this->testingFramework->createRecord('cache_pages', array(
            'page_id' => 9010021
        ));
        $this->testingFramework->createRecord('cache_pages', array(
            'page_id' => 9010022
        ));

        $this->object->result = $this->object->deleteCacheRecordPages('9010021, 9010022');
        $this->assertEquals(TRUE, $this->object->result);
    }
    
    /**
     * Testing delete 'cache_pagesection'
     */
    public function testDeleteRecordFromCachePagesectionTable() {
        $this->testingFramework->createRecord('cache_pagesection', array(
            'page_id' => 9010021
        ));
        $this->testingFramework->createRecord('cache_pagesection', array(
            'page_id' => 9010022
        ));

        $this->object->result = $this->object->deleteCacheRecordPagesection('9010021, 9010022');
        $this->assertEquals(TRUE, $this->object->result);
    }    
    
    /**
     * Testing get record from table index_phash
     */
    public function testGetPhashRecordFromTableIndexPhash() {
        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010031,
            'crdate' => 1351784588
        ));

        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010032,
            'crdate' => 1351784588
        ));

        $this->object->result = $this->object->getPhash(1351784589);
        $this->assertEquals(TRUE, is_array($this->object->result));
    }

    /**
     * Testing get record from table index_phash with younger records
     */
    public function testGetPhashRecordFromTableIndexPhashNotYoungerRecords() {
        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010031,
            'crdate' => 1351784588
        ));

        $this->testingFramework->createRecord('index_phash', array(
            'phash' => 9010032,
            'crdate' => time()
        ));
        $this->object->result = $this->object->getPhash();

        $testOld = 0;
        $testYoug = 0;

        foreach ($this->object->result as $item) {
            if ($item['phash'] == '9010031') {
                $testOld = 1;
            }
            if ($item['phash'] == '9010032') {
                $testYoung = 1;
            }
        }
        $this->assertEquals(1, $testOld);
        $this->assertEquals(0, $testYoung);
    }

    /**
     * Testing valid count of days, count = 3
     */
    public function testValidConfCountOfDaysIsThree() {
        $this->object->configuration = array();
        $this->object->configuration['countOfDays'] = 3;
        $this->object->result1 = tx_mesearch_utility_generalutility::valid($this->object->configuration);
        $this->assertEquals($this->object->configuration, $this->object->result1);
    }

    /**
     * Testing valid count of days, count > 3
     */
    public function testValidConfCountOfDaysGreaterThanThree() {
        $this->object->configuration = array();
        $this->object->configuration['countOfDays'] = rand(4, 999);
        $this->object->result2 = tx_mesearch_utility_generalutility::valid($this->object->configuration);
        $this->assertEquals($this->object->configuration, $this->object->result2);
    }

    /**
     * Testing valid count of days, count < 2
     */
    public function testValidConfCountOfDaysLessThanThree() {
        $this->object->configuration = array();
        $this->object->configuration['countOfDays'] = rand(0, 2);
        $this->object->result3 = tx_mesearch_utility_generalutility::valid($this->object->configuration);
        $this->assertEquals(FALSE, $this->object->result3);
    }

    /**
     * Testing valid count of days, count = sting
     */
    public function testValidConfCountOfDaysIsString() {
        $this->object->configuration = array();
        $this->object->configuration['countOfDays'] = 'string';
        $this->object->result4 = tx_mesearch_utility_generalutility::valid($this->object->configuration);
        $this->assertEquals(FALSE, $this->object->result4);
    }

}

?>
