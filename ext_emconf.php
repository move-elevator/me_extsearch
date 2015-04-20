<?php


$EM_CONF[$_EXTKEY] = array(
	'title' => 'm:e Indexed Search Extensions',
	'description' => 'Extends indexed_search to clear search index records older than 3 and more days. ' .
		'Add autocomplete for search form and prepare pagebrowser to used twitter bootstrap.',
	'category' => 'plugin',
	'author' => 'move : elevator',
	'author_email' => 'typo3@move-elevator.de',
	'author_company' => 'move elevator GmbH',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.0.7',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.0.0-6.2.99',
			'extbase' => ''
		),
		'conflicts' => array(),
		'suggests' => array(),
	)
);