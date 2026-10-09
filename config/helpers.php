<?php
session_start();

// Sanitasi output HTML
function e($teks)
{
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// Flash message: disimpan di session, tampil sekali setelah redirect
function setFlash($tipe, $pesan)
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function showFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert ' . e($flash['tipe']) . '">' . e($flash['pesan']) . '</div>';
    }
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function rupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Ambil data untuk dropdown
function getKategori($db)
{
    return $db->query('SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori')->fetchAll();
}

function getSupplier($db)
{
    return $db->query('SELECT id, nama_supplier FROM supplier ORDER BY nama_supplier')->fetchAll();
}

// Validasi input form produk, mengembalikan array pesan error
function validasiProduk($db, $data)
{
    $errors = [];

    if ($data['nama_produk'] === '') {
        $errors[] = 'Nama produk wajib diisi.';
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM kategori WHERE id = ?');
    $stmt->execute([$data['kategori_id']]);
    if ($stmt->fetchColumn() == 0) {
        $errors[] = 'Kategori wajib dipilih.';
    }

    $stmt = $db->prepare('SELECT COUNT(*) FROM supplier WHERE id = ?');
    $stmt->execute([$data['supplier_id']]);
    if ($stmt->fetchColumn() == 0) {
        $errors[] = 'Supplier wajib dipilih.';
    }

    if (filter_var($data['stok'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
        $errors[] = 'Stok harus berupa angka bulat minimal 0.';
    }

    if (!is_numeric($data['harga']) || $data['harga'] < 0) {
        $errors[] = 'Harga harus berupa angka minimal 0.';
    }

    return $errors;
}
