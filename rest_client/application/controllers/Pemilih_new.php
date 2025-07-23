<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemilih extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        
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
        
        // Ambil data pemilih dari API
        $data_pemilih = $this->get_data_pemilih();
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Kelola Pemilih - Admin E-Voting HIMSI</title>";
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
        echo ".pemilih-table { background: rgba(255, 255, 255, 0.1); border-radius: 15px; overflow: hidden; }";
        echo ".table { color: white; margin-bottom: 0; }";
        echo ".table thead th { background: rgba(16, 185, 129, 0.3); border: none; color: white; font-weight: 700; padding: 1rem; }";
        echo ".table tbody td { border: 1px solid rgba(255, 255, 255, 0.1); padding: 1rem; background: rgba(255, 255, 255, 0.05); }";
        echo ".table tbody tr:hover { background: rgba(255, 255, 255, 0.1); }";
        echo ".status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }";
        echo ".status-sudah { background: linear-gradient(135deg, #10b981, #059669); color: white; }";
        echo ".status-belum { background: linear-gradient(135deg, #f59e0b, #d97706); color: white; }";
        echo ".stats-summary { background: rgba(16, 185, 129, 0.2); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 15px; padding: 2rem; text-align: center; color: white; margin-bottom: 2rem; }";
        echo ".stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem; }";
        echo ".stat-item { background: rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 10px; text-align: center; }";
        echo ".no-data { background: rgba(255, 193, 7, 0.2); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 15px; padding: 3rem; text-align: center; color: white; }";
        echo ".back-btn { background: linear-gradient(135deg, #6c757d, #495057); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-flex; align-items: center; font-weight: 600; transition: all 0.3s ease; margin-top: 2rem; }";
        echo ".back-btn:hover { color: white; text-decoration: none; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4); }";
        echo ".session-info { background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 10px; padding: 1rem; margin-bottom: 2rem; color: white; text-align: center; }";
        echo ".crud-btn { padding: 8px 15px; border-radius: 15px; text-decoration: none; font-weight: 600; margin: 0 3px; transition: all 0.3s ease; border: none; }";
        echo ".btn-add { background: linear-gradient(135deg, #10b981, #059669); color: white; }";
        echo ".btn-edit { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }";
        echo ".btn-delete { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }";
        echo ".btn-add:hover, .btn-edit:hover, .btn-delete:hover { color: white; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3); }";
        echo ".crud-actions { margin-bottom: 2rem; text-align: center; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<nav class='navbar navbar-expand-lg'>";
        echo "<div class='container-fluid'>";
        echo "<a class='navbar-brand' href='" . site_url('admin/dashboard') . "'><i class='fas fa-shield-alt me-2'></i>E-Voting HIMSI</a>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='text-white fw-bold me-3'>Panel Admin - Kelola Pemilih</span>";
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
        echo "<i class='fas fa-users me-3'></i>Kelola Data Pemilih";
        echo "</h1>";
        
        // CRUD Actions
        echo "<div class='crud-actions'>";
        echo "<a href='" . site_url('pemilih/tambah') . "' class='crud-btn btn-add'>";
        echo "<i class='fas fa-plus me-2'></i>Tambah Pemilih Baru";
        echo "</a>";
        echo "</div>";
        
        if (!empty($data_pemilih)) {
            // Hitung statistik
            $total_pemilih = count($data_pemilih);
            $sudah_vote = 0;
            $belum_vote = 0;
            
            foreach ($data_pemilih as $pemilih) {
                if (isset($pemilih['status_vote']) && $pemilih['status_vote'] == 1) {
                    $sudah_vote++;
                } else {
                    $belum_vote++;
                }
            }
            
            $partisipasi = $total_pemilih > 0 ? round(($sudah_vote / $total_pemilih) * 100, 2) : 0;
            
            echo "<div class='stats-summary'>";
            echo "<h4><i class='fas fa-chart-bar me-2'></i>Statistik Pemilih</h4>";
            echo "<div class='stats-grid'>";
            echo "<div class='stat-item'>";
            echo "<h3>" . $total_pemilih . "</h3>";
            echo "<p>Total Pemilih</p>";
            echo "</div>";
            echo "<div class='stat-item'>";
            echo "<h3>" . $sudah_vote . "</h3>";
            echo "<p>Sudah Memilih</p>";
            echo "</div>";
            echo "<div class='stat-item'>";
            echo "<h3>" . $belum_vote . "</h3>";
            echo "<p>Belum Memilih</p>";
            echo "</div>";
            echo "<div class='stat-item'>";
            echo "<h3>" . $partisipasi . "%</h3>";
            echo "<p>Partisipasi</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            
            echo "<div class='pemilih-table'>";
            echo "<table class='table table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>No</th>";
            echo "<th>Nama</th>";
            echo "<th>NIM</th>";
            echo "<th>Jurusan</th>";
            echo "<th>Status Vote</th>";
            echo "<th>Waktu Vote</th>";
            echo "<th>Aksi</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            foreach ($data_pemilih as $index => $pemilih) {
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>";
                echo "<td><i class='fas fa-user me-2'></i>" . (isset($pemilih['nama']) ? htmlspecialchars($pemilih['nama']) : 'Nama tidak tersedia') . "</td>";
                echo "<td>" . (isset($pemilih['nim']) ? htmlspecialchars($pemilih['nim']) : 'NIM tidak tersedia') . "</td>";
                echo "<td>" . (isset($pemilih['jurusan']) ? htmlspecialchars($pemilih['jurusan']) : 'Jurusan tidak tersedia') . "</td>";
                echo "<td>";
                if (isset($pemilih['status_vote']) && $pemilih['status_vote'] == 1) {
                    echo "<span class='status-badge status-sudah'><i class='fas fa-check me-1'></i>Sudah Memilih</span>";
                } else {
                    echo "<span class='status-badge status-belum'><i class='fas fa-clock me-1'></i>Belum Memilih</span>";
                }
                echo "</td>";
                echo "<td>";
                if (isset($pemilih['waktu_vote']) && !empty($pemilih['waktu_vote'])) {
                    echo "<i class='fas fa-calendar me-2'></i>" . date('d/m/Y H:i', strtotime($pemilih['waktu_vote']));
                } else {
                    echo "<span class='text-muted'>-</span>";
                }
                echo "</td>";
                echo "<td>";
                $pemilih_id = isset($pemilih['id']) ? $pemilih['id'] : ($index + 1);
                echo "<a href='" . site_url('pemilih/edit/' . $pemilih_id) . "' class='crud-btn btn-edit'>";
                echo "<i class='fas fa-edit me-1'></i>Edit";
                echo "</a>";
                echo "<a href='" . site_url('pemilih/hapus/' . $pemilih_id) . "' class='crud-btn btn-delete' onclick='return confirm(\"Yakin hapus data pemilih ini?\")'>";
                echo "<i class='fas fa-trash me-1'></i>Hapus";
                echo "</a>";
                echo "</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='no-data'>";
            echo "<i class='fas fa-user-plus fa-3x mb-3'></i>";
            echo "<h3>Belum Ada Data Pemilih</h3>";
            echo "<p class='mb-0'>Belum ada pemilih yang terdaftar dalam sistem atau tidak dapat mengambil data dari server.</p>";
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

    // Method untuk mengambil data pemilih dari API
    private function get_data_pemilih()
    {
        $api_url = 'http://localhost:8001/index.php/api/pemilih';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 200 && !empty($response)) {
            $data = json_decode($response, true);
            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }
        }
        return array();
    }

    public function tambah() {
        if ($this->input->method() === 'post') {
            // Proses form submission
            $nama = $this->input->post('nama');
            $nim = $this->input->post('nim');
            $jurusan = $this->input->post('jurusan');
            $password = $this->input->post('password');
            
            // Kirim ke API
            $api_url = 'http://localhost:8001/index.php/api/pemilih/create';
            $post_data = array(
                'nama' => $nama,
                'nim' => $nim,
                'jurusan' => $jurusan,
                'password' => $password
            );
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $result = json_decode($response, true);
                if (isset($result['status']) && $result['status'] === true) {
                    $this->session->set_flashdata('success', 'Pemilih berhasil ditambahkan!');
                    redirect('pemilih');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan pemilih: ' . (isset($result['message']) ? $result['message'] : 'Unknown error'));
                }
            } else {
                $this->session->set_flashdata('error', 'Tidak dapat terhubung ke server API!');
            }
        }
        
        // Tampilkan form
        $this->show_form_tambah();
    }

    public function edit($id) {
        if ($this->input->method() === 'post') {
            // Proses form submission
            $nama = $this->input->post('nama');
            $nim = $this->input->post('nim');
            $jurusan = $this->input->post('jurusan');
            $password = $this->input->post('password');
            
            // Kirim ke API
            $api_url = 'http://localhost:8001/index.php/api/pemilih/update/' . $id;
            $post_data = array(
                'nama' => $nama,
                'nim' => $nim,
                'jurusan' => $jurusan
            );
            
            if (!empty($password)) {
                $post_data['password'] = $password;
            }
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($response) {
                $result = json_decode($response, true);
                if (isset($result['status']) && $result['status'] === true) {
                    $this->session->set_flashdata('success', 'Pemilih berhasil diupdate!');
                    redirect('pemilih');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate pemilih: ' . (isset($result['message']) ? $result['message'] : 'Unknown error'));
                }
            } else {
                $this->session->set_flashdata('error', 'Tidak dapat terhubung ke server API!');
            }
        } else {
            // Ambil data pemilih untuk edit
            $pemilih_data = $this->get_pemilih_by_id($id);
            $this->show_form_edit($id, $pemilih_data);
        }
    }

    public function hapus($id) {
        // Kirim ke API
        $api_url = 'http://localhost:8001/index.php/api/pemilih/delete/' . $id;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $result = json_decode($response, true);
            if (isset($result['status']) && $result['status'] === true) {
                $this->session->set_flashdata('success', 'Pemilih berhasil dihapus!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus pemilih: ' . (isset($result['message']) ? $result['message'] : 'Unknown error'));
            }
        } else {
            $this->session->set_flashdata('error', 'Tidak dapat terhubung ke server API!');
        }
        
        redirect('pemilih');
    }

    private function show_form_tambah() {
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Tambah Pemilih - Admin E-Voting HIMSI</title>";
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
        echo "<h1 class='page-title'><i class='fas fa-user-plus me-3'></i>Tambah Pemilih Baru</h1>";
        
        // Show flash messages
        if ($this->session->flashdata('error')) {
            echo "<div class='alert alert-danger'>" . $this->session->flashdata('error') . "</div>";
        }
        
        echo "<form method='post'>";
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-user me-2'></i>Nama Lengkap</label>";
        echo "<input type='text' class='form-control' name='nama' placeholder='Masukkan nama lengkap' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-id-card me-2'></i>NIM</label>";
        echo "<input type='text' class='form-control' name='nim' placeholder='Masukkan NIM' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-graduation-cap me-2'></i>Jurusan</label>";
        echo "<input type='text' class='form-control' name='jurusan' placeholder='Masukkan jurusan' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-lock me-2'></i>Password</label>";
        echo "<input type='password' class='form-control' name='password' placeholder='Masukkan password' required>";
        echo "</div>";
        
        echo "<button type='submit' class='btn-submit'><i class='fas fa-save me-2'></i>Simpan Pemilih</button>";
        echo "</form>";
        
        echo "<div class='text-center mt-4'>";
        echo "<a href='" . site_url('pemilih') . "' class='btn-back'><i class='fas fa-arrow-left me-2'></i>Kembali ke Daftar</a>";
        echo "</div>";
        
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }

    private function show_form_edit($id, $pemilih_data) {
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Edit Pemilih - Admin E-Voting HIMSI</title>";
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
        echo "<h1 class='page-title'><i class='fas fa-user-edit me-3'></i>Edit Data Pemilih</h1>";
        
        // Show flash messages
        if ($this->session->flashdata('error')) {
            echo "<div class='alert alert-danger'>" . $this->session->flashdata('error') . "</div>";
        }
        
        $nama = isset($pemilih_data['nama']) ? $pemilih_data['nama'] : '';
        $nim = isset($pemilih_data['nim']) ? $pemilih_data['nim'] : '';
        $jurusan = isset($pemilih_data['jurusan']) ? $pemilih_data['jurusan'] : '';
        
        echo "<form method='post'>";
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-user me-2'></i>Nama Lengkap</label>";
        echo "<input type='text' class='form-control' name='nama' value='" . htmlspecialchars($nama) . "' placeholder='Masukkan nama lengkap' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-id-card me-2'></i>NIM</label>";
        echo "<input type='text' class='form-control' name='nim' value='" . htmlspecialchars($nim) . "' placeholder='Masukkan NIM' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-graduation-cap me-2'></i>Jurusan</label>";
        echo "<input type='text' class='form-control' name='jurusan' value='" . htmlspecialchars($jurusan) . "' placeholder='Masukkan jurusan' required>";
        echo "</div>";
        
        echo "<div class='mb-3'>";
        echo "<label class='form-label'><i class='fas fa-lock me-2'></i>Password Baru (kosongkan jika tidak ingin mengubah)</label>";
        echo "<input type='password' class='form-control' name='password' placeholder='Masukkan password baru'>";
        echo "</div>";
        
        echo "<button type='submit' class='btn-submit'><i class='fas fa-save me-2'></i>Update Pemilih</button>";
        echo "</form>";
        
        echo "<div class='text-center mt-4'>";
        echo "<a href='" . site_url('pemilih') . "' class='btn-back'><i class='fas fa-arrow-left me-2'></i>Kembali ke Daftar</a>";
        echo "</div>";
        
        echo "</div>";
        echo "</body>";
        echo "</html>";
    }

    private function get_pemilih_by_id($id) {
        $api_url = 'http://localhost:8001/index.php/api/pemilih/read/' . $id;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $result = json_decode($response, true);
            if (isset($result['status']) && $result['status'] === true && isset($result['data'])) {
                return $result['data'];
            }
        }
        
        return array();
    }
}
