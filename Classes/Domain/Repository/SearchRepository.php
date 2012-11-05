<?php

class Tx_MeSearch_Domain_Repository_SearchRepository extends Tx_Extbase_Persistence_Repository {

    /**
     * 
     * @param type $datetime
     * @return type
     */
    public function deleteIndexes($datetime) {
        // DELETE FROM where ...
        $sqlStatement = '';
        $query = $this->createQuery();
        $query->statement($sqlStatement);
        $result = $query->execute();
        if (count($result) > 0) {
            return TRUE;
        }
        return FALSE;
    }

}

?>
