<?php
require 'config/database.php';
require 'config/helpers.php';

// Hapus hanya boleh lewat POST (dari tombol Hapus yang sudah dikonfirmasi)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if ($id === false) {
    setFlash('gagal', 'ID produk tidak valid.');
    redirect('index.php');
}

$db = Database::getInstance()->getConnection();

try {
    $stmt = $db->prepare('DELETE FROM produk WHERE id = ?');
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        setFlash('sukses', 'Produk berhasil dihapus.');
    } else {
        setFlash('gagal', 'Produk tidak ditemukan.');
    }
} catch (PDOException $e) {
    setFlash('gagal', 'Gagal menghapus produk.');
}

redirect('index.php');
