<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pemilih - Sistem E-Voting</title>
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
            font-size: 1.8rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        .pemilih-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
        }

        .user-info {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-right: 2rem;
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

        .pemilih-info {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            font-weight: 500;
        }

        .status-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .status-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 20px 20px 0 0;
        }

        .status-belum::before {
            background: linear-gradient(90deg, var(--warning-color), #fb923c);
        }

        .status-sudah::before {
            background: linear-gradient(90deg, var(--success-color), #34d399);
        }

        .status-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .status-belum .status-icon {
            background: rgba(251, 146, 60, 0.2);
        }

        .status-sudah .status-icon {
            background: rgba(52, 211, 153, 0.2);
        }

        .status-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .status-description {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            line-height: 1.6;
        }

        .calon-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .calon-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .calon-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.15);
        }

        .foto-container {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .foto-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .calon-foto {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 2;
        }

        .calon-content {
            padding: 2rem;
        }

        .calon-name {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-align: center;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        }

        .calon-section {
            margin-bottom: 1.5rem;
        }

        .calon-section-title {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .calon-section-content {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .btn-vote {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-vote::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-vote:hover::before {
            left: 100%;
        }

        .btn-vote:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
        }

        .btn-vote:disabled {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-vote:disabled::before {
            display: none;
        }

        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 15px;
            text-align: center;
            font-weight: 600;
            border: none;
            backdrop-filter: blur(20px);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            color: white;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.2);
            color: white;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .alert-info {
            background: rgba(99, 102, 241, 0.2);
            color: white;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .no-calon {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            font-size: 1.1rem;
        }

        .no-calon i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.6);
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }
            
            .calon-container {
                grid-template-columns: 1fr;
            }
            
            .container-fluid {
                padding: 1rem;
            }
            
            .navbar-brand {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-vote-yea me-2"></i>
                E-Voting HIMSI
                <span class="pemilih-badge">PEMILIH</span>
            </a>
            <div class="d-flex align-items-center">
                <span class="user-info me-3">
                    <i class="fas fa-user me-2"></i>
                    <?= $pemilih_nama; ?> (<?= $pemilih_nim; ?>)
                </span>
                <a href="<?= site_url('auth_pemilih/logout'); ?>" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="welcome-content">
                <h1 class="welcome-title">
                    <i class="fas fa-user-check me-3"></i>
                    Dashboard Pemilih
                </h1>
                <p class="welcome-subtitle">Selamat datang di sistem e-voting HIMSI</p>
                <p class="pemilih-info">
                    <i class="fas fa-id-card me-2"></i>
                    <?= $pemilih_nama; ?> | NIM: <?= $pemilih_nim; ?>
                </p>
            </div>
        </div>

        <!-- Status Card -->
        <div class="status-card <?= ($status_memilih == '1') ? 'status-sudah' : 'status-belum'; ?>">
            <div class="status-icon">
                <?php if ($status_memilih == '1'): ?>
                    <i class="fas fa-check-circle"></i>
                <?php else: ?>
                    <i class="fas fa-exclamation-triangle"></i>
                <?php endif; ?>
            </div>
            <h3 class="status-title">
                <?php if ($status_memilih == '1'): ?>
                    Anda Sudah Memilih
                <?php else: ?>
                    Anda Belum Memilih
                <?php endif; ?>
            </h3>
            <p class="status-description">
                <?php if ($status_memilih == '1'): ?>
                    Terima kasih telah berpartisipasi dalam pemilihan. Suara Anda telah tercatat dalam sistem.
                <?php else: ?>
                    Silakan pilih salah satu calon di bawah ini untuk melakukan voting.
                <?php endif; ?>
            </p>
        </div>

        <!-- Alert Messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <!-- Calon Cards -->
        <?php if (!empty($data_calon)): ?>
            <div class="calon-container">
                <?php foreach ($data_calon as $calon): ?>
                    <div class="calon-card">
                        <div class="foto-container">
                            <?php if (!empty($calon['foto'])): ?>
                                <img src="<?= 'http://localhost/y/rest_server/uploads/' . $calon['foto']; ?>" alt="Foto <?= htmlspecialchars($calon['nama_calon']); ?>" class="calon-foto">
                            <?php else: ?>
                                <div class="calon-foto" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="font-size: 3rem; color: white;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="calon-content">
                            <h3 class="calon-name"><?= htmlspecialchars($calon['nama_calon']); ?></h3>
                            
                            <?php if (!empty($calon['visi'])): ?>
                                <div class="calon-section">
                                    <div class="calon-section-title">
                                        <i class="fas fa-eye me-2"></i>Visi
                                    </div>
                                    <div class="calon-section-content">
                                        <?= nl2br(htmlspecialchars($calon['visi'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($calon['misi'])): ?>
                                <div class="calon-section">
                                    <div class="calon-section-title">
                                        <i class="fas fa-bullseye me-2"></i>Misi
                                    </div>
                                    <div class="calon-section-content">
                                        <?= nl2br(htmlspecialchars($calon['misi'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <button type="button" 
                                    class="btn-vote" 
                                    onclick="pilihCalon(<?= $calon['id_calon']; ?>, '<?= htmlspecialchars($calon['nama_calon']); ?>')"
                                    <?= ($status_memilih == '1') ? 'disabled' : ''; ?>>
                                <?php if ($status_memilih == '1'): ?>
                                    <i class="fas fa-check me-2"></i>Sudah Memilih
                                <?php else: ?>
                                    <i class="fas fa-vote-yea me-2"></i>Pilih Calon Ini
                                <?php endif; ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-calon">
                <i class="fas fa-users-slash"></i>
                <h3>Belum Ada Calon</h3>
                <p>Saat ini belum ada calon yang terdaftar dalam sistem.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        function pilihCalon(idCalon, namaCalon) {
            if (confirm('Apakah Anda yakin ingin memilih ' + namaCalon + '?\n\nSetelah memilih, Anda tidak dapat mengubah pilihan lagi.')) {
                // Show loading state
                event.target.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                event.target.disabled = true;
                
                // Submit vote via AJAX
                $.ajax({
                    url: '<?= site_url('dashboard_pemilih/pilih_calon'); ?>',
                    method: 'POST',
                    data: {
                        id_calon: idCalon
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('Voting berhasil! Terima kasih atas partisipasi Anda.');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                            // Reset button
                            event.target.innerHTML = '<i class="fas fa-vote-yea me-2"></i>Pilih Calon Ini';
                            event.target.disabled = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('AJAX Error:', xhr.responseText);
                        alert('Terjadi kesalahan sistem. Silakan coba lagi.');
                        // Reset button
                        event.target.innerHTML = '<i class="fas fa-vote-yea me-2"></i>Pilih Calon Ini';
                        event.target.disabled = false;
                    }
                });
            }
        }

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all calon cards
            document.querySelectorAll('.calon-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>
