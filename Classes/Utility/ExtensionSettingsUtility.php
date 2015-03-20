<?php

namespace MoveElevator\MeExtsearch\Utility;

use \MoveElevator\MeLibrary\Utility\ExtensionSettingsUtility as MeLibraryExtensionSettingsUtility;

/**
 * Class ExtensionSettingsUtility
 *
 * @package MoveElevator\MeExtsearch\Utility
 */
class ExtensionSettingsUtility extends MeLibraryExtensionSettingsUtility {

	const EXT_KEY = 'me_extsearch';

	/**
	 * Check whether search result list pagination must be overwrite
	 *
	 * @return bool
	 */
	static public function checkPageBrowserOverwrite() {
		return (bool) parent::getSinglePropertyByName(EXT_KEY, 'overwritePagebrowser');
	}
}