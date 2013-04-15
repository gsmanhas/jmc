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

$route['default_controller'] = "home";

$route['404_override'] = '';

$route['cron'] = 'cron';
$route['cron/shipping_notify'] = 'cron/shipping_notify';

// $route['paypal_test'] = "paypal_test";

// $route['email_signup'] = "email-signup";
$route['email-signup-page'] = "email_signup_page";

$route['ajax'] = "ajax";
$route['ajax/search_customers'] = "ajax/search_customers";
$route['ajax/search_products'] = "ajax/search_products";
$route['ajax/destination_state'] = "ajax/destination_state";
$route['ajax/shipping_method'] = "ajax/shipping_method";
$route['ajax/promo'] = "ajax/promo";
$route['ajax/ship_state'] = "ajax/ship_state";
$route['ajax/discountcode_rel_products'] = "ajax/discountcode_rel_products";
$route['ajax/save_order'] = "ajax/save_order";
$route['ajax/search_orderlist_products'] = "ajax/search_orderlist_products";
$route['ajax/update_order'] = "ajax/update_order";
$route['ajax/customer_case_send_message'] = "ajax/customer_case_send_message";
$route['ajax/josies_bio_save'] = "ajax/josies_bio_save";
$route['ajax/send_shipping_notify'] = "ajax/send_shipping_notify";
$route['ajax/send_order_notify'] = "ajax/send_order_notify";
$route['ajax/get_states'] = "ajax/get_states";

$route['view-order/:any'] = "view_order/$1";

$route['error-js'] = "error_js";
$route['error-ie6'] = "error_ie6";

$route['shippingbag'] = "shippingbag";

$route['paypal-return?:any'] = "paypal_return";
$route['paypal-cancel/:any'] = "paypal_cancel";

$route['email-signup-error?:any']   = "email_signup_error";
$route['email-signup-success'] = "email_signup/success";

$route['lostpasswordprocess'] = "lostpasswordprocess";
$route['passwordhelp'] = "passwordhelp";
$route['passwordhelp/submit'] = "passwordhelp/submit";

$route['search_genxml'] = "search_genxml";
$route['search-products'] = "search_products";

$route['ingredients'] = "ingredients";
$route['testimonials'] = "testimonials";
$route['about-the-brand'] = "about_the_brand";
$route['view-icons/:any'] = "view_icons/index/$1";

$route['thankyou'] = "thankyou";

$route['viewcart'] = "viewcart";
$route['checkout'] = "checkout";

$route['quickview'] = "quick_view";
$route['quickview/:any'] = "quick_view/index/$1";

$route['send-wishlist'] = "send_wishlist";
$route['send-wishlist/:any'] = "send_wishlist/index/$1";

$route['get-the-look'] = "get_the_look";
$route['get-the-look/allbuy/:any'] = "get_the_look/allbuy/$1";
$route['get-the-look/entry/:any'] = "get_the_look/entry/$1";
$route['get-the-look/add2wish/:any'] = "get_the_look/add2wish/$1";



$route['events-calendar'] = "events_calendar";

$route['guestcheckout'] = "guestcheckout";
$route['guestcheckout/submit'] = "guestcheckout/submit";
$route['membercheckout'] = "membercheckout";
$route['membercheckout/submit'] = "membercheckout/submit";

$route['contact-us']        = "customerservices";
$route['contact-us/submit'] = "customerservices/submit";

$route['thanks-for-contact']        = "thanks_for_contact";

$route['argan-beauty'] = "argan_beauty";
$route['giving-back']  = "giving_back";
$route['josies-bio']   = "josies_bio";
$route['press']   = "press";

$route['stores']  = "stores";

// $route['products']      = "products";
// $route['products/:any'] = "products/index/$1";

$route['reviews'] = "reviews";
$route['reviews/sendmessage'] = "reviews/sendmessage";

$route['shop']      = "shop";
$route['shop/:any'] = "shop/index/$1";

$route['login'] = "login";
$route['login/submit'] = "login/submit";
$route['login/success'] = "login/success";

$route['signin'] = "signin";
$route['signin/submit'] = "signin/submit";
$route['signin/success'] = "signin/success";

$route['signout'] = "signout";
$route['signout/success'] = "signout/success";

$route['register'] = "register";
$route['register/submit']   = "register/submit";
$route['register/success']  = "register/success";
$route['register/activate/:any'] = "register/activate";
// $route['register'.'(.*)?'] = 'register/index/$1';

$route['member'] = "member";
$route['member/profile'] = "member/profile";
$route['member/savechange'] = "member/savechange";
$route['member/remove_wish_list/:any'] = "member/remove_wish_list/$1";

$route['myaccount'] = "myaccount";
$route['myaccount/account-info'] = "myaccount/account_info";
$route['myaccount/account-info-update'] = "myaccount/account_update";
$route['myaccount/password'] = "myaccount/password";
$route['myaccount/reset-password'] = "myaccount";
$route['myaccount/order-status'] = "myaccount/order_status";
$route['myaccount/wishlist'] = "myaccount/wishlist";
$route['myaccount/remove_wishlist/:any'] = "myaccount/remove_wishlist/$1";

$route['myshoppingcart'] = "myshoppingcart";

$route['printer/:any'] = "printer/$1";
$route['egiftcards'] = "egiftcards";
$route['egiftcards/:any'] = "egiftcards/$1";

// $route['admin/argan-beauty'] = "admin/argan_beauty";
// $route['admin/argan-beauty/save'] = "admin/argan_beauty/save";
// $route['admin/giving-back'] = "admin/giving_back";
// $route['admin/giving-back/save'] = "admin/giving_back/save";
// 
// $route['admin/josies-bio'] = "admin/josies_bio";
// $route['admin/josies-bio/save'] = "admin/josies_bio/save";
// 
// $route['admin'] = "admin";
// $route['admin'.'(.*)?'] = 'admin$1';

// $route['admin/([a-zA-Z_-]+)/(:any)'] = '$1/admin/$2';
// $route['admin/login'] = 'admin/login';
// $route['admin/logout'] = 'admin/logout';
// $route['admin/([a-zA-Z_-]+)'] = '$1/admin/index';
// $route['admin'] = 'admin';

$route[':any'] = "home";
// $route['home'] = "home";


/* End of file routes.php */
/* Location: ./application/config/routes.php */