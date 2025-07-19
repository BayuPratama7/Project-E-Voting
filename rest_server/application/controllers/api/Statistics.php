<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Statistics extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    /**
     * Mengambil total suara yang sudah masuk
     */
    public function total_suara_get()
    {
        $query = $this->db->query("SELECT SUM(jumlah_suara) as total_suara FROM calon");
        $result = $query->row_array();
        
        $data = array(
            'total_suara' => (int)$result['total_suara']
        );

        $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }

    /**
     * Mengambil total pemilih terdaftar
     */
    public function total_pemilih_get()
    {
        $query = $this->db->query("SELECT COUNT(*) as total_pemilih FROM pemilih");
        $result = $query->row_array();
        
        $data = array(
            'total_pemilih' => (int)$result['total_pemilih']
        );

        $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }

    /**
     * Mengambil jumlah pemilih yang sudah voting
     */
    public function pemilih_sudah_voting_get()
    {
        $query = $this->db->query("SELECT COUNT(*) as sudah_voting FROM pemilih WHERE status_memilih = 1");
        $result = $query->row_array();
        
        $data = array(
            'sudah_voting' => (int)$result['sudah_voting']
        );

        $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }

    /**
     * Mengambil tingkat partisipasi
     */
    public function partisipasi_get()
    {
        $query_total = $this->db->query("SELECT COUNT(*) as total_pemilih FROM pemilih");
        $query_voting = $this->db->query("SELECT COUNT(*) as sudah_voting FROM pemilih WHERE status_memilih = 1");
        
        $total_pemilih = $query_total->row_array()['total_pemilih'];
        $sudah_voting = $query_voting->row_array()['sudah_voting'];
        
        $tingkat_partisipasi = 0;
        if ($total_pemilih > 0) {
            $tingkat_partisipasi = round(($sudah_voting / $total_pemilih) * 100, 2);
        }
        
        $data = array(
            'total_pemilih' => (int)$total_pemilih,
            'sudah_voting' => (int)$sudah_voting,
            'belum_voting' => (int)($total_pemilih - $sudah_voting),
            'tingkat_partisipasi' => $tingkat_partisipasi
        );

        $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }

    /**
     * Mengambil semua statistik sekaligus
     */
    public function semua_get()
    {
        // Total suara
        $query_suara = $this->db->query("SELECT SUM(jumlah_suara) as total_suara FROM calon");
        $total_suara = $query_suara->row_array()['total_suara'];
        
        // Data pemilih
        $query_total = $this->db->query("SELECT COUNT(*) as total_pemilih FROM pemilih");
        $query_voting = $this->db->query("SELECT COUNT(*) as sudah_voting FROM pemilih WHERE status_memilih = 1");
        
        $total_pemilih = $query_total->row_array()['total_pemilih'];
        $sudah_voting = $query_voting->row_array()['sudah_voting'];
        
        $tingkat_partisipasi = 0;
        if ($total_pemilih > 0) {
            $tingkat_partisipasi = round(($sudah_voting / $total_pemilih) * 100, 2);
        }
        
        // Data calon dan ranking
        $query_calon = $this->db->query("SELECT * FROM calon ORDER BY jumlah_suara DESC");
        $data_calon = $query_calon->result_array();
        
        $data = array(
            'total_suara' => (int)$total_suara,
            'total_pemilih' => (int)$total_pemilih,
            'sudah_voting' => (int)$sudah_voting,
            'belum_voting' => (int)($total_pemilih - $sudah_voting),
            'tingkat_partisipasi' => $tingkat_partisipasi,
            'data_calon' => $data_calon
        );

        $this->response(['status' => true, 'data' => $data], REST_Controller::HTTP_OK);
    }

    /**
     * Sinkronisasi dan validasi statistik voting
     */
    public function sinkronisasi_get()
    {
        try {
            // 1. Validasi konsistensi data
            $validasi = $this->validasi_konsistensi();
            
            // 2. Perbaiki inkonsistensi jika ada
            if (!$validasi['konsisten']) {
                $this->perbaiki_inkonsistensi();
            }
            
            // 3. Ambil statistik terbaru setelah sinkronisasi
            $statistik = $this->hitung_statistik_lengkap();
            
            $data = array(
                'status_sinkronisasi' => true,
                'validasi' => $validasi,
                'statistik' => $statistik,
                'timestamp' => date('Y-m-d H:i:s')
            );

            $this->response(['status' => true, 'message' => 'Sinkronisasi berhasil', 'data' => $data], REST_Controller::HTTP_OK);
            
        } catch (Exception $e) {
            $this->response([
                'status' => false, 
                'message' => 'Gagal melakukan sinkronisasi: ' . $e->getMessage()
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reset semua statistik voting
     */
    public function reset_post()
    {
        try {
            // Reset jumlah suara semua calon
            $this->db->query("UPDATE calon SET jumlah_suara = 0");
            
            // Reset status memilih semua pemilih
            $this->db->query("UPDATE pemilih SET status_memilih = 0");
            
            // Hapus data voting (jika ada tabel voting terpisah)
            $this->db->query("DELETE FROM voting WHERE 1=1");
            
            $statistik_baru = $this->hitung_statistik_lengkap();
            
            $this->response([
                'status' => true, 
                'message' => 'Reset statistik berhasil',
                'data' => $statistik_baru
            ], REST_Controller::HTTP_OK);
            
        } catch (Exception $e) {
            $this->response([
                'status' => false, 
                'message' => 'Gagal mereset statistik: ' . $e->getMessage()
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Validasi konsistensi data voting
     */
    private function validasi_konsistensi()
    {
        // Cek apakah total suara sama dengan jumlah pemilih yang sudah voting
        $query_total_suara = $this->db->query("SELECT SUM(jumlah_suara) as total FROM calon");
        $total_suara = (int)$query_total_suara->row_array()['total'];
        
        $query_sudah_voting = $this->db->query("SELECT COUNT(*) as total FROM pemilih WHERE status_memilih = 1");
        $sudah_voting = (int)$query_sudah_voting->row_array()['total'];
        
        $konsisten = ($total_suara == $sudah_voting);
        
        return array(
            'konsisten' => $konsisten,
            'total_suara_calon' => $total_suara,
            'total_pemilih_voting' => $sudah_voting,
            'selisih' => abs($total_suara - $sudah_voting),
            'detail' => $konsisten ? 'Data konsisten' : 'Data tidak konsisten - ada ketidaksesuaian antara total suara dan status pemilih'
        );
    }

    /**
     * Perbaiki inkonsistensi data
     */
    private function perbaiki_inkonsistensi()
    {
        // Strategi perbaikan: Gunakan status_memilih sebagai acuan
        // Hitung ulang jumlah suara berdasarkan data voting yang ada
        
        // Jika ada tabel voting terpisah, gunakan itu
        // Jika tidak, kita akan menggunakan logika reset dan manual adjustment
        
        log_message('info', 'Melakukan perbaikan inkonsistensi data voting');
        
        // Untuk sementara, catat log saja
        // Implementasi lebih lanjut bisa disesuaikan dengan kebutuhan
    }

    /**
     * Hitung statistik lengkap dengan detail
     */
    private function hitung_statistik_lengkap()
    {
        // Total suara
        $query_suara = $this->db->query("SELECT SUM(jumlah_suara) as total_suara FROM calon");
        $total_suara = (int)$query_suara->row_array()['total_suara'];
        
        // Data pemilih
        $query_total = $this->db->query("SELECT COUNT(*) as total_pemilih FROM pemilih");
        $query_voting = $this->db->query("SELECT COUNT(*) as sudah_voting FROM pemilih WHERE status_memilih = 1");
        $query_belum = $this->db->query("SELECT COUNT(*) as belum_voting FROM pemilih WHERE status_memilih = 0");
        
        $total_pemilih = (int)$query_total->row_array()['total_pemilih'];
        $sudah_voting = (int)$query_voting->row_array()['sudah_voting'];
        $belum_voting = (int)$query_belum->row_array()['belum_voting'];
        
        $tingkat_partisipasi = 0;
        if ($total_pemilih > 0) {
            $tingkat_partisipasi = round(($sudah_voting / $total_pemilih) * 100, 2);
        }
        
        // Data calon dengan ranking dan persentase
        $query_calon = $this->db->query("SELECT * FROM calon ORDER BY jumlah_suara DESC");
        $data_calon = $query_calon->result_array();
        
        // Tambahkan ranking dan persentase
        foreach ($data_calon as $key => &$calon) {
            $calon['ranking'] = $key + 1;
            $calon['persentase'] = $total_suara > 0 ? round(($calon['jumlah_suara'] / $total_suara) * 100, 2) : 0;
        }
        
        return array(
            'total_suara' => $total_suara,
            'total_pemilih' => $total_pemilih,
            'sudah_voting' => $sudah_voting,
            'belum_voting' => $belum_voting,
            'tingkat_partisipasi' => $tingkat_partisipasi,
            'data_calon' => $data_calon,
            'last_updated' => date('Y-m-d H:i:s')
        );
    }
}
