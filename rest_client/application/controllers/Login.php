<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('status') === 'login') {
            redirect('dashboard');
        }
    }

    public function index()
    {
        // Tampilkan halaman form login
        // Anda bisa membuat view sendiri atau menampilkannya langsung
        // Contoh: $this->load->view('login_view');
        echo "<h1>Halaman Login Admin</h1>";
        echo "<form action='".site_url('login/aksi_login')."' method='post'>";
        echo "Username: <input type='text' name='username'><br>";
        echo "Password: <input type='password' name='password'><br>";
        echo "<button type='submit'>Login</button>";
        echo "</form>";
    }

    public function aksi_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        // Di sini Anda bisa memvalidasi ke database atau ke REST API Server.
        // Untuk contoh ini, kita gunakan validasi sederhana.
        // Di aplikasi nyata, Anda akan menggunakan library cURL untuk bertanya ke rest_server
        if ($username === 'admin' && $password === '1234') {
            // Jika kredensial benar
            $session_data = array(
                'username' => $username,
                'status' => 'login'
            );

            $this->session->set_userdata($session_data);

            // Redirect ke controller dashboard
            redirect('dashboard');
        } else {
            // Jika kredensial salah
            // Anda bisa menggunakan flashdata untuk menampilkan pesan error
            // $this->session->set_flashdata('error', 'Username atau Password salah!');
            echo "Username atau password salah!";
            echo "<br><a href='".site_url('login')."'>Kembali ke Login</a>";
            // Redirect kembali ke halaman login
            // redirect('login');
        }
    }

    public function logout()
    {
        // Hapus semua data session
        $this->session->sess_destroy();
        // Redirect ke halaman login
        redirect('login');
    }
}
