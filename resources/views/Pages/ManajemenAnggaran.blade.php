@extends('Layout.layout')

@section('title', 'Manajemen Anggaran')

@section('body')
<div class="flex">

    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC]">

        {{-- HEADER --}}
        <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Manajemen Anggaran</h1>
                <p class="text-sm text-gray-500 font-poppins">Kelola anggaran bulanan Anda</p>
            </div>

            <div class="flex items-center gap-5">
                {{-- 🔍 Search Button --}}
                <button id="search-icon-btn" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
                    </svg>
                </button>

                {{-- 🔹 Input Search --}}
                <input type="text" name="search" id="search-input-field"
                    form="filterForm" placeholder="Cari & tekan Enter"
                    value="{{ request('search') }}"
                    class="hidden w-48 text-sm outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 border rounded-lg px-3 py-1.5 shadow-sm">

                {{-- User Info --}}
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

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <form method="GET" action="{{ route('anggaran.index') }}" id="filterForm"
                    class="flex items-center gap-3">

                    <select name="periode_filter" onchange="submitFilterForm()"
                        class="border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                        <option value="">Semua Periode</option>
                        @foreach ($periodeList as $periode)
                            <option value="{{ $periode }}" {{ request('periode_filter') == $periode ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y') }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="kategori_id" onchange="submitFilterForm()"
                        class="border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->id }}"
                                {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->kategori }}
                            </option>
                        @endforeach
                    </select>

                    <a href="{{ route('anggaran.index') }}"
                        class="flex items-center text-sm text-indigo-600 hover:text-indigo-800 ml-2 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Reset
                    </a>
                </form>

                <button onclick="openForm()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins text-sm flex items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Anggaran
                </button>
            </div>

            <div class="bg-white shadow-md overflow-hidden">
                <table class="min-w-full table-auto border-1 border-gray-300">
                    <thead>
                        <tr class="bg-blue-200">
                            <th class="px-4 py-2 text-center font-poppins">Kategori</th>
                            <th class="px-4 py-2 text-center font-poppins">Jumlah (Rp)</th>
                            <th class="px-4 py-2 text-center font-poppins">Periode</th>
                            <th class="px-4 py-2 text-center font-poppins">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggaran as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-center font-poppins">
                                    {{ $item->kategori->kategori ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center font-poppins">
                                    Rp {{ number_format($item->jmlh_anggaran, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-center font-poppins">
                                    {{ \Carbon\Carbon::parse($item->periode . '-01')->translatedFormat('F Y') }}
                                </td>
                                <td class="text-center">
                                    <div class="flex justify-center items-center gap-3">
                                        <button 
                                            onclick="editForm({{ $item->id }}, {{ $item->id_kategori }}, {{ $item->jmlh_anggaran }}, '{{ $item->periode }}')" 
                                            class="bg-green-400 hover:bg-green-600 text-white p-1 rounded-md flex items-center justify-center">
                                            <i class='bx bxs-edit text-2xl'></i>
                                        </button>

                                        <form action="{{ route('anggaran.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="bg-red-400 hover:bg-red-600 text-white p-1 rounded-md flex items-center justify-center cursor-pointer delete-btn">
                                                <i class='bx bx-trash text-2xl'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500 font-poppins">Belum ada anggaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="formModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-[90%] md:w-[400px] text-left">
        <h2 id="formTitle" class="text-xl font-semibold mb-4">Tambah Anggaran</h2>

        <form id="anggaranForm" method="POST" action="{{ route('anggaran.store') }}">
            @csrf
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="mb-3">
                <label class="block mb-1 font-medium">Kategori</label>
                <select name="id_kategori" id="kategoriSelect" class="border rounded p-2 w-full">
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Jumlah Anggaran</label>
                <input type="number" name="jmlh_anggaran" id="jmlhInput" class="border rounded p-2 w-full" required>
            </div>

            <div class="mb-3">
                <label for="periode" class="block mb-1 font-medium">Periode</label>
                <input type="month" name="periode" id="periode" class="border rounded p-2 w-full" required>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" onclick="closeForm()"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">Batal</button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="confirmModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96 text-center">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
        <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus anggaran ini?</p>
        <div class="flex justify-center gap-4">
            <button id="cancelDelete"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">Tidak</button>
            <button id="confirmDelete"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
    function submitFilterForm() {
        document.getElementById('filterForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchBtn = document.getElementById('search-icon-btn');
        const searchInput = document.getElementById('search-input-field');

        searchBtn.addEventListener('click', function () {
            searchBtn.classList.add('hidden');
            searchInput.classList.remove('hidden');
            searchInput.focus();
        });

        searchInput.addEventListener('blur', function () {
            if (searchInput.value === '') {
                searchInput.classList.add('hidden');
                searchBtn.classList.remove('hidden');
            }
        });

        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitFilterForm();
            }
        });

        if (searchInput.value !== '') {
            searchBtn.classList.add('hidden');
            searchInput.classList.remove('hidden');
        }
    });

    const modal = document.getElementById('formModal');
    const form = document.getElementById('anggaranForm');
    const title = document.getElementById('formTitle');
    const method = document.getElementById('formMethod');

    function openForm() {
        form.action = "{{ route('anggaran.store') }}";
        method.value = "POST";
        title.textContent = "Tambah Anggaran";
        form.reset();
        modal.classList.remove('hidden');
    }

    function editForm(id, id_kategori, jmlh, periode) {
        form.action = `/anggaran/${id}`;
        method.value = "PUT";
        title.textContent = "Edit Anggaran";
        document.getElementById('kategoriSelect').value = id_kategori;
        document.getElementById('jmlhInput').value = jmlh;
        document.getElementById('periode').value = periode;
        modal.classList.remove('hidden');
    }

    function closeForm() {
        modal.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const confirmModal = document.getElementById('confirmModal');
        const cancelBtn = document.getElementById('cancelDelete');
        const confirmBtn = document.getElementById('confirmDelete');
        let formToSubmit = null;

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                formToSubmit = button.closest('form');
                confirmModal.classList.remove('hidden');
            });
        });

        cancelBtn.addEventListener('click', () => {
            confirmModal.classList.add('hidden');
            formToSubmit = null;
        });

        confirmBtn.addEventListener('click', () => {
            if (formToSubmit) formToSubmit.submit();
            confirmModal.classList.add('hidden');
        });

        confirmModal.addEventListener('click', (e) => {
            if (e.target === confirmModal) confirmModal.classList.add('hidden');
        });
    });
</script>
@endsection
