<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = 'frontend/index/filter';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['admin']='admin/admin';
###FRONT-END
//Pagination homepage +  main menu
$route['page/(:any)'] = "frontend/index/filter/$1";
$route['(:any)/(:any).html'] = "frontend/index/filter/$1/$2";
$route['(:any)/(:any).html/page/(:any)'] = "frontend/index/filter/$1/$2/$3";
//Pagination search
$route['search'] = 'frontend/search/index';
//Head
$route['(:any)/dau-so-(:num)'] = "frontend/index/head/$1/$2";
$route['(:any)/dau-so-(:num)/page/(:num)'] = "frontend/index/head/$1/$2/$3";
$route['(:any)/dau-so-(:num)/sim-(:any)'] = "frontend/index/head/$1/$2/$3";
$route['(:any)/dau-so-(:num)/sim-(:any)/(:num)'] = "frontend/index/head/$1/$2/$3/$4";
//Url-friendly
$route['news/(:any)'] = 'frontend/news/index/$1';
//Order
$route['order/don-hang.html'] = 'frontend/order/done';
//$route['order/mua-sim-trong-gio-hang.html'] = 'frontend/order/done';
$route['order/mua-sim-trong-gio-hang.html'] = 'frontend/order/mycart';
//Pages
$route['noi-dung/(:any)'] = 'frontend/pagecontent/index/$1';



###BACK-END
//login
$route['admin'] = 'admin/login/index';
//SIM
$route['admin/sim'] = 'admin/sim/index';
$route['admin/sim/manage'] = 'admin/sim/index';
$route['admin/sim/page/(:any)'] = 'admin/sim/index/$1';
$route['admin/sim/import'] = 'admin/sim/import';
$route['admin/checksim'] = 'admin/checksim';
$route['admin/sim/percent-sell'] = 'admin/sim/percentsell';
$route['admin/sim/del-percent-sell/(:num)'] = 'admin/sim/delpercentsell/$1';
$route['admin/sim/del/(:num)'] = 'admin/sim/del/$1';
$route['admin/sim/delduplicate'] = 'admin/sim/delduplicate';
$route['admin/sim/delsold'] = 'admin/sim/delsold';
$route['admin/sim/delsold/page/(:num)'] = 'admin/sim/delsold/$1';
$route['admin/sim/download'] = 'admin/sim/download';

//Agency
$route['admin/agency'] = 'admin/agency/index';
$route['admin/agency/manage'] = 'admin/agency/index';
$route['admin/agency/add'] = 'admin/agency/add';
$route['admin/agency/percent-agency'] = 'admin/agency/percentagency';
$route['admin/agency/editprofile/(:num)'] = 'admin/agency/editprofile/$1';
$route['admin/agency/edit/(:num)'] = 'admin/agency/edit/$1';
$route['admin/agency/del/(:num)'] = 'admin/agency/del/$1';
$route['admin/agency/del-percent-agency/(:num)'] = 'admin/agency/delpercentagency/$1';

//News
$route['admin/news'] = 'admin/news/index';
$route['admin/news/manage'] = 'admin/news/index';
$route['admin/news/add'] = 'admin/news/add';
$route['admin/news/edit/(:num)'] = 'admin/news/edit/$1';
$route['admin/news/del/(:any)'] = 'admin/news/del/$1';

//Order
$route['admin/order'] = 'admin/order/index';

$route['admin/order/page'] = 'admin/order/index';
$route['admin/order/page/(:num)'] = 'admin/order/index/$1';
$route['admin/order/search'] = 'admin/order/search';
$route['admin/order/del/(:num)'] = 'admin/order/del/$1';
$route['admin/order/listdel'] = 'admin/order/listdel';
$route['admin/order/listdel/page'] = 'admin/order/listdel';
$route['admin/order/listdel/page/(:num)'] = 'admin/order/listdel/$1';
$route['admin/order/detail/(:num)'] = 'admin/order_detail/index/$1';
$route['admin/order/(:num)/detail/(:num)/notdeliver'] = 'admin/order_detail/notdeliver/$1/$2';
$route['admin/order/detail/forward/(:num)'] = 'admin/order_detail/forward/$1';
$route['admin/order/detail/smsagency/(:num)'] = 'admin/order_detail/smsagency/$1';
$route['admin/order/(:num)/detail/smscustomer/(:num)'] = 'admin/order_detail/smscustomer/$1/$2';

$route['admin/order/(:num)/detail/(:num)/noteadd'] = 'admin/order_detail/noteadd/$1/$2';

//Users
$route['admin/user'] = 'admin/user';
$route['admin/user/add'] = 'admin/user/add';
$route['admin/user/edit/(:num)'] = 'admin/user/edit/$1';
$route['admin/user/del/$1'] = 'admin/user/del/$1';
//Checksim
//Page content
$route['admin/pagecontent'] = 'admin/pagecontent/index';
$route['admin/pagecontent/add'] = 'admin/pagecontent/add';
$route['admin/pagecontent/edit/(:num)'] = 'admin/pagecontent/edit/$1';
//Check site
$route['admin/tools/checksites'] = 'admin/tools/checksites';
/* End of file routes.php */
/* Location: ./application/config/routes.php */