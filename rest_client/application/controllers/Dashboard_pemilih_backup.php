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
                'message' => 'Data tidak lengkap.'
            ]);
            return;
        }

        try {
            // Panggil API untuk memilih calon
            $response = $this->_client->request('POST', 'pemilihan/pilih', [
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
            margin-top: 30px; 
        }
        .calon-card { 
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
            overflow: hidden; 
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .calon-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.15); 
        }
        .calon-card .foto-container { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            padding: 30px; 
            text-align: center; 
        }
        .calon-card img { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid white; 
        }
        .calon-card .content { padding: 25px; }
        .calon-card h3 { 
            color: #333; 
            margin-bottom: 15px; 
            font-size: 22px; 
            text-align: center;
        }
        .calon-card .section { margin-bottom: 20px; }
        .calon-card .section-title { 
            font-weight: bold; 
            color: #667eea; 
            margin-bottom: 8px; 
            font-size: 14px; 
            text-transform: uppercase;
        }
        .calon-card .section-content { 
            color: #555; 
            line-height: 1.6; 
            font-size: 14px;
        }
        .btn-vote { 
            width: 100%; 
            padding: 15px; 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: all 0.3s; 
            margin-top: 10px;
        }
        .btn-vote:hover { 
            background: linear-gradient(135deg, #218838 0%, #1a9b7a 100%); 
            transform: translateY(-2px); 
        }
        .btn-vote:disabled { 
            background: #6c757d; 
            cursor: not-allowed; 
            transform: none;
        }
        .alert { 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 8px; 
            text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .no-calon { 
            text-align: center; 
            padding: 50px; 
            color: #666; 
            background: white; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="navbar">
        <h1>Dashboard Pemilih</h1>
        <div class="user-info">
            <span class="user-name">' . $pemilih_nama . ' (' . $pemilih_nim . ')</span>
            <a href="' . site_url('auth_pemilih/logout') . '" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">';
    
        // Tampilkan pesan success/error jika ada
        if ($this->session->flashdata('success')) {
            echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
        }
        if ($this->session->flashdata('error')) {
            echo '<div class="alert alert-error">' . $this->session->flashdata('error') . '</div>';
        }
        
        echo '<div class="welcome-box">
            <h2>Selamat Datang di Sistem E-Voting HIMSI</h2>
            <p>Silakan pilih calon yang Anda inginkan dengan klik tombol "PILIH" di bawah foto calon.</p>';
            
        if ($status_memilih == '1') {
            echo '<div class="status status-sudah">✓ Anda sudah memberikan suara</div>';
        } else {
            echo '<div class="status status-belum">⚠ Anda belum memberikan suara</div>';
        }
        
        echo '</div>';
        
        if (!empty($data_calon)) {
            echo '<div class="calon-container">';
            foreach ($data_calon as $calon) {
                echo '<div class="calon-card">
                    <div class="foto-container">
                        <img src="http://localhost/y/rest_server/uploads/' . htmlspecialchars($calon['foto']) . '" alt="Foto ' . htmlspecialchars($calon['nama_calon']) . '">
                    </div>
                    <div class="content">
                        <h3>' . htmlspecialchars($calon['nama_calon']) . '</h3>
                        
                        <div class="section">
                            <div class="section-title">Visi</div>
                            <div class="section-content">' . nl2br(htmlspecialchars($calon['visi'])) . '</div>
                        </div>
                        
                        <div class="section">
                            <div class="section-title">Misi</div>
                            <div class="section-content">' . nl2br(htmlspecialchars($calon['misi'])) . '</div>
                        </div>';
                        
                if ($status_memilih == '1') {
                    echo '<button class="btn-vote" disabled>Anda sudah memilih</button>';
                } else {
                    echo '<button class="btn-vote" onclick="vote(' . $calon['id_calon'] . ', \'' . htmlspecialchars($calon['nama_calon']) . '\')">PILIH</button>';
                }
                
                echo '</div>
                </div>';
            }
            echo '</div>';
        } else {
            echo '<div class="no-calon">
                <h3>Belum Ada Calon</h3>
                <p>Saat ini belum ada calon yang tersedia untuk dipilih.</p>
            </div>';
        }
        
        echo '</div>
    
    <script>
        function vote(id_calon, nama_calon) {
            if (confirm("Apakah Anda yakin ingin memilih " + nama_calon + "?\\n\\nPerhatian: Setelah memilih, Anda tidak dapat mengubah pilihan!")) {
                $.ajax({
                    url: "' . site_url('dashboard_pemilih/vote') . '",
                    method: "POST",
                    data: {
                        id_calon: id_calon
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $(".btn-vote").prop("disabled", true).text("Memproses...");
                    },
                    success: function(response) {
                        if (response.status) {
                            alert("Terima kasih! Suara Anda telah berhasil dicatat.");
                            location.reload();
                        } else {
                            alert("Gagal memberikan suara: " + response.message);
                            $(".btn-vote").prop("disabled", false).text("PILIH");
                        }
                    },
                    error: function() {
                        alert("Terjadi kesalahan sistem. Silakan coba lagi.");
                        $(".btn-vote").prop("disabled", false).text("PILIH");
                    }
                });
            }
        }
    </script>
</body>
</html>';
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

    public function vote()
    {
        // Cek apakah pemilih sudah memilih
        if ($this->session->userdata('pemilih_status_memilih') == '1') {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'Anda sudah memberikan suara sebelumnya.']);
            return;
        }

        $id_calon = $this->input->post('id_calon');
        $id_pemilih = $this->session->userdata('pemilih_id');

        if (empty($id_calon)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'ID Calon tidak valid.']);
            return;
        }

        try {
            // Kirim vote ke API
            $response = $this->_client->request('POST', 'pemilihan/vote', [
                'form_params' => [
                    'id_calon' => $id_calon,
                    'id_pemilih' => $id_pemilih
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['status']) && $result['status'] === true) {
                // Update status memilih di session
                $this->session->set_userdata('pemilih_status_memilih', '1');
                
                header('Content-Type: application/json');
                echo json_encode(['status' => true, 'message' => 'Suara berhasil dicatat.']);
            } else {
                $error_msg = isset($result['message']) ? $result['message'] : 'Gagal memberikan suara.';
                header('Content-Type: application/json');
                echo json_encode(['status' => false, 'message' => $error_msg]);
            }

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $result = json_decode($response->getBody()->getContents(), true);
            $error_msg = isset($result['message']) ? $result['message'] : 'Gagal memberikan suara.';
            
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => $error_msg]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'Terjadi kesalahan sistem.']);
        }
    }
}
