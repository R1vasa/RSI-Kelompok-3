@extends('Layout.layout') 
{{-- Meng-extend layout utama yang ada di folder Layout --}}

@section('title', 'Edit Goals')
{{-- Mengatur judul halaman menjadi "Edit Goals" --}}

@section('body')
    <div class="flex">

        {{-- Sidebar di sebelah kiri --}}
        <x-sidebar></x-sidebar>

        {{-- Konten utama halaman --}}
        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    {{-- Hanya tampil jika user sudah login --}}
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Edit Goals</h1>
                @endauth
            </div>

            {{-- Card utama untuk form edit --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Edit Transaksi</h1>

                {{-- Form untuk update data goals --}}
                <form action="{{ route('goals.update', $goals->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- Token keamanan untuk mencegah CSRF --}}
                    @method('PUT') {{-- Mengubah method menjadi PUT sesuai standar RESTful update --}}

                    {{-- Input judul goals --}}
                    <div class="mb-3">
                        <label class="block font-medium">Judul goals</label>
                        <input type="text" name="judul_goals" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            value="{{ old('judul_goals', $goals->judul_goals) }}" required>
                        @error('judul_goals')
                            {{-- Menampilkan pesan error jika validasi gagal --}}
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input jumlah target --}}
                    <div class="mb-3">
                        <label class="block font-medium">Jumlah Target(Rp)</label>
                        <input type="number" name="jumlah_target" value="{{old('jumlah_target', $goals->jumlah_target)}}" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2
                            value="{{ old('jumlah_target', $goals->jumlah_target) }}" required>
                        @error('jumlah_target')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input tanggal target --}}
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tgl_target" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2"
                            value="{{ old('tgl_target', $goals->tgl_target) }}" required>
                        @error('tgl_target')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input upload gambar goals (opsional) --}}
                    <div class="mb-3">
                        <label class="block font-medium">Gambar Goals (opsional)</label>
                        <input type="file" name="gambar" accept="image/*" class="bg-blue-100 opacity-60 w-full border rounded-full px-3 py-2">
                        @error('gambar')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol navigasi dan submit --}}
                    <div class="flex justify-between mt-6">
                        {{-- Tombol kembali ke halaman index goals --}}
                        <a href="{{ route('goals.index') }}"
                            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-full cursor-pointer">Kembali</a>
                        {{-- Tombol untuk menyimpan perubahan --}}
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk preview gambar sebelum upload --}}
    <script>
        document.querySelector('input[name="gambar"]').addEventListener('change', function(e) {
            const [file] = e.target.files; // Ambil file yang dipilih
            if (file) {
                const preview = document.createElement('img'); // Buat elemen img baru
                preview.src = URL.createObjectURL(file); // Buat URL sementara untuk preview
                preview.className = 'w-32 h-32 object-cover rounded-lg border mt-2';
                
                // Hapus preview lama jika ada
                const old = document.querySelector('.preview-image');
                if (old) old.remove();
                
                // Tambahkan class dan tampilkan di bawah input file
                preview.classList.add('preview-image');
                e.target.parentNode.insertBefore(preview, e.target.nextSibling);
            }
        });
    </script>
@endsection

