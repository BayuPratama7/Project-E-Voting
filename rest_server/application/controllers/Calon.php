<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Calon extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    /**
     * Mengambil data calon (GET)
     */
    public function index_get()
    {
        $id = $this->get('id_calon');
        $action = $this->get('action');
        
        // Handle special actions
        if ($action === 'reset_all_votes') {
            $this->db->set('jumlah_suara', 0);
            if ($this->db->update('calon')) {
                $affected_rows = $this->db->affected_rows();
                $this->response([
                    'status' => true, 
                    'message' => 'Berhasil mereset suara ' . $affected_rows . ' calon menjadi 0.',
                    'affected_rows' => $affected_rows
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => 'Gagal mereset suara calon.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
            return;
        }
        
        if ($action === 'reset_vote' && $id !== null) {
            $this->db->where('id_calon', $id);
            $this->db->set('jumlah_suara', 0);
            if ($this->db->update('calon')) {
                $this->response(['status' => true, 'message' => 'Suara calon berhasil direset menjadi 0.'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['status' => false, 'message' => 'Gagal mereset suara calon.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
            return;
        }
        
        // Normal GET behavior
        if ($id === null) {
            $data = $this->db->get('calon')->result_array();
        } else {
            $this->db->where('id_calon', $id);
            $data = $this->db->get('calon')->result_array();
        }

        if ($data) {
            $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Data tidak ditemukan'], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * Menambah data calon baru (POST)
     */
    public function index_post() {
        $data = [
            'nama_calon' => $this->post('nama_calon'),
            'visi'       => $this->post('visi'),
            'misi'       => $this->post('misi'),
            'foto'       => 'default.jpg'
        ];

        if (empty($data['nama_calon']) || empty($data['visi']) || empty($data['misi'])) {
             $this->response(['status' => false, 'message' => 'Nama, visi, dan misi tidak boleh kosong.'], REST_Controller::HTTP_BAD_REQUEST);
             return;
        }

        if (!empty($_FILES['foto']['name'])) {
            $this->load->library('upload');
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                $data['foto'] = $this->upload->data('file_name');
            } else {
                $this->response(['status' => false, 'message' => 'Gagal mengupload file: ' . $this->upload->display_errors('', '')], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }
        }

        if ($this->db->insert('calon', $data)) {
            $data['id_calon'] = $this->db->insert_id();
            $this->response(['status' => true, 'message' => 'Data calon berhasil ditambahkan.', 'data' => $data], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['status' => false, 'message' => 'Gagal menambahkan data ke database.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Mengubah data calon yang ada (PUT)
     */
    public function index_put() {
        // PERBAIKAN KUNCI: Gunakan $this->post() karena client mengirim data
        // melalui request POST asli (teknik method spoofing).
        $id = $this->post('id_calon');
        $data = [
            'nama_calon' => $this->post('nama_calon'),
            'visi'       => $this->post('visi'),
            'misi'       => $this->post('misi')
        ];

        // Tambahkan jumlah_suara jika dikirim
        if ($this->post('jumlah_suara') !== null) {
            $data['jumlah_suara'] = $this->post('jumlah_suara');
        }

        if (empty($id) || empty($data['nama_calon']) || empty($data['visi']) || empty($data['misi'])) {
             $this->response(['status' => false, 'message' => 'ID, Nama, visi, dan misi tidak boleh kosong.'], REST_Controller::HTTP_BAD_REQUEST);
             return;
        }

        if (!empty($_FILES['foto']['name'])) {
            $calon_lama = $this->db->get_where('calon', ['id_calon' => $id])->row();
            if (!$calon_lama) {
                $this->response(['status' => false, 'message' => 'ID Calon tidak ditemukan.'], REST_Controller::HTTP_NOT_FOUND);
                return;
            }
            $foto_lama = $calon_lama->foto;

            $this->load->library('upload');
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                if ($foto_lama && $foto_lama != 'default.jpg' && file_exists('./uploads/' . $foto_lama)) {
                    unlink('./uploads/' . $foto_lama);
                }
                $data['foto'] = $this->upload->data('file_name');
            } else {
                $this->response(['status' => false, 'message' => 'Gagal mengupload file baru: ' . $this->upload->display_errors('', '')], REST_Controller::HTTP_BAD_REQUEST);
                return;
            }
        }

        $this->db->where('id_calon', $id);
        if ($this->db->update('calon', $data)) {
            $this->response(['status' => true, 'message' => 'Data calon berhasil diubah.'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Gagal mengubah data di database.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Menghapus data calon (DELETE)
     */
    public function index_delete() {
        $id = $this->delete('id_calon');
        if ($id === null) {
            $this->response(['status' => false, 'message' => 'ID tidak boleh kosong.'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $calon = $this->db->get_where('calon', ['id_calon' => $id])->row();
        if (!$calon) {
            $this->response(['status' => false, 'message' => 'ID Calon tidak ditemukan.'], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        $foto_lama = $calon->foto;

        $this->db->where('id_calon', $id);
        if ($this->db->delete('calon')) {
            if ($foto_lama && $foto_lama != 'default.jpg' && file_exists('./uploads/' . $foto_lama)) {
                unlink('./uploads/' . $foto_lama);
            }
            $this->response(['status' => true, 'message' => 'Data calon berhasil dihapus.'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Gagal menghapus data.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reset vote untuk calon tertentu (GET)
     */
    public function reset_vote_get($id_calon = null) {
        if ($id_calon === null) {
            $this->response(['status' => false, 'message' => 'ID Calon diperlukan.'], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $this->db->where('id_calon', $id_calon);
        $this->db->set('jumlah_suara', 0);
        if ($this->db->update('calon')) {
            $this->response(['status' => true, 'message' => 'Suara calon berhasil direset menjadi 0.'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Gagal mereset suara calon.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reset suara semua calon menjadi 0 (GET)
     */
    public function reset_all_votes_get() {
        $this->db->set('jumlah_suara', 0);
        if ($this->db->update('calon')) {
            $affected_rows = $this->db->affected_rows();
            $this->response([
                'status' => true, 
                'message' => 'Berhasil mereset suara ' . $affected_rows . ' calon menjadi 0.',
                'affected_rows' => $affected_rows
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Gagal mereset suara calon.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
