<?php defined('BASEPATH') OR exit('No direct script access allowed');

// SYSTEM
$route['default_controller']   = 'route';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

$route['marplace']       = 'route/marplace';
$route['invoice']        = 'route/invoice';
$route['invoice/(:any)'] = 'route/invoice/$1';