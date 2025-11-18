@extends('Layout.layout')
{{-- Meng-extend layout utama dari folder Layout --}}

@section('title', 'Transaksi')
{{-- Mengatur judul halaman browser menjadi "Transaksi" --}}

@section('body')
    <div class="flex">

        {{-- Sidebar navigasi utama --}}
        <x-sidebar></x-sidebar>

        {{-- KONTEN UTAMA --}}
        <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC]">

            {{-- HEADER ATAS --}}
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Transaksi</h1>
                    <p class="text-sm text-gray-500 font-poppins">Memantau aktivitas keuangan anda</p>
                </div>

                {{-- Bagian kanan header: tombol search dan profil --}}
                <div class="flex items-center gap-5">

                    {{-- 1️⃣ Tombol ikon search --}}
                    <button id="search-icon-btn" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                        </svg>
                    </button>

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

            {{-- ✅ Modal sukses dan peringatan anggaran --}}
            @if (session('success') || session('warning'))
                <div id="modal-wrapper" class="fixed inset-0 flex items-center justify-center z-50 space-x-4">

                    {{-- Modal sukses --}}
                    @if (session('success'))
                        <div id="success-modal" class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in">
                            <div class="flex justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Berhasil!</h2>
                            <p class="text-gray-600 mt-1">{{ session('success') }}</p>
                        </div>
                    @endif

                    {{-- Modal peringatan --}}
                    @if (session('warning'))
                        <div id="warning-modal"
                            class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in border-2 border-yellow-400 hidden">
                            <div class="flex justify-center mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-800">Peringatan!</h2>
                            <p class="text-gray-600 mt-1">{{ session('warning') }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- 🔧 Script animasi modal --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const successModal = document.getElementById('success-modal');
                    const warningModal = document.getElementById('warning-modal');
                    const wrapper = document.getElementById('modal-wrapper');
                    const duration = 2500; // durasi tampil modal (ms)

                    // Jika ada success dan warning muncul bergantian
                    if (successModal && warningModal) {
                        setTimeout(() => {
                            successModal.classList.add('opacity-0', 'transition', 'duration-500');
                            setTimeout(() => {
                                successModal.remove();
                                warningModal.classList.remove('hidden');
                                setTimeout(() => {
                                    warningModal.classList.add('opacity-0', 'transition', 'duration-500');
                                    setTimeout(() => wrapper.remove(), 500);
                                }, duration);
                            }, 500);
                        }, duration);
                    } 
                    // Jika hanya satu modal
                    else if (successModal || warningModal) {
                        const modal = successModal || warningModal;
                        setTimeout(() => {
                            modal.classList.add('opacity-0', 'transition', 'duration-500');
                            setTimeout(() => wrapper.remove(), 500);
                        }, duration);
                    }
                });
            </script>

            {{-- CSS animasi fade in modal --}}
            <style>
            @keyframes fade-in {
                from { opacity: 0; transform: scale(0.95); }
                to { opacity: 1; transform: scale(1); }
            }
            .animate-fade-in {
                animation: fade-in 0.4s ease-out;
            }
            </style>

            {{-- 🔹 BAGIAN FILTER TRANSAKSI --}}
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">

                    {{-- Form filter transaksi --}}
                    <form method="GET" action="{{ route('transaksi.index') }}" class="flex items-center gap-2" id="filterForm">

                        {{-- Filter: Rentang tanggal --}}
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

                        {{-- Filter: Jenis transaksi --}}
                        <select name="jenis_transaksi" onchange="submitFilterForm()"
                            class="border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan" {{ request('jenis_transaksi') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ request('jenis_transaksi') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>

                        {{-- Filter: Kategori --}}
                        <select name="kategori_id" onchange="submitFilterForm()"
                            class="border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris ?? [] as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Tombol reset filter --}}
                        <a href="{{ route('transaksi.index') }}" class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 ml-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Reset
                        </a>
                    </form>

                    {{-- Tombol Ekspor PDF dan Tambah Transaksi --}}
                    <dphp iv class="flex items-center gap-3">
                        {{-- Tombol Ekspor --}}
                        <form action="{{ route('laporan.export.pdf') }}" method="GET">
                            <input type="hidden" name="date_range" value="{{ request('date_range') }}">
                            <input type="hidden" name="jenis_transaksi" value="{{ request('jenis_transaksi') }}">
                            <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center">
                                <i class='bx bx-file mr-2'></i> Ekspor PDF
                            </button>
                        </form>

                        {{-- Tombol Tambah --}}
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

                {{-- 🔹 TABEL DATA TRANSAKSI --}}
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
                            @forelse ($transaksis as $transaksi)
                                <tr class="hover:bg-gray-50">
                                    {{-- Kolom judul transaksi --}}
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->judul_transaksi }}</td>

                                    {{-- Kolom kategori --}}
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->kategori->kategori }}</td>

                                    {{-- Kolom jenis transaksi --}}
                                    <td class="px-4 py-2 font-poppins text-center">{{ ucfirst($transaksi->jenis_transaksi) }}</td>

                                    {{-- Kolom jumlah transaksi --}}
                                    <td class="px-4 py-2 font-poppins text-center">
                                        Rp {{ number_format($transaksi->jumlah_transaksi, 0, ',', '.') }}
                                    </td>

                                    {{-- Kolom tanggal transaksi --}}
                                    <td class="px-4 py-2 font-poppins text-center">
                                        {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d-m-Y') }}
                                    </td>

                                    {{-- Kolom aksi edit & hapus --}}
                                    <td class="text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            {{-- Tombol edit --}}
                                            <a href="{{ route('transaksi.edit', $transaksi->id) }}"
                                                class="bg-green-400 hover:bg-green-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                                <i class='bx bxs-edit text-2xl'></i>
                                            </a>

                                            {{-- Tombol hapus dengan modal konfirmasi --}}
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
                           @empty
                                {{-- ✅ INI BAGIAN BARU UNTUK MENAMPILKAN PESAN KOSONG --}}
                                <tr>
                                    <td colspan="6" class="text-center text-gray-500 py-4 font-poppins">
                                        Transaksi tidak ada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔹 Modal konfirmasi hapus --}}
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

    {{-- 🔸 Script untuk modal hapus --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirmModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            let formToSubmit = null;

            // Saat tombol hapus diklik
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    formToSubmit = button.closest('form');
                    modal.classList.remove('hidden');
                });
            });

            // Tombol batal
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                formToSubmit = null;
            });

            // Tombol konfirmasi hapus
            confirmBtn.addEventListener('click', () => {
                if (formToSubmit) formToSubmit.submit();
                modal.classList.add('hidden');
            });

            // Klik di luar modal = tutup modal
            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>

    {{-- 🔸 Script untuk auto-submit filter --}}
    <script>
        function submitFilterForm() {
            setTimeout(() => document.getElementById('filterForm').submit(), 100);
        }
    </script>

    {{-- 🔸 Script Litepicker (kalender tanggal) --}}
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
                        if (date1 && date2)
                            hidden.value = date1.format('YYYY-MM-DD') + ' to ' + date2.format('YYYY-MM-DD');
                        else
                            hidden.value = '';
                        submitFilterForm();
                    });
                },
            });
        });
    </script>

    {{-- 🔸 Script input search toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('search-icon-btn');
            const searchInput = document.getElementById('search-input-field');

            // Tampilkan input search saat ikon diklik
            searchBtn.addEventListener('click', function() {
                searchBtn.classList.add('hidden');
                searchInput.classList.remove('hidden');
                searchInput.focus();
            });

            // Sembunyikan input jika dikosongkan
            searchInput.addEventListener('blur', function() {
                if (searchInput.value === '') {
                    searchInput.classList.add('hidden');
                    searchBtn.classList.remove('hidden');
                }
            });

            // Tekan Enter untuk cari langsung
            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    submitFilterForm();
                }
            });

            // Jika ada nilai pencarian sebelumnya, tampilkan input
            if (searchInput.value !== '') {
                searchBtn.classList.add('hidden');
                searchInput.classList.remove('hidden');
            }
        });
    </script>
@endsection

