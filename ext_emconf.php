<?php


$EM_CONF[$_EXTKEY] = array(
	'title' => 'm:e Indexed Search Extensions',
	'description' => '',
	'category' => 'plugin',
	'author' => 'Jan Maennig',
	'author_email' => 'jma@move-elevator.de',
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
	'version' => '1.0.1',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.3',
			'typo3' => '6.1-6.2.99',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'_md5_values_when_last_written' => 'a:14:{s:16:"ext_autoload.php";s:4:"9551";s:21:"ext_conf_template.txt";s:4:"0764";s:12:"ext_icon.gif";s:4:"11a6";s:17:"ext_localconf.php";s:4:"de05";s:14:"ext_tables.sql";s:4:"df09";s:76:"Classes/Service/ClearIndexSearchCache/ClearIndexSearchCacheTypo45Service.php";s:4:"3a61";s:76:"Classes/Service/ClearIndexSearchCache/ClearIndexSearchCacheTypo46Service.php";s:4:"a720";s:34:"Classes/Utility/GeneralUtility.php";s:4:"77b9";s:32:"Classes/Utility/IndexUtility.php";s:4:"a7d1";s:31:"Classes/Utility/PageUtility.php";s:4:"e966";s:32:"Classes/Utility/PhashUtility.php";s:4:"7a9a";s:24:"Documentation/manual.sxw";s:4:"8d2d";s:16:"Tasks/Delete.php";s:4:"d83d";s:31:"Tests/Unit/Tasks/DeleteTest.php";s:4:"e501";}',
);

?>