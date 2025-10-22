@extends('Layout.layout')

@section('title', 'Setor Tabungan')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Setor Tabungan</h1>
                @endauth
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('setor.store', $goals->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah setor(Rp)</label>
                        <input type="number" name="jumlah_tabungan" class="w-full border rounded-full px-3 py-2" required>
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
