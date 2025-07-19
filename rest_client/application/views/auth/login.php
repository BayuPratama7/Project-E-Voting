<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Sistem E-Voting</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            position: relative;
        }
        .floating-home-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            background: #FF0000;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            border: 3px solid white;
            box-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
            animation: bounce 2s infinite;
            text-transform: uppercase;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
        .floating-home-btn:hover {
            background: white;
            color: #FF0000;
            text-decoration: none;
            transform: scale(1.1);
        }
        .login-container { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); 
            width: 400px; 
            max-width: 90%; 
        }
        .back-to-home {
            text-align: center;
            margin-bottom: 25px;
            padding: 25px;
            background: #FF0000;
            border-radius: 20px;
            border: 5px solid #FF0000;
            animation: pulse 1.5s infinite;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.6);
        }
        @keyframes pulse {
            0% { 
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7), 0 0 30px rgba(255, 0, 0, 0.6); 
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 0 15px rgba(255, 0, 0, 0), 0 0 30px rgba(255, 0, 0, 0.6); 
                transform: scale(1.05);
            }
            100% { 
                box-shadow: 0 0 0 0 rgba(255, 0, 0, 0), 0 0 30px rgba(255, 0, 0, 0.6); 
                transform: scale(1);
            }
        }
        .back-to-home a {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 24px;
            font-weight: 900;
            padding: 20px 25px;
            border: 4px solid white;
            border-radius: 30px;
            background: #FF0000;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .back-to-home a:hover {
            background: white;
            color: #FF0000;
            transform: scale(1.15);
            text-decoration: none;
            box-shadow: 0 0 20px rgba(255,255,255,0.8);
        }
        .login-header { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        .login-header h1 { 
            color: #333; 
            margin-bottom: 10px; 
            font-size: 28px; 
        }
        .login-header p { 
            color: #666; 
            font-size: 14px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-group label { 
            display: block; 
            margin-bottom: 5px; 
            color: #333; 
            font-weight: bold; 
        }
        .form-group input { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #ddd; 
            border-radius: 8px; 
            font-size: 16px; 
            transition: border-color 0.3s; 
        }
        .form-group input:focus { 
            outline: none; 
            border-color: #667eea; 
        }
        .btn { 
            width: 100%; 
            padding: 12px; 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: transform 0.2s; 
        }
        .btn:hover { 
            transform: translateY(-2px); 
        }
        .alert { 
            padding: 12px; 
            margin-bottom: 20px; 
            border-radius: 6px; 
            text-align: center; 
        }
        .alert-error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
        .alert-success { 
            background: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .footer-note {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .admin-link {
            text-align: center;
            margin-top: 15px;
        }
        .admin-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        .admin-link a:hover {
            text-decoration: underline;
        }
        .default-info {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 13px;
            color: #666;
        }
        .bottom-home-btn {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #28a745;
            border-radius: 15px;
            border: 3px solid #28a745;
        }
        .bottom-home-btn a {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            padding: 12px 20px;
            border: 2px solid white;
            border-radius: 25px;
            background: #28a745;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        .bottom-home-btn a:hover {
            background: white;
            color: #28a745;
            transform: scale(1.05);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- FLOATING BUTTON KIRI ATAS -->
    <a href="<?= site_url('/') ?>" class="floating-home-btn">
        🏠 HALAMAN UTAMA
    </a>

    <div class="login-container">
        <div class="back-to-home">
            <a href="<?= site_url('/') ?>">
                🏠 KEMBALI KE HALAMAN UTAMA
            </a>
        </div>
        
        <div class="login-header">
            <h1>Login Admin</h1>
            <p>Masukkan username dan password untuk mengakses panel admin</p>
        </div>
        
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success">
                <?= $this->session->flashdata('success'); ?>
            </div>
        <?php endif; ?>
        
        <?= form_open('auth/aksi_login', array('method' => 'post')); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Masukkan Username Anda" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan Password Anda" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        <?= form_close(); ?>
        
        <div class="admin-link">
            <a href="<?= site_url('auth_pemilih/login'); ?>">Login sebagai Pemilih</a>
        </div>
        
        <div class="default-info">
            <strong>Default:</strong> username = <strong>admin</strong>, password = <strong>1234</strong>
        </div>
        
        <div class="bottom-home-btn">
            <a href="<?= site_url('/') ?>">
                🏠 KEMBALI KE HALAMAN UTAMA
            </a>
        </div>
        
        <div class="footer-note">
            Sistem E-Voting HIMSI
        </div>
    </div>
</body>
</html>
