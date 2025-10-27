@extends('Layout.layout')

@section('title', 'Manajemen Anggaran')

@section('body')
<div class="flex">
    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] p-6 bg-[#F8FAFC] font-poppins relative">
        <h1 class="text-3xl font-semibold mb-6">Manajemen Anggaran</h1>

        {{-- Tombol Tambah --}}
        <button onclick="openForm()" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4">
            + Tambah Anggaran
        </button>

        {{-- Tabel Data --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="p-5 ">Kategori</th>
                        <th class="p-3">Jumlah (Rp)</th>
                        <th class="p-3">Periode</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggaran as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $item->kategori->kategori ?? '-' }}</td>
                        <td class="p-3">{{ number_format($item->jmlh_anggaran, 0, ',', '.') }}</td>
                        <td class="p-3">{{ $item->periode }}</td>
                        <td class="p-3 space-x-2">
                            <button 
                                onclick="editForm({{ $item->id }}, {{ $item->id_kategori }}, {{ $item->jmlh_anggaran }}, '{{ $item->periode }}')" 
                                class="inline-flex items-center justify-center bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                Edit
                            </button>
                            <form action="{{ route('anggaran.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin ingin menghapus?')" 
                                    class="inline-flex items-center justify-center bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">Belum ada anggaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Form --}}
<div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg w-[90%] md:w-[400px] p-6 shadow-lg">
        <h2 id="formTitle" class="text-xl font-semibold mb-4">Tambah Anggaran</h2>

        <form id="anggaranForm" method="POST" action="{{ route('anggaran.store') }}">
            @csrf
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="mb-3">
                <label class="block mb-1 font-medium">Kategori</label>
                <select name="id_kategori" id="kategoriSelect" class="border rounded p-2 w-full">
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->id }}">{{ $kat->kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Jumlah Anggaran</label>
                <input type="number" name="jmlh_anggaran" id="jmlhInput" class="border rounded p-2 w-full" required>
            </div>

            <div class="mb-3">
                <label for="periode" class="font-medium">Periode:</label>
                <input type="month" name="periode" id="periode" class="border rounded p-2 w-full" required>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeForm()" 
                    class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Batal</button>
                <button type="submit" 
                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Script Modal --}}
<script>
    const modal = document.getElementById('formModal');
    const form = document.getElementById('anggaranForm');
    const title = document.getElementById('formTitle');
    const method = document.getElementById('formMethod');

    function openForm() {
        form.action = "{{ route('anggaran.store') }}";
        method.value = "POST";
        title.textContent = "Tambah Anggaran";
        form.reset();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function editForm(id, id_kategori, jmlh, periode) {
        form.action = `/anggaran/${id}`;
        method.value = "PUT";
        title.textContent = "Edit Anggaran";
        document.getElementById('kategoriSelect').value = id_kategori;
        document.getElementById('jmlhInput').value = jmlh;
        document.getElementById('periode').value = periode;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeForm() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
