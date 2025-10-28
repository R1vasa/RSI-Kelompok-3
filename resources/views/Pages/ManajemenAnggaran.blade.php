@extends('Layout.layout')

@section('title', 'Manajemen Anggaran')

@section('body')
<div class="flex">
    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen">
        {{-- Header --}}
        <div class="bg-[#F8FAFC] flex items-center p-1">
            <h1 class="p-4 font-semibold font-poppins text-2xl">Manajemen Anggaran</h1>
        </div>

        {{-- Modal sukses --}}
        @if (session('success'))
            <div id="success-modal" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in">
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
                    from { opacity: 0; transform: scale(0.9); }
                    to { opacity: 1; transform: scale(1); }
                }
                .animate-fade-in { animation: fadeIn 0.4s ease-out; }
            </style>
        @endif

        {{-- Konten Utama --}}
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <button onclick="openForm()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full font-poppins">
                    Tambah Anggaran
                </button>
            </div>

            {{-- Tabel Data --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <table class="min-w-full table-auto">
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
                            <tr class="border-b">
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

                                        <form action="{{ route('anggaran.destroy', $item->id) }}" method="POST"
                                            class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                class="bg-red-400 hover:bg-red-600 text-white p-1 rounded-md flex items-center justify-center cursor-pointer delete-btn">
                                                <i class='bx bx-trash text-2xl'></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500 font-poppins">
                                    Belum ada anggaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Form Tambah/Edit --}}
<div id="formModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity duration-300">
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
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">
                    Batal
                </button>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div id="confirmModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96 text-center">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
        <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus anggaran ini?</p>
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

{{-- Script --}}
<script>
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

    // Modal hapus
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
