@extends('Layout.layout')
{{-- Menggunakan layout utama yang bernama "layout" --}}

@section('title', 'Goals')
{{-- Menentukan judul halaman yang akan muncul di tab browser --}}

@section('body')
    <div class="flex">

        {{-- Komponen Sidebar (menggunakan Blade Component) --}}
        <x-sidebar></x-sidebar>

        {{-- Bagian utama konten halaman --}}
        <div class="flex-1 ml-[20%] min-h-screen">

            {{-- Header halaman --}}
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Goals</h1>
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

            {{-- === Pesan Sukses Setelah Aksi (misal: tambah, edit, hapus) === --}}
            @if (session('success'))
                <!-- Modal yang muncul ketika aksi berhasil -->
                <div id="success-modal" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in">
                        <div class="flex justify-center mb-3">
                            <!-- Ikon centang -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-800">Berhasil!</h2>
                        <p class="text-gray-600 mt-1">{{ session('success') }}</p>
                    </div>
                </div>

                <script>
                    // Tutup modal otomatis setelah 3 detik
                    setTimeout(() => {
                        const modal = document.getElementById('success-modal');
                        if (modal) {
                            modal.classList.add('opacity-0', 'transition', 'duration-700');
                            setTimeout(() => modal.remove(), 700);
                        }
                    }, 3000);
                </script>

                <style>
                    /* Animasi muncul (fade-in) */
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: scale(0.9);
                        }

                        to {
                            opacity: 1;
                            transform: scale(1);
                        }
                    }

                    .animate-fade-in {
                        animation: fadeIn 0.4s ease-out;
                    }
                </style>
            @endif


            {{-- Tombol Tambah Goals --}}
            <div class="flex justify-between items-center my-6 px-6">
                <a href="{{ route('goals.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins">
                    + Tambah Goals
                </a>
            </div>

            {{-- === Perulangan menampilkan semua goals === --}}
            @foreach ($goals as $goals)
                @php
                    // Hitung persentase progress
                    $progress = 0;
                    if ($goals->jumlah_target > 0) {
                        $progress = ($goals->current_amount / $goals->jumlah_target) * 100;
                    }

                    // Batasi nilai progress maksimal 100%
                    if ($progress > 100) {
                        $progress = 100;
                    }

                    // Tentukan status apakah sudah tercapai atau belum
                    $status = $goals->current_amount >= $goals->jumlah_target ? 'Tercapai' : 'Belum Tercapai';
                @endphp

                {{-- Kartu Goals individual --}}
                <div class="flex justify-between bg-[#F4F9FF] rounded-full p-1 m-6 px-6 items-center shadow-md">

                    {{-- Kolom kiri: gambar dan info goal --}}
                    <div class="flex items-center space-x-4">

                        {{-- Gambar goal (jika tidak ada, tampilkan ikon default) --}}
                        <img src="{{ $goals->gambar ? asset('storage/' . $goals->gambar) : 'https://img.icons8.com/?size=100&id=40axph0YuvfK&format=png&color=000000' }}"
                            alt="goals icon" class="w-16 h-16 rounded-full border border-gray-300 object-cover">

                        {{-- Detail teks goals --}}
                        <div>
                            <h3 class="font-poppins font-semibold text-lg flex items-center gap-2">
                                {{ $goals->judul_goals }}

                                {{-- Tampilkan status goals --}}
                                @if ($status === 'Tercapai')
                                    <span class="text-green-600 text-sm font-semibold">✅ {{ $status }}</span>
                                @else
                                    <span class="text-yellow-500 text-sm font-semibold">⏳ {{ $status }}</span>
                                @endif
                            </h3>

                            {{-- Progress bar untuk visualisasi kemajuan --}}
                            <div class="w-156 bg-gray-400 rounded-full h-3 mt-2 overflow-hidden">
                                <div class="bg-[#5DD39E] h-3 rounded-full transition-all duration-500"
                                    style="width: {{ $progress }}%;"></div>
                            </div>

                            {{-- Menampilkan nominal terkumpul dan target --}}
                            <p class="text-sm text-gray-600 mt-1">
                                Rp {{ number_format($goals->current_amount, 0, ',', '.') }} /
                                Rp {{ number_format($goals->jumlah_target, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Tanggal target pencapaian --}}
                        <p class="text-lg text-black font-semibold">
                            Target: {{ \Carbon\Carbon::parse($goals->tgl_target)->format('d-m-Y') }}
                        </p>
                    </div>

                    {{-- Kolom kanan: tombol aksi --}}
                    <div class="flex items-center space-x-2 mr-5">

                        {{-- Tombol Setor (disabled jika sudah tercapai) --}}
                        @if ($status === 'Tercapai')
                            <button
                                class="bg-gray-300 text-white p-2 rounded-md font-poppins flex items-center justify-center cursor-not-allowed"
                                disabled>
                                <img src="https://img.icons8.com/?size=100&id=PEmFcgjhBgKF&format=png&color=000000"
                                    alt="Edit" class="w-5 h-5">
                            </button>
                        @else
                            <a href="{{ route('setor.create', $goals->id) }}"
                                class="bg-yellow-400 hover:bg-yellow-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                <i class='bx bx-money-withdraw text-2xl'></i>
                            </a>
                        @endif

                        {{-- Tombol Edit Goals --}}
                        <a href="{{ route('goals.edit', $goals->id) }}"
                            class="bg-green-300 hover:bg-green-600 text-white p-1 rounded-md font-poppins flex items-center justify-center cursor-pointer">
                            <i class='bx bxs-edit text-2xl'></i>
                        </a>

                        {{-- Tombol Hapus Goals --}}
                        <form action="{{ route('goals.destroy', $goals->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" data-id="{{ $goals->id }}"
                                class="bg-red-400 hover:bg-red-600 text-white p-1 rounded-md font-poppins flex items-center justify-center cursor-pointer delete-btn">
                                <i class='bx bx-trash text-2xl'></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- === Modal Konfirmasi Hapus (reusable untuk semua goals) === --}}
    <div id="confirmModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96 text-center">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
            <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus goals ini?</p>
            <div class="flex justify-center gap-4">
                <button id="cancelDelete"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">
                    Tidak
                </button>
                <button id="confirmDelete"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        // Script untuk logika modal konfirmasi hapus
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            let formToSubmit = null;

            // Ketika tombol hapus diklik → tampilkan modal
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    formToSubmit = button.closest('form'); // Simpan form yang akan dihapus
                    modal.classList.remove('hidden');
                });
            });

            // Tombol "Tidak" → batalkan penghapusan
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                formToSubmit = null;
            });

            // Tombol "Ya, Hapus" → submit form
            confirmBtn.addEventListener('click', () => {
                if (formToSubmit) formToSubmit.submit();
                modal.classList.add('hidden');
            });

            // Klik area gelap di luar modal → tutup modal
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>
@endsection
