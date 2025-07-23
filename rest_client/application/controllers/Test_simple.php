<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_simple extends CI_Controller {

    public function index()
    {
        echo "<h1>Test Controller Working!</h1>";
        echo "<p>This proves CodeIgniter routing is working</p>";
        echo "<p>Current URL: " . current_url() . "</p>";
        echo "<p>Base URL: " . base_url() . "</p>";
        
        // Test if Auth controller file exists
        $auth_file = APPPATH . 'controllers/Auth.php';
        if (file_exists($auth_file)) {
            echo "<p>✓ Auth.php file exists at: $auth_file</p>";
        } else {
            echo "<p>✗ Auth.php file missing</p>";
        }
        
        // Test untuk load Auth manually
        echo "<h2>Testing Auth Controller Load:</h2>";
        try {
            // Load Auth controller class
            if (!class_exists('Auth')) {
                require_once($auth_file);
            }
            
            if (class_exists('Auth')) {
                echo "<p>✓ Auth class loaded successfully</p>";
                echo "<p><a href='" . site_url('auth/login') . "'>Try Auth/login</a></p>";
            } else {
                echo "<p>✗ Auth class failed to load</p>";
            }
        } catch (Exception $e) {
            echo "<p>✗ Error loading Auth: " . $e->getMessage() . "</p>";
        }
    }
}
