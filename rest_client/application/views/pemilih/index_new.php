<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Kelola Data Pemilih' ?> - E-Voting HIMSI</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-bg: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Glass Morphism Navigation */
        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: white !important;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 0.5rem 1rem !important;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-header h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
        }

        /* Action Buttons */
        .action-buttons {
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-glass {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-glass:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-glass.btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-glass.btn-success:hover {
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-glass.btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }

        .btn-glass.btn-warning:hover {
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        .btn-glass.btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        .btn-glass.btn-danger:hover {
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
        }

        /* Table Styling */
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-sudah {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-belum {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        /* Action buttons in table */
        .btn-action {
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-action.btn-sm-success {
            background: #10b981;
            color: white;
        }

        .btn-action.btn-sm-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-action.btn-sm-danger {
            background: #ef4444;
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: white;
            text-decoration: none;
        }

        /* Alert Styling */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            color: #059669;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .table-responsive {
                border-radius: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/') ?>">
                <i class="fas fa-vote-yea me-2"></i>
                E-Voting HIMSI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('dashboard/dashboard') ?>">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('pemilih') ?>">
                            <i class="fas fa-users me-1"></i>Data Pemilih
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('calon') ?>">
                            <i class="fas fa-user-tie me-1"></i>Data Calon
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('pemilihan/hasil') ?>">
                            <i class="fas fa-chart-bar me-1"></i>Hasil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('auth/logout') ?>">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-users me-3"></i>Kelola Data Pemilih</h1>
            <p>Manajemen data pemilih sistem e-voting HIMSI</p>
        </div>

        <!-- Content Card -->
        <div class="glass-card">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="<?= site_url('pemilih/tambah') ?>" class="btn-glass">
                    <i class="fas fa-plus"></i>
                    Tambah Pemilih
                </a>
                <a href="<?= site_url('pemilih/toggle_all_status') ?>" class="btn-glass btn-warning" 
                   onclick="return confirm('Apakah Anda yakin ingin mengubah status semua pemilih?')">
                    <i class="fas fa-toggle-on"></i>
                    Toggle Status Semua
                </a>
                <a href="<?= site_url('pemilih/reset_all_status') ?>" class="btn-glass btn-danger" 
                   onclick="return confirm('Apakah Anda yakin ingin mereset status voting semua pemilih?')">
                    <i class="fas fa-undo"></i>
                    Reset Status Voting
                </a>
                <a href="<?= site_url('pemilih/reset_votes') ?>" class="btn-glass btn-warning" 
                   onclick="return confirm('Apakah Anda yakin ingin mereset semua suara?')">
                    <i class="fas fa-eraser"></i>
                    Reset Semua Suara
                </a>
            </div>

            <!-- Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Status Voting</th>
                                <th>Tanggal Daftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pemilih)): ?>
                                <?php foreach ($pemilih as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['id_pemilih']); ?></td>
                                        <td><strong><?= htmlspecialchars($p['nim']); ?></strong></td>
                                        <td><?= htmlspecialchars($p['nama']); ?></td>
                                        <td>
                                            <?php if ($p['status_memilih'] == 1): ?>
                                                <span class="status-badge status-sudah">
                                                    <i class="fas fa-check-circle me-1"></i>Sudah Memilih
                                                </span>
                                            <?php else: ?>
                                                <span class="status-badge status-belum">
                                                    <i class="fas fa-clock me-1"></i>Belum Memilih
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= isset($p['created_at']) && $p['created_at'] ? 
                                                date('d/m/Y H:i', strtotime($p['created_at'])) : 
                                                '<em>Tidak tersedia</em>'; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= site_url('pemilih/edit/' . $p['id_pemilih']) ?>" 
                                                   class="btn-action btn-sm-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?= site_url('pemilih/toggle_status/' . $p['id_pemilih']) ?>" 
                                                   class="btn-action btn-sm-success" title="Toggle Status">
                                                    <i class="fas fa-toggle-on"></i>
                                                </a>
                                                <a href="<?= site_url('pemilih/hapus/' . $p['id_pemilih']) ?>" 
                                                   class="btn-action btn-sm-danger" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus pemilih ini?')" 
                                                   title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
                                        <br>
                                        <span class="text-muted">Belum ada data pemilih</span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
