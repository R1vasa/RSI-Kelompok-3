@extends('Layout.layout')

@section('title', 'Tambah Kas')

@section('body')
    <div class="flex">
        <x-sidebar></x-sidebar>
        <div class="flex-1 ml-[20%] min-h-screen font-poppins">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">{{ $forums->forum }}</h1>
                @endauth
            </div>
            <div class="bg-white p-6 over">
                <form action="{{ route('edit.trans', ['slug' => $forums->slug, 'id' => $trans->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Judul Transaksi</label>
                        <div class="mx-4">
                            <input type="text" name="nama"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                value="{{ $trans->nama }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jenis</label>
                        <div class="mx-4">
                            <select name="jenis"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                                <option value="pemasukan">
                                    Pemasukan</option>
                                <option value="pengeluaran">
                                    Pengeluaran</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jumlah (Rp)</label>
                        <div class="mx-4">
                            <input type="number" name="nominal"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                value="{{ $trans->nominal }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="deskripsi">Deskripsi</label>
                        <div class="mx-4">
                            <textarea name="deskripsi" class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                rows="2" required id="deskripsi" value='{{ $trans->deskripsi }}'>{{ old('deskripsi', $trans->deskripsi ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="tgl">Tanggal</label>
                        <div class="mx-4">
                            <input type="date" name="tgl_transaksi" id="tgl"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                value="{{ $trans->tgl_transaksi }}" required>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('forum.trans', ['slug' => $forums->slug]) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
