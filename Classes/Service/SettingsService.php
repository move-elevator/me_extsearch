<?php

namespace MoveElevator\MeExtsearch\Service;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
* Settings service. Provides access to the plugin settings
* coming from TypoScript, Flexform and the Plugin content element.
*/
class SettingsService implements SingletonInterface {

	/**
	* @var array|null
	*/
	protected $settings = NULL;

	/**
	* @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	*/
	protected $configurationManager;

	/**
	 * Injects the Configuration Manager and loads the settings
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
	}

	public function initializeObject() {
		if ($this->configurationManager === NULL) {
			$this->configurationManager = GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
		}
	}

	/**
	* Returns all settings.
	*
	* @param string $extensionName
	* @param string $pluginName
	* @return array|null
	*/
	public function getSettings($extensionName = 'MeExtsearch', $pluginName = NULL) {
			$this->settings = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
				$extensionName,
				$pluginName
			);
		return $this->settings;
	}

	/**
	* Returns the settings at path $path, which is separated by ".", e.g. "pages.uid".
	* "pages.uid" would return $this->settings['pages']['uid'].
	*
	* If the path is invalid or no entry is found, false is returned.
	*
	* @param string $path
	* @param string $extensionName
	* @param string $pluginName
	* @return array
	*/
	public function getByPath($path, $extensionName = 'MeExtsearch', $pluginName = NULL) {
		return ObjectAccess::getPropertyPath(
			$this->getSettings($extensionName, $pluginName),
			$path
		);
	}

	/**
	 * Returns the whole TypoScript array
	 *
	 * @return array
	 */
	public function getFullTypoScript() {
		$ts = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		return $ts;
	}

}
?>