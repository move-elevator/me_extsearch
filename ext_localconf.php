<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tx_indexedsearch'] = array(
	'className' => 'MoveElevator\\MeExtsearch\\Controller\\SearchFormController',
);

$TYPO3_CONF_VARS['FE']['eID_include']['me_extsearch_autocomplete'] = 'EXT:me_extsearch/Classes/Eid/Autocomplete.php';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'MoveElevator\MeExtsearch\Command\IndexCommandController';