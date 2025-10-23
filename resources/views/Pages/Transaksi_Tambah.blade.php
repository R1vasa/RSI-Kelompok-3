@extends('Layout.layout')

@section('title', 'Tambah Transaksi')
<div class="flex">

    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen">
        <div class="bg-[#F8FAFC] flex items-center p-1">
            @auth
                <h1 class="p-4 font-semibold font-poppins text-2xl">Welcome, {{ Auth::user()->nama }}</h1>
            @endauth
        </div>
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('transaksi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block font-medium">Judul Transaksi</label>
                    <input type="text" name="judul_transaksi" class="w-full border rounded-full px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block font-medium">Kategori</label>
                    <select name="id_kategori" class="w-full border rounded-full px-3 py-2" required>
                        <option value="1">Jajan</option>
                        <option value="2">Service</option>
                        <option value="4">Transportasi</option>
                        <option value="3">Makan</option>
                        <option value="5">Lain-lain</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block font-medium">Jenis</label>
                    <select name="jenis_transaksi" class="w-full border rounded-full px-3 py-2" required>
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block font-medium">Jumlah (Rp)</label>
                    <input type="number" name="jumlah_transaksi" class="w-full border rounded-full px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block font-medium">Tanggal</label>
                    <input type="date" name="tgl_transaksi" class="w-full border rounded-full px-3 py-2" required>
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
