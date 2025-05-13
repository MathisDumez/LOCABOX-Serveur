<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller'] = 'Vitrine_Controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

#Vitrine_Controller
$route['vitrine/index'] = 'Vitrine_Controller/index';
$route['vitrine/detail/(:num)'] = 'Vitrine_Controller/detail/$1';

#Identification_Controller
$route['id/inscription'] = 'Identification_Controller/inscription';
$route['id/process_inscription'] = 'Identification_Controller/process_inscription';
$route['id/identification'] = 'Identification_Controller/identification';
$route['id/login'] = 'Identification_Controller/login';
$route['id/logout'] = 'Identification_Controller/logout';

#User_Controller
$route['user/dashboard'] = 'User_Controller/dashboard_user';
$route['admin/dashboard'] = 'User_Controller/dashboard_admin';
$route['user/change_password'] = 'User_Controller/changement_mdp';
$route['user/update_password'] = 'User_Controller/update_password';
$route['user/reserver'] = 'User_Controller/reserver';
$route['user/valider_reservation'] = 'User_Controller/valider_reservation';
$route['user/annuler_reservation/(:num)'] = 'User_Controller/annuler_reservation/$1';

#Box_Controller
$route['admin/gestion_box'] = 'Box_Controller/gestion_box';
$route['admin/ajouter_box'] = 'Box_Controller/afficher_ajouter_box';
$route['admin/ajouter_box_submit'] = 'Box_Controller/ajouter_box';
$route['admin/detail_box/(:num)'] = 'Box_Controller/detail_box/$1';
$route['admin/acces_box/(:num)'] = 'Box_Controller/acces_box/$1';
$route['admin/alarme_box/(:num)'] = 'Box_Controller/alarme_box/$1';
$route['admin/modifier_box/(:num)'] = 'Box_Controller/modifier_box/$1';
$route['admin/modifier_box_submit/(:num)'] = 'Box_Controller/modifier_box_submit/$1';
$route['admin/supprimer_box/(:num)'] = 'Box_Controller/supprimer_box/$1';

#Batiment_Controller
$route['admin/gestion_batiment'] = 'Batiment_Controller/gestion_batiment';
$route['admin/ajouter_batiment'] = 'Batiment_Controller/afficher_ajouter_batiment';
$route['admin/ajouter_batiment_submit'] = 'Batiment_Controller/ajouter_batiment';
$route['admin/modifier_batiment/(:num)'] = 'Batiment_Controller/afficher_modifier_batiment/$1';
$route['admin/modifier_batiment_submit/(:num)'] = 'Batiment_Controller/modifier_batiment/$1';
$route['admin/supprimer_batiment/(:num)'] = 'Batiment_Controller/supprimer_batiment/$1';

#Reservation_Controller
$route['admin/gestion_reservation'] = 'Reservation_Controller/gestion_reservation';
$route['admin/modifier_reservation/(:num)'] = 'Reservation_Controller/modifier_reservation/$1';
$route['admin/valider_reservation/(:num)'] = 'Reservation_Controller/valider_reservation/$1';
$route['admin/annuler_reservation/(:num)'] = 'Reservation_Controller/annuler_reservation/$1';

#Client_Controller
$route['admin/gestion_client'] = 'Client_Controller/gestion_client';
$route['admin/ajouter_client'] = 'Client_Controller/form_add_client';
$route['admin/insert_client'] = 'Client_Controller/insert_client';
$route['admin/modifier_client/(:num)'] = 'Client_Controller/form_update_client/$1';
$route['admin/update_client/(:num)'] = 'Client_Controller/update_user/$1';
$route['admin/supprimer_client/(:num)'] = 'Client_Controller/delete_user/$1';

#Code_Controller
$route['admin/gestion_code'] = 'Code_Controller/gestion_code';