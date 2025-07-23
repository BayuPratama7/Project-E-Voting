<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        echo "<h1>🎉 BERHASIL! CodeIgniter Controller Bekerja!</h1>";
        echo "<p>Timestamp: " . date('Y-m-d H:i:s') . "</p>";
        echo "<p>Base URL: " . base_url() . "</p>";
        echo "<p>Site URL: " . site_url() . "</p>";
        
        echo "<h2>✅ Working Links:</h2>";
        echo "<a href='" . site_url('test/database') . "'>Test Database</a><br>";
        echo "<a href='" . site_url('test/session') . "'>Test Session</a><br>";
        echo "<a href='" . site_url('welcome') . "'>Back to Welcome</a><br>";
        echo "<a href='" . site_url('auth_pemilih/login') . "'>Login Pemilih</a><br>";
        echo "<a href='" . site_url('auth_pemilih/register') . "'>Register Pemilih</a><br>";
    }
    
    public function database()
    {
        echo "<h1>🔍 Database Connection Test</h1>";
        
        $this->load->database();
        
        try {
            // Test connection
            if ($this->db->conn_id) {
                echo "<p style='color: green;'>✅ Database Connected!</p>";
                
                // Test tables
                $tables = ['pemilih', 'admin', 'kandidat', 'suara'];
                foreach ($tables as $table) {
                    $query = $this->db->query("SHOW TABLES LIKE '$table'");
                    if ($query->num_rows() > 0) {
                        echo "<p style='color: green;'>✅ Table '$table' exists</p>";
                        
                        // Count records
                        $count = $this->db->count_all($table);
                        echo "<p>&nbsp;&nbsp;&nbsp;Records: $count</p>";
                    } else {
                        echo "<p style='color: red;'>❌ Table '$table' not found</p>";
                    }
                }
            } else {
                echo "<p style='color: red;'>❌ Database connection failed</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
        
        echo "<p><a href='" . site_url('test') . "'>← Back to Test</a></p>";
    }

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
