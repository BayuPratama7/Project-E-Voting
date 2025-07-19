<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <title>Sistem E-Voting HIMSI</title>
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
        .main-container { 
            background: white; 
            padding: 50px; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); 
            text-align: center; 
            max-width: 500px; 
            width: 90%; 
        }
        .logo-section {
            margin-bottom: 40px;
        }
        .logo-section h1 { 
            color: #333; 
            margin-bottom: 10px; 
            font-size: 32px; 
            font-weight: bold;
        }
        .logo-section p { 
            color: #666; 
            font-size: 16px; 
            margin-bottom: 30px;
        }
        .login-options {
            display: flex;
            gap: 20px;
            flex-direction: column;
        }
        .login-btn { 
            padding: 20px 30px; 
            border: none; 
            border-radius: 12px; 
            font-size: 18px; 
            font-weight: bold; 
            cursor: pointer; 
            text-decoration: none;
            display: block;
            transition: all 0.3s; 
            position: relative;
            overflow: hidden;
        }
        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .login-btn:hover::before {
            left: 100%;
        }
        .btn-pemilih { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
        }
        .btn-pemilih:hover { 
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%); 
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-admin { 
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%); 
            color: white; 
        }
        .btn-admin:hover { 
            background: linear-gradient(135deg, #218838 0%, #1a9b7a 100%); 
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }
        .footer-info {
            margin-top: 30px;
            color: #888;
            font-size: 14px;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="logo-section">
            <h1>🗳️ E-Voting HIMSI</h1>
            <p>Sistem Pemilihan Elektronik<br>Himpunan Mahasiswa Sistem Informasi</p>
        </div>
        
        <div class="login-options">
            <a href="<?= site_url('auth_pemilih/login'); ?>" class="login-btn btn-pemilih">
                <span class="icon">👤</span>
                Login sebagai Pemilih
            </a>
            
            <a href="<?= site_url('auth/login'); ?>" class="login-btn btn-admin">
                <span class="icon">⚙️</span>
                Login sebagai Admin
            </a>
        </div>
        
        <div class="footer-info">
            <p>Pilih jenis login sesuai dengan peran Anda</p>
        </div>
    </div>
</body>
</html>