<?php

namespace MoveElevator\MeExtsearch\Utility;

/**
 * Class ExtensionSettingsUtility
 *
 * @package MoveElevator\MeExtsearch\Utility
 */
class ExtensionSettingsUtility {
	const EXT_KEY = 'me_extsearch';

	/**
	 * Check whether search result list pagination must be overwrite
	 *
	 * @return bool
	 */
	static public function checkPageBrowserOverwrite() {
		return (bool)self::getSinglePropertyByName('overwritePagebrowser');
	}

	/**
	 * Get value of single property from extension configuration
	 *
	 * @param string $propertyName
	 * @return mixed
	 */
	public static function getSinglePropertyByName($propertyName) {
		if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][self::EXT_KEY])) {
			return NULL;
		}
		$extensionSettings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][self::EXT_KEY]);
		if (!is_array($extensionSettings) || !isset($extensionSettings[$propertyName])) {
			return NULL;
		}

		return $extensionSettings[$propertyName];
	}
}