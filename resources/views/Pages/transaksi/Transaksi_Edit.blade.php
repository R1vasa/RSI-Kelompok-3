@extends('Layout.layout')
{{-- Meng-extend layout utama dari folder Layout --}}

@section('title', 'Edit Transaksi')
{{-- Mengatur judul halaman browser menjadi "Edit Transaksi" --}}

@section('body')
    <div class="flex">

        {{-- Sidebar navigasi di sisi kiri --}}
        <x-sidebar></x-sidebar>

        {{-- Area utama halaman --}}
        <div class="flex-1 ml-[20%] min-h-screen">

            {{-- Header bagian atas halaman --}}
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    {{-- Judul halaman hanya muncul jika user login --}}
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Edit Transaksi</h1>
                @endauth
            </div>

            {{-- Card utama untuk form edit --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Edit Transaksi</h1>

                {{-- Form untuk memperbarui data transaksi --}}
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                    @csrf {{-- Token keamanan Laravel --}}
                    @method('PUT') {{-- Mengubah method menjadi PUT untuk proses update --}}

                    {{-- Input: Judul Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Judul Transaksi</label>
                        <input type="text" name="judul_transaksi" 
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            value="{{ old('judul_transaksi', $transaksi->judul_transaksi) }}" 
                            palceholder="contoh: Nasi Pecel" required>
                        @error('judul_transaksi')
                            {{-- Pesan error jika validasi judul gagal --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Kategori --}}
                    <div class="mb-3">
                        <label class="block font-medium">Kategori</label>
                        <select name="id_kategori" 
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                            {{-- Looping daftar kategori dari controller --}}
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{-- Menandai kategori yang sedang digunakan oleh transaksi --}}
                                    {{ old('id_kategori', $transaksi->id_kategori) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                             @endforeach
                        </select>
                    </div>

                    {{-- Input: Jenis Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jenis</label>
                        <select name="jenis_transaksi" 
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                            <option value="Pemasukan"
                                {{-- Pilih otomatis jika data lama bernilai 'Pemasukan' --}}
                                {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'Pemasukan' ? 'selected' : '' }}>
                                Pemasukan
                            </option>
                            <option value="Pengeluaran"
                                {{-- Pilih otomatis jika data lama bernilai 'Pengeluaran' --}}
                                {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'Pengeluaran' ? 'selected' : '' }}>
                                Pengeluaran
                            </option>
                        </select>
                    </div>

                    {{-- Input: Jumlah Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah (Rp)</label>
                        <input type="number" name="jumlah_transaksi" 
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            value="{{ old('jumlah_transaksi', $transaksi->jumlah_transaksi) }}" required>
                        @error('jumlah_transaksi')
                            {{-- Pesan error jika jumlah transaksi tidak valid --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Tanggal Transaksi --}}
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tgl_transaksi" 
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            value="{{ old('tgl_transaksi', $transaksi->tgl_transaksi) }}" required>
                    </div>

                    {{-- Tombol navigasi dan simpan --}}
                    <div class="flex justify-between mt-6">
                        {{-- Tombol kembali ke halaman index transaksi --}}
                        <a href="{{ route('transaksi.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-full cursor-pointer">
                            Kembali
                        </a>

                        {{-- Tombol simpan perubahan transaksi --}}
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

