<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function session()
    {
        echo "<h2>Session Test</h2>";
        echo "<pre>";
        print_r($this->session->all_userdata());
        echo "</pre>";
        
        echo "<hr>";
        echo "Status Login: " . ($this->session->userdata('status') === 'login' ? 'LOGGED IN' : 'NOT LOGGED IN');
        echo "<br>";
        echo "Username: " . $this->session->userdata('username');
        echo "<br>";
        echo "Nama: " . $this->session->userdata('nama');
        
        echo "<hr>";
        echo '<a href="' . site_url('auth/login') . '">Go to Login</a> | ';
        echo '<a href="' . site_url('dashboard/dashboard') . '">Go to Dashboard</a> | ';
        echo '<a href="' . site_url('auth/logout') . '">Logout</a>';
    }
}
