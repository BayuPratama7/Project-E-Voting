<!DOCTYPE html>
<html>
<head>
    <title>Daftar Pemilih - Sistem E-Voting</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px;
        }
        .register-container { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); 
            width: 450px; 
            max-width: 90%; 
        }
        .register-header { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        .register-header h1 { 
            color: #333; 
            margin-bottom: 10px; 
            font-size: 28px; 
        }
        .register-header p { 
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
        .form-group .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
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
            margin-bottom: 15px;
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
        .footer-links {
            text-align: center;
            margin-top: 20px;
        }
        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            margin: 0 10px;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        .footer-note {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 12px;
        }
        .password-requirements {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 10px;
            margin-top: 15px;
            font-size: 12px;
        }
        .password-requirements h5 {
            margin-bottom: 8px;
            color: #495057;
        }
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
            color: #6c757d;
        }
        .password-requirements li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Daftar Pemilih</h1>
            <p>Buat akun baru untuk mengikuti pemilihan</p>
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
        
        <?= form_open('auth_pemilih/aksi_register'); ?>
            <div class="form-group">
                <label for="nim">NIM</label>
                <input type="text" name="nim" id="nim" placeholder="Masukkan NIM Anda" required 
                       value="<?= set_value('nim'); ?>" maxlength="15">
                <div class="help-text">NIM harus berupa angka dengan panjang 8-15 digit</div>
            </div>
            
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Lengkap Anda" required
                       value="<?= set_value('nama'); ?>" maxlength="100">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan Password" required>
                <div class="help-text">Password minimal 6 karakter</div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi Password" required>
            </div>
            
            <div class="password-requirements">
                <h5>Persyaratan Password:</h5>
                <ul>
                    <li>Minimal 6 karakter</li>
                    <li>Gunakan kombinasi huruf dan angka</li>
                    <li>Hindari menggunakan informasi pribadi</li>
                </ul>
            </div>
            
            <button type="submit" class="btn">Daftar Sekarang</button>
        <?= form_close(); ?>
        
        <div class="footer-links">
            <a href="<?= site_url('auth_pemilih/login'); ?>">Sudah punya akun? Login di sini</a>
        </div>
        
        <div class="footer-links">
            <a href="<?= site_url('auth/login'); ?>">Login sebagai Admin</a>
        </div>
        
        <div class="footer-note">
            Sistem E-Voting HIMSI
        </div>
    </div>

    <script>
        // Validasi form di sisi client
        document.getElementById('nim').addEventListener('input', function(e) {
            // Hanya izinkan angka
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            form.addEventListener('submit', function(e) {
                // Validasi konfirmasi password
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    alert('Konfirmasi password tidak sesuai!');
                    confirmPassword.focus();
                    return false;
                }

                // Validasi panjang password
                if (password.value.length < 6) {
                    e.preventDefault();
                    alert('Password harus minimal 6 karakter!');
                    password.focus();
                    return false;
                }

                // Validasi NIM
                const nim = document.getElementById('nim').value;
                if (nim.length < 8 || nim.length > 15) {
                    e.preventDefault();
                    alert('NIM harus berupa angka dengan panjang 8-15 digit!');
                    document.getElementById('nim').focus();
                    return false;
                }
            });

            // Real-time validasi konfirmasi password
            confirmPassword.addEventListener('input', function() {
                if (this.value && password.value !== this.value) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#ddd';
                }
            });
        });
    </script>
</body>
</html>
