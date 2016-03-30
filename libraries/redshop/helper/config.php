<?php
/**
 * @package     Redshop.Library
 * @subpackage  Config
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

/**
 * Redshop Configuration
 *
 * @package     Redshop.Library
 * @subpackage  Config
 * @since       1.5
 */
class RedshopHelperConfig
{
	/**
	 * javascript strings for configuration variables
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected static $jsStrings = array();

	private static $isLoad = false;

	/**
	 * Configuration
	 *
	 * @var  stdClass
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 */
	public function __construct($namespace = '')
	{
		$this->loadConfig($namespace);
	}

	/**
	 * Magic method to transparently use registry methods on config
	 *
	 * @param   string  $name       Name of the function.
	 * @param   array   $arguments  [0] The name of the variable [1] The default value.
	 *
	 * @return  mixed   The filtered input value.
	 */
	public function __call($name, $arguments)
	{
		if (method_exists($this->config, $name))
		{
			return call_user_func_array(array($this->config, $name), $arguments);
		}

		trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
	}

	/**
	 * Get the path to this station configuration file
	 *
	 * @return  string
	 */
	protected function getConfigurationFilePath()
	{
		return JPATH_SITE . '/redshop/conf/config.php';
	}

	/**
	 * Default loading is trying to use the associated table
	 *
	 * @return  self
	 */
	public function loadConfig($namespace = '')
	{
		$this->config = new JRegistry;

		$file = $this->getConfigurationFilePath();

		if (!file_exists($file))
		{
			return $this;
		}

		if (is_file($file))
		{
			include_once $file;
		}

		// Sanitize the namespace.
		$namespace = ucfirst((string) preg_replace('/[^A-Z_]/i', '', $namespace));

		// Build the config name.
		$name = 'RedshopConfig' . $namespace;

		// Handle the PHP configuration type.
		if (class_exists($name))
		{
			// Create the JConfig object
			$class = new $name;

			// Load the configuration values into the registry
			$this->config->loadObject($class);
		}

		return $this;
	}

	/**
	 * Save configuration to file
	 *
	 * @param   mixed  $config  Null to avoid binding any data | JRegistry to binf config and save
	 *
	 * @return  boolean
	 */
	public function save($config = null)
	{
		if ($config instanceof JRegistry || $config instanceof \Joomla\Registry\Registry)
		{
			$this->config->merge($config);
		}

		jimport('joomla.filesystem.path');
		jimport('joomla.filesystem.file');

		// Set the configuration file path.
		$file         = $this->getConfigurationFilePath();
		$configFolder = dirname($file);

		if (!is_dir($configFolder) && !mkdir($configFolder, 0755, true))
		{
			throw new Exception('Unable to create configuration folder');
		}

		$app = JFactory::getApplication();

		// Attempt to make the file writeable if using FTP.
		if (JPath::isOwner($file) && !JPath::setPermissions($file, '0644'))
		{
			$app->enqueueMessage(JText::_('LIB_REDSHOP_ERROR_CONFIGURATION_PHP_NOTWRITABLE'), 'notice');
		}

		// Attempt to write the configuration file as a PHP class named RedshopConfig.
		$configuration = $config->toString('PHP', array('class' => 'RedshopConfig', 'closingtag' => false));

		if (!JFile::write($file, $configuration))
		{
			throw new RuntimeException(JText::_('LIB_REDSHOP_ERROR_WRITE_FAILED'));
		}

		// Attempt to make the file unwriteable if using FTP.
		if (JPath::isOwner($file) && !JPath::setPermissions($file, '0444'))
		{
			$app->enqueueMessage(JText::_('LIB_REDSHOP_ERROR_CONFIGURATION_PHP_NOTUNWRITABLE'), 'notice');
		}

		return true;
	}

	/**
	 * Stores redshop configuration strings in the JavaScript language store.
	 *
	 * @param   string  $key    The Javascript config string key.
	 * @param   string  $value  The Javascript config string value.
	 *
	 * @return  string
	 *
	 * @since   1.5
	 */
	public static function script($key = null, $value = null)
	{
		// Add the key to the array if not null.
		if ($key !== null)
		{
			// Assign key to the value
			self::$jsStrings[strtoupper($key)] = $value;
		}

		return self::$jsStrings;
	}

	/**
	 * Set javascript strings
	 *
	 * @return  void
	 */
	public static function scriptDeclaration()
	{
		if (self::$isLoad)
		{
			return;
		}

		// Load redshop script
		JHtml::script('com_redshop/redshop.js', false, true);

		JFactory::getDocument()->addScriptDeclaration('
			(function() {
				var RedshopStrings = ' . json_encode(self::script()) . ';
				if (typeof redSHOP == "undefined") {
					redSHOP = {};
					redSHOP.RSConfig.strings = RedshopStrings;
				}
				else {
					redSHOP.RSConfig.load(RedshopStrings);
				}
			})();
		');
		self::$isLoad = true;
	}
}