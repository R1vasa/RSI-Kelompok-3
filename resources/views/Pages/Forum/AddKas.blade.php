@extends('Layout.layout')
{{-- Menggunakan layout utama yang ada di resources/views/Layout/layout.blade.php --}}

@section('title', 'Tambah Kas')
{{-- Menetapkan judul halaman yang akan muncul di <title> dari layout utama --}}

@section('body')
    <!-- =========================================================
                 HALAMAN TAMBAH KAS (FORUM ORGANISASI)
                 Fungsinya: Memungkinkan user menambahkan data kas baru
                 ke dalam forum organisasi tertentu.
            ========================================================== -->
    <div class="flex">
        <!-- Sidebar Navigasi -->
        <x-sidebar></x-sidebar>

        <!-- Bagian konten utama (kanan) -->
        <div class="flex-1 ml-[20%] min-h-screen font-poppins">

            <!-- Header Halaman -->
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <!-- Menampilkan nama forum jika user sedang login -->
                    <h1 class="p-4 font-semibold font-poppins text-2xl">{{ $forums->forum }}</h1>
                @endauth
            </div>

            <!-- ======================================================
                         FORM TAMBAH DATA KAS
                         Aksi: route('tambah.kas', ['slug' => $forums->slug])
                         Metode: POST
                         Tujuan: Menyimpan transaksi kas baru ke database
                    ======================================================= -->
            <div class="bg-white p-6">
                <form action="{{ route('tambah.kas', ['slug' => $forums->slug]) }}" method="POST">
                    @csrf <!-- Token keamanan Laravel untuk mencegah CSRF attack -->

                    <!-- ======================
                                 Input: Nama Transaksi
                            ======================= -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Nama</label>
                        <div class="mx-4">
                            <input type="text" name="nama_transaksi"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <!-- ======================
                                 Input: Jumlah Uang
                            ======================= -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jumlah (Rp)</label>
                        <div class="mx-4">
                            <input type="number" name="jumlah"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <!-- ======================
                                 Input: Tanggal Transaksi
                            ======================= -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="tgl">Tanggal</label>
                        <div class="mx-4">
                            <input type="date" name="tgl_transaksi_org" id="tgl"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <!-- ======================
                                 Tombol Aksi
                            ======================= -->
                    <div class="flex justify-between mt-6">
                        <!-- Tombol Kembali ke halaman daftar kas -->
                        <a href="{{ route('forum.kas', ['slug' => $forums->slug]) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            Kembali
                        </a>

                        <!-- Tombol Simpan data kas baru -->
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
