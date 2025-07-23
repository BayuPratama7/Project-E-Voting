<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pemilihan</title>
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
            transition: left 0.5s;
        }

        .btn-logout:hover::before {
            left: 100%;
        }

        .btn-logout:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(238, 90, 36, 0.6);
            color: white;
        }

        .welcome-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 1s ease;
        }

        .welcome-card:hover::before {
            left: 100%;
        }

        .welcome-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
        }

        .stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            transition: height 0.3s ease;
        }

        .stats-card:hover::before {
            height: 10px;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .stats-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stats-icon {
            font-size: 3rem;
            opacity: 0.8;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(5deg);
            opacity: 1;
        }

        .card-calon .stats-number { 
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-calon .stats-icon { color: #6366f1; }

        .card-pemilih .stats-number { 
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-pemilih .stats-icon { color: #10b981; }

        .card-sudah .stats-number { 
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-sudah .stats-icon { color: #f59e0b; }

        .card-belum .stats-number { 
            background: linear-gradient(135deg, #ef4444, #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-belum .stats-icon { color: #ef4444; }

        .admin-menu {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .menu-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 8px;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        .menu-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }

        .menu-btn:hover::before {
            left: 100%;
        }

        .menu-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.5);
            color: white;
            text-decoration: none;
        }

        .menu-btn.btn-success {
            background: linear-gradient(135deg, var(--success-color), #059669);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .menu-btn.btn-success:hover {
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.5);
        }

        .menu-btn.btn-warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        .menu-btn.btn-warning:hover {
            box-shadow: 0 15px 40px rgba(245, 158, 11, 0.5);
        }

        .alert-custom {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: white;
            border-radius: 15px;
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
            animation: slideInFromTop 0.8s ease-out;
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container-fluid {
            padding: 2rem 1rem;
        }

        .row {
            margin-bottom: 2rem;
        }

        .fade-in {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { 
                opacity: 0; 
                transform: translateY(40px);
            }
            to { 
                opacity: 1; 
                transform: translateY(0);
            }
        }

        .welcome-text {
            color: white;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .welcome-description {
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .section-title {
            color: white;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            font-weight: 700;
        }

        /* Floating animation for icons */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }

        /* Sparkle effect */
        .sparkle {
            position: relative;
        }

        .sparkle::before {
            content: '✨';
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 1.2rem;
            animation: sparkle 2s ease-in-out infinite;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        /* Loading dots animation */
        .loading-dots::after {
            content: '';
            animation: dots 1.5s ease-in-out infinite;
        }

        @keyframes dots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60%, 100% { content: '...'; }
        }

        @media (max-width: 768px) {
            .stats-number { font-size: 2.5rem; }
            .stats-icon { font-size: 2.5rem; }
            .container-fluid { padding: 1rem 0.5rem; }
            .navbar-brand { font-size: 1.5rem; }
            .menu-btn { padding: 12px 20px; margin: 5px; }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand sparkle" href="<?= site_url('/') ?>">
                <i class="fas fa-vote-yea me-2 floating-icon"></i>
                🚀 Dashboard Admin Premium
            </a>
            <div class="d-flex">
                <a href="<?php echo site_url('admin_auth/logout'); ?>" class="btn btn-logout">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid" style="margin-top: 100px;">
        <!-- Welcome Alert -->
        <div class="row fade-in">
            <div class="col-12">
                <div class="alert alert-custom" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4 floating-icon"></i>
                        <div>
                            <strong>🎉 Login berhasil!</strong> Selamat datang Administrator <span class="loading-dots"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="row fade-in">
            <div class="col-12">
                <div class="welcome-card p-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-4 welcome-text">
                                <i class="fas fa-tachometer-alt me-3 floating-icon"></i>
                                ✨ Selamat Datang, <?php echo $this->session->userdata('username'); ?>!
                            </h1>
                            <p class="lead welcome-description mb-0">
                                🎯 Anda telah berhasil login ke panel administrasi sistem pemilihan premium. 
                                Kelola data dan pantau statistik pemilihan dengan antarmuka yang menakjubkan!
                            </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-user-shield floating-icon" style="font-size: 5rem; color: white; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.3));"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row fade-in">
            <?php 
            $stats_icons = [
                'Total Calon' => ['icon' => 'fas fa-users', 'class' => 'card-calon'],
                'Total Pemilih' => ['icon' => 'fas fa-user-friends', 'class' => 'card-pemilih'],
                'Sudah Memilih' => ['icon' => 'fas fa-check-circle', 'class' => 'card-sudah'],
                'Belum Memilih' => ['icon' => 'fas fa-clock', 'class' => 'card-belum']
            ];
            
            foreach ($stats as $stat): 
                $icon_info = isset($stats_icons[$stat['name']]) ? $stats_icons[$stat['name']] : ['icon' => 'fas fa-chart-bar', 'class' => 'card-calon'];
            ?>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card <?= $icon_info['class'] ?> p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stats-number"><?= htmlspecialchars($stat['value']) ?></div>
                            <div class="stats-label"><?= htmlspecialchars($stat['name']) ?></div>
                        </div>
                        <div class="stats-icon">
                            <i class="<?= $icon_info['icon'] ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Admin Menu -->
        <div class="row fade-in">
            <div class="col-12">
                <div class="admin-menu p-5">
                    <h3 class="mb-5 section-title">
                        <i class="fas fa-cogs me-3 floating-icon"></i>
                        🛠️ Menu Administrasi Premium
                    </h3>
                    <div class="d-flex flex-wrap justify-content-center">
                        <a href="<?php echo site_url('calon'); ?>" class="menu-btn sparkle">
                            <i class="fas fa-user-tie me-2"></i>
                            👤 Kelola Data Calon
                        </a>
                        <a href="<?php echo site_url('pemilih'); ?>" class="menu-btn btn-success sparkle">
                            <i class="fas fa-users me-2"></i>
                            👥 Kelola Data Pemilih
                        </a>
                        <a href="<?php echo site_url('/'); ?>" class="menu-btn btn-warning sparkle">
                            <i class="fas fa-home me-2"></i>
                            🏠 Halaman Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Advanced animations and interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Create floating particles
            createFloatingParticles();
            
            // Add stagger animation to cards with enhanced effects
            const cards = document.querySelectorAll('.stats-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.2}s`;
                card.classList.add('fade-in');
                
                // Add tilt effect on mouse move
                card.addEventListener('mousemove', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = (y - centerY) / 10;
                    const rotateY = (centerX - x) / 10;
                    
                    this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px) scale(1.02)`;
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px) scale(1)';
                });
            });

            // Enhanced ripple effect for buttons
            const buttons = document.querySelectorAll('.menu-btn');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    createRipple(e, this);
                    
                    // Add success feedback
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });
            
            // Auto-refresh stats with smooth counter animation
            setTimeout(() => {
                animateCounters();
            }, 1000);
            
            // Add parallax effect to background
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const parallax = document.querySelector('body::before');
                if (parallax) {
                    parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
                }
            });
        });

        function createFloatingParticles() {
            const particleCount = 20;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.background = `rgba(255, 255, 255, ${Math.random() * 0.3 + 0.1})`;
                particle.style.borderRadius = '50%';
                particle.style.left = Math.random() * window.innerWidth + 'px';
                particle.style.top = Math.random() * window.innerHeight + 'px';
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '-1';
                
                const duration = Math.random() * 20 + 10;
                particle.style.animation = `float ${duration}s ease-in-out infinite`;
                
                document.body.appendChild(particle);
                
                // Remove particle after animation
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, duration * 1000);
            }
        }

        function createRipple(event, element) {
            const ripple = document.createElement('span');
            const rect = element.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height) * 2;
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255,255,255,0.6)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            ripple.style.pointerEvents = 'none';
            ripple.style.zIndex = '1000';
            
            element.style.position = 'relative';
            element.style.overflow = 'hidden';
            element.appendChild(ripple);
            
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 800);
        }

        function animateCounters() {
            const numbers = document.querySelectorAll('.stats-number');
            numbers.forEach(number => {
                const target = parseInt(number.textContent);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    number.textContent = Math.floor(current);
                }, 30);
            });
        }

        // Enhanced CSS animations
        const enhancedStyle = document.createElement('style');
        enhancedStyle.textContent = `
            @keyframes ripple {
                0% {
                    transform: scale(0);
                    opacity: 0.6;
                }
                100% {
                    transform: scale(1);
                    opacity: 0;
                }
            }
            
            @keyframes glow {
                0%, 100% {
                    filter: drop-shadow(0 0 5px currentColor);
                }
                50% {
                    filter: drop-shadow(0 0 20px currentColor);
                }
            }
            
            .stats-icon {
                animation: glow 3s ease-in-out infinite;
            }
            
            .welcome-card:hover {
                animation: pulse 1s ease-in-out;
            }
            
            @keyframes pulse {
                0%, 100% { transform: scale(1) translateY(-5px); }
                50% { transform: scale(1.02) translateY(-8px); }
            }
        `;
        document.head.appendChild(enhancedStyle);
    </script>
</body>
</html>
