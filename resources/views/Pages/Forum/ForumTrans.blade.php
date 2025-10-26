@extends('Layout.layout')
@section('title', 'Forum Kas')
@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen font-poppins ">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">{{ $forums->forum }}</h1>
                @endauth
            </div>
            <div class="p-6">
                <div class="flex gap-4 ">
                    <img src="{{ asset('storage/' . $forums->gambar_forum) }}" alt=""
                        class="h-30 w-30 object-cover rounded-full border-2 border-gray-300 p-1 mb-4">
                    <div class="w-lg mt-3">
                        <h1 class="font-bold text-2xl mb-2">{{ $forums->forum }}</h1>
                        <p class="max-w-lg text-md">{{ $forums->deskripsi }}</p>
                    </div>
                </div>
                <div class="flex justify-between p-2">
                    <h1 class="text-sm font-semibold">
                        Periode 1 - 30 November 2023
                    </h1>
                    @if ($akses->role == 'bendahara')
                        <div>
                            <a href="{{ route('tambah.trans.index', ['slug' => $forums->slug]) }}"
                                class="px-5 py-2 bg-emerald-500 text-white rounded-xs">Tambah transaksi</a>
                            <a href="" class="px-5 py-2 bg-yellow-500 text-white rounded-xs">Ekspor</a>
                        </div>
                    @endif
                </div>
                <div class="bg-white shadow-md rounded-lg p-6">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-blue-200">
                                <th class="px-4 py-2 text-center font-poppins">Judul</th>
                                <th class="px-4 py-2 text-center font-poppins">Deskripsi</th>
                                <th class="px-4 py-2 text-center font-poppins">Tanggal</th>
                                <th class="px-4 py-2 text-center font-poppins">Jumlah</th>
                                @if ($akses->role == 'bendahara')
                                    <th class="px-4 py-2 text-center font-poppins">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trans as $Trans)
                                <tr class="border-b">
                                    <td class="px-4 py-2 font-poppins text-center">{{ $Trans->nama }}
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center relative group">
                                        {{ Str::limit($Trans->deskripsi, 20) }}
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 -top-8 
                 bg-gray-800 text-white text-xs rounded-md px-2 py-1 
                 opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                            {{ $Trans->deskripsi }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 font-poppins text-center">
                                        {{ \Carbon\Carbon::parse($Trans->tgl_transaksi)->format('d-m-Y') }}</td>
                                    @if ($Trans->jenis == 'pemasukan')
                                        <td class="px-4 py-2 font-poppins text-center text-green-500">+ Rp
                                            {{ number_format($Trans->nominal, 0, ',', '.') }}</td>
                                    @else
                                        <td class="px-4 py-2 font-poppins text-center text-red-500">- Rp
                                            {{ number_format($Trans->nominal, 0, ',', '.') }}</td>
                                    @endif
                                    @if ($akses->role == 'bendahara')
                                        <td class="text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('edit.trans.index', ['slug' => $forums->slug, 'id' => $Trans->id]) }}"
                                                    class="bg-green-400 hover:bg-green-600 text-white p-1 rounded-md font-poppins flex items-center justify-center">
                                                    <i class='bx bxs-edit text-2xl'></i>
                                                </a>

                                                <form
                                                    action="{{ route('forum.transaksi.destroy', ['slug' => $forums->slug, 'id' => $Trans->id]) }}"
                                                    method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
                                    @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 bg-white shadow-md rounded-lg p-4 w-1/2 mx-auto">
                    <table class="w-full text-sm">
                        <tr>
                            <td class="font-semibold">Pemasukan</td>
                            <td class="text-green-600 font-bold text-right">
                                + Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-semibold">Pengeluaran</td>
                            <td class="text-red-600 font-bold text-right">
                                - Rp. {{ number_format($totalPengeluaran, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr class="border-t border-gray-300">
                            <td class="font-semibold pt-2">Saldo Akhir</td>
                            <td class="font-bold text-right pt-2">
                                Rp. {{ number_format($saldoAkhir, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
