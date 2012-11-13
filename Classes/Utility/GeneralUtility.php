<?php

class tx_mesearch_utility_generalutility {

    public static function getExtConfiguration() {
        $configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['me_extsearch']);
        return self::valid($configuration);
    }

    public static function valid(array $configuration) {
        if ((int) $configuration['countOfDays'] > 0) {
            return $configuration;
        } else {
           return false; 
        }
    }

}

?>
