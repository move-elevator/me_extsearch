<?php

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
}

?>
