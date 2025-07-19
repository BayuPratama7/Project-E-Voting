<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Tambah Calon' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3><?= $title ?? 'Tambah Data Calon' ?></h3>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <?= form_open_multipart('calon/tambah_action'); ?>
                <div class="form-group">
                    <label for="nama_calon">Nama Calon</label>
                    <input type="text" name="nama_calon" class="form-control" id="nama_calon" required>
                </div>
                <div class="form-group">
                    <label for="visi">Visi</label>
                    <textarea name="visi" class="form-control" id="visi" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="misi">Misi</label>
                    <textarea name="misi" class="form-control" id="misi" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" class="form-control-file" id="foto">
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= base_url('calon') ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>
</body>
</html>
