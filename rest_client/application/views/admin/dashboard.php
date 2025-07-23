<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem E-Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        /* Floating particles background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .navbar-brand {
            font-weight: 800;
            color: white !important;
            font-size: 1.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
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

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .admin-badge {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
            box-shadow: 0 2px 10px rgba(238, 90, 36, 0.3);
        }

        .btn-logout {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-logout:hover::before {
            left: 100%;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(238, 90, 36, 0.6);
        }

        .container-fluid {
            padding: 2rem;
        }

        .welcome-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 3rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            font-weight: 400;
            margin-bottom: 0.5rem;
        }

        .admin-info {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 500;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px 20px 0 0;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.15);
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 600;
        }

        .actions-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .action-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.15);
        }

        .action-title {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        }

        .action-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .btn-action {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.6);
            color: white;
            text-decoration: none;
        }

        .quick-stats {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .quick-stats-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        }

        .quick-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-stat-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .quick-stat-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        .quick-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .quick-stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .stats-container,
            .actions-container {
                grid-template-columns: 1fr;
            }
            
            .container-fluid {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('dashboard/dashboard') ?>">
                <i class="fas fa-vote-yea me-2"></i>
                E-Voting HIMSI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= site_url('dashboard/dashboard') ?>">
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
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('admin_auth/logout') ?>">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="welcome-content">
                <h1 class="welcome-title">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    Dashboard Administrator
                </h1>
                <p class="welcome-subtitle">Selamat datang di panel kontrol sistem e-voting</p>
                <p class="admin-info">
                    <i class="fas fa-user-shield me-2"></i>
                    Admin: <?= $nama; ?> | Login: <?= $username; ?>
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?= $stats_pemilih['total']; ?></div>
                <div class="stat-label">Total Pemilih</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number"><?= $stats_pemilih['sudah_memilih']; ?></div>
                <div class="stat-label">Sudah Memilih</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-number"><?= $stats_pemilih['belum_memilih']; ?></div>
                <div class="stat-label">Belum Memilih</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-number"><?= $stats_calon['total']; ?></div>
                <div class="stat-label">Total Calon</div>
            </div>
        </div>

        <!-- Default Credentials Info -->
        <div class="quick-stats">
            <h3 class="quick-stats-title">
                <i class="fas fa-key me-2"></i>
                Kredensial Default
            </h3>
            <div class="quick-stats-grid">
                <div class="quick-stat-item">
                    <div class="quick-stat-value" style="font-size: 1.5rem;">admin</div>
                    <div class="quick-stat-label">Username</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value" style="font-size: 1.5rem;">1234</div>
                    <div class="quick-stat-label">Password</div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <h3 class="quick-stats-title">
                <i class="fas fa-chart-pie me-2"></i>
                Statistik Voting
            </h3>
            <div class="quick-stats-grid">
                <div class="quick-stat-item">
                    <div class="quick-stat-value"><?= number_format(($stats_pemilih['total'] > 0 ? ($stats_pemilih['sudah_memilih'] / $stats_pemilih['total']) * 100 : 0), 1); ?>%</div>
                    <div class="quick-stat-label">Tingkat Partisipasi</div>
                </div>
                <div class="quick-stat-item">
                    <div class="quick-stat-value"><?= $stats_calon['total_suara']; ?></div>
                    <div class="quick-stat-label">Total Suara</div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="actions-container">
            <div class="action-card">
                <h3 class="action-title">
                    <i class="fas fa-users-cog me-2"></i>
                    Kelola Pemilih
                </h3>
                <p class="action-description">
                    Tambah, edit, atau hapus data pemilih yang terdaftar dalam sistem e-voting.
                </p>
                <a href="<?= site_url('pemilih'); ?>" class="btn-action">
                    <i class="fas fa-edit me-2"></i>
                    Kelola Pemilih
                </a>
            </div>
            
            <div class="action-card">
                <h3 class="action-title">
                    <i class="fas fa-user-graduate me-2"></i>
                    Kelola Calon
                </h3>
                <p class="action-description">
                    Tambah, edit, atau hapus data calon yang akan mengikuti pemilihan.
                </p>
                <a href="<?= site_url('calon'); ?>" class="btn-action">
                    <i class="fas fa-user-edit me-2"></i>
                    Kelola Calon
                </a>
            </div>
            
            <div class="action-card">
                <h3 class="action-title">
                    <i class="fas fa-poll me-2"></i>
                    Hasil Pemilihan
                </h3>
                <p class="action-description">
                    Lihat hasil real-time dari pemilihan yang sedang berlangsung.
                </p>
                <a href="<?= site_url('pemilihan/hasil'); ?>" class="btn-action">
                    <i class="fas fa-chart-bar me-2"></i>
                    Lihat Hasil
                </a>
            </div>
            
            <div class="action-card">
                <h3 class="action-title">
                    <i class="fas fa-cogs me-2"></i>
                    Pengaturan Sistem
                </h3>
                <p class="action-description">
                    Konfigurasi pengaturan sistem e-voting dan manajemen admin.
                </p>
                <a href="<?= site_url('admin/settings'); ?>" class="btn-action">
                    <i class="fas fa-tools me-2"></i>
                    Pengaturan
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Auto refresh statistics every 30 seconds
        setInterval(function() {
            location.reload();
        }, 30000);

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat numbers on page load
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(function(element) {
                const finalValue = parseInt(element.textContent);
                let currentValue = 0;
                const increment = finalValue / 50;
                
                const timer = setInterval(function() {
                    currentValue += increment;
                    if (currentValue >= finalValue) {
                        currentValue = finalValue;
                        clearInterval(timer);
                    }
                    element.textContent = Math.round(currentValue);
                }, 30);
            });
        });
    </script>
</body>
</html>
