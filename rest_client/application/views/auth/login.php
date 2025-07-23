<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - E-Voting HIMSI</title>
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
        
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .logo-text {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            color: white;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 15px;
            font-weight: bold;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .alert {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 10px;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }
        
        .login-info {
            background: rgba(13, 110, 253, 0.2);
            border: 1px solid rgba(13, 110, 253, 0.3);
            border-radius: 10px;
            color: white;
            padding: 15px;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-text">
            <i class="fas fa-user-shield"></i> Login Admin
        </div>
        
        <?php if (isset($error) && $error): ?>
            <div class="alert">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo site_url('admin_auth/login_action'); ?>" class="login-form">
            <input type="text" name="username" class="form-control" placeholder="Username Admin" required>
            <input type="password" name="password" class="form-control" placeholder="Password Admin" required>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> LOGIN ADMIN
            </button>
        </form>
        
        <div class="login-info">
            <strong>💡 Login Admin:</strong><br>
            Username: <code>admin</code><br>
            Password: <code>admin</code> / <code>1234</code> / <code>admin123</code>
        </div>
        
        <div class="back-link">
            <a href="<?= site_url('/') ?>">
                <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>