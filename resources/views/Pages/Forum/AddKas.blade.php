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
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Tambah Kas Forum</h1>
                    <p class="text-sm text-gray-500 font-poppins">Memantau aktivitas keuangan anda</p>
                </div>

                {{-- Bagian kanan header: tombol search dan profil --}}
                <div class="flex items-center gap-5">

                    {{-- 2️⃣ Input pencarian (hidden secara default) --}}
                    <input type="text" name="search_judul" id="search-input-field" form="filterForm"
                        placeholder="Cari & tekan Enter" value="{{ request('search_judul') }}"
                        class="hidden w-48 text-sm outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 border rounded-lg px-3 py-1.5 shadow-sm">

                    {{-- 3️⃣ Avatar dan info user login --}}
                    <div class="flex items-center gap-2">
                        <img class="w-8 h-8 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=e0e7ff&color=4f46e5"
                            alt="Avatar">
                        <div>
                            <p class="text-sm font-medium text-gray-700 font-poppins">{{ Auth::user()->nama }}</p>
                            <p class="text-xs text-gray-500 font-poppins">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
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
