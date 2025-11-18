@extends('Layout.layout')
{{-- Meng-extend layout utama dari folder Layout --}}

@section('title', 'Tambah Transaksi')
{{-- Mengatur judul halaman browser menjadi "Tambah Transaksi" --}}

@section('body')
    <div class="flex">

        {{-- Sidebar navigasi kiri --}}
        <x-sidebar></x-sidebar>

        {{-- Area konten utama --}}
        <div class="flex-1 ml-[20%] min-h-screen">

            {{-- Header bagian atas halaman --}}
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Tambah Transaksi</h1>
                    <p class="text-sm text-gray-500 font-poppins">Memantau aktivitas keuangan anda</p>
                </div>

                {{-- Bagian kanan header: tombol search dan profil --}}
                <div class="flex items-center gap-5">

                    {{-- 1️⃣ Tombol ikon search --}}
                    <button id="search-icon-btn" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </button>

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

            {{-- Card utama untuk form tambah transaksi --}}
            <div class="bg-white shadow-md rounded-lg p-6">

                {{-- Form tambah transaksi baru --}}
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf {{-- Token keamanan Laravel wajib untuk form POST --}}

                    {{-- Input: Judul Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Judul Transaksi</label>
                        <input type="text" name="judul_transaksi" value="{{ old('judul_transaksi') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            placeholder="contoh: Nasi Pecel" required>
                        @error('judul_transaksi')
                            {{-- Pesan error jika validasi judul gagal --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Kategori --}}
                    <div class="mb-3">
                        <label class="block font-medium">Kategori</label>
                        <select name="id_kategori" value="{{ old('id_kategori') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            {{-- Opsi kategori statis (bisa diubah ke dinamis dari database) --}}
                            <option value="1">Jajan</option>
                            <option value="2">Service</option>
                            <option value="4">Transportasi</option>
                            <option value="3">Makan</option>
                            <option value="5">Lain-lain</option>
                        </select>
                    </div>

                    {{-- Input: Jenis Transaksi (Pemasukan / Pengeluaran) --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jenis</label>
                        <select name="jenis_transaksi" value="{{ old('jenis_transaksi') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                            <option value="" disabled selected>Pilih Jenis Transaksi</option>
                            <option value="Pemasukan">Pemasukan</option>
                            <option value="Pengeluaran">Pengeluaran</option>
                        </select>
                    </div>

                    {{-- Input: Jumlah Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah (Rp)</label>
                        <input type="number" name="jumlah_transaksi" value="{{ old('jumlah_transaksi') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" placeholder="contoh: 100000"
                            required>
                        @error('jumlah_transaksi')
                            {{-- Pesan error jika jumlah tidak valid --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Tanggal Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tgl_transaksi" value="{{ old('tgl_transaksi') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                    </div>

                    {{-- Tombol navigasi dan simpan --}}
                    <div class="flex justify-between mt-6">
                        {{-- Tombol kembali ke daftar transaksi --}}
                        <a href="{{ route('transaksi.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-full cursor-pointer">
                            Kembali
                        </a>

                        {{-- Tombol simpan data transaksi --}}
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full cursor-pointer">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
