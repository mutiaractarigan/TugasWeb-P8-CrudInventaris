<?php
require 'config/database.php';
require 'config/helpers.php';

$db = Database::getInstance()->getConnection();

$id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) {
    setFlash('gagal', 'ID produk tidak valid.');
    redirect('index.php');
}

// Ambil data lama untuk mengisi form (pre-filled)
$stmt = $db->prepare('SELECT * FROM produk WHERE id = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    setFlash('gagal', 'Produk tidak ditemukan.');
    redirect('index.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama_produk' => trim($_POST['nama_produk'] ?? ''),
        'kategori_id' => $_POST['kategori_id'] ?? '',
        'supplier_id' => $_POST['supplier_id'] ?? '',
        'stok' => trim($_POST['stok'] ?? ''),
        'harga' => trim($_POST['harga'] ?? ''),
    ];

    $errors = validasiProduk($db, $data);

    if (empty($errors)) {
        try {
            $stmt = $db->prepare('UPDATE produk
                                  SET nama_produk = ?, kategori_id = ?, supplier_id = ?, stok = ?, harga = ?
                                  WHERE id = ?');
            $stmt->execute([
                $data['nama_produk'],
                $data['kategori_id'],
                $data['supplier_id'],
                $data['stok'],
                $data['harga'],
                $id,
            ]);
            setFlash('sukses', 'Produk "' . $data['nama_produk'] . '" berhasil diperbarui.');
        } catch (PDOException $e) {
            setFlash('gagal', 'Gagal memperbarui produk.');
        }
        redirect('index.php');
    }
}

$kategori = getKategori($db);
$supplier = getSupplier($db);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container small">
        <h1>Edit Produk</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert gagal">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="edit.php?id=<?= e($id) ?>">
            <label for="nama_produk">Nama Produk</label>
            <input type="text" id="nama_produk" name="nama_produk" value="<?= e($data['nama_produk']) ?>">

            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" name="kategori_id">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= e($k['id']) ?>" <?= $k['id'] == $data['kategori_id'] ? 'selected' : '' ?>>
                        <?= e($k['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="supplier_id">Supplier</label>
            <select id="supplier_id" name="supplier_id">
                <option value="">-- Pilih Supplier --</option>
                <?php foreach ($supplier as $s): ?>
                    <option value="<?= e($s['id']) ?>" <?= $s['id'] == $data['supplier_id'] ? 'selected' : '' ?>>
                        <?= e($s['nama_supplier']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" min="0" value="<?= e($data['stok']) ?>">

            <label for="harga">Harga (Rp)</label>
            <input type="number" id="harga" name="harga" min="0" value="<?= e($data['harga']) ?>">

            <div class="form-actions">
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
