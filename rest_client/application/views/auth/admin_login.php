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
        .admin-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
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
        .pemilih-link {
            text-align: center;
            margin-top: 15px;
        }
        .pemilih-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }
        .pemilih-link a:hover {
            text-decoration: underline;
        }
        .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="icon">
                <i class="fas fa-user-shield" style="font-family: FontAwesome;"></i>
                👨‍💼
            </div>
            <div class="admin-badge">ADMINISTRATOR</div>
            <h1>Login Admin</h1>
            <p>Masuk ke panel administrasi sistem e-voting</p>
            
            <div style="background: #e3f2fd; border: 1px solid #2196f3; border-radius: 8px; padding: 15px; margin: 20px 0; font-size: 14px;">
                <div style="color: #1976d2; font-weight: bold; margin-bottom: 8px;">
                    <i class="fas fa-info-circle"></i> Kredensial Default:
                </div>
                <div style="color: #333;">
                    <strong>Username:</strong> admin<br>
                    <strong>Password:</strong> 1234
                </div>
            </div>
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
        
        <?= form_open('auth/aksi_login'); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Masukkan username admin" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password admin" required>
            </div>
            
            <button type="submit" class="btn">Login Admin</button>
        <?= form_close(); ?>
        
        <div class="pemilih-link">
            <a href="<?= site_url('auth_pemilih/login'); ?>">Login sebagai Pemilih</a>
        </div>
        
        <div class="footer-note">
            Sistem E-Voting HIMSI - Admin Panel
        </div>
    </div>
</body>
</html>
