<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_pemilih extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Pemilih_model');
    }

    public function index()
    {
        // Jika sudah login, redirect ke dashboard pemilih
        if ($this->session->userdata('pemilih_status') === 'login') {
            redirect('dashboard_pemilih');
        }
        
        // Tampilkan halaman login
        $this->login();
    }

    public function login()
    {
        // Jika sudah login, redirect ke dashboard pemilih
        if ($this->session->userdata('pemilih_status') === 'login') {
            redirect('dashboard_pemilih');
        }
        
        $this->load->view('auth_pemilih/login');
    }

    public function register()
    {
        // Jika sudah login, redirect ke dashboard pemilih
        if ($this->session->userdata('pemilih_status') === 'login') {
            redirect('dashboard_pemilih');
        }
        
        $this->load->view('auth_pemilih/register');
    }

    public function aksi_login()
    {
        // Validasi input dasar
        $nim = $this->input->post('nim');
        $password = $this->input->post('password');

        if (empty($nim) || empty($password)) {
            $this->session->set_flashdata('error', 'NIM dan password harus diisi!');
            redirect('auth_pemilih/login');
            return;
        }

        // Cari pemilih berdasarkan NIM
        $pemilih_data = $this->Pemilih_model->get_pemilih_by_nim($nim);
        
        if ($pemilih_data && password_verify($password, $pemilih_data['password'])) {
            // Login berhasil
            $session_data = array(
                'pemilih_id' => $pemilih_data['id_pemilih'],
                'pemilih_nim' => $pemilih_data['nim'],
                'pemilih_nama' => $pemilih_data['nama'],
                'pemilih_status_memilih' => $pemilih_data['status_memilih'],
                'pemilih_status' => 'login'
            );

            $this->session->set_userdata($session_data);
            $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $pemilih_data['nama']);

            redirect('dashboard_pemilih/dashboard');
        } else {
            // Login gagal
            $this->session->set_flashdata('error', 'Login gagal. Periksa NIM dan password Anda.');
            redirect('auth_pemilih/login');
        }
    }

    public function aksi_register()
    {
        $nim = $this->input->post('nim');
        $nama = $this->input->post('nama');
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');

        // Validasi input
        if (empty($nim) || empty($nama) || empty($password) || empty($confirm_password)) {
            $this->session->set_flashdata('error', 'Semua field harus diisi!');
            redirect('auth_pemilih/register');
            return;
        }

        // Validasi konfirmasi password
        if ($password !== $confirm_password) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak sesuai!');
            redirect('auth_pemilih/register');
            return;
        }

        // Validasi panjang password
        if (strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Password minimal 6 karakter!');
            redirect('auth_pemilih/register');
            return;
        }

        // Cek apakah NIM sudah terdaftar
        $existing_pemilih = $this->Pemilih_model->get_pemilih_by_nim($nim);
        if ($existing_pemilih) {
            $this->session->set_flashdata('error', 'NIM sudah terdaftar! Silakan gunakan NIM lain.');
            redirect('auth_pemilih/register');
            return;
        }

        // Siapkan data untuk database
        $data = array(
            'nim' => $nim,
            'nama' => $nama,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status_memilih' => 0,
            'created_at' => date('Y-m-d H:i:s')
        );

        // Insert ke database
        if ($this->Pemilih_model->insert_pemilih($data)) {
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
            redirect('auth_pemilih/login');
        } else {
            $this->session->set_flashdata('error', 'Registrasi gagal. Silakan coba lagi.');
            redirect('auth_pemilih/register');
        }
    }

    public function logout()
    {
        // Hapus semua data session pemilih
        $this->session->unset_userdata(array(
            'pemilih_id', 
            'pemilih_nim', 
            'pemilih_nama', 
            'pemilih_status_memilih', 
            'pemilih_status'
        ));
        
        $this->session->set_flashdata('success', 'Logout berhasil!');
        
        // Redirect ke halaman login
        redirect('auth_pemilih/login');
    }
}
