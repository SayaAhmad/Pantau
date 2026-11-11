# PantauWeb

Aplikasi Laravel untuk memantau ketersediaan website, waktu respons, dan riwayat pemeriksaan. Antarmuka menggunakan bahasa Indonesia dan database SQLite.

## Menjalankan di Windows dengan Laravel Herd

Buka PowerShell baru. Jika perintah PHP belum tersedia, muat PATH Herd pada jendela PowerShell tersebut:

```powershell
$herdBin = "$env:USERPROFILE\.config\herd\bin"
$env:PATH = "$herdBin;$herdBin\php85;$env:PATH"
```

Jalankan perintah berikut dari root repo `PantauWeb`:

```powershell
composer install
php artisan migrate
php artisan serve
```

Buka `http://127.0.0.1:8000`, lalu buat akun di halaman pendaftaran. SQLite dipakai secara lokal; tabelnya akan dibuat oleh migrasi.

Jalankan pemeriksaan otomatis dari jendela PowerShell kedua:

```powershell
$herdBin = "$env:USERPROFILE\.config\herd\bin"
$env:PATH = "$herdBin;$herdBin\php85;$env:PATH"
cd "C:\path\to\PantauWeb"
php artisan schedule:work
```

Scheduler memeriksa website yang sudah waktunya diperiksa setiap menit. Interval setiap website dapat diatur 1, 5, 10, 15, 30, atau 60 menit.

## Fitur

- Registrasi, masuk, keluar, dan pengaturan profil akun.
- Menambah, mengubah, dan menghapus website.
- Pemeriksaan status HTTP dan waktu respons, otomatis serta manual.
- Ringkasan dashboard dan riwayat pemeriksaan terbaru.
- Peringatan di dashboard saat ada website yang gagal diakses.
- Data website dipisahkan berdasarkan akun.

Peringatan gangguan pada versi ini muncul di dashboard. Pengiriman email atau pesan ke layanan eksternal belum dikonfigurasi.

