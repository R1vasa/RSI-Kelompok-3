@extends('Layout.layout')

@section('title', 'Transaksi')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        {{-- KONTEN UTAMA --}}
        <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC]">

            {{-- HEADER --}}
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Transaksi</h1>
                    <p class="text-sm text-gray-500 font-poppins">Memantau aktivitas keuangan anda</p>
                </div>

                <div class="flex items-center gap-5">
                
                {{-- 1. Tombol Ikon Search (Trigger) --}}
                <button id="search-icon-btn" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                    </svg>
                </button>

                {{-- 2. Input Search (Tersembunyi, tapi terhubung ke 'filterForm') --}}
                <input type="text" name="search_judul"
                    id="search-input-field"
                    form="filterForm"
                    placeholder="Cari & tekan Enter"
                    value="{{ request('search_judul') }}"
                    class="hidden w-48 text-sm outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 border rounded-lg px-3 py-1.5 shadow-sm">

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

            {{-- 🔹 MODAL SUKSES --}}
            @if (session('success'))
                <div id="success-modal" class="fixed inset-0 flex items-center justify-center z-50">
                    <div class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in">
                        <div class="flex justify-center mb-3">
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
                    setTimeout(() => {
                        const modal = document.getElementById('success-modal');
                        if (modal) {
                            modal.classList.add('opacity-0', 'transition', 'duration-700');
                            setTimeout(() => modal.remove(), 700);
                        }
                    }, 3000);
                </script>

                <style>
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

            {{-- 🔹 FILTER TRANSAKSI --}}
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <form method="GET" action="{{ route('transaksi.index') }}" class="flex items-center gap-2"
                        id="filterForm">

                        

                        {{-- Date Range --}}
                        <div class="flex items-center border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <input id="datepicker" type="text" placeholder="Pilih rentang tanggal"
                                class="outline-none border-none p-0 w-48 text-sm"
                                value="{{ request('date_range_display') }}">
                            <input type="hidden" name="date_range" id="date_range_hidden"
                                value="{{ request('date_range') }}">
                        </div>

                        {{-- Jenis --}}
                        <select name="jenis_transaksi" onchange="submitFilterForm()"
                            class="border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan" {{ request('jenis_transaksi') == 'pemasukan' ? 'selected' : '' }}>
                                Pemasukan</option>
                            <option value="pengeluaran" {{ request('jenis_transaksi') == 'pengeluaran' ? 'selected' : '' }}>
                                Pengeluaran</option>
                        </select>

                        {{-- Kategori --}}
                        <select name="kategori_id" onchange="submitFilterForm()"
                            class="border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Reset --}}
                        <a href="{{ route('transaksi.index') }}"
                            class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 ml-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reset
                        </a>
                    </form>

                    {{-- Tombol Ekspor dan Tambah --}}
                    <div class="flex items-center gap-3">
                        <button
                            class="bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-poppins text-sm flex items-center gap-2 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Export
                        </button>
                        <a href="{{ route('transaksi.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins text-sm flex items-center gap-2 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tambah Transaksi
                        </a>
                    </div>
                </div>

                {{-- 🔹 TABEL TRANSAKSI (versi baru) --}}
                <div class="bg-white shadow-md overflow-hidden">
                    <table class="min-w-full table-auto border-1 border-gray-300">
                        <thead>
                            <tr class="bg-blue-200">
                                <th class="px-4 py-2 text-center font-poppins">Judul</th>
                                <th class="px-4 py-2 text-center font-poppins">Kategori</th>
                                <th class="px-4 py-2 text-center font-poppins">Jenis</th>
                                <th class="px-4 py-2 text-center font-poppins">Jumlah</th>
                                <th class="px-4 py-2 text-center font-poppins">Tanggal</th>
                                <th class="px-4 py-2 text-center font-poppins">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $transaksi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->judul_transaksi }}</td>
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->kategori->kategori }}
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center">
                                        {{ ucfirst($transaksi->jenis_transaksi) }}
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center">
                                        Rp {{ number_format($transaksi->jumlah_transaksi, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center">
                                        {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d-m-Y') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('transaksi.edit', $transaksi->id) }}"
                                                class="bg-green-400 hover:bg-green-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                                <i class='bx bxs-edit text-2xl'></i>
                                            </a>

                                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}"
                                                method="POST" class="inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" data-id="{{ $transaksi->id }}"
                                                    class="bg-red-400 hover:bg-red-600 text-white p-1 rounded-md font-poppins flex items-center justify-center cursor-pointer delete-btn">
                                                    <i class='bx bx-trash text-2xl'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Modal Konfirmasi Delete --}}
    <div id="confirmModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96 text-center">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
            <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus transaksi ini?</p>
            <div class="flex justify-center gap-4">
                <button id="cancelDelete"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">Tidak</button>
                <button id="confirmDelete"
                    class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- 🔸 Script Modal Delete --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirmModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            let formToSubmit = null;

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    formToSubmit = button.closest('form');
                    modal.classList.remove('hidden');
                });
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                formToSubmit = null;
            });

            confirmBtn.addEventListener('click', () => {
                if (formToSubmit) formToSubmit.submit();
                modal.classList.add('hidden');
            });

            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>

    {{-- 🔸 Script Auto Submit Filter --}}
    <script>
        function submitFilterForm() {
            setTimeout(() => document.getElementById('filterForm').submit(), 100);
        }
    </script>

    {{-- 🔸 Script Litepicker --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const picker = new Litepicker({
                element: document.getElementById('datepicker'),
                singleMode: false,
                autoApply: true,
                format: 'DD MMM YYYY',
                separator: ' - ',
                dropdowns: {
                    months: true,
                    years: true
                },
                setup: (picker) => {
                    picker.on('selected', (date1, date2) => {
                        const hidden = document.getElementById('date_range_hidden');
                        if (date1 && date2) hidden.value = date1.format('YYYY-MM-DD') + ' to ' +
                            date2.format('YYYY-MM-DD');
                        else hidden.value = '';
                        submitFilterForm();
                    });
                },
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('search-icon-btn');
            const searchInput = document.getElementById('search-input-field');
    
            // 1. Saat ikon search diklik
            searchBtn.addEventListener('click', function() {
                searchBtn.classList.add('hidden'); // Sembunyikan ikon
                searchInput.classList.remove('hidden'); // Tampilkan input
                searchInput.focus(); // Langsung fokus ke input
            });
    
            // 2. Saat klik di luar input (blur)
            searchInput.addEventListener('blur', function() {

                if (searchInput.value === '') {
                    searchInput.classList.add('hidden'); // Sembunyikan input
                    searchBtn.classList.remove('hidden'); // Tampilkan lagi ikon
                }
            });

            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Mencegah aksi default 'Enter'
                    submitFilterForm(); // Memanggil fungsi auto-submit Anda yang sudah ada
                }
            });
    
            if (searchInput.value !== '') {
                searchBtn.classList.add('hidden');
                searchInput.classList.remove('hidden');
            }
        });
    </script>
@endsection