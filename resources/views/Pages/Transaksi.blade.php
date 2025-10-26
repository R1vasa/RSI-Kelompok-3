@extends('Layout.layout')

@section('title', 'Transaksi')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                <h1 class="p-4 font-semibold font-poppins text-2xl">Daftar Transaksi</h1>
            </div>

            @if (session('success'))
                <!-- Modal Background -->
                <div id="success-modal" class="fixed inset-0 flex items-center justify-center z-50">
                    <!-- Modal Box -->
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
                    /* Animasi muncul */
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

            {{-- Header + Tombol Tambah --}}

            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('transaksi.create') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full font-poppins">Tambah
                        Transaksi</a>
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
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->judul_transaksi }}
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center">{{ $transaksi->kategori->kategori }}
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center">
                                        {{ ucfirst($transaksi->jenis_transaksi) }}</td>
                                    <td class="px-4 py-2 font-poppins text-center">Rp
                                        {{ number_format($transaksi->jumlah_transaksi, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 font-poppins text-center">
                                        {{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d-m-Y') }}</td>

                                    <td class="text-center">
                                        <div class="flex justify-center items-center gap-3">
                                            <a href="{{ route('transaksi.edit', $transaksi->id) }}"
                                                class="bg-green-400 hover:bg-green-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                                <i class='bx bxs-edit text-2xl'></i>
                                            </a>

                                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Yakin ingin hapus goal ini?')"
                                                    class="bg-red-400 hover:bg-red-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                                    <i class='bx bx-trash text-2xl'></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
