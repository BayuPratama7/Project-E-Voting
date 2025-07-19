<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Calon extends REST_Controller {

    function __construct() {
        parent::__construct();
        // Memuat komponen yang dibutuhkan
        $this->load->database();
        $this->load->helper('url');
    }

    /**
     * Mengambil data calon (GET)
     */
    public function index_get()
    {
        $id = $this->get('id_calon');
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
            'foto'       => 'default.jpg' // Foto default jika tidak ada yang diupload
        ];

        // Validasi sederhana
        if (empty($data['nama_calon']) || empty($data['visi']) || empty($data['misi'])) {
             $this->response(['status' => false, 'message' => 'Nama, visi, dan misi tidak boleh kosong.'], REST_Controller::HTTP_BAD_REQUEST);
             return;
        }

        // Menangani proses upload file
        if (!empty($_FILES['foto']['name'])) {
            $this->load->library('upload');
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048; // 2MB
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
        // ==================================================================
        // PERBAIKAN KUNCI ADA DI SINI
        // Kita menggunakan helper input bawaan CodeIgniter ($this->input->post())
        // karena data asli dikirim melalui metode POST.
        // ==================================================================
        $id = $this->input->post('id_calon');
        $data = [
            'nama_calon' => $this->input->post('nama_calon'),
            'visi'       => $this->input->post('visi'),
            'misi'       => $this->input->post('misi')
        ];

        // Validasi sederhana
        if (empty($id) || empty($data['nama_calon']) || empty($data['visi']) || empty($data['misi'])) {
             $this->response(['status' => false, 'message' => 'ID, Nama, visi, dan misi tidak boleh kosong.'], REST_Controller::HTTP_BAD_REQUEST);
             return;
        }

        // Cek jika ada file baru yang diupload
        if (!empty($_FILES['foto']['name'])) {
            // Ambil data foto lama sebelum diupdate
            $calon_lama = $this->db->get_where('calon', ['id_calon' => $id])->row();
            if (!$calon_lama) {
                $this->response(['status' => false, 'message' => 'ID Calon tidak ditemukan.'], REST_Controller::HTTP_NOT_FOUND);
                return;
            }
            $foto_lama = $calon_lama->foto;

            // Proses upload file baru
            $this->load->library('upload');
            $config['upload_path']   = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto')) {
                // Jika upload berhasil, hapus file lama (jika bukan default)
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

        // Ambil data foto sebelum dihapus dari DB
        $calon = $this->db->get_where('calon', ['id_calon' => $id])->row();
        if (!$calon) {
            $this->response(['status' => false, 'message' => 'ID Calon tidak ditemukan.'], REST_Controller::HTTP_NOT_FOUND);
            return;
        }
        $foto_lama = $calon->foto;

        // Hapus data dari database
        $this->db->where('id_calon', $id);
        if ($this->db->delete('calon')) {
            // Jika berhasil hapus dari DB, hapus juga file fotonya
            if ($foto_lama && $foto_lama != 'default.jpg' && file_exists('./uploads/' . $foto_lama)) {
                unlink('./uploads/' . $foto_lama);
            }
            $this->response(['status' => true, 'message' => 'Data calon berhasil dihapus.'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['status' => false, 'message' => 'Gagal menghapus data.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
