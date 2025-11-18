@extends('Layout.layout')
{{-- Menggunakan layout utama (resources/views/Layout/layout.blade.php) --}}

@section('title', 'Tambah Kas')
{{-- Menentukan judul halaman yang akan ditampilkan di tag <title> layout utama --}}

@section('body')
    <!-- ===============================================================
                 💰 HALAMAN TAMBAH TRANSAKSI KEUANGAN FORUM
                 Deskripsi:
                 Halaman ini digunakan untuk menambahkan transaksi baru (pemasukan/pengeluaran)
                 ke dalam forum organisasi tertentu.
            ================================================================ -->
    <div class="flex">
        <!-- Sidebar navigasi utama aplikasi -->
        <x-sidebar></x-sidebar>

        <!-- ===========================
                     Area konten utama (kanan)
                ============================ -->
        <div class="flex-1 ml-[20%] min-h-screen font-poppins">

            <!-- Header bagian atas halaman -->
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Tambah Transaksi Forum</h1>
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

            <!-- ===========================
                         FORM TAMBAH TRANSAKSI
                         Aksi: route('tambah.trans')
                         Method: POST
                         Tujuan: Menyimpan data transaksi ke tabel 'transaksi_organisasi'
                    ============================ -->
            <div class="bg-white p-6">
                <form action="{{ route('tambah.trans', ['slug' => $forums->slug]) }}" method="POST">
                    @csrf {{-- Token keamanan Laravel untuk mencegah serangan CSRF --}}

                    <!-- ====================================
                                 Input 1 — Judul Transaksi
                                 Contoh: "Pembelian alat tulis" atau "Dana kas masuk"
                            ===================================== -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Judul Transaksi</label>
                        <div class="mx-4">
                            <input type="text" name="nama"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <!-- ====================================
                                 Input 2 — Jenis Transaksi
                                 Pilihan antara: pemasukan / pengeluaran
                            ===================================== -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jenis</label>
                        <div class="mx-4">
                            <select name="jenis"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                                <option value="pemasukan">Pemasukan</option>
                                <option value="pengeluaran">Pengeluaran</option>
                            </select>
                        </div>
                    </div>

                    <!-- ====================================
                                 Input 3 — Jumlah Nominal Transaksi
                                 Diisi dengan angka (contoh: 500000)
                            ===================================== -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jumlah (Rp)</label>
                        <div class="mx-4">
                            <input type="number" name="nominal"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <!-- ====================================
                                 Input 4 — Deskripsi Transaksi
                                 Berfungsi menjelaskan detail transaksi
                            ===================================== -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="deskripsi">Deskripsi</label>
                        <div class="mx-4">
                            <textarea name="deskripsi" class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                rows="2" required id="deskripsi"></textarea>
                        </div>
                    </div>

                    <!-- ====================================
                                 Input 5 — Tanggal Transaksi
                                 Format: YYYY-MM-DD
                            ===================================== -->
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="tgl">Tanggal</label>
                        <div class="mx-4">
                            <input type="date" name="tgl_transaksi" id="tgl"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <!-- ====================================
                                 Tombol Aksi (Kembali & Simpan)
                            ===================================== -->
                    <div class="flex justify-between mt-6">
                        <!-- Tombol untuk kembali ke halaman daftar transaksi -->
                        <a href="{{ route('forum.trans', ['slug' => $forums->slug]) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            Kembali
                        </a>

                        <!-- Tombol untuk menyimpan data transaksi -->
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
