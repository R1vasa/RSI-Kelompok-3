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
                {{-- Header Forum --}}
                <div class="flex gap-4">
                    <img src="{{ asset('storage/' . $forums->gambar_forum) }}"
                        class="h-30 w-30 object-cover rounded-full border-2 border-gray-300 p-1 mb-4" alt="">
                    <div class="w-lg mt-3">
                        <h1 class="font-bold text-2xl mb-2">{{ $forums->forum }}</h1>
                        <p class="max-w-lg text-md">{{ $forums->deskripsi }}</p>
                    </div>
                </div>

                {{-- Filter dan Aksi --}}
                <div class="flex justify-between items-center p-2">
                    <form method="GET" action="{{ route('forum.trans', $forums->slug) }}" id="filterForm"
                        class="flex items-center gap-2">
                        <div
                            class="flex items-center border bg-white rounded-lg px-3 py-2 text-sm text-gray-600 shadow-sm cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <input id="datepicker" type="text" placeholder="Pilih periode"
                                class="outline-none border-none p-0 w-48 text-sm"
                                value="{{ $periodeAwal && $periodeAkhir ? \Carbon\Carbon::parse($periodeAwal)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($periodeAkhir)->format('d M Y') : '' }}">
                            <input type="hidden" name="date_range" id="date_range_hidden"
                                value="{{ $periodeAwal && $periodeAkhir ? $periodeAwal . ' to ' . $periodeAkhir : '' }}">
                        </div>
                    </form>

                    {{-- Tombol Aksi Bendahara --}}
                    @if ($akses->role == 'bendahara')
                        <div class="flex gap-2">
                            <a href="{{ route('tambah.trans.index', ['slug' => $forums->slug]) }}"
                                class="px-5 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition">
                                Tambah transaksi
                            </a>

                            <form action="{{ route('forum.laporan.export', ['slug' => $forums->slug]) }}" method="GET">
                                <input type="hidden" name="periode" value="{{ request('date_range') }}">
                                <button type="submit"
                                    class="flex items-center gap-2 px-5 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                    <i class='bx bx-export text-lg'></i>
                                    <span>Ekspor PDF</span>
                                </button>
                            </form>

                        </div>
                    @endif
                </div>

                {{-- Informasi Periode --}}
                <div class="px-4 mb-4 text-sm text-gray-700 font-medium">
                    @if ($periodeAwal && $periodeAkhir)
                        Periode: {{ \Carbon\Carbon::parse($periodeAwal)->translatedFormat('d M Y') }} –
                        {{ \Carbon\Carbon::parse($periodeAkhir)->translatedFormat('d M Y') }}
                    @else
                        Menampilkan semua transaksi
                    @endif
                </div>

                {{-- Tabel Transaksi --}}
                <div class="px-6 py-2">
                    <table class="min-w-full table-auto border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                        <thead>
                            <tr class="bg-blue-200 text-gray-800 text-sm uppercase">
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
                            @forelse ($trans as $Trans)
                                <tr class="hover:bg-blue-50 transition">
                                    <td class="px-4 py-2 text-center font-semibold">{{ $Trans->nama }}</td>
                                    <td class="px-4 py-2 text-center relative group text-gray-700">
                                        {{ Str::limit($Trans->deskripsi, 25) }}
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 -top-8 bg-gray-800 text-white text-xs rounded-md px-2 py-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ $Trans->deskripsi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center text-gray-600">
                                        {{ \Carbon\Carbon::parse($Trans->tgl_transaksi)->format('d M Y') }}
                                    </td>

                                    @if ($Trans->jenis == 'pemasukan')
                                        <td class="px-4 py-2 text-center text-green-600 font-semibold">
                                            + Rp {{ number_format($Trans->nominal, 0, ',', '.') }}
                                        </td>
                                    @else
                                        <td class="px-4 py-2 text-center text-red-600 font-semibold">
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
                            @empty
                                <tr>
                                    <td colspan="{{ $akses->role == 'bendahara' ? 5 : 4 }}"
                                        class="text-center py-4 text-gray-500 italic">
                                        Tidak ada transaksi pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Ringkasan Saldo --}}
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

    {{-- 🔔 Modal konfirmasi hapus --}}
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

    {{-- Script Konfirmasi Hapus dan Filter Periode --}}
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
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
                if (formToSubmit) formToSubmit.submit();
                modal.classList.add('hidden');
            });

            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });

            // 📅 Filter Periode
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
                        if (date1 && date2) {
                            hidden.value = date1.format('YYYY-MM-DD') + ' to ' + date2.format(
                                'YYYY-MM-DD');
                            document.getElementById('filterForm').submit();
                        }
                    });
                },
            });
        });
    </script>
@endsection
