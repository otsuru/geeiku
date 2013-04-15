<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * If you want to override the default configuration, add the keys you
 * want to change here, and assign new values to them.
 */

// set default charset
ini_set('default_charset', 'UTF-8');

return array(
	'index_file' => false,
	'profiling' => false,
	'language'          => 'ja',
	'language_fallback' => 'en',
	'locale'            => 'ja_JP.UTF-8',
	'always_load'  => array(
      
		'packages'  => array(
			'orm',
		)
	),
);


      
