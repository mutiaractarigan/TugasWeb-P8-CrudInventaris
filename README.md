# TugasWeb-P8-CrudInventaris

Tugas Rutin 8 — CRUD Inventaris.

Aplikasi CRUD inventaris produk dengan PHP Native + PDO (MySQL/MariaDB).

## Cara menjalankan

1. Import database: buka phpMyAdmin lalu import `schema.sql`, atau lewat terminal:
   ```bash
   mysql -u root -p < schema.sql
   ```
2. Sesuaikan username/password database di `config/database.php` jika perlu
   (default Laragon/XAMPP: `root` tanpa password).
3. Jalankan server:
   ```bash
   php -S localhost:8000
   ```
   lalu buka http://localhost:8000 (atau taruh folder ini di `htdocs` / `www` Laragon/XAMPP).

## Struktur proyek

```
TugasWeb-P8-CrudInventaris/
├── config/
│   ├── database.php   (Singleton PDO)
│   └── helpers.php    (session, flash message, htmlspecialchars, validasi)
├── schema.sql         (DDL + seed)
├── index.php          (READ - list + pencarian)
├── create.php         (CREATE)
├── edit.php           (UPDATE)
├── delete.php         (DELETE)
└── assets/style.css
```

## Database `inventaris_db`

- `kategori` (5 data)
- `supplier` (5 data)
- `produk` (7 data) → foreign key ke `kategori` dan `supplier`
