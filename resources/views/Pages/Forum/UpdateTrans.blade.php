@extends('Layout.layout')
{{-- Menggunakan layout utama aplikasi agar tampilan konsisten di seluruh halaman --}}

@section('title', 'Tambah Kas')
{{-- Menentukan judul halaman yang muncul di tab browser --}}

@section('body')
    <!-- ===============================================================
                         HALAMAN EDIT TRANSAKSI FORUM ORGANISASI
                         Deskripsi:
                         - Halaman ini digunakan oleh bendahara forum untuk mengubah data transaksi
                           (pemasukan atau pengeluaran) yang sudah ada.
                         - Data transaksi diambil dari database melalui variabel `$trans`.
                         - Aksi penyimpanan dilakukan melalui method PUT (update).
                    ============================================================== -->

    <div class="flex">
        <!-- Sidebar navigasi utama -->
        <x-sidebar></x-sidebar>

        <!-- Area konten utama halaman -->
        <div class="flex-1 ml-[20%] min-h-screen font-poppins">

            <!-- Header halaman yang menampilkan nama forum aktif -->
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">{{ $forums->forum }}</h1>

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

            <!-- Area utama form edit transaksi -->
            <div class="bg-white p-6 over">

                {{-- ==========================================================
                     FORM EDIT TRANSAKSI
                     - Menggunakan route `edit.trans`
                     - Method PUT untuk memperbarui data
                     - Parameter: slug forum & id transaksi
                =========================================================== --}}
                <form action="{{ route('edit.trans', ['slug' => $forums->slug, 'id' => $trans->id]) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Laravel directive untuk menggunakan HTTP PUT (update) --}}

                    {{-- ===========================
                         INPUT: Judul Transaksi
                    ============================ --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Judul Transaksi</label>
                        <div class="mx-4">
                            <input type="text" name="nama"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                value="{{ $trans->nama }}" required>
                        </div>
                    </div>

                    {{-- ===========================
                         INPUT: Jenis Transaksi
                    ============================ --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jenis</label>
                        <div class="mx-4">
                            <select name="jenis"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                                {{-- Menentukan opsi yang aktif sesuai jenis transaksi --}}
                                <option value="pemasukan" {{ $trans->jenis == 'pemasukan' ? 'selected' : '' }}>
                                    Pemasukan
                                </option>
                                <option value="pengeluaran" {{ $trans->jenis == 'pengeluaran' ? 'selected' : '' }}>
                                    Pengeluaran
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- ===========================
                         INPUT: Nominal Transaksi
                    ============================ --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jumlah (Rp)</label>
                        <div class="mx-4">
                            <input type="number" name="nominal"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                value="{{ $trans->nominal }}" required>
                        </div>
                    </div>

                    {{-- ===========================
                         INPUT: Deskripsi Transaksi
                    ============================ --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="deskripsi">Deskripsi</label>
                        <div class="mx-4">
                            <textarea name="deskripsi" class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                rows="2" required id="deskripsi">{{ old('deskripsi', $trans->deskripsi ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- ===========================
                         INPUT: Tanggal Transaksi
                    ============================ --}}
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="tgl">Tanggal</label>
                        <div class="mx-4">
                            <input type="date" name="tgl_transaksi" id="tgl"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                value="{{ $trans->tgl_transaksi }}" required>
                        </div>
                    </div>

                    {{-- ===========================
                         TOMBOL AKSI FORM
                    ============================ --}}
                    <div class="flex justify-between mt-6">
                        <!-- Tombol kembali ke halaman transaksi forum -->
                        <a href="{{ route('forum.trans', ['slug' => $forums->slug]) }}"
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
