<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calon extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        $this->load->database();
        $this->load->model('Calon_model');
        
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

    public function index() {
        header('Content-Type: text/html; charset=utf-8');
        
        // Ambil data calon dari database
        $data_calon = $this->Calon_model->get_all_calon();
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Kelola Calon - Admin E-Voting HIMSI</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>";
        echo "<style>";
        echo "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');";
        echo "body { background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6); background-size: 400% 400%; animation: gradientShift 15s ease infinite; min-height: 100vh; font-family: 'Inter', sans-serif; }";
        echo "@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }";
        echo ".navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); padding: 1rem 0; }";
        echo ".navbar-brand { font-weight: 800; color: white !important; font-size: 1.8rem; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }";
        echo ".container-main { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 25px; padding: 3rem; margin: 2rem auto; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); max-width: 1200px; }";
        echo ".page-title { color: white; font-size: 2.5rem; font-weight: 800; text-align: center; margin-bottom: 2rem; text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3); }";
        echo ".calon-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem; margin: 2rem 0; }";
        echo ".calon-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; padding: 2rem; text-align: center; color: white; transition: all 0.3s ease; }";
        echo ".calon-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); }";
        echo ".calon-avatar { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2.5rem; color: white; }";
        echo ".calon-name { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }";
        echo ".calon-info { color: rgba(255, 255, 255, 0.8); margin-bottom: 1rem; }";
        echo ".vote-count { background: linear-gradient(135deg, #f59e0b, #d97706); padding: 10px 20px; border-radius: 15px; font-weight: 700; display: inline-block; margin-top: 1rem; }";
        echo ".no-data { background: rgba(255, 193, 7, 0.2); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 15px; padding: 3rem; text-align: center; color: white; }";
        echo ".back-btn { background: linear-gradient(135deg, #6c757d, #495057); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-flex; align-items: center; font-weight: 600; transition: all 0.3s ease; margin-top: 2rem; }";
        echo ".back-btn:hover { color: white; text-decoration: none; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4); }";
        echo ".stats-summary { background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 15px; padding: 2rem; text-align: center; color: white; margin-bottom: 2rem; }";
        echo ".session-info { background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; padding: 1rem; margin-bottom: 2rem; color: white; text-align: center; }";
        echo ".crud-btn { padding: 8px 15px; border-radius: 15px; text-decoration: none; font-weight: 600; margin: 0 3px; transition: all 0.3s ease; border: none; }";
        echo ".btn-add { background: linear-gradient(135deg, #10b981, #059669); color: white; }";
        echo ".btn-edit { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }";
        echo ".btn-delete { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }";
        echo ".btn-add:hover, .btn-edit:hover, .btn-delete:hover { color: white; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3); }";
        echo ".crud-actions { margin-bottom: 2rem; text-align: center; }";
        echo ".calon-actions { margin-top: 1rem; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<nav class='navbar navbar-expand-lg'>";
        echo "<div class='container-fluid'>";
        echo "<a class='navbar-brand' href='" . site_url('admin/dashboard') . "'><i class='fas fa-shield-alt me-2'></i>E-Voting HIMSI</a>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='text-white fw-bold me-3'>Panel Admin - Kelola Calon</span>";
        echo "<a class='nav-link' href='" . site_url('admin_auth/logout') . "'>";
        echo "<i class='fas fa-sign-out-alt me-1'></i>Logout";
        echo "</a>";
        echo "</div>";
        echo "</div>";
        echo "</nav>";
        
        echo "<div class='container-main'>";
        
        // Session info
        echo "<div class='session-info'>";
        echo "<i class='fas fa-user-check me-2'></i>";
        echo "Session Admin: " . ($this->session->userdata('admin_username') ?: $this->session->userdata('username'));
        echo " | Login Time: " . ($this->session->userdata('admin_login_time') ?: $this->session->userdata('login_time'));
        echo "</div>";
        
        echo "<h1 class='page-title'>";
        echo "<i class='fas fa-user-tie me-3'></i>Kelola Data Calon";
        echo "</h1>";
        
        // CRUD Actions
        echo "<div class='crud-actions'>";
        echo "<a href='" . site_url('calon/tambah') . "' class='crud-btn btn-add'>";
        echo "<i class='fas fa-plus me-2'></i>Tambah Calon Baru";
        echo "</a>";
        echo "</div>";
        
        if (!empty($data_calon)) {
            echo "<div class='stats-summary'>";
            echo "<h4><i class='fas fa-chart-bar me-2'></i>Statistik Calon</h4>";
            echo "<p class='mb-0'>Total: <strong>" . count($data_calon) . " kandidat</strong> terdaftar dalam sistem</p>";
            echo "</div>";
            
            echo "<div class='calon-grid'>";
            foreach ($data_calon as $index => $calon) {
                echo "<div class='calon-card'>";
                echo "<div class='calon-avatar'>";
                echo "<i class='fas fa-user'></i>";
                echo "</div>";
                echo "<div class='calon-name'>" . (isset($calon['nama']) ? htmlspecialchars($calon['nama']) : 'Calon ' . ($index + 1)) . "</div>";
                echo "<div class='calon-info'>";
                echo "<p><i class='fas fa-id-card me-2'></i>ID: " . (isset($calon['id']) ? $calon['id'] : ($index + 1)) . "</p>";
                if (isset($calon['visi'])) {
                    echo "<p><i class='fas fa-eye me-2'></i>Visi: " . htmlspecialchars(substr($calon['visi'], 0, 50)) . "...</p>";
                }
                if (isset($calon['misi'])) {
                    echo "<p><i class='fas fa-bullseye me-2'></i>Misi: " . htmlspecialchars(substr($calon['misi'], 0, 50)) . "...</p>";
                }
                echo "</div>";
                echo "<div class='vote-count'>";
                echo "<i class='fas fa-vote-yea me-2'></i>";
                echo (isset($calon['jumlah_suara']) ? $calon['jumlah_suara'] : 0) . " suara";
                echo "</div>";
                echo "<div class='calon-actions'>";
                $calon_id = isset($calon['id']) ? $calon['id'] : ($index + 1);
                echo "<a href='" . site_url('calon/edit/' . $calon_id) . "' class='crud-btn btn-edit'>";
                echo "<i class='fas fa-edit me-1'></i>Edit";
                echo "</a>";
                echo "<a href='" . site_url('calon/hapus/' . $calon_id) . "' class='crud-btn btn-delete' onclick='return confirm(\"Yakin hapus calon ini?\")'>";
                echo "<i class='fas fa-trash me-1'></i>Hapus";
                echo "</a>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div class='no-data'>";
            echo "<i class='fas fa-user-plus fa-3x mb-3'></i>";
            echo "<h3>Belum Ada Data Calon</h3>";
            echo "<p class='mb-0'>Belum ada calon kandidat yang terdaftar dalam sistem atau tidak dapat mengambil data dari server.</p>";
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

    // Method untuk mengambil data calon dari database
    private function get_data_calon() {
        return $this->Calon_model->get_all_calon();
    }

    public function tambah() {
        if ($this->input->method() === 'post') {
            // Proses form submission
            $nama = $this->input->post('nama');
            $visi = $this->input->post('visi');
            $misi = $this->input->post('misi');
            
            // Validasi input
            if (empty($nama) || empty($visi) || empty($misi)) {
                $this->session->set_flashdata('error', 'Semua field harus diisi!');
                $this->show_form_tambah();
                return;
            }
            
            // Siapkan data untuk database
            $data = array(
                'nama_calon' => $nama,
                'foto' => '', // Default empty, could be handled in future
                'visi' => $visi,
                'misi' => $misi,
                'jumlah_suara' => 0
            );
            
            // Insert ke database
            if ($this->Calon_model->insert_calon($data)) {
                $this->session->set_flashdata('success', 'Calon berhasil ditambahkan!');
                redirect('calon');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan calon!');
            }
        }
        
        // Tampilkan form
        $this->show_form_tambah();
    }

    public function edit($id) {
        if ($this->input->method() === 'post') {
            // Proses form submission
            $nama = $this->input->post('nama');
            $visi = $this->input->post('visi');
            $misi = $this->input->post('misi');
            
            // Validasi input
            if (empty($nama) || empty($visi) || empty($misi)) {
                $this->session->set_flashdata('error', 'Semua field harus diisi!');
                $calon_data = $this->Calon_model->get_calon_by_id($id);
                $this->show_form_edit($id, $calon_data);
                return;
            }
            
            // Siapkan data untuk update
            $data = array(
                'nama_calon' => $nama,
                'visi' => $visi,
                'misi' => $misi
            );
            
            // Update database
            if ($this->Calon_model->update_calon($id, $data)) {
                $this->session->set_flashdata('success', 'Calon berhasil diupdate!');
                redirect('calon');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate calon!');
            }
        } else {
            // Ambil data calon untuk edit
            $calon_data = $this->Calon_model->get_calon_by_id($id);
            $this->show_form_edit($id, $calon_data);
        }
    }

    public function hapus($id) {
        // Hapus dari database
        if ($this->Calon_model->delete_calon($id)) {
            $this->session->set_flashdata('success', 'Calon berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus calon!');
        }
        
        redirect('calon');
    }

    private function show_form_tambah() {
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Tambah Calon - Admin E-Voting HIMSI</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>";
        echo "<style>";
        echo "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');";
        echo "body { background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6); background-size: 400% 400%; animation: gradientShift 15s ease infinite; min-height: 100vh; font-family: 'Inter', sans-serif; }";
        echo "@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }";
        echo ".container-main { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 25px; padding: 3rem; margin: 2rem auto; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); max-width: 800px; }";
        echo ".page-title { color: white; font-size: 2.5rem; font-weight: 800; text-align: center; margin-bottom: 2rem; text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3); }";
        echo ".form-control { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 15px; padding: 15px; }";
        echo ".form-control:focus { background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.4); box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25); color: white; }";
        echo ".form-control::placeholder { color: rgba(255, 255, 255, 0.7); }";
        echo ".form-label { color: white; font-weight: 600; margin-bottom: 10px; }";
        echo ".btn-submit { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 15px 30px; border: none; border-radius: 25px; font-weight: 600; width: 100%; margin-top: 20px; }";
        echo ".btn-back { background: linear-gradient(135deg, #6c757d, #495057); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-flex; align-items: center; font-weight: 600; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<div class='container-main'>";
        echo "<h1 class='page-title'><i class='fas fa-user-plus me-3'></i>Tambah Calon Baru</h1>";
        
        // Show flash messages
        if ($this->session->flashdata('error')) {
            echo "<div class='alert alert-danger'>" . $this->session->flashdata('error') . "</div>";
        }
        
        echo "<form method='post'>";
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-user me-2'></i>Nama Calon</label>";
        echo "<input type='text' class='form-control' name='nama' placeholder='Masukkan nama calon' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-eye me-2'></i>Visi</label>";
        echo "<textarea class='form-control' name='visi' rows='4' placeholder='Masukkan visi calon' required></textarea>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-bullseye me-2'></i>Misi</label>";
        echo "<textarea class='form-control' name='misi' rows='4' placeholder='Masukkan misi calon' required></textarea>";
        echo "</div>";
        
        echo "<button type='submit' class='btn-submit'><i class='fas fa-save me-2'></i>Simpan Calon</button>";
        echo "</form>";
        
        echo "<div class='text-center mt-4'>";
        echo "<a href='" . site_url('calon') . "' class='btn-back'><i class='fas fa-arrow-left me-2'></i>Kembali ke Daftar</a>";
        echo "</div>";
        
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }

    private function show_form_edit($id, $calon_data) {
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Edit Calon - Admin E-Voting HIMSI</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>";
        echo "<style>";
        echo "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');";
        echo "body { background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6); background-size: 400% 400%; animation: gradientShift 15s ease infinite; min-height: 100vh; font-family: 'Inter', sans-serif; }";
        echo "@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }";
        echo ".container-main { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 25px; padding: 3rem; margin: 2rem auto; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); max-width: 800px; }";
        echo ".page-title { color: white; font-size: 2.5rem; font-weight: 800; text-align: center; margin-bottom: 2rem; text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3); }";
        echo ".form-control { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white; border-radius: 15px; padding: 15px; }";
        echo ".form-control:focus { background: rgba(255, 255, 255, 0.15); border-color: rgba(255, 255, 255, 0.4); box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25); color: white; }";
        echo ".form-control::placeholder { color: rgba(255, 255, 255, 0.7); }";
        echo ".form-label { color: white; font-weight: 600; margin-bottom: 10px; }";
        echo ".btn-submit { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 15px 30px; border: none; border-radius: 25px; font-weight: 600; width: 100%; margin-top: 20px; }";
        echo ".btn-back { background: linear-gradient(135deg, #6c757d, #495057); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-flex; align-items: center; font-weight: 600; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<div class='container-main'>";
        echo "<h1 class='page-title'><i class='fas fa-user-edit me-3'></i>Edit Data Calon</h1>";
        
        // Show flash messages
        if ($this->session->flashdata('error')) {
            echo "<div class='alert alert-danger'>" . $this->session->flashdata('error') . "</div>";
        }
        
        $nama = isset($calon_data['nama']) ? $calon_data['nama'] : '';
        $visi = isset($calon_data['visi']) ? $calon_data['visi'] : '';
        $misi = isset($calon_data['misi']) ? $calon_data['misi'] : '';
        
        echo "<form method='post'>";
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-user me-2'></i>Nama Calon</label>";
        echo "<input type='text' class='form-control' name='nama' value='" . htmlspecialchars($nama) . "' placeholder='Masukkan nama calon' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-eye me-2'></i>Visi</label>";
        echo "<textarea class='form-control' name='visi' rows='4' placeholder='Masukkan visi calon' required>" . htmlspecialchars($visi) . "</textarea>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-bullseye me-2'></i>Misi</label>";
        echo "<textarea class='form-control' name='misi' rows='4' placeholder='Masukkan misi calon' required>" . htmlspecialchars($misi) . "</textarea>";
        echo "</div>";
        
        echo "<button type='submit' class='btn-submit'><i class='fas fa-save me-2'></i>Update Calon</button>";
        echo "</form>";
        
        echo "<div class='text-center mt-4'>";
        echo "<a href='" . site_url('calon') . "' class='btn-back'><i class='fas fa-arrow-left me-2'></i>Kembali ke Daftar</a>";
        echo "</div>";
        
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }

    private function get_calon_by_id($id) {
        return $this->Calon_model->get_calon_by_id($id);
    }
}
