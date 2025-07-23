<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting HIMSI - Portal Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .main-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 50px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .logo-section {
            margin-bottom: 40px;
        }
        
        .logo-icon {
            font-size: 4rem;
            color: white;
            margin-bottom: 20px;
        }
        
        .logo-text {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .logo-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        
        .role-card {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 30px;
            margin: 15px;
            color: white;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
        }
        
        .role-card:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-5px);
            color: white;
            text-decoration: none;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .role-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .role-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .role-desc {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .footer-info {
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-vote-yea"></i>
            </div>
            <h1 class="logo-text">E-Voting HIMSI</h1>
            <p class="logo-subtitle">Sistem Pemilihan Elektronik Himpunan Mahasiswa</p>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <a href="<?= site_url('admin_auth/login') ?>" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="role-title">LOGIN ADMIN</div>
                    <div class="role-desc">
                        Akses panel admin untuk mengelola kandidat, pemilih, dan hasil voting
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="<?= site_url('auth_pemilih/login') ?>" class="role-card">
                    <div class="role-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="role-title">LOGIN PEMILIH</div>
                    <div class="role-desc">
                        Akses untuk mahasiswa melakukan pemilihan kandidat favorit
                    </div>
                </a>
            </div>
        </div>
        
        <div class="footer-info">
            <i class="fas fa-info-circle"></i>
            Pilih role sesuai dengan akses yang Anda miliki
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
