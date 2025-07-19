<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    var $API ="";

    function __construct() {
        parent::__construct();
        // Sesuaikan URL dengan lokasi rest_server Anda
        $this->API = "http://localhost/y/rest_server";
    }

    // Halaman login
    public function index() {
        if($this->session->userdata('logged_in')){
            redirect('pemilihan');
        }
        $this->load->view('login');
    }

    // Proses login
    function login_process() {
        if (isset($_POST['submit'])) {
            $params = array(
                'nim'       =>  $this->input->post('nim'),
                'password'  =>  $this->input->post('password'),
                'X-API-KEY' => 'himsi-key' // API Key
            );

            $response = $this->curl->simple_post($this->API.'/api/auth/login', $params);
            $data = json_decode($response);

            if ($data && $data->status == TRUE) {
                // Buat session
                $session_data = array(
                    'id_pemilih' => $data->data->id_pemilih,
                    'nim'        => $data->data->nim,
                    'nama'       => $data->data->nama,
                    'logged_in'  => TRUE
                );
                $this->session->set_userdata($session_data);
                redirect('pemilihan');
            } else {
                // Jika login gagal
                $message = ($data && isset($data->message)) ? $data->message : 'Terjadi kesalahan saat login.';
                $this->session->set_flashdata('error', $message);
                redirect('auth');
            }
        } else {
            redirect('auth');
        }
    }

    // Proses logout
    function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}
