<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_meextsearch_tasks_delete'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Delete Search Cache',
    'description' => 'Delete Cache Indexed Searche older then 3 days'
);
?>
