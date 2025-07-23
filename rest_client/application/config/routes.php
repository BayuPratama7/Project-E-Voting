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
|	https://codeigniter.com/user_guide/general/routing.html
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

// Routes untuk halaman utama
$route['login'] = 'admin_auth/login';
$route['logout'] = 'admin_auth/logout';
$route['auth/logout'] = 'admin_auth/logout';
$route['auth/login'] = 'admin_auth/login';

// Routes untuk admin
$route['admin'] = 'admin/dashboard';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/hasil'] = 'hasil_pemilihan/hasil';
$route['admin/calon'] = 'calon/index';
$route['admin/pemilih'] = 'pemilih/index';
$route['admin/logout'] = 'admin/logout';
$route['admin/kembali'] = 'admin/kembali';

// Routes untuk CRUD Calon
$route['calon'] = 'calon/index';
$route['calon/tambah'] = 'calon/tambah';
$route['calon/edit/(:num)'] = 'calon/edit/$1';
$route['calon/hapus/(:num)'] = 'calon/hapus/$1';
$route['calon/kembali'] = 'calon/kembali';

// Routes untuk CRUD Pemilih  
$route['pemilih'] = 'pemilih/index';
$route['pemilih/tambah'] = 'pemilih/tambah';
$route['pemilih/edit/(:num)'] = 'pemilih/edit/$1';
$route['pemilih/hapus/(:num)'] = 'pemilih/hapus/$1';
$route['pemilih/kembali'] = 'pemilih/kembali';

// Routes untuk Hasil Pemilihan
$route['hasil'] = 'hasil_pemilihan/hasil';
$route['hasil/reset'] = 'hasil_pemilihan/reset_voting';
$route['hasil/export'] = 'hasil_pemilihan/export_excel';
$route['hasil/refresh'] = 'hasil_pemilihan/refresh_data';
$route['hasil/kembali'] = 'hasil_pemilihan/kembali';

// Routes untuk pemilih - SESUAI URL DI SCREENSHOT
$route['auth_pemilih/login'] = 'auth_pemilih/login';
$route['auth_pemilih/aksi_login'] = 'auth_pemilih/aksi_login';
$route['auth_pemilih/register'] = 'auth_pemilih/register';
$route['auth_pemilih/aksi_register'] = 'auth_pemilih/aksi_register';
$route['auth_pemilih/logout'] = 'auth_pemilih/logout';

// Routes dashboard
$route['dashboard_pemilih/dashboard'] = 'dashboard_pemilih/dashboard';
$route['vote'] = 'dashboard_pemilih/vote';

// Routes untuk hasil pemilihan
$route['pemilihan/hasil'] = 'hasil_pemilihan/hasil';
