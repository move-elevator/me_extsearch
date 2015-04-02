<?php

use \MoveElevator\MeExtsearch\Utility\ExtensionSettingsUtility;

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$meEstSearchNameSpaces = array(
	'searchForm' => 'MoveElevator\MeExtsearch\Controller\SearchFormController',
	'command' => 'MoveElevator\MeExtsearch\Command\IndexCommandController'
);

if ((bool) \MoveElevator\MeExtsearch\Utility\ExtensionSettingsUtility::checkPageBrowserOverwrite() === TRUE) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tx_indexedsearch'] = array(
		'className' => $meEstSearchNameSpaces['searchForm'],
	);
}

$TYPO3_CONF_VARS['FE']['eID_include']['me_extsearch_autocomplete'] = 'EXT:me_extsearch/Classes/Eid/Autocomplete.php';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = $meEstSearchNameSpaces['command'];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['indexed_search']['addRootLineFields']['page_id'] = array();
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['indexed_search']['pi1_hooks']['initialize_postProc'] =
	'EXT:me_extsearch/Classes/Hooks/HooksHandler.php:HooksHandler';
