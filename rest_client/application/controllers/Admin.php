<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    var $API = "";

    function __construct() {
        parent::__construct();
        // Sesuaikan URL dengan lokasi rest_server Anda
        $this->API = "http://localhost/y/rest_server";
        $this->load->library('curl');
        $this->load->helper('url');
    }

    // Halaman untuk menampilkan hasil perolehan suara
        public function index() {
        // Panggil API untuk mendapatkan daftar calon beserta jumlah suaranya
        $params = array('X-API-KEY' => 'himsi-key');
        $response = $this->curl->simple_get($this->API.'/api/pemilihan/calon', $params);
        $data['hasil'] = json_decode($response);

        // Pastikan respons valid dan memiliki data
        if ($data['hasil'] && isset($data['hasil']->data)) {
            // Hitung total suara untuk persentase
            $data['total_suara'] = array_sum(array_column($data['hasil']->data, 'jumlah_suara'));
        } else {
            $data['total_suara'] = 0; // Set total suara ke 0 jika tidak ada data
        }

        $this->load->view('admin/hasil', $data);
    }

 }

