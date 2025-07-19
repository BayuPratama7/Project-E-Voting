<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Data Pemilih' ?></title>
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
        .container { margin: 10px; border: 1px solid #D0D0D0; box-shadow: 0 0 8px #D0D0D0; padding: 20px; }
        h1 { color: #444; border-bottom: 1px solid #D0D0D0; padding-bottom: 10px; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #3c763d; background-color: #dff0d8; border-color: #d6e9c6; }
        .alert-error { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
        .btn { display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; background-image: none; border: 1px solid transparent; border-radius: 4px; text-decoration: none; }
        .btn-primary { color: #fff; background-color: #337ab7; border-color: #2e6da4; }
        .status-belum { color: #d9534f; font-weight: bold; }
        .status-sudah { color: #5cb85c; font-weight: bold; }
        .btn-group { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn-action { 
            padding: 8px 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            text-decoration: none; 
            font-size: 14px; 
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-toggle { background: #007bff; color: white; }
        .btn-toggle:hover { background: #0056b3; }
        .btn-reset { background: #dc3545; color: white; }
        .btn-reset:hover { background: #c82333; }
        .btn-reset-votes { background: #ffc107; color: #212529; }
        .btn-reset-votes:hover { background: #e0a800; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
    </style>
</head>
<body>

<div class="nav-header">
    <a href="<?= site_url('/') ?>" class="nav-brand">🗳️ E-Voting HIMSI</a>
    <div class="nav-links">
        <a href="<?= site_url('/') ?>">Halaman Utama</a>
        <a href="<?= site_url('dashboard/dashboard') ?>">Dashboard</a>
        <a href="<?= site_url('auth/logout') ?>">Logout</a>
    </div>
</div>

<div class="container">
    <h1><?= isset($title) ? $title : 'Data Pemilih' ?></h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('info')): ?>
        <div class="alert alert-info"><?= $this->session->flashdata('info'); ?></div>
    <?php endif; ?>

    <div class="btn-group">
        <a href="<?= site_url('pemilih/reset_semua_status'); ?>" 
           class="btn-action btn-reset" 
           onclick="return confirm('Apakah Anda yakin ingin mereset semua status pemilih menjadi \'Belum Memilih\'?\\nTindakan ini tidak dapat dibatalkan!')">
           🔄 Reset Semua Status Pemilih
        </a>
        <a href="<?= site_url('pemilih/reset_suara_calon'); ?>" 
           class="btn-action btn-reset-votes" 
           onclick="return confirm('Apakah Anda yakin ingin mereset semua suara calon menjadi 0?\\nTindakan ini tidak dapat dibatalkan!')">
           🗳️ Reset Suara Calon
        </a>
    </div>

    <a href="<?= site_url('dashboard/dashboard'); ?>" class="btn btn-primary" style="position: fixed; bottom: 10px; left: 10px;">Kembali ke Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pemilih</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Status Memilih</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pemilih) && is_array($pemilih)): ?>
                <?php $no = 1; foreach ($pemilih as $p): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($p['id_pemilih']); ?></td>
                    <td><?= htmlspecialchars($p['nim']); ?></td>
                    <td><?= htmlspecialchars($p['nama']); ?></td>
                    <td>
                        <?php if ($p['status_memilih'] == '1'): ?>
                            <span class="status-sudah">✓ Sudah Memilih</span>
                        <?php else: ?>
                            <span class="status-belum">✗ Belum Memilih</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                            $current_status = $p['status_memilih'];
                            $next_status_text = ($current_status == '1') ? 'Set Belum Memilih' : 'Set Sudah Memilih';
                            $btn_class = ($current_status == '1') ? 'btn-reset' : 'btn-toggle';
                            $icon = ($current_status == '1') ? '↶' : '✓';
                        ?>
                        <a href="<?= site_url('pemilih/ubah_status/' . $p['id_pemilih']); ?>" 
                           class="btn-action btn-small <?= $btn_class; ?>"
                           onclick="return confirm('Apakah Anda yakin ingin mengubah status <?= htmlspecialchars($p['nama']); ?> menjadi \'<?= $next_status_text; ?>\'?')">
                           <?= $icon; ?> <?= $next_status_text; ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data pemilih.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
