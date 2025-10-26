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
                <form action="{{ route('edit.kas', ['slug' => $forums->slug, 'id' => $kas->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Nama</label>
                        <div class="mx-4">
                            <input type="text" name="nama_transaksi"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2">Jumlah (Rp)</label>
                        <div class="mx-4">
                            <input type="number" name="jumlah"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold text-md mb-2" for="tgl">Tanggal</label>
                        <div class="mx-4">
                            <input type="date" name="tgl_transaksi_org" id="tgl"
                                class="w-full border-gray-300 border rounded-3xl resize-none bg-secondary px-2 py-3"
                                required>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('forum.kas', ['slug' => $forums->slug]) }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
