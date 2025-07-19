<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Perolehan Suara - Pemilihan Ketua HIMSI</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f8f9fa; color: #333; }
        .container { width: 80%; max-width: 900px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #343a40; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 30px;}
        .hasil-wrapper { display: flex; flex-direction: column; gap: 20px; }
        .calon-hasil { display: flex; align-items: center; gap: 20px; }
        .calon-hasil img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; }
        .calon-info { flex-grow: 1; }
        .calon-info h3 { margin: 0 0 10px 0; color: #007bff; }
        .progress-bar { width: 100%; background-color: #e9ecef; border-radius: 5px; overflow: hidden; }
        .progress { height: 25px; background-color: #28a745; text-align: right; color: white; padding-right: 10px; line-height: 25px; box-sizing: border-box; transition: width 0.5s ease-in-out; }
        .info-suara { font-size: 14px; color: #6c757d; text-align: right; margin-top: 5px;}
        .footer-link { text-align: center; margin-top: 30px; }
        .footer-link a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

<div class="container">
    <h1>Hasil Perolehan Suara</h1>

    <div class="hasil-wrapper">
        <?php if (isset($hasil->status) && $hasil->status == TRUE && !empty($hasil->data)): ?>
            <?php foreach ($hasil->data as $calon): ?>
                <?php
                    // Hitung persentase, hindari pembagian dengan nol
                    $persentase = ($total_suara > 0) ? ($calon->jumlah_suara / $total_suara) * 100 : 0;
                ?>
                <div class="calon-hasil">
                    <img src="<?php echo 'http://localhost/y/rest_server/assets/images/'.$calon->foto; ?>" alt="Foto <?php echo $calon->nama_calon; ?>">
                    <div class="calon-info">
                        <h3><?php echo $calon->nama_calon; ?></h3>
                        <div class="progress-bar">
                            <div class="progress" style="width: <?php echo $persentase; ?>%;"><?php echo round($persentase, 1); ?>%</div>
                        </div>
                        <div class="info-suara"><?php echo $calon->jumlah_suara; ?> Suara</div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada data suara yang masuk.</p>
        <?php endif; ?>
    </div>
    <div class="footer-link"><a href="<?php echo site_url('auth'); ?>">Kembali ke Halaman Login</a></div>
</div>
</body>
</html>
