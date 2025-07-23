<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Database Config for InfinityFree Hosting
|--------------------------------------------------------------------------
| Ganti nilai dibawah dengan detail database dari InfinityFree
|--------------------------------------------------------------------------
*/

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'	=> '',
    'hostname' => 'sql302.infinityfree.com',
    'username' => 'if0_39534816',
    'password' => 'votinghimsi',
    'database' => 'if0_39534816_voting_db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,  // Set FALSE untuk production
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => FALSE
);

// Konfigurasi untuk environment development (localhost)
$db['development'] = array(
    'dsn'	=> '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'if0_39534816_voting_db',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);

// Auto-detect environment dan set active group
if (isset($_SERVER['HTTP_HOST'])) {
    if (strpos($_SERVER['HTTP_HOST'], 'infinityfree') !== false || 
        strpos($_SERVER['HTTP_HOST'], 'epizy.com') !== false ||
        $_SERVER['HTTP_HOST'] === 'votinghimsi.infinityfreeapp.com') {
        $active_group = 'default';  // InfinityFree
    } else {
        $active_group = 'development';  // Localhost
    }
}
