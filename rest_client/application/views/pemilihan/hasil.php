<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemilihan - E-Voting HIMSI</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
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

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            color: white !important;
            font-size: 1.5rem;
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

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .main-container {
            padding: 2rem 0;
        }

        .header-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header-section h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .header-section p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .stats-section {
            margin-bottom: 3rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.total-suara {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-icon.total-pemilih {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .stat-icon.partisipasi {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 1rem;
        }

        .results-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .candidate-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .candidate-card.winner {
            border: 2px solid #ffd700;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 255, 255, 0.9) 100%);
        }

        .candidate-card.winner::before {
            content: '👑';
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
        }

        .candidate-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        .candidate-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .candidate-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #dee2e6;
        }

        .candidate-details h4 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        .vote-count {
            font-size: 1.8rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .vote-percentage {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        .progress-container {
            margin-top: 1rem;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
            background: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            transition: width 1s ease-in-out;
        }

        .chart-container {
            margin-top: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1.5rem;
        }

        .refresh-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .refresh-btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }
            
            .candidate-info {
                flex-direction: column;
                text-align: center;
            }
            
            .candidate-photo {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
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
                        <a class="nav-link active" href="<?= site_url('pemilihan/hasil') ?>">
                            <i class="fas fa-chart-bar me-1"></i>Hasil
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('auth/logout') ?>">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container main-container">
        <!-- Header Section -->
        <div class="header-section">
            <h1><i class="fas fa-chart-bar me-3"></i>Hasil Pemilihan</h1>
            <p>Hasil Real-time Pemilihan Ketua HIMSI</p>
            <button class="refresh-btn mt-3" onclick="location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Refresh Data
            </button>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon total-suara">
                            <i class="fas fa-vote-yea"></i>
                        </div>
                        <div class="stat-number"><?= number_format($total_suara); ?></div>
                        <div class="stat-label">Total Suara Masuk</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon total-pemilih">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number"><?= number_format($total_pemilih); ?></div>
                        <div class="stat-label">Total Pemilih Terdaftar</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon partisipasi">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="stat-number"><?= $tingkat_partisipasi; ?>%</div>
                        <div class="stat-label">Tingkat Partisipasi</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="results-section">
            <h2 class="section-title">
                <i class="fas fa-trophy"></i>
                Perolehan Suara per Calon
            </h2>

            <?php if (!empty($data_hasil)): ?>
                <?php 
                $total_suara_valid = max($total_suara, 1); // Hindari pembagian dengan 0
                $rank = 1;
                ?>
                <?php foreach ($data_hasil as $calon): ?>
                    <?php 
                    $persentase = round(($calon['jumlah_suara'] / $total_suara_valid) * 100, 2);
                    $is_winner = ($rank === 1 && $calon['jumlah_suara'] > 0);
                    ?>
                    <div class="candidate-card <?= $is_winner ? 'winner' : ''; ?>">
                        <div class="candidate-info">
                            <div class="candidate-photo-container">
                                <?php if (!empty($calon['foto'])): ?>
                                    <img src="<?= 'http://localhost/y/rest_server/uploads/' . $calon['foto']; ?>" 
                                         alt="Foto <?= htmlspecialchars($calon['nama_calon']); ?>" 
                                         class="candidate-photo">
                                <?php else: ?>
                                    <div class="candidate-photo" style="background: #dee2e6; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user" style="color: #6c757d; font-size: 2rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="candidate-details flex-grow-1">
                                <h4><?= htmlspecialchars($calon['nama_calon']); ?></h4>
                                
                                <div class="d-flex align-items-center gap-3">
                                    <div class="vote-count">
                                        <?= number_format($calon['jumlah_suara']); ?> suara
                                    </div>
                                    <div class="vote-percentage">
                                        <?= $persentase; ?>%
                                    </div>
                                </div>
                                
                                <div class="progress-container">
                                    <div class="progress">
                                        <div class="progress-bar" 
                                             style="width: <?= $persentase; ?>%" 
                                             data-percentage="<?= $persentase; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rank-display">
                                <div style="font-size: 2rem; font-weight: 800; color: #667eea;">
                                    #<?= $rank; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $rank++; ?>
                <?php endforeach; ?>
                
                <!-- Chart Section -->
                <div class="chart-container">
                    <h3 class="section-title">
                        <i class="fas fa-chart-donut"></i>
                        Grafik Perolehan Suara
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="pieChart" width="400" height="300"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="barChart" width="400" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox" style="font-size: 4rem; color: #dee2e6; margin-bottom: 1rem;"></i>
                    <h4 style="color: #6c757d;">Belum ada data hasil pemilihan</h4>
                    <p style="color: #6c757d;">Data akan muncul setelah ada pemilih yang memberikan suara</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Chart.js Script -->
    <?php if (!empty($data_hasil)): ?>
    <script>
        // Data untuk chart
        const chartData = {
            labels: [<?php foreach ($data_hasil as $calon): ?>'<?= htmlspecialchars($calon['nama_calon']); ?>',<?php endforeach; ?>],
            datasets: [{
                data: [<?php foreach ($data_hasil as $calon): ?><?= $calon['jumlah_suara']; ?>,<?php endforeach; ?>],
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#ffecd2',
                    '#fcb69f',
                    '#a8edea',
                    '#fed6e3'
                ],
                borderWidth: 0
            }]
        };

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                family: 'Segoe UI'
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Suara',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Jumlah Suara',
                    data: chartData.datasets[0].data,
                    backgroundColor: '#667eea',
                    borderColor: '#764ba2',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Suara',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Animate progress bars
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const percentage = bar.getAttribute('data-percentage');
                setTimeout(() => {
                    bar.style.width = percentage + '%';
                }, 500);
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
