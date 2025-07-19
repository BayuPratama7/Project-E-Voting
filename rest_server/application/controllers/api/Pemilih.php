<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Pemilih extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model('Pemilih_model', 'pemilih');
    }

    // Menampilkan data pemilih
    public function index_get() {
        $id = $this->get('id');
        if ($id === null) {
            $pemilih = $this->pemilih->getPemilih();
        } else {
            $pemilih = $this->pemilih->getPemilih($id);
        }

        if ($pemilih) {
            $this->response([
                'status' => TRUE,
                'data' => $pemilih
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'ID tidak ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    // Menghapus data pemilih
    public function index_delete() {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status' => FALSE,
                'message' => 'Berikan ID yang akan dihapus'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->pemilih->deletePemilih($id) > 0) {
                $this->response([
                    'status' => TRUE,
                    'id' => $id,
                    'message' => 'Data berhasil dihapus'
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'ID tidak ditemukan'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    // Menambah data pemilih baru
    public function index_post() {
        $data = [
            'nim' => $this->post('nim'),
            'nama' => $this->post('nama'),
            'password' => $this->post('password'),
            'status_memilih' => $this->post('status_memilih') ? $this->post('status_memilih') : 0
        ];

        // Validasi input
        if (empty($data['nim']) || empty($data['nama']) || empty($data['password'])) {
            $this->response([
                'status' => FALSE, 
                'message' => 'NIM, Nama, dan Password wajib diisi'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Cek apakah NIM sudah ada
        $existing = $this->pemilih->getPemilih();
        foreach($existing as $pemilih) {
            if($pemilih['nim'] == $data['nim']) {
                $this->response([
                    'status' => FALSE, 
                    'message' => 'NIM sudah terdaftar'
                ], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }
        }

        if ($this->pemilih->createPemilih($data) > 0) {
            $this->response([
                'status' => TRUE, 
                'message' => 'Data pemilih baru berhasil ditambahkan'
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => FALSE, 
                'message' => 'Gagal menambahkan data'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    // Memperbarui data pemilih
    public function index_put() {
        $id = $this->put('id');
        
        // Ambil data yang akan diupdate, hanya yang dikirim
        $data = [];
        if ($this->put('nama') !== null) {
            $data['nama'] = $this->put('nama');
        }
        if ($this->put('nik') !== null) {
            $data['nik'] = $this->put('nik');
        }
        if ($this->put('alamat') !== null) {
            $data['alamat'] = $this->put('alamat');
        }
        if ($this->put('status_memilih') !== null) {
            $data['status_memilih'] = $this->put('status_memilih');
        }
        if ($this->put('nim') !== null) {
            $data['nim'] = $this->put('nim');
        }
        if ($this->put('password') !== null) {
            $data['password'] = $this->put('password');
        }

        if (empty($data)) {
            $this->response(['status' => FALSE, 'message' => 'Tidak ada data yang akan diupdate'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        if ($this->pemilih->updatePemilih($data, $id) > 0) {
            $this->response(['status' => TRUE, 'message' => 'Data pemilih berhasil diperbarui'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => FALSE, 'message' => 'Gagal memperbarui data atau data tidak berubah'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}

