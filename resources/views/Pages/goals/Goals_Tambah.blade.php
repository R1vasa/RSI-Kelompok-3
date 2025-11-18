@extends('Layout.layout')
{{-- Meng-extend layout utama bernama "layout" dari folder Layout --}}

@section('title', 'Tambah Goals')
{{-- Mengatur judul halaman browser menjadi "Tambah Goals" --}}

@section('body')
    <div class="flex">

        {{-- Sidebar komponen --}}
        <x-sidebar></x-sidebar>

        {{-- Area utama halaman --}}
        <div class="flex-1 ml-[20%] min-h-screen">
            {{-- Header bagian atas halaman --}}
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Tambah Goals</h1>
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

            {{-- Card/form utama --}}
            <div class="bg-white shadow-md rounded-lg p-6">

                {{-- Form untuk menambah data goals baru --}}
                <form action="{{ route('goals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- Token keamanan untuk mencegah serangan CSRF --}}

                    {{-- Input: Judul Goals --}}
                    <div class="mb-3">
                        <label class="block font-medium">Judul Goals</label>
                        <input type="text" name="judul_goals" value="{{ old('judul_goals') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            placeholder="contoh: Iphone 17" required>
                        @error('judul_goals')
                            {{-- Menampilkan pesan error jika validasi judul gagal --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Jumlah Target --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah Target</label>
                        <input type="number" name="jumlah_target" value="{{ old('jumlah_target') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" placeholder="contoh: 100000"
                            required>
                        @error('jumlah_target')
                            {{-- Menampilkan pesan error jika validasi jumlah target gagal --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Tanggal Target --}}
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal Target</label>
                        <input type="date" name="tgl_target" value="{{ old('tgl_target') }}"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                        @error('tgl_target')
                            {{-- Menampilkan pesan error jika tanggal tidak valid --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input: Gambar Goals (opsional) --}}
                    <div class="mb-3">
                        {{-- Jika sedang mengedit (opsional, bila data $goals tersedia) maka tampilkan preview gambar lama --}}
                        @if (isset($goals) && $goals->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $goals->gambar) }}" alt="Preview Gambar"
                                    class="w-32 h-32 object-cover rounded-lg border">
                            </div>
                        @endif

                        <label class="block font-medium">Gambar (opsional)</label>
                        <input type="file" name="gambar" accept="image/*"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2">
                        @error('gambar')
                            {{-- Menampilkan pesan error jika gambar tidak sesuai validasi --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol navigasi dan submit --}}
                    <div class="flex justify-between mt-6">
                        {{-- Tombol kembali ke halaman goals utama --}}
                        <a href="{{ route('goals.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-full cursor-pointer">Kembali</a>
                        {{-- Tombol untuk menyimpan goals baru --}}
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script JavaScript untuk preview gambar sebelum di-upload --}}
    <script>
        document.querySelector('input[name="gambar"]').addEventListener('change', function(e) {
            const [file] = e.target.files; // Ambil file yang dipilih user
            if (file) {
                const preview = document.createElement('img'); // Buat elemen img baru untuk preview
                preview.src = URL.createObjectURL(file); // Tampilkan file gambar ke browser tanpa upload
                preview.className = 'w-32 h-32 object-cover rounded-lg border mt-2';

                // Hapus preview lama jika sudah ada sebelumnya
                const old = document.querySelector('.preview-image');
                if (old) old.remove();

                // Tambahkan class dan tampilkan gambar di bawah input file
                preview.classList.add('preview-image');
                e.target.parentNode.insertBefore(preview, e.target.nextSibling);
            }
        });
    </script>
@endsection
