<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Auth_pemilih extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library('session');
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
        $nim = $this->input->post('nim', true);
        $password = $this->input->post('password', true);

        // Validasi input
        if (empty($nim) || empty($password)) {
            $this->session->set_flashdata('error', 'NIM dan password harus diisi!');
            redirect('auth_pemilih/login');
            return;
        }

        try {
            // Panggil API login pemilih
            $client = new Client([
                'base_uri' => 'http://localhost/y/rest_server/api/',
                'timeout'  => 5.0
            ]);

            $response = $client->request('POST', 'auth/login', [
                'form_params' => [
                    'nim' => $nim,
                    'password' => $password
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['status']) && $result['status'] === true) {
                // Login berhasil, buat session pemilih
                $pemilih_data = $result['data'];
                
                $session_data = array(
                    'pemilih_id' => $pemilih_data['id_pemilih'],
                    'pemilih_nim' => $pemilih_data['nim'],
                    'pemilih_nama' => $pemilih_data['nama'],
                    'pemilih_status_memilih' => $pemilih_data['status_memilih'],
                    'pemilih_status' => 'login'
                );

                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Login berhasil! Selamat datang ' . $pemilih_data['nama']);

                // Redirect ke dashboard pemilih
                redirect('dashboard_pemilih');
            } else {
                // Login gagal
                $error_msg = isset($result['message']) ? $result['message'] : 'Login gagal. Periksa NIM dan password Anda.';
                $this->session->set_flashdata('error', $error_msg);
                redirect('auth_pemilih/login');
            }

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);
            $error_msg = isset($result['message']) ? $result['message'] : 'Login gagal. Periksa NIM dan password Anda.';
            $this->session->set_flashdata('error', $error_msg);
            redirect('auth_pemilih/login');
        } catch (ConnectException $e) {
            $this->session->set_flashdata('error', 'Tidak dapat terhubung ke server. Silakan coba lagi.');
            redirect('auth_pemilih/login');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            redirect('auth_pemilih/login');
        }
    }

    public function aksi_register()
    {
        $nim = $this->input->post('nim', true);
        $nama = $this->input->post('nama', true);
        $password = $this->input->post('password', true);
        $confirm_password = $this->input->post('confirm_password', true);

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

        try {
            // Panggil API register pemilih
            $client = new Client([
                'base_uri' => 'http://localhost/y/rest_server/api/',
                'timeout'  => 5.0
            ]);

            $response = $client->request('POST', 'auth/register', [
                'form_params' => [
                    'nim' => $nim,
                    'nama' => $nama,
                    'password' => $password,
                    'confirm_password' => $confirm_password
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['status']) && $result['status'] === true) {
                // Registrasi berhasil
                $this->session->set_flashdata('success', $result['message']);
                redirect('auth_pemilih/login');
            } else {
                // Registrasi gagal
                $error_msg = isset($result['message']) ? $result['message'] : 'Registrasi gagal. Silakan coba lagi.';
                $this->session->set_flashdata('error', $error_msg);
                redirect('auth_pemilih/register');
            }

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);
            $error_msg = isset($result['message']) ? $result['message'] : 'Registrasi gagal. Silakan coba lagi.';
            $this->session->set_flashdata('error', $error_msg);
            redirect('auth_pemilih/register');
        } catch (ConnectException $e) {
            $this->session->set_flashdata('error', 'Tidak dapat terhubung ke server. Silakan coba lagi.');
            redirect('auth_pemilih/register');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
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
