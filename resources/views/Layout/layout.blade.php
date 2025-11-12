<!DOCTYPE html>
<html lang="en">

<head>
    <!-- =====================================================
         BAGIAN HEAD — Pengaturan Dasar & Import Library
         File ini berfungsi sebagai *layout utama* untuk semua halaman.
         Halaman lain akan mewarisi struktur ini menggunakan extends.
    ====================================================== -->

    <!-- Metadata dasar halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Import Google Font: Noto Sans (untuk heading dan judul) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    <!-- Import Google Font: Poppins (untuk teks isi dan deskripsi) -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>

    <!-- Import ikon dari Boxicons (untuk elemen UI seperti menu, tombol, dll) -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Import stylesheet dan script untuk Litepicker (komponen date range picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <!-- Integrasi TailwindCSS melalui Laravel Vite -->
    @vite('resources/css/app.css')

    <!-- Style tambahan untuk menetapkan kelas font -->
    <style>
        .font-noto {
            font-family: 'Noto Sans', sans-serif;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <!-- Judul halaman — dapat diubah oleh setiap halaman turunan -->
    <title>@yield('title', 'Document')</title>
</head>

<body>
    <!-- =====================================================
         BAGIAN BODY — Area Konten Dinamis
         Semua halaman yang mewarisi layout ini akan menempatkan
         konten utama mereka di dalam section 'body'.
    ====================================================== -->
    @yield('body')
</body>

</html>
