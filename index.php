<?php
require 'config/database.php';
require 'config/helpers.php';

$db = Database::getInstance()->getConnection();

// Pencarian (bonus)
$cari = trim($_GET['cari'] ?? '');

$sql = 'SELECT p.id, p.nama_produk, p.stok, p.harga,
               k.nama_kategori, s.nama_supplier
        FROM produk p
        JOIN kategori k ON p.kategori_id = k.id
        JOIN supplier s ON p.supplier_id = s.id';

if ($cari !== '') {
    $sql .= ' WHERE p.nama_produk LIKE :cari OR k.nama_kategori LIKE :cari2 OR s.nama_supplier LIKE :cari3';
}
$sql .= ' ORDER BY p.id DESC';

$stmt = $db->prepare($sql);
if ($cari !== '') {
    $keyword = '%' . $cari . '%';
    $stmt->bindValue(':cari', $keyword);
    $stmt->bindValue(':cari2', $keyword);
    $stmt->bindValue(':cari3', $keyword);
}
$stmt->execute();
$produk = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Inventaris Produk</h1>

        <?php showFlash(); ?>

        <div class="toolbar">
            <a href="create.php" class="btn">+ Tambah Produk</a>

            <form method="get" action="index.php" class="search">
                <input type="text" name="cari" placeholder="Cari produk..." value="<?= e($cari) ?>">
                <button type="submit" class="btn">Cari</button>
                <?php if ($cari !== ''): ?>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Supplier</th>
                    <th>Stok</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produk)): ?>
                    <tr>
                        <td colspan="7" class="kosong">Data produk tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($produk as $i => $row): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= e($row['nama_produk']) ?></td>
                        <td><?= e($row['nama_kategori']) ?></td>
                        <td><?= e($row['nama_supplier']) ?></td>
                        <td><?= e($row['stok']) ?></td>
                        <td><?= e(rupiah($row['harga'])) ?></td>
                        <td class="aksi">
                            <a href="edit.php?id=<?= e($row['id']) ?>" class="btn btn-small">Edit</a>
                            <form method="post" action="delete.php"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                <input type="hidden" name="id" value="<?= e($row['id']) ?>">
                                <button type="submit" class="btn btn-small btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
