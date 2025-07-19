<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pemilihan extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Calon_model', 'calon');
        $this->load->model('Pemilih_model', 'pemilih');
    }

    public function calon_get()
    {
        $calon = $this->calon->get_all_calon();

        if ($calon) {
            $this->response([
                'status' => TRUE,
                'data' => $calon
            ], REST_Controller::HTTP_OK);
        } else {
             $this->response([
                'status' => FALSE,
                'message' => 'Tidak ada calon yang ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function vote_post()
    {
        $id_calon = $this->post('id_calon');
        $id_pemilih = $this->post('id_pemilih');

        if ($id_calon === null || $id_pemilih === null) {
            $this->response([
                'status' => FALSE,
                'message' => 'ID Calon dan ID Pemilih diperlukan'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $vote = $this->calon->tambah_suara($id_calon);
        $update_status = $this->pemilih->update_status_memilih($id_pemilih);

        if ($vote > 0 && $update_status > 0) {
            $this->response(['status' => TRUE, 'message' => 'Terima kasih, suara Anda telah dicatat.'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['status' => FALSE, 'message' => 'Gagal memberikan suara.'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}

