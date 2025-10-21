<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>
    @vite('resources/css/app.css')
    <style>
        .font-noto {
            font-family: 'Noto Sans', sans-serif;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <title>Document</title>
</head>

<body class="bg-gray-50 font-poppins">
    <div class="flex">
        <div class="bg-linear-to-t from-[#1C69E6] to-[#3B82F6] h-dvh w-1/5 p-1 flex flex-col fixed">
            <div>
                <h1 class="font-noto text-2xl font-bold text-white p-4">FinTrack</h1>
                <hr class="border-t border-blue-400 opacity-100">
            </div>
            <div class="flex-1 overflow-y-auto">
                <div class="space-y-4 pl-2 font-noto font-semibold">
                    <h2 class="text-lg font-bold text-blue-200 mt-4">Keuangan Pribadi</h2>
                    <nav class="space-y-3 pl-4 text-white">
                        <a href="{{route ('transaksi.index')}}"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Transaksi</a>
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Anggaran</a>
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Target
                            Goals</a>
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Laporan</a>
                    </nav>
                </div>

                <hr class="border-t border-blue-400 opacity-100 my-6">

                <div class="space-y-4 pl-2 font-noto font-semibold">
                    <h2 class="text-lg font-bold text-blue-200">Keuangan Organisasi</h2>
                    <nav class="space-y-2 pl-4 text-white">
                        <a href="#"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Forum
                            Organisasi</a>
                    </nav>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="flex justify-center items-center mt-auto p-4">
                @csrf
                <button type="submit" class="cursor-pointer">logout</button>
            </form>
        </div>
        <div class="flex-1 ml-[20%] min-h-screen">
            <div class="bg-[#F8FAFC] flex items-center p-1">
                @auth
                    <h1 class="p-4 font-semibold font-poppins text-2xl">Welcome, {{ Auth::user()->nama }}</h1>
                @endauth
            </div>

        
             <div class="bg-white shadow-md rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-4">Edit Transaksi</h1>
                 @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-6">
                @foreach($errors->all() as $error)
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
                        <input type="number" name="jumlah_target" class="w-full border rounded px-3 py-2" value="{{ old('jumlah_target', $goals->jumlah_target) }}" required>
                    </div>
                     <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tgl_target" class="w-full border rounded px-3 py-2" value="{{ old('tgl_target', $goals->tgl_target) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="block font-medium">Gambar Goals (opsional)</label>
                        <input type="file" name="gambar" accept="image/*" class="w-full border rounded px-3 py-2">

                    <div class="flex justify-between mt-6">
                        <a href="{{ route('transaksi.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>