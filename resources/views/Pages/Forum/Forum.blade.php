@extends('Layout.layout')

@section('title', 'Forum')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Welcome, {{ Auth::user()->nama }}</h1>
                @endauth
            </div>

            <div class="p-6">

                <div class="flex items-center mb-6 flex-row-reverse gap-2">
                    <a href="{{ route('forum.add') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins">Tambah
                        Forum</a>
                    <button id="openModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-poppins">Join
                        Forum</button>
                </div>
                @foreach ($forums as $forum)
                    <div class="bg-[#F2F9FF] shadow-md rounded-full p-2 flex items-center space-x-4 mb-4">
                        <img src="{{ asset('storage/' . $forum->gambar_forum) }}" alt=""
                            class="w-18 h-18 rounded-full border-1 border-gray-300 object-cover p-1">
                        <div class="flex justify-center items-center gap-10">
                            <div class="grid-rows-2">
                                <h1 class="font-bold text-xl">{{ $forum->forum }}</h1>
                                <h1 class="max-w-lg text-md">{{ $forum->deskripsi }}</h1>
                            </div>
                            <div class="flex items-center space-x-4 absolute right-12">
                                @if ($forum->anggota->where('id_users', Auth::id())->first()?->role === 'bendahara')
                                    <button data-target="modalLink{{ $loop->index }}"
                                        class="bg-blue-500 w-10 h-10 rounded-full cursor-pointer"><i
                                            class='bx bx-link-alt text-xl text-white'></i></button>
                                @endif
                                <a href="{{ route('forum.kas', ['slug' => $forum->slug]) }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white w-32 text-center px-4 py-2 rounded-lg font-semibold text-md transition duration-150">
                                    Kas
                                </a>

                                <a href="{{ route('forum.trans', ['slug' => $forum->slug]) }}"
                                    class="bg-emerald-400 hover:bg-emerald-500 text-white w-32 text-center px-4 py-2 rounded-lg font-semibold text-md transition duration-150">
                                    Transaksi
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="modalLink{{ $loop->index }}"
                        class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">
                        <div class="bg-white rounded-xl shadow-lg p-8 w-[400px] relative">

                            <button id="closeModalLinkX" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                                ✕
                            </button>

                            <h2 class="text-xl font-semibold text-gray-800 mb-3 text-center">
                                Link Forum
                            </h2>
                            <p class="text-gray-500 text-sm mb-5 text-center">
                                Salin kode forum untuk akses join forum
                            </p>
                            <div>
                                <h1 class="bg-secondary p-3">{{ $forum->link_akses }}</h1>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div id="modal" class="fixed inset-0 bg-black/50 hidden justify-center items-center z-50">
                <div class="bg-white rounded-xl shadow-lg p-8 w-[400px] relative">

                    <button id="closeModalX" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
                        ✕
                    </button>

                    <h2 class="text-xl font-semibold text-gray-800 mb-3 text-center">
                        Gabung ke Forum
                    </h2>
                    <p class="text-gray-500 text-sm mb-5 text-center">
                        Masukkan kode akses forum yang kamu dapatkan
                    </p>

                    <form action="{{ route('forum.join.submit') }}" method="POST">
                        @csrf
                        <input type="text" name="link_akses"
                            class="w-full border rounded-lg px-4 py-2 mb-4 focus:ring focus:ring-blue-200"
                            placeholder="Contoh: Np6xQ2LJrWz7cDf" required>

                        @error('link_akses')
                            <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full font-medium">
                            Gabung Forum
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Modal Join Forum ===
            const modalJoin = document.getElementById('modal');
            const openJoinBtn = document.getElementById('openModal');
            const closeJoinBtn = document.getElementById('closeModalX');

            openJoinBtn.addEventListener('click', () => {
                modalJoin.classList.remove('hidden');
                modalJoin.classList.add('flex');
            });

            closeJoinBtn.addEventListener('click', () => {
                modalJoin.classList.add('hidden');
                modalJoin.classList.remove('flex');
            });

            modalJoin.addEventListener('click', (e) => {
                if (e.target === modalJoin) {
                    modalJoin.classList.add('hidden');
                    modalJoin.classList.remove('flex');
                }
            });

            // === Modal Link Forum (Loop) ===
            document.querySelectorAll('[data-target]').forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    const modal = document.getElementById(targetId);
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            document.querySelectorAll('#closeModalLinkX').forEach(closeBtn => {
                closeBtn.addEventListener('click', (e) => {
                    const modal = e.target.closest('.fixed');
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });

            document.querySelectorAll('[id^="modalLink"]').forEach(modal => {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            });
        });
    </script>


@endsection
