<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= html_escape($title); ?></title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        .container { margin: 10px; border: 1px solid #D0D0D0; box-shadow: 0 0 8px #D0D0D0; padding: 20px; }
        h1 { color: #444; border-bottom: 1px solid #D0D0D0; padding-bottom: 10px; }
        .btn { display: inline-block; padding: 6px 12px; margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; vertical-align: middle; cursor: pointer; background-image: none; border: 1px solid transparent; border-radius: 4px; text-decoration: none; }
        .btn-primary { color: #fff; background-color: #337ab7; border-color: #2e6da4; }
        .btn-default { color: #333; background-color: #fff; border-color: #ccc; }
        input[type="text"], textarea { width: 100%; padding: 8px; margin: 6px 0 12px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        label { font-weight: bold; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-error { color: #a94442; background-color: #f2dede; border-color: #ebccd1; }
    </style>
</head>
<body>

<div class="container">
    <h1><?= html_escape($title); ?></h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-error"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>

    <?= form_open_multipart('calon/ubah_action'); ?>

        <input type="hidden" name="id_calon" value="<?= html_escape($calon['id_calon']); ?>">

        <p>
            <label for="nama_calon">Nama Calon</label><br>
            <input type="text" name="nama_calon" id="nama_calon" value="<?= html_escape($calon['nama_calon']); ?>" required>
        </p>
        <p>
            <label for="visi">Visi</label><br>
            <textarea name="visi" id="visi" rows="4" required><?= html_escape($calon['visi']); ?></textarea>
        </p>
        <p>
            <label for="misi">Misi</label><br>
            <textarea name="misi" id="misi" rows="4" required><?= html_escape($calon['misi']); ?></textarea>
        </p>
        <p>
            <label>Foto Saat Ini</label><br>
            <img src="http://localhost/y/rest_server/uploads/<?= html_escape($calon['foto']); ?>" alt="Foto Saat Ini" style="max-width: 150px; margin-bottom: 10px;">
        </p>
        <p>
            <label for="foto">Ganti Foto (Opsional)</label><br>
            <input type="file" name="foto" id="foto">
        </p>
        <p>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="<?= site_url('calon'); ?>" class="btn btn-default">Batal</a>
        </p>

    <?= form_close(); ?>
</div>

</body>
</html>
