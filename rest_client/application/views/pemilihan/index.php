<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemilihan Ketua HIMSI</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f8f9fa; color: #333; }
        .container { width: 80%; max-width: 1200px; margin: 20px auto; }
        .header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        h1 { text-align: center; color: #343a40; }
        .welcome { font-size: 14px; }
        .btn-logout { background-color: #dc3545; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; transition: background-color 0.2s; }
        .btn-logout:hover { background-color: #c82333; }
        .calon-container { display: flex; justify-content: center; flex-wrap: wrap; gap: 30px; margin-top: 30px; }
        .calon-card { background-color: #fff; border: 1px solid #dee2e6; border-radius: 8px; width: 320px; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.05); }
        .calon-card img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #007bff; margin-bottom: 15px; }
        .calon-card h3 { color: #007bff; margin-top: 0; }
        .calon-card .detail-title { font-weight: bold; margin-top: 15px; text-align: left; }
        .calon-card p { text-align: left; font-size: 14px; line-height: 1.5; color: #555; margin: 5px 0 0 0; }
        .btn-vote { display: inline-block; background-color: #28a745; color: white; padding: 12px 25px; margin-top: 20px; border-radius: 5px; text-decoration: none; font-weight: bold; transition: background-color 0.2s; }
        .btn-vote:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Pemilihan Ketua HIMSI</h2>
        <div class="welcome">
            Selamat Datang, <strong><?php echo $this->session->userdata('nama'); ?></strong>!
            <a href="<?php echo site_url('auth/logout'); ?>" class="btn-logout">Logout</a>
        </div>
    </div>
    <h1>Daftar Calon</h1>

    <div class="calon-container">
        <?php foreach ($calon as $c): ?>
        <div class="calon-card">
            <img src="<?php echo 'http://localhost/y/rest_server/assets/images/'.$c->foto; ?>" alt="Foto <?php echo $c->nama_calon; ?>">
            <h3><?php echo $c->nama_calon; ?></h3>
            <div class="detail-title">Visi:</div>
            <p><?php echo $c->visi; ?></p>
            <div class="detail-title">Misi:</div>
            <p><?php echo nl2br(htmlspecialchars($c->misi)); ?></p>
            <a href="<?php echo site_url('pemilihan/vote/'.$c->id_calon); ?>" class="btn-vote" onclick="return confirm('Apakah Anda yakin memilih <?php echo $c->nama_calon; ?>?')">Pilih <?php echo $c->nama_calon; ?></a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
