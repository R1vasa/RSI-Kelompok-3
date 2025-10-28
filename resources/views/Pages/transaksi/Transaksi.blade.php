@extends('Layout.layout')

@section('title', 'Transaksi')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                <h1 class="p-4 font-semibold font-poppins text-2xl">Daftar Transaksi</h1>
            </div>

            @if (session('debug'))
    <div class="bg-gray-100 border p-2 text-xs mt-2">
        <pre>{{ print_r(session('debug'), true) }}</pre>
    </div>
@endif

            {{-- Modal sukses dan peringatan anggaran--}}
            @if (session('success') || session('warning'))
                <div id="modal-wrapper" class="fixed inset-0 flex items-center justify-center z-50 space-x-4">
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

                    @if (session('warning'))
                        <div id="warning-modal" class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in border-2 border-yellow-400 hidden">
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

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const successModal = document.getElementById('success-modal');
                    const warningModal = document.getElementById('warning-modal');
                    const wrapper = document.getElementById('modal-wrapper');
                    const duration = 2500;

                    if (successModal && warningModal) {
                        // tampilkan success dulu
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
                    } else if (successModal || warningModal) {
                        const modal = successModal || warningModal;
                        setTimeout(() => {
                            modal.classList.add('opacity-0', 'transition', 'duration-500');
                            setTimeout(() => wrapper.remove(), 500);
                        }, duration);
                    }
                });
            </script>

            <style>
            @keyframes fade-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
            }
            .animate-fade-in {
            animation: fade-in 0.4s ease-out;
            }
            </style>

            {{-- Konten Utama --}}
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('transaksi.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full font-poppins">
                        Tambah Transaksi
                    </a>
                </div>

                <div class="bg-white shadow-md rounded-lg p-6">
                    <table class="min-w-full table-auto">
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
                                <tr class="border-b">
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->judul_transaksi }}</td>
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->kategori->kategori }}</td>
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

                                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST"
                                                class="inline delete-form">
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

    {{-- Modal Konfirmasi Hapus --}}
    <div id="confirmModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-lg p-6 w-96 text-center">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Hapus</h2>
            <p class="text-gray-600 mb-6">Apakah kamu yakin ingin menghapus transaksi ini?</p>
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
        document.addEventListener('DOMContentLoaded', function() {
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
                if (formToSubmit) {
                    formToSubmit.submit();
                }
                modal.classList.add('hidden');
            });

            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>
@endsection
