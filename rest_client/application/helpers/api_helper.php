<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * API Helper
 * Membantu menentukan URL API berdasarkan environment
 */

if (!function_exists('get_api_base_url')) {
    function get_api_base_url() {
        // Auto-detect environment untuk API URL
        if (isset($_SERVER['HTTP_HOST'])) {
            if (strpos($_SERVER['HTTP_HOST'], 'infinityfree') !== false || 
                strpos($_SERVER['HTTP_HOST'], 'epizy.com') !== false ||
                strpos($_SERVER['HTTP_HOST'], '.rf.gd') !== false ||
                $_SERVER['HTTP_HOST'] === 'votinghimsi.infinityfreeapp.com') {
                // InfinityFree hosting - rest_server ada di subfolder yang sama
                if ($_SERVER['HTTP_HOST'] === 'votinghimsi.infinityfreeapp.com') {
                    return 'https://votinghimsi.infinityfreeapp.com/rest_server';
                } else {
                    return 'https://' . $_SERVER['HTTP_HOST'] . '/rest_server';
                }
            } else {
                // Localhost development
                return 'http://localhost:8001';
            }
        } else {
            return 'http://localhost:8001';
        }
    }
}

if (!function_exists('get_api_url')) {
    function get_api_url($endpoint = '') {
        $base_url = get_api_base_url();
        return $base_url . '/index.php/api/' . ltrim($endpoint, '/');
    }
}
