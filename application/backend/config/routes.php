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
| --------------------------------- ----------------------------------------
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

$route['default_controller'] = "home";
$route['404_override'] = '';

$route['argan-beauty'] = "argan_beauty";
$route['argan-beauty/save'] = "argan_beauty/save";
$route['giving-back'] = "giving_back";
$route['giving-back/save'] = "giving_back/save";
$route['josies-bio'] = "josies_bio";
$route['josies-bio/save'] = "josies_bio/save";

// $route['ajax'] = "ajax";
// $route['ajax/search_customers'] = "ajax/search_customers";
// $route['ajax/search_products'] = "ajax/search_products";
// $route['ajax/destination_state'] = "ajax/destination_state";
// $route['ajax/shipping_method'] = "ajax/shipping_method";
// $route['ajax/promo'] = "ajax/promo";
// $route['ajax/ship_state'] = "ajax/ship_state";
// $route['ajax/discountcode_rel_products'] = "ajax/discountcode_rel_products";
// $route['ajax/save_order'] = "ajax/save_order";
// $route['ajax/search_orderlist_products'] = "ajax/search_orderlist_products";
// $route['ajax/update_order'] = "ajax/update_order";
// $route['ajax/customer_case_send_message'] = "ajax/customer_case_send_message";
// $route['ajax/josies_bio_save'] = "ajax/josies_bio_save";


/* End of file routes.php */
/* Location: ./application/config/routes.php */