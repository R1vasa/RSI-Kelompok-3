@extends('Layout.layout')
{{-- Meng-extend layout utama bernama "layout" yang berisi struktur dasar halaman --}}

@section('title', 'Setor Tabungan')
{{-- Mengatur judul halaman browser menjadi "Setor Tabungan" --}}

@section('body')
    <div class="flex">

        {{-- Sidebar komponen navigasi di sisi kiri --}}
        <x-sidebar></x-sidebar>

        {{-- Area utama konten halaman --}}
        <div class="flex-1 ml-[20%] min-h-screen">
            {{-- Header bagian atas halaman --}}
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Setor Tabungan</h1>
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

            {{-- Card / container untuk form setor tabungan --}}
            <div class="bg-white shadow-md rounded-lg p-6">

                {{-- Form untuk menambah setoran ke goals tertentu --}}
                {{-- Mengirim data ke route bernama "setor.store" dengan ID goals terkait --}}
                <form action="{{ route('setor.store', $goals->id) }}" method="POST">
                    @csrf {{-- Token keamanan wajib untuk form POST di Laravel --}}

                    {{-- Input jumlah setor tabungan --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah setor(Rp)</label>
                        <input type="number" name="jumlah_tabungan"
                            class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" placeholder="contoh: 10000"
                            required>
                        @error('jumlah_tabungan')
                            {{-- Menampilkan pesan error jika validasi jumlah tabungan gagal --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol navigasi dan submit --}}
                    <div class="flex justify-between mt-6">
                        {{-- Tombol kembali ke halaman daftar goals --}}
                        <a href="{{ route('goals.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-full cursor-pointer">
                            Kembali
                        </a>

                        {{-- Tombol untuk menyimpan setoran --}}
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
