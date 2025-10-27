@extends('Layout.layout')
@section('title', 'Forum Kas')

@section('body')
    <div class="flex">
        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen font-poppins">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold text-2xl">{{ $forums->forum }}</h1>
                @endauth
            </div>

            <div class="p-6">
                <div class="flex gap-4">
                    <img src="{{ asset('storage/' . $forums->gambar_forum) }}"
                        class="h-30 w-30 object-cover rounded-full border-2 border-gray-300 p-1 mb-4" alt="">
                    <div class="w-lg mt-3">
                        <h1 class="font-bold text-2xl mb-2">{{ $forums->forum }}</h1>
                        <p class="max-w-lg text-md">{{ $forums->deskripsi }}</p>
                    </div>
                </div>

                <div class="flex justify-between p-2">
                    <h1 class="text-sm font-semibold">Periode 1 - 30 November 2023</h1>
                    @if ($akses->role == 'bendahara')
                        <div>
                            <a href="{{ route('tambah.trans.index', ['slug' => $forums->slug]) }}"
                                class="px-5 py-2 bg-emerald-500 text-white rounded-xs">Tambah transaksi</a>
                            <a href="#" class="px-5 py-2 bg-yellow-500 text-white rounded-xs">Ekspor</a>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-2">
                    <table class="min-w-full table-auto border-1 border-gray-300">
                        <thead>
                            <tr class="bg-blue-200">
                                <th class="px-4 py-2 text-center">Judul</th>
                                <th class="px-4 py-2 text-center">Deskripsi</th>
                                <th class="px-4 py-2 text-center">Tanggal</th>
                                <th class="px-4 py-2 text-center">Jumlah</th>
                                @if ($akses->role == 'bendahara')
                                    <th class="px-4 py-2 text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trans as $Trans)
                                <tr>
                                    <td class="px-4 py-2 text-center">{{ $Trans->nama }}</td>
                                    <td class="px-4 py-2 text-center relative group">
                                        {{ Str::limit($Trans->deskripsi, 20) }}
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 -top-8 bg-gray-800 text-white text-xs rounded-md px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ $Trans->deskripsi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($Trans->tgl_transaksi)->format('d-m-Y') }}
                                    </td>
                                    @if ($Trans->jenis == 'pemasukan')
                                        <td class="px-4 py-2 text-center text-green-500">
                                            + Rp {{ number_format($Trans->nominal, 0, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="px-4 py-2 text-center text-red-500">
                                            - Rp {{ number_format($Trans->nominal, 0, ',', '.') }}
                                        </td>
                                    @endif
                                    @if ($akses->role == 'bendahara')
                                        <td class="text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('edit.trans.index', ['slug' => $forums->slug, 'id' => $Trans->id]) }}"
                                                    class="bg-green-400 hover:bg-green-600 text-white p-1 rounded-md">
                                                    <i class='bx bxs-edit text-2xl'></i>
                                                </a>

                                                <form
                                                    action="{{ route('forum.transaksi.destroy', ['slug' => $forums->slug, 'id' => $Trans->id]) }}"
                                                    method="POST" class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="delete-btn bg-red-400 hover:bg-red-600 text-white p-1 rounded-md">
                                                        <i class='bx bx-trash text-2xl'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Ringkasan saldo --}}
                <div class="mt-6 bg-white shadow-md rounded-lg p-4 w-1/2 mx-auto">
                    <table class="w-full text-sm">
                        <tr>
                            <td class="font-semibold">Pemasukan</td>
                            <td class="text-green-600 font-bold text-right">
                                + Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Pengeluaran</td>
                            <td class="text-red-600 font-bold text-right">
                                - Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-t border-gray-300">
                            <td class="font-semibold pt-2">Saldo Akhir</td>
                            <td class="font-bold text-right pt-2">
                                Rp. {{ number_format($saldoAkhir, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔔 Modal konfirmasi hapus (satu saja, di luar foreach) --}}
    <div id="confirmModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden flex items-center justify-center z-50">
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

            // Buka modal
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    formToSubmit = button.closest('form');
                    modal.classList.remove('hidden');
                });
            });

            // Batal hapus
            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                formToSubmit = null;
            });

            // Konfirmasi hapus
            confirmBtn.addEventListener('click', () => {
                if (formToSubmit) formToSubmit.submit();
                modal.classList.add('hidden');
            });

            // Tutup modal saat klik luar area
            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    </script>
@endsection
