<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Kelola Calon' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3><?= $title ?? 'Kelola Data Calon' ?></h3>
    <hr>
    
    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success" role="alert">
        <?= $this->session->flashdata('success'); ?>
    </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger" role="alert">
        <?= $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>

    <a href="<?= base_url('calon/tambah') ?>" class="btn btn-primary mb-3">Tambah Calon</a>
    <a href="<?= base_url('admin') ?>" class="btn btn-secondary mb-3">Kembali ke Admin</a>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Foto</th>
                <th scope="col">Nama</th>
                <th scope="col">Visi</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($calon)): ?>
                <?php $i = 1; foreach($calon as $c): ?>
                <tr>
                    <th scope="row"><?= $i++ ?></th>
                    <td><img src="<?= base_url('uploads/' . $c['foto']) ?>" width="100"></td>
                    <td><?= $c['nama_calon'] ?></td>
                    <td><?= substr($c['visi'], 0, 100) ?>...</td>
                    <td>
                        <a href="<?= base_url('calon/ubah/') . $c['id_calon'] ?>" class="btn btn-sm btn-warning">Ubah</a>
                        <a href="<?= base_url('calon/hapus/') . $c['id_calon'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Data calon kosong.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
