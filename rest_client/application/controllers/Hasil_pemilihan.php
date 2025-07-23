<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil_pemilihan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Calon_model');
        $this->load->model('Pemilih_model');
        
        // Cek session admin
        $is_admin_logged_in = (
            $this->session->userdata('status') === 'login' ||
            $this->session->userdata('is_admin') === TRUE ||
            $this->session->userdata('logged_in') === TRUE
        );
        
        // Jika tidak ada session admin yang valid, redirect ke login
        if (!$is_admin_logged_in) {
            $this->session->set_flashdata('error', 'Silakan login sebagai admin terlebih dahulu!');
            redirect('admin_auth/login');
            exit();
        }
    }

    public function index()
    {
        $this->hasil();
    }

    public function hasil()
    {
        header('Content-Type: text/html; charset=utf-8');
        
        // Ambil data hasil dari database
        $data_hasil = $this->Calon_model->get_hasil_pemilihan();
        $total_suara = $this->Calon_model->get_total_suara();
        $total_pemilih = $this->Pemilih_model->get_total_pemilih();
        $tingkat_partisipasi = $total_pemilih > 0 ? round(($total_suara / $total_pemilih) * 100, 2) : 0;
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Hasil Pemilihan - Admin E-Voting HIMSI</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>";
        echo "<style>";
        echo "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');";
        echo "body { background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6); background-size: 400% 400%; animation: gradientShift 15s ease infinite; min-height: 100vh; font-family: 'Inter', sans-serif; }";
        echo "@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }";
        echo ".container { max-width: 1200px; margin: 0 auto; padding: 20px; }";
        echo ".page-title { color: white; text-align: center; margin-bottom: 30px; font-weight: 700; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }";
        echo ".stats-card { background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); border-radius: 20px; padding: 25px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.2); }";
        echo ".hasil-card { background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); border-radius: 20px; padding: 20px; margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.2); color: white; }";
        echo ".back-btn { background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.3); }";
        echo ".back-btn:hover { background: rgba(255,255,255,0.3); color: white; transform: translateY(-2px); }";
        echo ".btn-action { padding: 8px 16px; margin: 5px; border-radius: 8px; text-decoration: none; font-size: 14px; transition: all 0.3s ease; }";
        echo ".btn-reset { background: rgba(220,53,69,0.8); color: white; border: 1px solid rgba(220,53,69,0.5); }";
        echo ".btn-export { background: rgba(40,167,69,0.8); color: white; border: 1px solid rgba(40,167,69,0.5); }";
        echo ".btn-refresh { background: rgba(0,123,255,0.8); color: white; border: 1px solid rgba(0,123,255,0.5); }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<div class='container'>";
        echo "<h1 class='page-title'>";
        echo "<i class='fas fa-chart-bar me-3'></i>Hasil Pemilihan";
        echo "</h1>";
        
        // Statistik umum
        echo "<div class='stats-card'>";
        echo "<h4><i class='fas fa-info-circle me-2'></i>Statistik Pemilihan</h4>";
        echo "<div class='row'>";
        echo "<div class='col-md-3'><strong>Total Suara:</strong> " . $total_suara . "</div>";
        echo "<div class='col-md-3'><strong>Total Pemilih:</strong> " . $total_pemilih . "</div>";
        echo "<div class='col-md-3'><strong>Partisipasi:</strong> " . $tingkat_partisipasi . "%</div>";
        echo "<div class='col-md-3'><strong>Kandidat:</strong> " . count($data_hasil) . "</div>";
        echo "</div>";
        echo "</div>";
        
        // Tombol aksi
        echo "<div class='stats-card text-center'>";
        echo "<h5><i class='fas fa-tools me-2'></i>Aksi Management</h5>";
        echo "<a href='" . site_url('hasil/refresh') . "' class='btn-action btn-refresh'>";
        echo "<i class='fas fa-sync-alt me-1'></i>Refresh Data</a>";
        echo "<a href='" . site_url('hasil/export') . "' class='btn-action btn-export'>";
        echo "<i class='fas fa-download me-1'></i>Export Excel</a>";
        echo "<a href='" . site_url('hasil/reset') . "' class='btn-action btn-reset' onclick='return confirm(\"Yakin ingin mereset semua data voting?\")'>";
        echo "<i class='fas fa-trash me-1'></i>Reset Voting</a>";
        echo "</div>";
        
        // Data hasil
        if (!empty($data_hasil)) {
            foreach ($data_hasil as $index => $calon) {
                $nama = isset($calon['nama']) ? htmlspecialchars($calon['nama']) : 'Calon ' . ($index + 1);
                $suara = isset($calon['jumlah_suara']) ? (int)$calon['jumlah_suara'] : 0;
                $persentase = $total_suara > 0 ? round(($suara / $total_suara) * 100, 2) : 0;
                
                echo "<div class='hasil-card'>";
                echo "<div class='row align-items-center'>";
                echo "<div class='col-md-6'>";
                echo "<h5><i class='fas fa-user me-2'></i>" . $nama . "</h5>";
                echo "</div>";
                echo "<div class='col-md-3'>";
                echo "<h4><i class='fas fa-vote-yea me-2'></i>" . $suara . " suara</h4>";
                echo "</div>";
                echo "<div class='col-md-3'>";
                echo "<h4><i class='fas fa-percentage me-2'></i>" . $persentase . "%</h4>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='hasil-card text-center'>";
            echo "<h5><i class='fas fa-info-circle me-2'></i>Belum Ada Data Hasil</h5>";
            echo "<p>Tidak ada data hasil pemilihan atau belum ada yang memilih.</p>";
            echo "</div>";
        }
        
        echo "<div class='text-center'>";
        echo "<a href='" . site_url('/') . "' class='back-btn me-2'>";
        echo "<i class='fas fa-home me-2'></i>Halaman Utama";
        echo "</a>";
        echo "<a href='" . site_url('admin/dashboard') . "' class='back-btn'>";
        echo "<i class='fas fa-arrow-left me-2'></i>Kembali ke Dashboard";
        echo "</a>";
        echo "</div>";
        echo "</div>";
        
        echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
        echo "</body>";
        echo "</html>";
    }

    // Method untuk mengambil data hasil dari database
    private function get_data_hasil()
    {
        return $this->Calon_model->get_hasil_pemilihan();
    }

    private function calculate_total_suara($data_hasil)
    {
        return $this->Calon_model->get_total_suara();
    }

    private function get_total_pemilih()
    {
        return $this->Pemilih_model->get_total_pemilih();
    }

    // CRUD Methods untuk Hasil Pemilihan
    public function reset_voting() {
        // Reset semua suara
        if ($this->Calon_model->reset_suara()) {
            // Reset status memilih semua pemilih
            $this->db->set('status_memilih', 0);
            $this->db->update('pemilih');
            
            $this->session->set_flashdata('success', 'Pemilihan berhasil direset!');
        } else {
            $this->session->set_flashdata('error', 'Gagal mereset pemilihan!');
        }
        
        redirect('admin/hasil');
    }

    public function export_excel() {
        // Export hasil ke Excel - Simple HTML table export
        $data_hasil = $this->Calon_model->get_hasil_pemilihan();
        $total_suara = $this->Calon_model->get_total_suara();
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="hasil_pemilihan_' . date('Y-m-d') . '.xls"');
        
        echo "<table border='1'>";
        echo "<tr><th>No</th><th>Nama Calon</th><th>Jumlah Suara</th><th>Persentase</th></tr>";
        
        foreach ($data_hasil as $index => $calon) {
            $persentase = $total_suara > 0 ? round(($calon['jumlah_suara'] / $total_suara) * 100, 2) : 0;
            echo "<tr>";
            echo "<td>" . ($index + 1) . "</td>";
            echo "<td>" . htmlspecialchars($calon['nama']) . "</td>";
            echo "<td>" . $calon['jumlah_suara'] . "</td>";
            echo "<td>" . $persentase . "%</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }

    public function refresh_data() {
        // Refresh data hasil
        $this->session->set_flashdata('success', 'Data hasil berhasil direfresh!');
        redirect('admin/hasil');
    }

    public function kembali() {
        redirect('/');
    }
}
