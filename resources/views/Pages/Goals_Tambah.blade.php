@extends('Layout.layout')

@section('title', 'Tambah Goals')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Welcome, {{ Auth::user()->nama }}</h1>
                @endauth
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('goals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="block font-medium">Judul Goals</label>
                        <input type="text" name="judul_goals" class="w-full border rounded-full px-3 py-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium">Jumlah Target(Rp)</label>
                        <input type="number" name="jumlah_target" class="w-full border rounded-full px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal Target</label>
                        <input type="date" name="tgl_target" class="w-full border rounded-full px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Gambar Goals (opsional)</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('goals.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
