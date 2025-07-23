<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Voting HIMSI - Redirecting...</title>
    <meta http-equiv="refresh" content="0;url=rest_client/">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(-45deg, #667eea, #764ba2, #6366f1, #8b5cf6);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .redirect-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }
        
        .title {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        }
        
        .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .spinner-border {
            color: white;
        }
        
        .redirect-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 12px 25px;
            border-radius: 25px;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .redirect-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="redirect-container">
        <h1 class="title">
            <i class="fas fa-vote-yea me-2"></i>
            E-Voting HIMSI
        </h1>
        <p class="subtitle">Mengalihkan ke aplikasi E-Voting...</p>
        
        <div class="spinner-border mb-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        
        <p style="color: rgba(255, 255, 255, 0.8);">
            Jika tidak dialihkan otomatis, klik tombol di bawah:
        </p>
        
        <a href="rest_client/" class="redirect-link">
            <i class="fas fa-arrow-right me-2"></i>
            Masuk ke E-Voting
        </a>
        
        <div style="margin-top: 2rem; color: rgba(255, 255, 255, 0.6); font-size: 0.9rem;">
            <p>Sistem E-Voting HIMSI</p>
            <p>Himpunan Mahasiswa Sistem Informasi</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <script>
        // Auto redirect setelah 3 detik jika meta refresh tidak bekerja
        setTimeout(function() {
            window.location.href = 'rest_client/';
        }, 3000);
        
        // Immediate redirect untuk browser yang mendukung
        if (window.location.pathname === '/' || window.location.pathname === '/index.php') {
            window.location.replace('rest_client/');
        }
    </script>
</body>
</html>
