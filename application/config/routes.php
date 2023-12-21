<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Users
$route['user/login'] = 'Users/user/login';
$route['user/register'] = 'Users/user/register';
$route['user/transaksi'] = 'Users/user/transaksi';
$route['user/history'] = 'Users/user/transaksi';
$route['user/complaint'] = 'Users/user/complaint';
$route['user/detail_history/(:num)'] = 'Users/user/detail_transaksi/$1';
// $route['transaksi/(:num)'] = 'user/transaksi/$1';
$route['user/change_password'] = 'Users/user/change_password';
$route['user/profile'] = 'Users/user/profile';

//confirm payment
$route['user/confirm_payment'] = 'Users/User/confirm_payment';

//admin
$route['admin/login'] = 'Admin/admin/login';
$route['admin/delete_history'] = 'Admin/admin/history';
$route['admin/get_history'] = 'Admin/admin/history';
$route['admin/edit_history'] = 'Admin/admin/history';
$route['admin/detail_transaksi/(:num)'] = 'Admin/admin/detail_transaksi/$1';

//transaction
$route['get_transaction'] = 'transaction/transaction';


//product
$route['get_product'] = 'Admin/product/product';
$route['get_product_byIdCategory/(:num)'] = 'Admin/product/product_by_category/$1';
$route['admin/edit_product'] = 'Admin/product/product';
$route['admin/delete_product'] = 'Admin/product/product';
$route['admin/add_product'] = 'Admin/product/product';


//rules asosisi
$route['get_rules_asosiasi'] = 'Admin/rules/asosiasi';
$route['admin/edit_rules_asosiasi'] = 'Admin/rules/asosiasi';
$route['admin/delete_rules_asosiasi'] = 'Admin/rules/asosiasi';
$route['admin/add_rules_asosiasi'] = 'Admin/rules/asosiasi';

//rules komplain
$route['get_rules_komplain'] = 'Admin/rules/rules_komplain';
$route['admin/edit_rules_komplain'] = 'Admin/rules/rules_komplain';
$route['admin/delete_rules_komplain'] = 'Admin/rules/rules_komplain';
$route['admin/add_rules_komplain'] = 'Admin/rules/rules_komplain';

//category
$route['get_category'] = 'Admin/product/category';
$route['admin/edit_category'] = 'Admin/product/category';
$route['admin/delete_category'] = 'Admin/product/category';
$route['admin/add_category'] = 'Admin/product/category';

//recap
$route['admin/get_oneWeeks'] = 'Admin/admin/history_byWeek';
$route['admin/get_oneMonths'] = 'Admin/admin/history_bymonth';
