<!DOCTYPE html>
<html>
<head>
    <title>Login Pemilih - Sistem E-Voting</title>
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
        }
        .login-container { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); 
            width: 400px; 
            max-width: 90%; 
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Login Pemilih</h1>
            <p>Masukkan NIM dan password untuk memilih</p>
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
        
        <?= form_open('auth_pemilih/aksi_login'); ?>
            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text" name="nim" id="nim" placeholder="Masukkan NIM Anda" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan Password Anda" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        <?= form_close(); ?>
        
        <div class="admin-link">
            <a href="<?= site_url('auth_pemilih/register'); ?>">Belum punya akun? Daftar di sini</a>
        </div>
        
        <div class="admin-link">
            <a href="<?= site_url('auth/login'); ?>">Login sebagai Admin</a>
        </div>
        
        <div class="footer-note">
            Sistem E-Voting HIMSI
        </div>
    </div>
</body>
</html>
