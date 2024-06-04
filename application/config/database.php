<?php defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'          => '',
  'hostname'     => '103.101.225.48:3306',
  'username'     => 'renofizaldy',
  'password'     => 'R3n0=f1z4ldY',
	'database'     => 'surat_db', // all_in_one
	'dbdriver'     => 'mysqli',
	'dbprefix'     => '',
	'pconnect'     => FALSE,
	'db_debug'     => (ENVIRONMENT !== 'production'),
	'cache_on'     => FALSE,
	'cachedir'     => '',
	'char_set'     => 'utf8',
	'dbcollat'     => 'utf8_general_ci',
	'swap_pre'     => '',
	'encrypt'      => FALSE,
	'compress'     => FALSE,
	'stricton'     => FALSE,
	'failover'     => array(),
	'save_queries' => TRUE
);