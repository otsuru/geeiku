<?php
/**
 * Use this file to override global defaults.
 *
 * See the individual environment DB configs for specific config information.
 */

return array(
	'default' => array(
		'type' => 'mysql',
		'connection' => array(
			'hostname' => 'mysql429.db.sakura.ne.jp',
			'port' => '',
			'database' => 'sou-opinion_ac_db',
			'username' => 'sou-opinion',
			'password' => 'qFpSqwNJni6A',
		),
		'identifier' => '`',
		'table_prefix' => '',
		'charset' => 'utf8',
		'enable_cache' => true,
		'profiling' => false,
	),
);
