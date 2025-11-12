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
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    {{-- Teks judul hanya muncul jika user sudah login --}}
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Setor Tabungan</h1>
                @endauth
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
                        <input type="number" 
                               name="jumlah_tabungan" 
                               class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                               placeholder="contoh: 10000" 
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

