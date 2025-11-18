@extends('Layout.layout')
{{-- Menggunakan layout utama aplikasi (struktur HTML dasar, font, dan CSS global) --}}

@section('title', 'Forum')
{{-- Menentukan judul halaman yang akan ditampilkan di browser tab --}}

@section('body')
    <!-- ==============================================================
                     HALAMAN DAFTAR FORUM ORGANISASI
                     Fungsi:
                     - Menampilkan semua forum yang diikuti/dibuat oleh user.
                     - Menyediakan fitur untuk membuat forum baru (Tambah Forum).
                     - Menyediakan fitur untuk bergabung dengan forum (Join Forum).
                ============================================================== -->
    <div class="flex">
        <!-- Sidebar navigasi utama (komponen global) -->
        <x-sidebar></x-sidebar>

        <!-- ======================================
                         AREA KONTEN UTAMA
                    ======================================= -->
        <div class="flex-1 ml-[20%] min-h-screen">
            <!-- Header bagian atas halaman -->
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Forum</h1>
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

            <!-- ======================================
                             KONTEN UTAMA: LIST FORUM
                        ======================================= -->
            <div class="p-6">
                <!-- ======================
                                 Tombol Aksi di Header
                                 (Tambah Forum & Join Forum)
                            ======================= -->
                <div class="flex items-center mb-6 flex-row-reverse gap-2">
                    <!-- Tombol Tambah Forum -->
                    <a href="{{ route('forum.add') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins">
                        Tambah Forum
                    </a>

                    <!-- Tombol Join Forum -->
                    <button id="openModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins">
                        Join Forum
                    </button>
                </div>

                <!-- ======================
                                 LOOP DAFTAR FORUM
                                 Menampilkan setiap forum yang user ikuti atau buat
                            ======================= -->
                @foreach ($forums as $forum)
                    <div class="bg-[#F2F9FF] shadow-md rounded-full p-2 flex items-center space-x-4 mb-4">
                        <!-- Gambar forum -->
                        <img src="{{ asset('storage/' . $forum->gambar_forum) }}" alt=""
                            class="w-18 h-18 rounded-full border-1 border-gray-300 object-cover p-1">

                        <!-- Informasi forum -->
                        <div class="flex justify-center items-center gap-10">
                            <div class="grid-rows-2">
                                <h1 class="font-bold text-xl">{{ $forum->forum }}</h1>
                                <h1 class="max-w-lg text-md">{{ $forum->deskripsi }}</h1>
                            </div>

                            <!-- Tombol aksi per forum -->
                            <div class="flex items-center space-x-4 absolute right-12">
                                <!-- Jika user adalah bendahara forum, tampilkan tombol "Link Forum" -->
                                @if ($forum->anggota->where('id_users', Auth::id())->first()?->role === 'bendahara')
                                    <button data-target="modalLink{{ $loop->index }}"
                                        class="bg-blue-500 w-10 h-10 rounded-full cursor-pointer">
                                        <i class='bx bx-link-alt text-xl text-white'></i>
                                    </button>
                                @endif

                                <!-- Tombol menuju halaman kas forum -->
                                <a href="{{ route('forum.kas', ['slug' => $forum->slug]) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white w-32 text-center px-4 py-2 rounded-lg font-semibold text-md transition duration-150">
                                    Kas
                                </a>

                                <!-- Tombol menuju halaman transaksi forum -->
                                <a href="{{ route('forum.trans', ['slug' => $forum->slug]) }}"
                                    class="bg-emerald-400 hover:bg-emerald-500 text-white w-32 text-center px-4 py-2 rounded-lg font-semibold text-md transition duration-150">
                                    Transaksi
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ======================
                                     MODAL LINK FORUM
                                     Menampilkan kode link akses forum
                                     (Hanya untuk bendahara forum)
    ======================= -->
                    <div id="modalLink{{ $loop->index }}"
                        class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">
                        <div class="bg-white rounded-xl shadow-lg p-8 w-[400px] relative">
                            <!-- Tombol Tutup Modal -->
                            <button id="closeModalLinkX" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                                ✕
                            </button>

                            <!-- Konten Modal -->
                            <h2 class="text-xl font-semibold text-gray-800 mb-3 text-center">
                                Link Forum
                            </h2>
                            <p class="text-gray-500 text-sm mb-5 text-center">
                                Salin kode forum untuk akses join forum
                            </p>
                            <div>
                                <h1 class="bg-secondary p-3">{{ $forum->link_akses }}</h1>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- ======================
                             MODAL JOIN FORUM
                             Digunakan untuk memasukkan kode forum
                        ======================= -->
            <div id="modal" class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">
                <div class="bg-white rounded-xl shadow-lg p-8 w-[400px] relative">
                    <!-- Tombol Tutup Modal -->
                    <button id="closeModalX" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">✕</button>

                    <!-- Judul dan Deskripsi -->
                    <h2 class="text-xl font-semibold text-gray-800 mb-3 text-center">
                        Gabung ke Forum
                    </h2>
                    <p class="text-gray-500 text-sm mb-5 text-center">
                        Masukkan kode akses forum yang kamu dapatkan
                    </p>

                    <!-- Form Join Forum -->
                    <form action="{{ route('forum.join.submit') }}" method="POST">
                        @csrf
                        <input type="text" name="link_akses"
                            class="w-full border rounded-lg px-4 py-2 mb-4 focus:ring focus:ring-blue-200"
                            placeholder="Contoh: Np6xQ2LJrWz7cDf" required>

                        @error('link_akses')
                            <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full font-medium">
                            Gabung Forum
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================================
                     SCRIPT: PENGATURAN MODAL (Join & Link Forum)
                     Fungsi:
                     - Membuka & menutup modal join forum
                     - Menampilkan modal link forum (per forum)
                     - Menutup modal jika klik di luar area
                =========================================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Modal Join Forum ===
            const modalJoin = document.getElementById('modal');
            const openJoinBtn = document.getElementById('openModal');
            const closeJoinBtn = document.getElementById('closeModalX');

            // Tampilkan modal join
            openJoinBtn.addEventListener('click', () => {
                modalJoin.classList.remove('hidden');
                modalJoin.classList.add('flex');
            });

            // Tutup modal join
            closeJoinBtn.addEventListener('click', () => {
                modalJoin.classList.add('hidden');
                modalJoin.classList.remove('flex');
            });

            // Klik di luar modal → tutup
            modalJoin.addEventListener('click', (e) => {
                if (e.target === modalJoin) {
                    modalJoin.classList.add('hidden');
                    modalJoin.classList.remove('flex');
                }
            });

            // === Modal Link Forum (loop untuk setiap forum) ===
            document.querySelectorAll('[data-target]').forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    const modal = document.getElementById(targetId);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            // Tombol tutup modal link
            document.querySelectorAll('#closeModalLinkX').forEach(closeBtn => {
                closeBtn.addEventListener('click', (e) => {
                    const modal = e.target.closest('.fixed');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });

            // Tutup modal link saat klik di luar area
            document.querySelectorAll('[id^="modalLink"]').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            });
        });
    </script>
@endsection
