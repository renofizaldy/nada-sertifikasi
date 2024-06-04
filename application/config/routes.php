<?php defined('BASEPATH') OR exit('No direct script access allowed');

// SYSTEM
$route['default_controller']   = 'route/login';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

$route['surat/(:any)'] = 'route/surat/$1';
$route['print/(:any)'] = 'route/print/$1';
$route['login']        = 'route/login';