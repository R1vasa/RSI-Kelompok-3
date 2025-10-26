@extends('Layout.layout')

@section('title', 'Tambah Goals')

@section('body')
    <div class="flex">

        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Tambah Goals</h1>
                @endauth
            </div>
           <div class="bg-white shadow-md rounded-lg p-6">


            <form action="{{ route('goals.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block font-medium">Judul Goals</label>
                    <input type="text" name="judul_goals" value="{{ old('judul_goals') }}" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" 
                    placeholder="contoh: Iphone 17" required>
                    @error('judul_goals')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Jumlah Target</label>
                    <input type="number" name="jumlah_target" value="{{ old('jumlah_target') }}" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" 
                    placeholder="contoh: 100000" required>
                    @error('jumlah_target')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Tanggal Target</label>
                    <input type="date" name="tgl_target" value="{{ old('tgl_target') }}" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2" required>
                    @error('tgl_target')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                        @if(isset($goals) && $goals->gambar)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $goals->gambar) }}" alt="Preview Gambar" class="w-32 h-32 object-cover rounded-lg border">
                </div>
            @endif

                    <label class="block font-medium">Gambar (opsional)</label>
                    <input type="file" name="gambar" accept="image/*" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2">
                    @error('gambar')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between mt-6">
                    <a href="{{ route('goals.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-full cursor-pointer">Kembali</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>
 </div>
    <script>
        document.querySelector('input[name="gambar"]').addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.createElement('img');
                preview.src = URL.createObjectURL(file);
                preview.className = 'w-32 h-32 object-cover rounded-lg border mt-2';
                
                // hapus preview lama kalau ada
                const old = document.querySelector('.preview-image');
                if (old) old.remove();
                
                preview.classList.add('preview-image');
                e.target.parentNode.insertBefore(preview, e.target.nextSibling);
            }
        });
        </script>
@endsection