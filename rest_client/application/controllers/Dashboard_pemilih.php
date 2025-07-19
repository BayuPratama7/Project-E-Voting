<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Dashboard_pemilih extends CI_Controller {

    private $_client;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        
        // Cek session pemilih, jika tidak ada, tendang ke login
        if ($this->session->userdata('pemilih_status') !== 'login') {
            redirect('auth_pemilih/login');
        }

        // Inisialisasi Guzzle Client
        $this->_client = new Client([
            'base_uri' => 'http://localhost/y/rest_server/api/',
            'timeout'  => 5.0
        ]);
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $pemilih_nama = $this->session->userdata('pemilih_nama');
        $pemilih_nim = $this->session->userdata('pemilih_nim');
        $status_memilih = $this->session->userdata('pemilih_status_memilih');
        
        // Ambil data calon dari API
        $data_calon = $this->get_calon_data();
        
        // Siapkan data untuk view
        $data = array(
            'pemilih_nama' => $pemilih_nama,
            'pemilih_nim' => $pemilih_nim,
            'status_memilih' => $status_memilih,
            'data_calon' => $data_calon
        );
        
        // Load view
        $this->load->view('pemilih/dashboard', $data);
    }

    public function pilih_calon()
    {
        // Pastikan request method adalah POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_404();
            return;
        }

        // Cek apakah pemilih sudah memilih
        $status_memilih = $this->session->userdata('pemilih_status_memilih');
        if ($status_memilih == '1') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Anda sudah memilih sebelumnya.'
            ]);
            return;
        }

        $id_calon = $this->input->post('id_calon', true);
        $pemilih_id = $this->session->userdata('pemilih_id');

        if (empty($id_calon) || empty($pemilih_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data tidak lengkap. Silakan login terlebih dahulu.'
            ]);
            return;
        }

        try {
            // Panggil API untuk memilih calon
            $response = $this->_client->request('POST', 'pemilihan/vote', [
                'form_params' => [
                    'id_calon' => $id_calon,
                    'id_pemilih' => $pemilih_id
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['status']) && $result['status'] === true) {
                // Update session pemilih
                $this->session->set_userdata('pemilih_status_memilih', '1');
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Voting berhasil!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => isset($result['message']) ? $result['message'] : 'Voting gagal.'
                ]);
            }

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);
            echo json_encode([
                'status' => 'error',
                'message' => isset($result['message']) ? $result['message'] : 'Voting gagal.'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem.'
            ]);
        }
    }

    private function get_calon_data()
    {
        try {
            $response = $this->_client->request('GET', 'calon');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']) && is_array($result['data'])) {
                return $result['data'];
            }
        } catch (Exception $e) {
            // Handle error silently
        }
        
        return [];
    }
}
