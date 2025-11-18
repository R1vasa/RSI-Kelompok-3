@extends('Layout.layout')
{{-- Menggunakan layout utama yang berisi struktur dasar HTML dan import CSS --}}

@section('title', 'Tambah Forum')
{{-- Menentukan judul halaman yang muncul di tag <title> layout utama --}}

@section('body')
    <!-- ==========================================================
                 🏛️ HALAMAN TAMBAH FORUM ORGANISASI
                 Deskripsi:
                 Halaman ini digunakan untuk membuat forum baru,
                 lengkap dengan gambar profil, nama, dan deskripsi forum.
            =========================================================== -->
    <div class="flex">
        <!-- Sidebar navigasi utama (komponen global) -->
        <x-sidebar></x-sidebar>

        <!-- ===============================
                     AREA KONTEN UTAMA (Bagian kanan)
                ================================ -->
        <div class="flex-1 ml-[20%] min-h-screen">

            <!-- Header atas halaman -->
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Tambah Forum</h1>
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

            <!-- ===============================
                         FORM TAMBAH FORUM
                         Method: POST
                         Action: route('forum.store')
                         Tujuan: Menyimpan data forum baru ke database
                    ================================ -->
            <div class="px-10 py-6 font-poppins">
                <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- Token keamanan Laravel untuk mencegah CSRF --}}

                    <!-- ========================================
                                 Upload Gambar Forum (Foto Profil Forum)
                            ========================================= -->
                    <div class="relative">
                        <!-- Preview default sebelum gambar diunggah -->
                        <img src="/image/default_forum.png"
                            class="w-32 h-32 rounded-full border-2 border-gray-300 object-cover mb-4" alt="Gambar Forum"
                            id="preview">

                        <!-- Tombol edit gambar (ikon pensil) -->
                        <label for="forumImg"
                            class="bg-blue-200 h-8 w-8 flex justify-center items-center rounded-full absolute top-2 cursor-pointer">
                            <i class='bx bxs-edit text-2xl'></i>
                        </label>

                        <!-- Input file tersembunyi untuk unggah gambar -->
                        <input type="file" name='gambar_forum' accept=".jpg, .jpeg, .png" id="forumImg"
                            class="w-full border rounded px-3 py-2 hidden">
                    </div>

                    <!-- ========================================
                                 Input 1 — Nama Forum
                            ========================================= -->
                    <div class="mb-4 mt-8">
                        <label class="block font-semibold text-lg mb-2">Nama Forum</label>
                        <div class="mx-4">
                            <input type="text" name="forum"
                                class="w-full border-gray-300 bg-secondary border rounded-full px-2 py-3" required>
                        </div>
                    </div>

                    <!-- ========================================
                                 Input 2 — Deskripsi Forum
                            ========================================= -->
                    <div class="mb-4">
                        <label class="block font-semibold text-lg mb-2">Deskripsi Forum</label>
                        <div class="mx-4">
                            <textarea name="deskripsi" class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                rows="4" required></textarea>
                        </div>
                    </div>

                    <!-- ========================================
                                 Tombol Aksi (Kembali dan Simpan)
                            ========================================= -->
                    <div class="flex justify-between mt-6">
                        <!-- Tombol Kembali ke halaman utama forum -->
                        <a href="{{ route('forum.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                            Kembali
                        </a>

                        <!-- Tombol Submit untuk membuat forum baru -->
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded cursor-pointer">
                            Buat Forum
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ==========================================================
                 📸 SCRIPT: PREVIEW GAMBAR FORUM
                 Fungsi:
                 - Menampilkan gambar pratinjau sebelum form disubmit.
                 - Menggunakan File API JavaScript (URL.createObjectURL).
            =========================================================== -->
    <script>
        const fileInput = document.getElementById('forumImg');
        const profileImg = document.getElementById('preview');

        // Saat pengguna memilih file gambar → tampilkan preview
        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                profileImg.src = URL.createObjectURL(file);
            }
        });
    </script>
@endsection
