<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Calon extends CI_Controller {

    private $_client;

    public function __construct()
    {
        parent::__construct();
        // Cek session, jika tidak ada, tendang ke login
        if ($this->session->userdata('status') !== 'login') {
            redirect('auth/login');
        }

        // Muat helper yang diperlukan
        $this->load->helper(array('form', 'url'));

        // Inisialisasi Guzzle Client
        $this->_client = new Client([
            'base_uri' => 'http://localhost/y/rest_server/api/', // Sesuaikan dengan URL API Anda
            'timeout'  => 5.0
        ]);
    }

    /**
     * Menampilkan daftar semua calon (READ)
     */
    public function index()
    {
        try {
            $response = $this->_client->request('GET', 'calon');
            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['data']) && is_array($result['data'])) {
                $data['calon'] = $result['data'];
            } else {
                $data['calon'] = [];
                $errorMessage = isset($result['message']) ? $result['message'] : 'Gagal mengambil data atau format tidak valid.';
                $this->session->set_flashdata('error', $errorMessage);
            }
            $data['title'] = "Kelola Data Calon";
            $this->load->view('calon/index', $data);

        } catch (ConnectException $e) {
            show_error("Tidak dapat terhubung ke API server. Pastikan rest_server berjalan dan URL benar.", 503, "Koneksi Gagal");
        } catch (ClientException $e) {
            $response = $e->getResponse();
            show_error("Gagal mengambil data dari API: " . $response->getBody()->getContents(), $response->getStatusCode(), "Error dari API Server");
        }
    }

    /**
     * Menampilkan form untuk menambah data calon
     */
    public function tambah()
    {
        $data['title'] = 'Form Tambah Data Calon';
        $this->load->view('calon/tambah', $data);
    }

    /**
     * Memproses data dari form tambah
     */
    public function tambah_action()
    {
        try {
            $multipart = [
                ['name' => 'nama_calon', 'contents' => $this->input->post('nama_calon', true)],
                ['name' => 'visi', 'contents' => $this->input->post('visi', true)],
                ['name' => 'misi', 'contents' => $this->input->post('misi', true)]
            ];

            if (!empty($_FILES['foto']['name'])) {
                $multipart[] = [
                    'name'     => 'foto',
                    'contents' => fopen($_FILES['foto']['tmp_name'], 'r'),
                    'filename' => $_FILES['foto']['name']
                ];
            }

            $this->_client->request('POST', 'calon', ['multipart' => $multipart]);
            $this->session->set_flashdata('success', 'Data calon berhasil ditambahkan');
            redirect('calon');

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->session->set_flashdata('error', 'Gagal menambahkan data: ' . $response->getBody()->getContents());
            redirect('calon/tambah');
        }
    }

    /**
     * Menampilkan form untuk mengubah data calon
     */
    public function ubah($id)
    {
        try {
            $response = $this->_client->request('GET', 'calon', ['query' => ['id_calon' => $id]]);
            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['data'][0])) {
                $data['calon'] = $result['data'][0];
                $data['title'] = 'Form Ubah Data Calon';
                $this->load->view('calon/ubah', $data);
            } else {
                $this->session->set_flashdata('error', 'Data calon tidak ditemukan di API.');
                redirect('calon');
            }
        } catch (Exception $e) {
            show_error("Gagal mengambil data dari API: " . $e->getMessage(), 500, "Error dari API Server");
        }
    }

    /**
     * Memproses data dari form ubah
     */
    public function ubah_action()
    {
        try {
            $multipart = [
                ['name' => '_method', 'contents' => 'PUT'],
                ['name' => 'id_calon', 'contents' => $this->input->post('id_calon', true)],
                ['name' => 'nama_calon', 'contents' => $this->input->post('nama_calon', true)],
                ['name' => 'visi', 'contents' => $this->input->post('visi', true)],
                ['name' => 'misi', 'contents' => $this->input->post('misi', true)],
            ];

            if (!empty($_FILES['foto']['name'])) {
                $multipart[] = [
                    'name'     => 'foto',
                    'contents' => fopen($_FILES['foto']['tmp_name'], 'r'),
                    'filename' => $_FILES['foto']['name']
                ];
            }

            $this->_client->request('POST', 'calon', ['multipart' => $multipart]);
            $this->session->set_flashdata('success', 'Data calon berhasil diubah');
            redirect('calon');

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->session->set_flashdata('error', 'Gagal mengubah data: ' . $response->getBody()->getContents());
            redirect('calon/ubah/' . $this->input->post('id_calon'));
        }
    }

    /**
     * Menghapus data calon berdasarkan ID
     */
    public function hapus($id)
    {
        try {
            $this->_client->request('DELETE', 'calon', ['form_params' => ['id_calon' => $id]]);
            $this->session->set_flashdata('success', 'Data calon berhasil dihapus');
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $this->session->set_flashdata('error', 'Gagal menghapus data: ' . $response->getBody()->getContents());
        }
        redirect('calon');
    }
}
