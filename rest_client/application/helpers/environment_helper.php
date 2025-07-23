<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Configuration Helper untuk Environment
 * 
 * Helper ini akan mendeteksi environment dan memberikan URL yang tepat
 * untuk development dan production
 */

if (!function_exists('get_api_base_url')) {
    function get_api_base_url() {
        // Deteksi environment
        if (ENVIRONMENT === 'production') {
            // Production URL - FIXED dengan index.php
            return 'https://votinghimsi.infinityfreeapp.com/rest_server/index.php/api/';
        } else {
            // URL untuk development
            return 'http://localhost:8001/index.php/api/';
        }
    }
}

if (!function_exists('get_upload_base_url')) {
    function get_upload_base_url() {
        // Deteksi environment
        if (ENVIRONMENT === 'production') {
            // Production URL
            return 'https://votinghimsi.infinityfreeapp.com/rest_server/uploads/';
        } else {
            // URL untuk development
            return 'http://localhost:8001/uploads/';
        }
    }
}

if (!function_exists('get_client_base_url')) {
    function get_client_base_url() {
        // Deteksi environment
        if (ENVIRONMENT === 'production') {
            // Production URL
            return 'https://votinghimsi.infinityfreeapp.com/rest_client/';
        } else {
            // URL untuk development
            return 'http://localhost:8000/';
        }
    }
}

if (!function_exists('is_https')) {
    function is_https() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
            || $_SERVER['SERVER_PORT'] == 443
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https');
    }
}
