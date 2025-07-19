<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        
        // Cek session, jika tidak ada, tendang ke login
        if ($this->session->userdata('status') !== 'login') {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Redirect ke fungsi dashboard
        redirect('dashboard/dashboard');
    }

    public function dashboard()
    {
        $username = $this->session->userdata('username');
        $nama = $this->session->userdata('nama');
        
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .navbar { background: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { margin: 0; }
        .navbar a { color: white; text-decoration: none; padding: 8px 16px; background: #dc3545; border-radius: 4px; }
        .navbar a:hover { background: #c82333; }
        .container { padding: 30px; }
        .welcome-box { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .stats { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); flex: 1; min-width: 200px; }
        .stat-box h3 { margin: 0 0 10px 0; color: #495057; }
        .stat-box .number { font-size: 2em; font-weight: bold; color: #007bff; }
        .menu-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-top: 30px; }
        .menu-btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 10px 10px 0; }
        .menu-btn:hover { background: #0056b3; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Dashboard Admin</h1>
        <a href="' . site_url('auth/logout') . '">Logout</a>
    </div>
    
    <div class="container">';
    
        // Tampilkan pesan success jika ada
        if ($this->session->flashdata('success')) {
            echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
        }
        
        echo '<div class="welcome-box">
            <h2>Selamat Datang, ' . ($nama ? $nama : $username) . '!</h2>
            <p>Anda telah berhasil login ke panel administrasi.</p>
        </div>
        
        <div class="stats">
            <div class="stat-box">
                <h3>Total Admin</h3>
                <div class="number">1</div>
            </div>
            <div class="stat-box">
                <h3>Status System</h3>
                <div class="number">Online</div>
            </div>
            <div class="stat-box">
                <h3>User Login</h3>
                <div class="number">' . $username . '</div>
            </div>
        </div>
        
        <div class="menu-box">
            <h3>Menu Administrasi</h3>
            <a href="' . site_url('calon') . '" class="menu-btn">Kelola Data Calon</a>
            <a href="' . site_url('test/session') . '" class="menu-btn">Test Session</a>
        </div>
    </div>
</body>
</html>';
    }
}
