<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library('session');
    }

    public function index()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('status') === 'login') {
            redirect('dashboard/dashboard');
        }
        
        // Tampilkan halaman login
        $this->login();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('status') === 'login') {
            redirect('dashboard/dashboard');
        }
        
        // Tampilkan halaman login menggunakan view
        $this->load->view('auth/admin_login');
    }

    public function aksi_login()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        // Validasi input
        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan password harus diisi!');
            redirect('auth/login');
            return;
        }

        // Validasi sederhana (untuk development)
        if ($username === 'admin' && $password === '1234') {
            // Jika kredensial benar, buat session
            $session_data = array(
                'user_id' => 1,
                'username' => $username,
                'nama' => 'Administrator',
                'status' => 'login'
            );

            $this->session->set_userdata($session_data);
            $this->session->set_flashdata('success', 'Login berhasil! Selamat datang Administrator');

            // Redirect ke dashboard
            redirect('dashboard/dashboard');
        } else {
            // Jika kredensial salah
            $this->session->set_flashdata('error', 'Username atau password salah!');
            redirect('auth/login');
        }
    }

    public function logout()
    {
        // Hapus semua data session
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logout berhasil!');
        
        // Redirect ke halaman login
        redirect('auth/login');
    }
}
