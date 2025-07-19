<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - E-Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            padding: 30px;
            margin: 20px 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin: 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 10px;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
        }

        .status-consistent {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
        }

        .status-inconsistent {
            background: linear-gradient(45deg, #f44336, #da190b);
            color: white;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .btn-danger-glass {
            background: rgba(220, 53, 69, 0.3);
            border-color: rgba(220, 53, 69, 0.5);
        }

        .btn-danger-glass:hover {
            background: rgba(220, 53, 69, 0.5);
            border-color: rgba(220, 53, 69, 0.7);
        }

        .alert-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            color: #fff;
        }

        .loading-spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid #fff;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .navbar-glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .progress-glass {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-glass {
            background: linear-gradient(45deg, #667eea, #764ba2);
            height: 8px;
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        .chart-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }

        .candidate-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ranking-badge {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            color: #333;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-glass">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <i class="fas fa-chart-line me-2"></i>
                Kelola Statistik Voting
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="<?= base_url('dashboard/dashboard') ?>">
                    <i class="fas fa-home me-1"></i> Dashboard
                </a>
                <a class="nav-link text-white" href="<?= base_url('pemilihan/hasil') ?>">
                    <i class="fas fa-chart-bar me-1"></i> Hasil
                </a>
                <a class="nav-link text-white" href="<?= base_url('dashboard/logout') ?>">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Status Sinkronisasi -->
        <div class="glass-container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white mb-0">
                        <i class="fas fa-sync-alt me-2"></i>
                        Status Sinkronisasi Data
                    </h2>
                    <p class="text-white-50 mb-0">Real-time monitoring & control</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-glass" onclick="refreshStats()">
                        <i class="fas fa-refresh me-1"></i> Refresh
                    </button>
                    <button class="btn btn-danger-glass ms-2" onclick="resetStats()">
                        <i class="fas fa-trash me-1"></i> Reset All
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="glass-container text-center" style="display: none;">
            <div class="loading-spinner"></div>
            <p class="text-white mt-3">Memuat data statistik...</p>
        </div>

        <!-- Validasi Status -->
        <div id="validation-status" class="glass-container" style="display: none;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="text-white mb-2">
                        <i class="fas fa-shield-alt me-2"></i>
                        Status Validasi Data
                    </h4>
                    <div id="validation-badge"></div>
                    <div id="validation-details" class="mt-3"></div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="stat-card">
                        <div class="stat-number" id="selisih-count">0</div>
                        <div class="stat-label">Selisih Data</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div id="main-stats" class="row" style="display: none;">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="total-suara">0</div>
                    <div class="stat-label">
                        <i class="fas fa-vote-yea me-1"></i>
                        Total Suara
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="total-pemilih">0</div>
                    <div class="stat-label">
                        <i class="fas fa-users me-1"></i>
                        Total Pemilih
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="sudah-voting">0</div>
                    <div class="stat-label">
                        <i class="fas fa-check-circle me-1"></i>
                        Sudah Voting
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-number" id="tingkat-partisipasi">0%</div>
                    <div class="stat-label">
                        <i class="fas fa-percentage me-1"></i>
                        Partisipasi
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar Partisipasi -->
        <div id="participation-progress" class="glass-container" style="display: none;">
            <h5 class="text-white mb-3">
                <i class="fas fa-chart-line me-2"></i>
                Progress Partisipasi
            </h5>
            <div class="progress-glass">
                <div id="progress-bar" class="progress-bar-glass" style="width: 0%"></div>
            </div>
            <div class="text-center mt-2">
                <span class="text-white" id="progress-text">0% (0 dari 0 pemilih)</span>
            </div>
        </div>

        <!-- Data Calon -->
        <div id="candidates-data" class="glass-container" style="display: none;">
            <h5 class="text-white mb-4">
                <i class="fas fa-trophy me-2"></i>
                Ranking Calon
            </h5>
            <div id="candidates-list"></div>
        </div>

        <!-- Chart -->
        <div id="chart-section" class="glass-container" style="display: none;">
            <h5 class="text-white mb-4">
                <i class="fas fa-chart-pie me-2"></i>
                Distribusi Suara
            </h5>
            <div class="chart-container">
                <canvas id="voteChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Log Section -->
        <div id="log-section" class="glass-container" style="display: none;">
            <h5 class="text-white mb-3">
                <i class="fas fa-list-alt me-2"></i>
                Log Sinkronisasi
            </h5>
            <div id="sync-log" class="alert-glass p-3">
                <p id="last-sync" class="mb-0 text-white"></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let chart = null;

        // Load data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            refreshStats();
            
            // Auto refresh setiap 30 detik
            setInterval(refreshStats, 30000);
        });

        function refreshStats() {
            showLoading(true);
            
            fetch('<?= base_url("admin_statistics/get_stats") ?>')
                .then(response => response.json())
                .then(data => {
                    showLoading(false);
                    
                    if (data.status) {
                        updateDisplay(data.data);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showLoading(false);
                    showError('Error: ' + error.message);
                });
        }

        function updateDisplay(data) {
            // Update validation status
            const validasi = data.validasi;
            const statusBadge = document.getElementById('validation-badge');
            const validationDetails = document.getElementById('validation-details');
            
            if (validasi.konsisten) {
                statusBadge.innerHTML = '<span class="status-badge status-consistent"><i class="fas fa-check me-1"></i>Data Konsisten</span>';
                validationDetails.innerHTML = `
                    <div class="alert-glass alert p-3">
                        <p class="mb-1 text-white"><strong>✅ Validasi berhasil!</strong></p>
                        <p class="mb-0 text-white-50">${validasi.detail}</p>
                    </div>
                `;
            } else {
                statusBadge.innerHTML = '<span class="status-badge status-inconsistent"><i class="fas fa-exclamation-triangle me-1"></i>Data Tidak Konsisten</span>';
                validationDetails.innerHTML = `
                    <div class="alert-glass alert p-3">
                        <p class="mb-1 text-white"><strong>⚠️ Ditemukan inkonsistensi!</strong></p>
                        <p class="mb-1 text-white-50">${validasi.detail}</p>
                        <small class="text-white-50">Total suara di tabel calon: ${validasi.total_suara_calon}, Pemilih yang sudah voting: ${validasi.total_pemilih_voting}</small>
                    </div>
                `;
            }
            
            document.getElementById('selisih-count').textContent = validasi.selisih;

            // Update main statistics
            const stats = data.statistik;
            document.getElementById('total-suara').textContent = stats.total_suara.toLocaleString();
            document.getElementById('total-pemilih').textContent = stats.total_pemilih.toLocaleString();
            document.getElementById('sudah-voting').textContent = stats.sudah_voting.toLocaleString();
            document.getElementById('tingkat-partisipasi').textContent = stats.tingkat_partisipasi + '%';

            // Update progress bar
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            progressBar.style.width = stats.tingkat_partisipasi + '%';
            progressText.textContent = `${stats.tingkat_partisipasi}% (${stats.sudah_voting} dari ${stats.total_pemilih} pemilih)`;

            // Update candidates ranking
            updateCandidatesRanking(stats.data_calon);

            // Update chart
            updateChart(stats.data_calon);

            // Update log
            document.getElementById('last-sync').textContent = 'Terakhir disinkronisasi: ' + stats.last_updated;

            // Show all sections
            showAllSections();
        }

        function updateCandidatesRanking(candidates) {
            const candidatesList = document.getElementById('candidates-list');
            candidatesList.innerHTML = '';

            candidates.forEach(candidate => {
                const candidateItem = document.createElement('div');
                candidateItem.className = 'candidate-item';
                candidateItem.innerHTML = `
                    <div class="d-flex align-items-center">
                        <div class="ranking-badge me-3">${candidate.ranking}</div>
                        <div>
                            <h6 class="text-white mb-1">${candidate.nama_calon}</h6>
                            <small class="text-white-50">${candidate.visi}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-white fw-bold">${candidate.jumlah_suara.toLocaleString()} suara</div>
                        <small class="text-white-50">${candidate.persentase}%</small>
                    </div>
                `;
                candidatesList.appendChild(candidateItem);
            });
        }

        function updateChart(candidates) {
            const ctx = document.getElementById('voteChart').getContext('2d');
            
            if (chart) {
                chart.destroy();
            }

            const labels = candidates.map(c => c.nama_calon);
            const data = candidates.map(c => parseInt(c.jumlah_suara));
            const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];

            chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, candidates.length),
                        borderWidth: 2,
                        borderColor: 'rgba(255, 255, 255, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white'
                            }
                        }
                    }
                }
            });
        }

        function resetStats() {
            if (!confirm('PERINGATAN: Ini akan menghapus SEMUA data voting dan mereset statistik ke 0.\n\nApakah Anda benar-benar yakin ingin melanjutkan?')) {
                return;
            }

            if (!confirm('Konfirmasi sekali lagi: Semua data voting akan hilang permanen. Lanjutkan?')) {
                return;
            }

            showLoading(true);

            fetch('<?= base_url("admin_statistics/reset_stats") ?>', {
                method: 'POST'
            })
                .then(response => response.json())
                .then(data => {
                    showLoading(false);
                    
                    if (data.status) {
                        alert('✅ Reset statistik berhasil!');
                        refreshStats();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    showLoading(false);
                    alert('❌ Error: ' + error.message);
                });
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
            
            if (!show) {
                showAllSections();
            } else {
                hideAllSections();
            }
        }

        function showAllSections() {
            document.getElementById('validation-status').style.display = 'block';
            document.getElementById('main-stats').style.display = 'flex';
            document.getElementById('participation-progress').style.display = 'block';
            document.getElementById('candidates-data').style.display = 'block';
            document.getElementById('chart-section').style.display = 'block';
            document.getElementById('log-section').style.display = 'block';
        }

        function hideAllSections() {
            document.getElementById('validation-status').style.display = 'none';
            document.getElementById('main-stats').style.display = 'none';
            document.getElementById('participation-progress').style.display = 'none';
            document.getElementById('candidates-data').style.display = 'none';
            document.getElementById('chart-section').style.display = 'none';
            document.getElementById('log-section').style.display = 'none';
        }

        function showError(message) {
            const container = document.querySelector('.container');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert-glass alert mt-3';
            errorDiv.innerHTML = `
                <h5 class="text-white"><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
                <p class="text-white mb-0">${message}</p>
            `;
            container.appendChild(errorDiv);

            // Remove error after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>
