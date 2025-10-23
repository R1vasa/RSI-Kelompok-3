@extends('Layout.layout')

@section('title', 'Edit Goals')

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
                <h1 class="text-2xl font-bold mb-4">Edit Transaksi</h1>
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
                        <ul class="list-disc pl-6">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('goals.update', $goals->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="block font-medium">Judul goals</label>
                        <input type="text" name="judul_goals" class="w-full border rounded px-3 py-2"
                            value="{{ old('judul_goals', $goals->judul_goals) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah Target(Rp)</label>
                        <input type="number" name="jumlah_target" class="w-full border rounded px-3 py-2"
                            value="{{ old('jumlah_target', $goals->jumlah_target) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tgl_target" class="w-full border rounded px-3 py-2"
                            value="{{ old('tgl_target', $goals->tgl_target) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Gambar Goals (opsional)</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full border rounded px-3 py-2">

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
