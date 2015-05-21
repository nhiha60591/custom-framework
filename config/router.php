<?php
/**
 * Created by PhpStorm.
 * User: HuuHien
 * Date: 5/15/2015
 * Time: 6:31 AM
 */
$route = array();
$route['default_controller'] = 'welcome';
$route['^login'] = 'welcome/login';
$route['^singup'] = 'welcome/singup';
$route['^logout'] = 'welcome/logout';
$route['^forgot'] = 'welcome/forgot';
$route['^dashboard'] = 'dashboard/admin';
$route['^dashboard/(:any)'] = 'dashboard/$1';
$route['^admin'] = 'dashboard/admin';
$route['^change-password'] = 'welcome/change_password';
$route['^change-password/(:any)'] = 'welcome/change_password/$1';
$route['^my-account'] = 'welcome/my_account';
$route['examples'] = 'front/examples';
$route['^active/(:any)'] = 'welcome/active/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;