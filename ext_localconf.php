<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$meEstSearchNameSpaces = array(
	'searchForm' => 'MoveElevator\MeExtsearch\Controller\SearchFormController',
	'command' => 'MoveElevator\MeExtsearch\Command\IndexCommandController',
	'indexedSearchForm' => 'MoveElevator\MeExtsearch\Controller\SearchController'
);

if (
	class_exists('MoveElevator\MeExtsearch\Utility\ExtensionSettingsUtility') &&
	\MoveElevator\MeExtsearch\Utility\ExtensionSettingsUtility::checkPageBrowserOverwrite() === TRUE
) {
	$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['tx_indexedsearch'] = array(
		'className' => $meEstSearchNameSpaces['searchForm'],
	);
}

$TYPO3_CONF_VARS['FE']['eID_include']['me_extsearch_autocomplete'] = 'EXT:me_extsearch/Classes/Eid/Autocomplete.php';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = $meEstSearchNameSpaces['command'];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['indexed_search']['addRootLineFields']['tx_meextsearch_rl3'] = 3;
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['indexed_search']['pi1_hooks']['initialize_postProc'] =
	'EXT:me_extsearch/Classes/Hooks/HooksHandler.php:HooksHandler';

// register extbase plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('MoveElevator.' . $_EXTKEY, 'Pi2', array('Search' => 'form,search'), array('Search' => 'search'));