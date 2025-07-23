<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pemilihan Ketua HIMSI</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background-color: #fff; padding: 30px 40px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-align: center; width: 320px; }
        h2 { color: #333; margin-top: 0; }
        p { color: #666; font-size: 14px;}
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; background-color: #007bff; color: white; padding: 12px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: background-color 0.2s; }
        input[type="submit"]:hover { background-color: #0056b3; }
        .flash-message { padding: 10px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; }
        .success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb;}
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Pemilih</h2>
        <p>Gunakan NIM dan password Anda untuk memilih.</p>
        
        <?php if($this->session->flashdata('error')): ?>
            <div class="flash-message error">
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('message')): ?>
            <div class="flash-message success">
                <?php echo $this->session->flashdata('message'); ?>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin_auth/login_process'); ?>
            <input type="text" name="nim" placeholder="NIM" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Login">
        <?php echo form_close(); ?>
    </div>
</body>
</html>
