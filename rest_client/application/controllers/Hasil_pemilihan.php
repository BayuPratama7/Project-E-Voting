<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Hasil_pemilihan extends CI_Controller {

    private $_client;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        
        // Inisialisasi Guzzle Client
        $this->_client = new Client([
            'base_uri' => 'http://localhost/y/rest_server/api/',
            'timeout'  => 5.0
        ]);
    }

    public function index()
    {
        $this->hasil();
    }

    public function hasil()
    {
        // Ambil semua data statistik sekaligus dari API
        $statistik = $this->get_all_statistics();
        
        // Siapkan data untuk view
        $data = array(
            'data_hasil' => isset($statistik['data_calon']) ? $statistik['data_calon'] : [],
            'total_suara' => isset($statistik['total_suara']) ? $statistik['total_suara'] : 0,
            'total_pemilih' => isset($statistik['total_pemilih']) ? $statistik['total_pemilih'] : 0,
            'tingkat_partisipasi' => isset($statistik['tingkat_partisipasi']) ? $statistik['tingkat_partisipasi'] : 0
        );
        
        // Load view
        $this->load->view('pemilihan/hasil', $data);
    }

    private function get_all_statistics()
    {
        try {
            $response = $this->_client->request('GET', 'statistics/semua');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']) && is_array($result['data'])) {
                return $result['data'];
            }
        } catch (Exception $e) {
            // Handle error, fallback to individual calls
            return $this->get_fallback_statistics();
        }
        
        return [];
    }

    private function get_fallback_statistics()
    {
        $data_hasil = $this->get_hasil_pemilihan();
        $total_suara = $this->get_total_suara();
        $total_pemilih = $this->get_total_pemilih();
        $tingkat_partisipasi = $this->get_tingkat_partisipasi();
        
        return [
            'data_calon' => $data_hasil,
            'total_suara' => $total_suara,
            'total_pemilih' => $total_pemilih,
            'tingkat_partisipasi' => $tingkat_partisipasi
        ];
    }

    private function get_hasil_pemilihan()
    {
        try {
            $response = $this->_client->request('GET', 'calon');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']) && is_array($result['data'])) {
                // Urutkan berdasarkan jumlah suara terbanyak
                $data = $result['data'];
                usort($data, function($a, $b) {
                    return $b['jumlah_suara'] - $a['jumlah_suara'];
                });
                return $data;
            }
        } catch (Exception $e) {
            // Handle error silently
        }
        
        return [];
    }

    private function get_total_suara()
    {
        try {
            $response = $this->_client->request('GET', 'statistics/total_suara');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']['total_suara'])) {
                return $result['data']['total_suara'];
            }
        } catch (Exception $e) {
            // Handle error, fallback to manual calculation
        }
        
        // Fallback: hitung manual dari data calon
        $data_hasil = $this->get_hasil_pemilihan();
        $total = 0;
        foreach ($data_hasil as $calon) {
            $total += $calon['jumlah_suara'];
        }
        return $total;
    }

    private function get_total_pemilih()
    {
        try {
            $response = $this->_client->request('GET', 'statistics/total_pemilih');
            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['data']['total_pemilih'])) {
                return $result['data']['total_pemilih'];
            }
        } catch (Exception $e) {
            // Handle error silently
        }
        
        return 0;
    }

    private function get_tingkat_partisipasi()
    {
        $total_suara = $this->get_total_suara();
        $total_pemilih = $this->get_total_pemilih();
        
        if ($total_pemilih > 0) {
            return round(($total_suara / $total_pemilih) * 100, 2);
        }
        
        return 0;
    }
}
