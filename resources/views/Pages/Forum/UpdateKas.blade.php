@extends('Layout.layout')
{{-- Menggunakan layout utama agar konsisten dengan tampilan halaman lain --}}

@section('title', 'Edit Kas')
{{-- 🏷️ Menentukan judul halaman di tab browser --}}

@section('body')
    <!-- ==============================================================
                HALAMAN EDIT DATA KAS FORUM
                 Fungsi:
                 - Menampilkan form untuk mengubah data kas organisasi.
                 - Hanya dapat diakses oleh pengguna dengan peran "bendahara".
                 - Data yang diubah meliputi nama transaksi, jumlah, dan tanggal transaksi.
            ============================================================== -->

    <div class="flex">
        <!-- Sidebar navigasi utama -->
        <x-sidebar></x-sidebar>

        <!-- Konten utama halaman -->
        <div class="flex-1 ml-[20%] min-h-screen font-poppins">

            <!-- 🔹 Header halaman (menampilkan nama forum aktif) -->
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">{{ $forums->forum }}</h1>
                @endauth
            </div>

            <!-- Form Edit Kas -->
            <div class="bg-white p-6">
                {{-- =====================================================
                     FORM EDIT KAS
                     Method: PUT (karena update data)
                     Route: edit.kas
                     Parameter: slug forum & ID kas
                ====================================================== --}}
                <form action="{{ route('edit.kas', ['slug' => $forums->slug, 'id' => $kas->id]) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Laravel directive untuk HTTP PUT method --}}

                    {{-- =======================
                         Input Nama Transaksi
                    ======================== --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Nama</label>
                        <div class="mx-4">
                            <input type="text" name="nama_transaksi"
                                value="{{ old('nama_transaksi', $kas->nama_transaksi) }}" {{-- Menampilkan nilai lama --}}
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    {{-- =========================
                         Input Jumlah (Nominal)
                    ========================== --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jumlah (Rp)</label>
                        <div class="mx-4">
                            <input type="number" name="jumlah" value="{{ old('jumlah', $kas->jumlah) }}"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    {{-- =========================
                         Input Tanggal Transaksi
                    ========================== --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="tgl">Tanggal</label>
                        <div class="mx-4">
                            <input type="date" name="tgl_transaksi_org" id="tgl"
                                value="{{ old('tgl_transaksi_org', $kas->tgl_transaksi_org) }}"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    {{-- =========================
                         Tombol Aksi
                    ========================== --}}
                    <div class="flex justify-between mt-6">
                        <!-- Tombol kembali ke halaman daftar kas -->
                        <a href="{{ route('forum.kas', ['slug' => $forums->slug]) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            Kembali
                        </a>

                        <!-- Tombol simpan perubahan -->
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
