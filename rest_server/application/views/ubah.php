<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Ubah Calon' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3><?= $title ?? 'Ubah Data Calon' ?></h3>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <?= form_open_multipart('calon/ubah_action'); ?>
                <input type="hidden" name="id_calon" value="<?= $calon['id_calon'] ?>">
                <input type="hidden" name="foto_lama" value="<?= $calon['foto'] ?>">

                <div class="form-group">
                    <label for="nama_calon">Nama Calon</label>
                    <input type="text" name="nama_calon" class="form-control" id="nama_calon" value="<?= $calon['nama_calon'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="visi">Visi</label>
                    <textarea name="visi" class="form-control" id="visi" rows="3" required><?= $calon['visi'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="misi">Misi</label>
                    <textarea name="misi" class="form-control" id="misi" rows="5" required><?= $calon['misi'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="foto">Foto</label><br>
                    <img src="<?= base_url('uploads/' . $calon['foto']) ?>" width="150" class="mb-2 img-thumbnail">
                    <input type="file" name="foto" class="form-control-file" id="foto">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="<?= base_url('calon') ?>" class="btn btn-secondary">Batal</a>
            <?= form_close(); ?>
        </div>
    </div>
</div>
</body>
</html>
