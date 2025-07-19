<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        
        // Cek session, jika tidak ada, tendang ke login
        if ($this->session->userdata('status') !== 'login') {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Redirect ke fungsi dashboard
        redirect('dashboard/dashboard');
    }

    public function dashboard()
    {
        $username = $this->session->userdata('username');
        $nama = $this->session->userdata('nama');
        
        // Ambil data dari API untuk statistik
        $stats_calon = $this->get_calon_stats();
        $stats_pemilih = $this->get_pemilih_stats();
        
        // Siapkan data untuk view
        $data = array(
            'username' => $username,
            'nama' => $nama,
            'stats_calon' => $stats_calon,
            'stats_pemilih' => $stats_pemilih
        );
        
        // Load view
        $this->load->view('admin/dashboard', $data);
    }

    private function get_calon_stats()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'http://localhost/y/rest_server/api/calon');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']) && is_array($result['data'])) {
                $total_suara = 0;
                foreach ($result['data'] as $calon) {
                    if (isset($calon['jumlah_suara'])) {
                        $total_suara += (int)$calon['jumlah_suara'];
                    }
                }
                return [
                    'total' => count($result['data']),
                    'total_suara' => $total_suara
                ];
            }
        } catch (Exception $e) {
            // Handle error silently
        }
        
        return [
            'total' => 0,
            'total_suara' => 0
        ];
    }

    private function get_pemilih_stats()
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'http://localhost/y/rest_server/api/pemilih');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']) && is_array($result['data'])) {
                $total = count($result['data']);
                $sudah_memilih = 0;
                
                foreach ($result['data'] as $pemilih) {
                    if ($pemilih['status_memilih'] == '1') {
                        $sudah_memilih++;
                    }
                }
                
                return [
                    'total' => $total,
                    'sudah_memilih' => $sudah_memilih,
                    'belum_memilih' => $total - $sudah_memilih
                ];
            }
        } catch (Exception $e) {
            // Handle error silently
        }
        
        return [
            'total' => 0,
            'sudah_memilih' => 0,
            'belum_memilih' => 0
        ];
    }
}
