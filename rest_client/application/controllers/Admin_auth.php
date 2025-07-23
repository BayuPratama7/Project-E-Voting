<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library('session');
    }

    public function index()
    {
        $this->login();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('status') === 'login') {
            redirect('admin/dashboard');
        }
        
        // Load view login admin
        $data['error'] = $this->session->flashdata('error');
        $data['success'] = $this->session->flashdata('success');
        $this->load->view('auth/login', $data);
    }

    public function login_action()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        // Validasi admin dengan multiple password
        $valid_passwords = ['admin', '1234', 'admin123'];
        
        if ($username === 'admin' && in_array($password, $valid_passwords)) {
            // Set session data dengan multiple indicators
            $session_data = array(
                'admin_id' => 1,
                'username' => 'admin',
                'logged_in' => TRUE,
                'is_admin' => TRUE,
                'status' => 'login',
                'user_type' => 'admin',
                'login_time' => date('Y-m-d H:i:s'),
                'last_activity' => time()
            );
            
            $this->session->set_userdata($session_data);
            $this->session->set_flashdata('success', 'Login berhasil! Selamat datang Admin.');
            
            // Redirect ke dashboard admin
            redirect('admin/dashboard');
        } else {
            // Login gagal
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('admin_auth/login');
        }
    }

    public function logout()
    {
        // Hapus semua data session
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logout berhasil!');
        
        // Redirect ke halaman login
        redirect('admin_auth/login');
    }
}
?>
