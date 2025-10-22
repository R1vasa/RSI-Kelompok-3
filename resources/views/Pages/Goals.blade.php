@extends('Layout.layout')

@section('title', 'Goals')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                <h1 class="text-2xl font-bold font-poppins p-4">Daftar Goals</h1>
            </div>


            {{-- Pesan sukses --}}
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
            <div class="flex justify-between items-center my-6 px-6">
                <a href="{{ route('goals.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full font-poppins">
                    + Tambah Goals
                </a>
            </div>

            {{-- Daftar Goals --}}
            @foreach ($goals as $goals)
                @php
                    $progress = 0;
                    if ($goals->jumlah_target > 0) {
                        $progress = ($goals->current_amount / $goals->jumlah_target) * 100;
                    }
                    if ($progress > 100) {
                        $progress = 100;
                    }

                    // Tambahkan logika status tercapai
                    $status = $goals->current_amount >= $goals->jumlah_target ? 'Tercapai' : 'Belum Tercapai';
                @endphp

                {{-- Kartu Goal --}}
                <div class="flex justify-between bg-[#F4F9FF] rounded-full p-1 m-6 px-6 items-center shadow-md">

                    {{-- Kiri: Gambar --}}
                    <div class="flex items-center space-x-4">
                        <img src="{{ $goals->gambar ? asset('storage/' . $goals->gambar) : 'https://cdn-icons-png.flaticon.com/512/4228/4228710.png' }}"
                            alt="goals icon" class="w-16 h-16 rounded-full border border-gray-300 object-cover">

                        {{-- Tengah: Judul + Progress --}}
                        <div>
                            <h3 class="font-poppins font-semibold text-lg flex items-center gap-2">
                                {{ $goals->judul_goals }}

                                {{-- Tambahkan status tercapai --}}
                                @if ($status === 'Tercapai')
                                    <span class="text-green-600 text-sm font-semibold">✅ {{ $status }}</span>
                                @else
                                    <span class="text-yellow-500 text-sm font-semibold">⏳ {{ $status }}</span>
                                @endif
                            </h3>

                            {{-- Progress bar --}}
                            <div class="w-156 bg-gray-400 rounded-full h-3 mt-2 overflow-hidden">
                                <div class="bg-[#5DD39E] h-3 rounded-full transition-all duration-500"
                                    style="width: {{ $progress }}%;"></div>
                            </div>

                            {{-- Jumlah --}}
                            <p class="text-sm text-gray-600 mt-1">
                                Rp {{ number_format($goals->current_amount, 0, ',', '.') }} /
                                Rp {{ number_format($goals->jumlah_target, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    {{-- Kanan: Tombol aksi --}}
                    <div class="flex items-center space-x-2 mr-5">
                        {{-- Tombol Setor (pindah ke halaman setor) --}}
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
                        @endif

                        {{-- Tombol Edit --}}
                        <a href="{{ route('goals.edit', $goals->id) }}"
                            class="bg-green-300 hover:bg-green-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                            <i class='bx bxs-edit text-2xl'></i>
                        </a>

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('goals.destroy', $goals->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin hapus goal ini?')"
                                class="bg-red-400 hover:bg-red-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                <i class='bx bx-trash text-2xl'></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endsection
