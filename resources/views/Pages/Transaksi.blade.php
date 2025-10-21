<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    @vite('resources/css/app.css')
    <style>
        .font-noto {
            font-family: 'Noto Sans', sans-serif;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <div class="flex">
        <div class="bg-linear-to-t from-[#1C69E6] to-[#3B82F6] h-dvh w-1/5 p-1 flex flex-col fixed">
            <div>
                <h1 class="font-noto text-2xl font-bold text-white p-4">FinTrack</h1>
                <hr class="border-t border-blue-400 opacity-100">
            </div>
            <div class="flex-1 overflow-y-auto">
                <div class="space-y-4 pl-2 font-noto font-semibold">
                    <h2 class="text-lg font-bold text-blue-200 mt-4">Keuangan Pribadi</h2>
                    <nav class="space-y-3 pl-4 text-white">
                        <a href="{{route ('transaksi.index')}}"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Transaksi</a>
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Anggaran</a>
                        <a href="{{route ('goals.index')}}"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Target
                            Goals</a>
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Laporan</a>
                    </nav>
                </div>

                <hr class="border-t border-blue-400 opacity-100 my-6">

                <div class="space-y-4 pl-2 font-noto font-semibold">
                    <h2 class="text-lg font-bold text-blue-200">Keuangan Organisasi</h2>
                    <nav class="space-y-2 pl-4 text-white">
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Forum
                            Organisasi</a>
                    </nav>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="flex justify-center items-center mt-auto p-4">
                @csrf
                <button type="submit" class="cursor-pointer">logout</button>
            </form>
        </div>
        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Daftar Transaksi</h1>
                </div>

        @if (session('success'))
    <!-- Modal Background -->
    <div id="success-modal"
         class="fixed inset-0 flex items-center justify-center z-50">
        <!-- Modal Box -->
        <div class="bg-white rounded-2xl shadow-lg p-6 w-80 text-center animate-fade-in">
            <div class="flex justify-center mb-3">
                <!-- Ikon centang -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
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
                        <th class="px-4 py-2 text-center font-poppins"></th>
                        <th class="px-4 py-2 text-center font-poppins"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksis as $transaksi)
                        <tr class="border-b">
                            <td class="px-4 py-2 font-poppins">{{ $transaksi->judul_transaksi }}</td>
                            <td class="px-4 py-2 font-poppins">{{ $transaksi->kategori->kategori }}</td>
                            <td class="px-4 py-2 font-poppins">{{ ucfirst($transaksi->jenis_transaksi) }}</td>
                            <td class="px-4 py-2 font-poppins">Rp {{ number_format($transaksi->jumlah_transaksi, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 font-poppins">{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d-m-Y') }}</td>
                            
                            <td class="flex justify-center gap-3">
                            <a href="{{ route('transaksi.edit', $transaksi->id) }}" 
                            class="bg-green-400 hover:bg-green-600 text-white p-2 rounded-md font-poppins flex items-center justify-center">
                    <img src="https://img.icons8.com/?size=100&id=114092&format=png&color=000000"
                        alt="Edit" class="w-5 h-5">
                            </a>

                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Yakin ingin hapus goal ini?')"
                        class="bg-red-400 hover:bg-red-600 text-white p-2 rounded-md font-poppins flex items-center justify-center">
                    <img src="https://img.icons8.com/?size=100&id=9deX0HJ5iAFS&format=png&color=000000"
                        alt="Hapus" class="w-5 h-5">
                </button>
                            </form>
                            </td>
                    @endforeach
                </tbody>
            </table>

    </div>
</body>
