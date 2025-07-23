<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_pemilih extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Calon_model');
        $this->load->model('Pemilih_model');
        
        // Cek session pemilih, jika tidak ada, tendang ke login
        if ($this->session->userdata('pemilih_status') !== 'login') {
            redirect('auth_pemilih/login');
        }
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
        
        // Ambil data calon dari database
        $data_calon = $this->Calon_model->get_all_calon();
        
        // Siapkan data untuk view
        $data = array(
            'pemilih_nama' => $pemilih_nama,
            'pemilih_nim' => $pemilih_nim,
            'status_memilih' => $status_memilih,
            'data_calon' => $data_calon
        );
        
        // Cek apakah view ada
        if (file_exists(APPPATH.'views/pemilih/dashboard.php')) {
            $this->load->view('pemilih/dashboard', $data);
        } else {
            // Tampilkan dashboard sederhana
            echo "<h1>Dashboard Pemilih</h1>";
            echo "<p>Selamat datang, " . $pemilih_nama . "</p>";
            echo "<p>NIM: " . $pemilih_nim . "</p>";
            echo "<p>Status Memilih: " . ($status_memilih == '1' ? 'Sudah Memilih' : 'Belum Memilih') . "</p>";
            echo "<p><a href='" . site_url('auth_pemilih/logout') . "'>Logout</a></p>";
        }
    }

    public function pilih_calon()
    {
        // Set proper JSON header
        header('Content-Type: application/json');
        
        // Pastikan request method adalah POST
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Method tidak diperbolehkan.'
            ]);
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
                'message' => 'Data tidak lengkap. Silakan login terlebih dahulu. ID Calon: ' . $id_calon . ', Pemilih ID: ' . $pemilih_id
            ]);
            return;
        }

        try {
            // Mulai transaksi database
            $this->db->trans_start();
            
            // Tambah suara ke calon
            $result1 = $this->Calon_model->tambah_suara($id_calon);
            
            // Update status pemilih sudah memilih
            $result2 = $this->Pemilih_model->update_pemilih($pemilih_id, array('status_memilih' => 1));
            
            // Selesaikan transaksi
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE || !$result1 || !$result2) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Voting gagal disimpan. Database error.'
                ]);
            } else {
                // Update session pemilih
                $this->session->set_userdata('pemilih_status_memilih', '1');
                
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Voting berhasil!'
                ]);
            }

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

}
