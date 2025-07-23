<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($title); ?></title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .nav-header { 
            background: #343a40; 
            color: white; 
            padding: 15px 20px; 
            margin: -40px -40px 20px -40px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }
        .nav-brand { font-size: 18px; font-weight: bold; color: white; text-decoration: none; }
        .nav-links a { color: white; text-decoration: none; margin-left: 15px; }
        .nav-links a:hover { text-decoration: underline; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { max-width: 100px; height: auto; }
        .container { margin: 10px; border: 1px solid #D0D0D0; box-shadow: 0 0 8px #D0D0D0; padding: 20px; }
        h1 { color: #444; border-bottom: 1px solid #D0D0D0; padding-bottom: 10px; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
        .alert-error { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
        .btn { display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; background-image: none; border: 1px solid transparent; border-radius: 4px; text-decoration: none; }
        .btn-primary { color: #fff; background-color: #337ab7; border-color: #2e6da4; }
        .btn-danger { color: #fff; background-color: #d9534f; border-color: #d43f3a; }
        .btn-warning { color: #fff; background-color: #f0ad4e; border-color: #eea236; }
        .fixed-button {
            position: fixed;
            bottom: 10px;
            left: 10px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<div class="nav-header">
    <a href="<?= site_url('/') ?>" class="nav-brand">🗳️ E-Voting HIMSI</a>
    <div class="nav-links">
        <a href="<?= site_url('/') ?>">Halaman Utama</a>
        <a href="<?= site_url('dashboard/dashboard') ?>">Dashboard</a>
        <a href="<?= site_url('pemilih') ?>">Data Pemilih</a>
        <a href="<?= site_url('admin_auth/logout') ?>">Logout</a>
    </div>
</div>

<div class="container">
    <h1><?= html_escape($title); ?></h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <a href="<?= site_url('calon/tambah'); ?>" class="btn btn-primary">Tambah Data Calon</a>
    <a href="<?= site_url('dashboard'); ?>" class="btn btn-primary fixed-button">Kembali ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Calon</th>
                <th>Visi</th>
                <th>Misi</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($calon) && is_array($calon)): ?>
                <?php $no = 1; foreach ($calon as $c): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= html_escape($c['nama_calon']); ?></td>
                    <td><?= html_escape($c['visi']); ?></td>
                    <td><?= html_escape($c['misi']); ?></td>
                    <td><img src="http://localhost/y/rest_server/uploads/<?= html_escape($c['foto']); ?>" alt="Foto <?= html_escape($c['nama_calon']); ?>"></td>
                    <td>
                        <a href="<?= site_url('calon/ubah/' . $c['id_calon']); ?>" class="btn btn-warning">Ubah</a>
                        <a href="<?= site_url('calon/hapus/' . $c['id_calon']); ?>" onclick="return confirm('Yakin ingin menghapus data ini?');" class="btn btn-danger">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data calon.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

