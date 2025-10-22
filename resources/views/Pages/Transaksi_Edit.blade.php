@extends('Layout.layout')

@section('title', 'Edit Transaksi')

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
                <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="block font-medium">Judul Transaksi</label>
                        <input type="text" name="judul_transaksi" class="w-full border rounded px-3 py-2"
                            value="{{ old('judul_transaksi', $transaksi->judul_transaksi) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium">Kategori</label>
                        <select name="id_kategori" class="w-full border rounded px-3 py-2" required>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ old('id_kategori', $transaksi->id_kategori) == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium">Jenis</label>
                        <select name="jenis_transaksi" class="w-full border rounded px-3 py-2" required>
                            <option value="pemasukan"
                                {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'Pemasukan' ? 'selected' : '' }}>
                                Pemasukan</option>
                            <option value="pengeluaran"
                                {{ old('jenis_transaksi', $transaksi->jenis_transaksi) == 'Pengeluaran' ? 'selected' : '' }}>
                                Pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah (Rp)</label>
                        <input type="number" name="jumlah_transaksi" class="w-full border rounded px-3 py-2"
                            value="{{ old('jumlah_transaksi', $transaksi->jumlah_transaksi) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tgl_transaksi" class="w-full border rounded px-3 py-2"
                            value="{{ old('tgl_transaksi', $transaksi->tgl_transaksi) }}" required>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('transaksi.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
