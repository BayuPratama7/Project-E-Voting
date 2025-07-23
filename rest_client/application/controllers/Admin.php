<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
        
        // Cek multiple session indicators untuk admin
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
        
        error_log('Admin access granted - Valid session found');
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $username = $this->session->userdata('username') ?: 'admin';
        $nama = $this->session->userdata('nama') ?: 'Administrator';
        
        // Ambil statistik dasar
        $stats = $this->get_stats();
        
        // Set proper headers
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Dashboard Admin - E-Voting HIMSI</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>";
        echo "<style>";
        echo "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');";
        echo ":root {";
        echo "--primary-color: #6366f1;";
        echo "--secondary-color: #8b5cf6;";
        echo "--success-color: #10b981;";
        echo "--warning-color: #f59e0b;";
        echo "--danger-color: #ef4444;";
        echo "--dark-color: #1f2937;";
        echo "--light-bg: #f8fafc;";
        echo "--glass-bg: rgba(255, 255, 255, 0.1);";
        echo "--glass-border: rgba(255, 255, 255, 0.2);";
        echo "}";
        echo "* { margin: 0; padding: 0; box-sizing: border-box; }";
        echo "body {";
        echo "background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6);";
        echo "background-size: 400% 400%;";
        echo "animation: gradientShift 15s ease infinite;";
        echo "min-height: 100vh;";
        echo "font-family: 'Inter', sans-serif;";
        echo "overflow-x: hidden;";
        echo "}";
        echo "@keyframes gradientShift {";
        echo "0% { background-position: 0% 50%; }";
        echo "50% { background-position: 100% 50%; }";
        echo "100% { background-position: 0% 50%; }";
        echo "}";
        echo "body::before {";
        echo "content: '';";
        echo "position: fixed;";
        echo "top: 0; left: 0;";
        echo "width: 100%; height: 100%;";
        echo "background-image:";
        echo "radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),";
        echo "radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.15) 0%, transparent 50%),";
        echo "radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.15) 0%, transparent 50%);";
        echo "pointer-events: none; z-index: -1;";
        echo "}";
        echo ".navbar {";
        echo "background: rgba(255, 255, 255, 0.1);";
        echo "backdrop-filter: blur(20px);";
        echo "border-bottom: 1px solid rgba(255, 255, 255, 0.1);";
        echo "box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);";
        echo "padding: 1rem 0;";
        echo "transition: all 0.3s ease;";
        echo "}";
        echo ".navbar:hover { background: rgba(255, 255, 255, 0.15); }";
        echo ".navbar-brand {";
        echo "font-weight: 800;";
        echo "color: white !important;";
        echo "font-size: 2rem;";
        echo "text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);";
        echo "transition: all 0.3s ease;";
        echo "}";
        echo ".navbar-brand:hover {";
        echo "transform: scale(1.05);";
        echo "text-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);";
        echo "}";
        echo ".admin-badge {";
        echo "background: linear-gradient(135deg, #ff6b6b, #ee5a24);";
        echo "color: white;";
        echo "padding: 8px 20px;";
        echo "border-radius: 25px;";
        echo "font-size: 14px;";
        echo "font-weight: 700;";
        echo "margin-left: 15px;";
        echo "box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4);";
        echo "animation: pulse 2s infinite;";
        echo "}";
        echo "@keyframes pulse {";
        echo "0% { box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4); }";
        echo "50% { box-shadow: 0 6px 25px rgba(238, 90, 36, 0.6); }";
        echo "100% { box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4); }";
        echo "}";
        echo ".welcome-card {";
        echo "background: rgba(255, 255, 255, 0.1);";
        echo "backdrop-filter: blur(20px);";
        echo "border: 1px solid rgba(255, 255, 255, 0.2);";
        echo "border-radius: 30px;";
        echo "padding: 4rem;";
        echo "margin-bottom: 3rem;";
        echo "box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);";
        echo "position: relative;";
        echo "overflow: hidden;";
        echo "}";
        echo ".welcome-card::before {";
        echo "content: '';";
        echo "position: absolute;";
        echo "top: -50%; left: -50%;";
        echo "width: 200%; height: 200%;";
        echo "background: conic-gradient(from 0deg, transparent, rgba(255, 255, 255, 0.1), transparent);";
        echo "animation: rotate 20s linear infinite;";
        echo "pointer-events: none;";
        echo "}";
        echo "@keyframes rotate { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }";
        echo ".welcome-card .content { position: relative; z-index: 1; }";
        echo ".welcome-title {";
        echo "font-size: 3.5rem;";
        echo "font-weight: 900;";
        echo "background: linear-gradient(135deg, #fff, #e2e8f0);";
        echo "-webkit-background-clip: text;";
        echo "-webkit-text-fill-color: transparent;";
        echo "text-align: center;";
        echo "margin-bottom: 1rem;";
        echo "text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);";
        echo "}";
        echo ".welcome-subtitle {";
        echo "font-size: 1.3rem;";
        echo "color: rgba(255, 255, 255, 0.8);";
        echo "text-align: center;";
        echo "font-weight: 500;";
        echo "}";
        echo ".stats-section {";
        echo "margin: 4rem 0;";
        echo "}";
        echo ".section-title {";
        echo "color: white;";
        echo "font-size: 2.8rem;";
        echo "font-weight: 800;";
        echo "text-align: center;";
        echo "margin: 3rem 0 2rem 0;";
        echo "text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);";
        echo "position: relative;";
        echo "}";
        echo ".section-title::after {";
        echo "content: '';";
        echo "position: absolute;";
        echo "bottom: -15px; left: 50%;";
        echo "transform: translateX(-50%);";
        echo "width: 120px; height: 4px;";
        echo "background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));";
        echo "border-radius: 2px;";
        echo "}";
        echo ".stats-grid {";
        echo "display: grid;";
        echo "grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));";
        echo "gap: 2.5rem;";
        echo "margin: 3rem 0;";
        echo "}";
        echo ".stat-card {";
        echo "background: rgba(255, 255, 255, 0.1);";
        echo "backdrop-filter: blur(20px);";
        echo "border: 1px solid rgba(255, 255, 255, 0.2);";
        echo "border-radius: 25px;";
        echo "padding: 3rem 2rem;";
        echo "text-align: center;";
        echo "transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);";
        echo "position: relative;";
        echo "overflow: hidden;";
        echo "}";
        echo ".stat-card::before {";
        echo "content: '';";
        echo "position: absolute;";
        echo "top: 0; left: -100%;";
        echo "width: 100%; height: 100%;";
        echo "background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);";
        echo "transition: left 0.6s ease;";
        echo "}";
        echo ".stat-card:hover::before { left: 100%; }";
        echo ".stat-card:hover {";
        echo "transform: translateY(-15px) scale(1.02);";
        echo "box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);";
        echo "}";
        echo ".stat-icon {";
        echo "font-size: 4rem;";
        echo "margin-bottom: 1.5rem;";
        echo "background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));";
        echo "-webkit-background-clip: text;";
        echo "-webkit-text-fill-color: transparent;";
        echo "filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));";
        echo "}";
        echo ".stat-number {";
        echo "font-size: 4rem;";
        echo "font-weight: 900;";
        echo "color: white;";
        echo "margin-bottom: 1rem;";
        echo "text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);";
        echo "display: block;";
        echo "}";
        echo ".stat-label {";
        echo "color: rgba(255, 255, 255, 0.9);";
        echo "font-weight: 600;";
        echo "font-size: 1.2rem;";
        echo "text-transform: uppercase;";
        echo "letter-spacing: 1px;";
        echo "}";
        echo ".menu-section { margin: 4rem 0; }";
        echo ".menu-grid {";
        echo "display: grid;";
        echo "grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));";
        echo "gap: 2.5rem;";
        echo "margin: 3rem 0;";
        echo "}";
        echo ".menu-card {";
        echo "background: rgba(255, 255, 255, 0.1);";
        echo "backdrop-filter: blur(20px);";
        echo "border: 1px solid rgba(255, 255, 255, 0.2);";
        echo "border-radius: 25px;";
        echo "padding: 3rem;";
        echo "text-align: center;";
        echo "text-decoration: none;";
        echo "color: white;";
        echo "transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);";
        echo "position: relative;";
        echo "overflow: hidden;";
        echo "display: block;";
        echo "}";
        echo ".menu-card::before {";
        echo "content: '';";
        echo "position: absolute;";
        echo "top: 0; left: 0;";
        echo "width: 100%; height: 100%;";
        echo "background: linear-gradient(135deg, var(--success-color), var(--primary-color));";
        echo "opacity: 0;";
        echo "transition: opacity 0.3s ease;";
        echo "}";
        echo ".menu-card:hover::before { opacity: 0.15; }";
        echo ".menu-card:hover {";
        echo "transform: translateY(-15px) scale(1.03);";
        echo "box-shadow: 0 30px 60px rgba(0, 0, 0, 0.25);";
        echo "color: white;";
        echo "text-decoration: none;";
        echo "}";
        echo ".menu-icon {";
        echo "font-size: 5rem;";
        echo "margin-bottom: 2rem;";
        echo "background: linear-gradient(135deg, var(--success-color), var(--warning-color));";
        echo "-webkit-background-clip: text;";
        echo "-webkit-text-fill-color: transparent;";
        echo "filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));";
        echo "position: relative;";
        echo "z-index: 1;";
        echo "}";
        echo ".menu-title {";
        echo "font-size: 1.8rem;";
        echo "font-weight: 800;";
        echo "margin-bottom: 1rem;";
        echo "position: relative;";
        echo "z-index: 1;";
        echo "}";
        echo ".menu-desc {";
        echo "color: rgba(255, 255, 255, 0.8);";
        echo "font-size: 1.1rem;";
        echo "font-weight: 500;";
        echo "position: relative;";
        echo "z-index: 1;";
        echo "}";
        echo ".logout-card {";
        echo "background: rgba(239, 68, 68, 0.2);";
        echo "border: 1px solid rgba(239, 68, 68, 0.3);";
        echo "}";
        echo ".logout-card::before {";
        echo "background: linear-gradient(135deg, var(--danger-color), #dc2626);";
        echo "}";
        echo ".logout-card .menu-icon {";
        echo "background: linear-gradient(135deg, var(--danger-color), #dc2626);";
        echo "-webkit-background-clip: text;";
        echo "-webkit-text-fill-color: transparent;";
        echo "}";
        echo ".footer-info {";
        echo "text-align: center;";
        echo "color: rgba(255, 255, 255, 0.7);";
        echo "margin-top: 4rem;";
        echo "padding: 2rem;";
        echo "font-size: 1rem;";
        echo "}";
        echo ".floating-elements {";
        echo "position: fixed;";
        echo "width: 100%; height: 100%;";
        echo "pointer-events: none;";
        echo "z-index: -1;";
        echo "}";
        echo ".floating-element {";
        echo "position: absolute;";
        echo "background: rgba(255, 255, 255, 0.1);";
        echo "border-radius: 50%;";
        echo "animation: float 6s ease-in-out infinite;";
        echo "}";
        echo ".floating-element:nth-child(1) { top: 20%; left: 20%; width: 80px; height: 80px; animation-delay: 0s; }";
        echo ".floating-element:nth-child(2) { top: 60%; left: 80%; width: 120px; height: 120px; animation-delay: 2s; }";
        echo ".floating-element:nth-child(3) { top: 80%; left: 10%; width: 60px; height: 60px; animation-delay: 4s; }";
        echo "@keyframes float {";
        echo "0%, 100% { transform: translateY(0px) rotate(0deg); }";
        echo "50% { transform: translateY(-20px) rotate(180deg); }";
        echo "}";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        // Floating elements
        echo "<div class='floating-elements'>";
        echo "<div class='floating-element'></div>";
        echo "<div class='floating-element'></div>";
        echo "<div class='floating-element'></div>";
        echo "</div>";
        
        // Navbar
        echo "<nav class='navbar navbar-expand-lg'>";
        echo "<div class='container-fluid'>";
        echo "<a class='navbar-brand' href='#'>";
        echo "<i class='fas fa-shield-alt me-3'></i>E-Voting HIMSI";
        echo "</a>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='text-white fw-bold me-3 fs-5'>" . htmlspecialchars($nama) . "</span>";
        echo "<span class='admin-badge'>";
        echo "<i class='fas fa-crown me-2'></i>ADMINISTRATOR";
        echo "</span>";
        echo "</div>";
        echo "</div>";
        echo "</nav>";
        
        echo "<div class='container-fluid px-4'>";
        
        // Welcome Card
        echo "<div class='welcome-card'>";
        echo "<div class='content'>";
        echo "<h1 class='welcome-title'>";
        echo "<i class='fas fa-tachometer-alt me-3'></i>";
        echo "Dashboard Administrator";
        echo "</h1>";
        echo "<p class='welcome-subtitle'>";
        echo "Kelola sistem e-voting dengan mudah dan efisien";
        echo "</p>";
        echo "</div>";
        echo "</div>";
        
        // Statistics Section
        echo "<div class='stats-section'>";
        echo "<h2 class='section-title'>";
        echo "<i class='fas fa-chart-bar me-3'></i>Statistik Real-Time";
        echo "</h2>";
        
        echo "<div class='stats-grid'>";
        echo "<div class='stat-card'>";
        echo "<div class='stat-icon'><i class='fas fa-users'></i></div>";
        echo "<div class='stat-number'>" . $stats['total_calon'] . "</div>";
        echo "<div class='stat-label'>Total Kandidat</div>";
        echo "</div>";
        
        echo "<div class='stat-card'>";
        echo "<div class='stat-icon'><i class='fas fa-user-friends'></i></div>";
        echo "<div class='stat-number'>" . $stats['total_pemilih'] . "</div>";
        echo "<div class='stat-label'>Total Pemilih</div>";
        echo "</div>";
        
        echo "<div class='stat-card'>";
        echo "<div class='stat-icon'><i class='fas fa-check-circle'></i></div>";
        echo "<div class='stat-number'>" . $stats['sudah_memilih'] . "</div>";
        echo "<div class='stat-label'>Sudah Voting</div>";
        echo "</div>";
        
        echo "<div class='stat-card'>";
        echo "<div class='stat-icon'><i class='fas fa-clock'></i></div>";
        echo "<div class='stat-number'>" . $stats['belum_memilih'] . "</div>";
        echo "<div class='stat-label'>Belum Voting</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        
        // Menu Section
        echo "<div class='menu-section'>";
        echo "<h2 class='section-title'>";
        echo "<i class='fas fa-cogs me-3'></i>Panel Administrasi";
        echo "</h2>";
        
        echo "<div class='menu-grid'>";
        echo "<a href='" . site_url('admin/hasil') . "' class='menu-card'>";
        echo "<div class='menu-icon'><i class='fas fa-chart-pie'></i></div>";
        echo "<div class='menu-title'>Hasil Voting</div>";
        echo "<div class='menu-desc'>Lihat hasil voting secara real-time dan analisis data</div>";
        echo "</a>";
        
        echo "<a href='" . site_url('calon') . "' class='menu-card'>";
        echo "<div class='menu-icon'><i class='fas fa-user-tie'></i></div>";
        echo "<div class='menu-title'>Kelola Kandidat (File Terpisah)</div>";
        echo "<div class='menu-desc'>Manajemen data kandidat - File Calon.php</div>";
        echo "</a>";
        
        echo "<a href='" . site_url('pemilih') . "' class='menu-card'>";
        echo "<div class='menu-icon'><i class='fas fa-vote-yea'></i></div>";
        echo "<div class='menu-title'>Kelola Pemilih (File Terpisah)</div>";
        echo "<div class='menu-desc'>Manajemen data pemilih - File Pemilih.php</div>";
        echo "</a>";
        
        echo "<a href='" . site_url('auth/logout') . "' class='menu-card logout-card'>";
        echo "<div class='menu-icon'><i class='fas fa-sign-out-alt'></i></div>";
        echo "<div class='menu-title'>Logout Sistem</div>";
        echo "<div class='menu-desc'>Keluar dari panel administrator</div>";
        echo "</a>";
        echo "</div>";
        echo "</div>";
        
        // Footer
        echo "<div class='footer-info'>";
        echo "<p><i class='fas fa-user-shield me-2'></i>Admin: " . htmlspecialchars($nama) . " (" . htmlspecialchars($username) . ")</p>";
        echo "<p><i class='fas fa-calendar me-2'></i>Session Aktif | <i class='fas fa-server me-2'></i>Server Online</p>";
        echo "</div>";
        
        echo "</div>";
        
        echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
        echo "</body>";
        echo "</html>";
        
        // Force flush output
        if (ob_get_level()) {
            ob_end_flush();
        }
        flush();
    }

    public function hasil() {
        header('Content-Type: text/html; charset=utf-8');
        
        echo "<!DOCTYPE html>";
        echo "<html lang='id'>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
        echo "<title>Hasil Voting - Admin E-Voting HIMSI</title>";
        echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
        echo "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>";
        echo "<style>";
        echo "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');";
        echo ":root { --primary-color: #6366f1; --secondary-color: #8b5cf6; --success-color: #10b981; --warning-color: #f59e0b; --danger-color: #ef4444; }";
        echo "body { background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6); background-size: 400% 400%; animation: gradientShift 15s ease infinite; min-height: 100vh; font-family: 'Inter', sans-serif; }";
        echo "@keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }";
        echo ".navbar { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); padding: 1rem 0; }";
        echo ".navbar-brand { font-weight: 800; color: white !important; font-size: 1.8rem; text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }";
        echo ".container-main { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 25px; padding: 3rem; margin: 2rem auto; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); max-width: 1200px; }";
        echo ".page-title { color: white; font-size: 2.5rem; font-weight: 800; text-align: center; margin-bottom: 2rem; text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3); }";
        echo ".results-table { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }";
        echo ".table { color: white; margin-bottom: 0; }";
        echo ".table thead th { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; font-weight: 700; border: none; padding: 1.5rem 1rem; }";
        echo ".table tbody tr { background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.1); transition: all 0.3s ease; }";
        echo ".table tbody tr:hover { background: rgba(255, 255, 255, 0.1); transform: scale(1.01); }";
        echo ".table td { padding: 1.2rem 1rem; border: none; vertical-align: middle; }";
        echo ".total-card { background: rgba(16, 185, 129, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 15px; padding: 2rem; text-align: center; margin: 2rem 0; }";
        echo ".total-number { font-size: 3rem; font-weight: 800; color: var(--success-color); text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); }";
        echo ".back-btn { background: linear-gradient(135deg, #6c757d, #495057); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-flex; align-items: center; font-weight: 600; transition: all 0.3s ease; margin-top: 2rem; }";
        echo ".back-btn:hover { color: white; text-decoration: none; transform: translateY(-2px); box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4); }";
        echo ".no-data { background: rgba(255, 193, 7, 0.2); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 15px; padding: 3rem; text-align: center; color: white; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        // Navbar
        echo "<nav class='navbar navbar-expand-lg'>";
        echo "<div class='container-fluid'>";
        echo "<a class='navbar-brand' href='" . site_url('admin/dashboard') . "'><i class='fas fa-shield-alt me-2'></i>E-Voting HIMSI</a>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='text-white fw-bold'>Admin Panel</span>";
        echo "</div>";
        echo "</div>";
        echo "</nav>";
        
        echo "<div class='container-main'>";
        echo "<h1 class='page-title'>";
        echo "<i class='fas fa-chart-pie me-3'></i>Hasil Voting E-Voting HIMSI";
        echo "</h1>";
        
        // Ambil data hasil dari API
        $hasil_data = $this->get_hasil_voting();
        
        if (!empty($hasil_data)) {
            echo "<div class='results-table'>";
            echo "<table class='table table-hover'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th scope='col'><i class='fas fa-list-ol me-2'></i>No</th>";
            echo "<th scope='col'><i class='fas fa-user me-2'></i>Nama Calon</th>";
            echo "<th scope='col'><i class='fas fa-vote-yea me-2'></i>Jumlah Suara</th>";
            echo "<th scope='col'><i class='fas fa-percentage me-2'></i>Persentase</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            $total_suara = array_sum(array_column($hasil_data, 'jumlah_suara'));
            $no = 1;
            
            foreach ($hasil_data as $calon) {
                $persentase = $total_suara > 0 ? round(($calon['jumlah_suara'] / $total_suara) * 100, 2) : 0;
                echo "<tr>";
                echo "<td><span class='badge bg-primary rounded-pill'>" . $no++ . "</span></td>";
                echo "<td><strong>" . (isset($calon['nama']) ? htmlspecialchars($calon['nama']) : 'N/A') . "</strong></td>";
                echo "<td><span class='badge bg-success fs-6'>" . (isset($calon['jumlah_suara']) ? $calon['jumlah_suara'] : 0) . " suara</span></td>";
                echo "<td><span class='badge bg-warning text-dark fs-6'>" . $persentase . "%</span></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            
            echo "<div class='total-card'>";
            echo "<h3 class='text-white mb-3'><i class='fas fa-chart-bar me-2'></i>Total Suara Masuk</h3>";
            echo "<div class='total-number'>" . $total_suara . "</div>";
            echo "<p class='text-white-50 mb-0'>Total partisipasi pemilih</p>";
            echo "</div>";
        } else {
            echo "<div class='no-data'>";
            echo "<i class='fas fa-info-circle fa-3x mb-3'></i>";
            echo "<h3>Belum Ada Data Voting</h3>";
            echo "<p class='mb-0'>Belum ada pemilih yang melakukan voting atau data belum tersedia dari server.</p>";
            echo "</div>";
        }
        
        echo "<div class='text-center'>";
        echo "<a href='" . site_url('admin/dashboard') . "' class='back-btn'>";
        echo "<i class='fas fa-arrow-left me-2'></i>Kembali ke Dashboard";
        echo "</a>";
        echo "</div>";
        echo "</div>";
        
        echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
        echo "</body>";
        echo "</html>";
    }

    // Method untuk mengambil data calon dari API
    private function get_data_calon() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8001/index.php/api/calon',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code == 200 && !empty($response)) {
            $data = json_decode($response, true);
            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }
        }
        return array();
    }

    // Method untuk mengambil data pemilih dari API
    private function get_data_pemilih() {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost:8001/index.php/api/pemilih',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_code == 200 && !empty($response)) {
            $data = json_decode($response, true);
            if (isset($data['data']) && is_array($data['data'])) {
                return $data['data'];
            }
        }
        return array();
    }

    public function calon() {
        header('Content-Type: text/html; charset=utf-8');
        
        // Ambil data calon dari API
        $data_calon = $this->get_data_calon();
        
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
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<nav class='navbar navbar-expand-lg'>";
        echo "<div class='container-fluid'>";
        echo "<a class='navbar-brand' href='" . site_url('admin/dashboard') . "'><i class='fas fa-shield-alt me-2'></i>E-Voting HIMSI</a>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='text-white fw-bold'>Panel Admin - Kelola Calon</span>";
        echo "</div>";
        echo "</div>";
        echo "</nav>";
        
        echo "<div class='container-main'>";
        echo "<h1 class='page-title'>";
        echo "<i class='fas fa-user-tie me-3'></i>Daftar Calon Kandidat";
        echo "</h1>";
        
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
        echo "<a href='" . site_url('admin/dashboard') . "' class='back-btn'>";
        echo "<i class='fas fa-arrow-left me-2'></i>Kembali ke Dashboard";
        echo "</a>";
        echo "</div>";
        echo "</div>";
        
        echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
        echo "</body>";
        echo "</html>";
    }

    public function pemilih() {
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
        echo "</style>";
        echo "</head>";
        echo "<body>";
        
        echo "<nav class='navbar navbar-expand-lg'>";
        echo "<div class='container-fluid'>";
        echo "<a class='navbar-brand' href='" . site_url('admin/dashboard') . "'><i class='fas fa-shield-alt me-2'></i>E-Voting HIMSI</a>";
        echo "<div class='d-flex align-items-center'>";
        echo "<span class='text-white fw-bold'>Panel Admin - Kelola Pemilih</span>";
        echo "</div>";
        echo "</div>";
        echo "</nav>";
        
        echo "<div class='container-main'>";
        echo "<h1 class='page-title'>";
        echo "<i class='fas fa-users me-3'></i>Daftar Pemilih Terdaftar";
        echo "</h1>";
        
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
            
            echo "<div class='stats-summary'>";
            echo "<h4><i class='fas fa-chart-pie me-2'></i>Statistik Pemilih</h4>";
            echo "<div class='stats-grid'>";
            echo "<div class='stat-item'>";
            echo "<h5>$total_pemilih</h5>";
            echo "<p class='mb-0'>Total Pemilih</p>";
            echo "</div>";
            echo "<div class='stat-item'>";
            echo "<h5>$sudah_vote</h5>";
            echo "<p class='mb-0'>Sudah Memilih</p>";
            echo "</div>";
            echo "<div class='stat-item'>";
            echo "<h5>$belum_vote</h5>";
            echo "<p class='mb-0'>Belum Memilih</p>";
            echo "</div>";
            echo "<div class='stat-item'>";
            $persentase = $total_pemilih > 0 ? round(($sudah_vote / $total_pemilih) * 100, 1) : 0;
            echo "<h5>$persentase%</h5>";
            echo "<p class='mb-0'>Partisipasi</p>";
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
        echo "<a href='" . site_url('admin/dashboard') . "' class='back-btn'>";
        echo "<i class='fas fa-arrow-left me-2'></i>Kembali ke Dashboard";
        echo "</a>";
        echo "</div>";
        echo "</div>";
        
        echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script>";
        echo "</body>";
        echo "</html>";
    }

    private function get_stats() {
        // Statistik dasar menggunakan cURL
        $stats = array(
            'total_calon' => 0,
            'total_pemilih' => 0,
            'sudah_memilih' => 0,
            'belum_memilih' => 0
        );

        // Get calon count
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/index.php/api/calon');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response && $http_code == 200) {
            $result = json_decode($response, true);
            if (isset($result['data']) && is_array($result['data'])) {
                $stats['total_calon'] = count($result['data']);
            }
        }

        // Get pemilih count
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/index.php/api/pemilih');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response && $http_code == 200) {
            $result = json_decode($response, true);
            if (isset($result['data']) && is_array($result['data'])) {
                $stats['total_pemilih'] = count($result['data']);
                
                // Hitung yang sudah memilih
                foreach ($result['data'] as $pemilih) {
                    if (isset($pemilih['status_memilih']) && $pemilih['status_memilih'] == '1') {
                        $stats['sudah_memilih']++;
                    }
                }
                $stats['belum_memilih'] = $stats['total_pemilih'] - $stats['sudah_memilih'];
            }
        }

        return $stats;
    }

    private function get_hasil_voting() {
        // Get hasil voting dari API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8001/index.php/api/calon');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response && $http_code == 200) {
            $result = json_decode($response, true);
            if (isset($result['data']) && is_array($result['data'])) {
                return $result['data'];
            }
        }

        return [];
    }

    // Method untuk kembali ke halaman utama dan logout
    public function kembali() {
        redirect('/');
    }

    public function logout() {
        // Hapus semua data session
        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'Logout berhasil!');
        
        // Redirect ke halaman utama
        redirect('/');
    }
}

