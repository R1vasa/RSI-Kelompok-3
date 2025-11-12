<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Import font Noto Sans dari Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    <!-- Import font Poppins dari Google Fonts -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>

    <!-- Import file CSS utama dari Laravel Vite -->
    @vite('resources/css/app.css')

    <!-- Definisi kelas font kustom -->
    <style>
        .font-noto {
            font-family: 'Noto Sans', sans-serif;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <title>Login | FinTrack</title>
</head>

<body>
    <!-- Layout utama menggunakan Flexbox, membagi layar menjadi dua bagian -->
    <div class="flex p-15 h-screen">

        <!-- Bagian kiri: menampilkan logo dan ilustrasi -->
        <div class="flex flex-col w-1/2 font-bold text-4xl justify-center items-center">
            <!-- Logo FinTrack -->
            <div class="flex items-center">
                <img src="{{ asset('image/logo.png') }}" alt="FinTrack logo" class="h-12 w-12">
                <h1 class="p-4 font-black font-noto text-4xl">
                    Fin<span class="text-blue-500">Track</span>
                </h1>
            </div>

            <!-- Gambar ilustrasi di bawah logo -->
            <img src="{{ asset('image/image-auth.png') }}" alt="Authentication Illustration"
                class="h-fit w-fit object-cover mt-2">
        </div>

        <!-- Bagian kanan: Form Login -->
        <div class="w-1/2 flex flex-col justify-center items-center">

            <!-- Judul Halaman -->
            <h1 class="text-3xl font-black font-noto">
                Welcome back, <span class="text-blue-500">Pal!</span>
            </h1>
            <p class="font-poppins text-sm">Please Login to Your Account</p>

            <!-- Form Login -->
            <div>
                <form action="{{ route('login.create') }}" method="POST" class="font-poppins">
                    @csrf <!-- Token keamanan Laravel -->

                    <!-- Input Email -->
                    <div class="pt-6">
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="email" name="email" id="email"
                            required placeholder="Email" value="{{ old('email') }}">
                    </div>

                    <!-- Input Password -->
                    <div class="py-4">
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="password" name="password" id="password"
                            required placeholder="Password">
                    </div>

                    <!-- Tombol Login -->
                    <div class="pt-12">
                        <!-- Menampilkan pesan error jika validasi gagal -->
                        @if ($errors->any())
                            <p class="text-red-500 text-sm mb-2">{{ $errors->first() }}</p>
                        @endif

                        <button class="w-sm bg-blue-500 p-4 rounded-xl text-white cursor-pointer font-semibold">
                            Login
                        </button>
                    </div>

                    <!-- Pemisah antara login manual dan login Google -->
                    <div class="flex items-center justify-center p-2">
                        <div class="w-12 border-t border-gray-300"></div>
                        <span class="mx-4 text-gray-500 text-sm">
                            or login with
                        </span>
                        <div class="w-12 border-t border-gray-300"></div>
                    </div>

                    <!-- Tombol Login dengan Google -->
                    <div class="flex justify-center items-center">
                        <a href="{{ route('google.redirect') }}"
                            class="w-xs bg-white border-gray-300 border-1 p-4 rounded-xl text-black cursor-pointer font-medium flex items-center justify-center">
                            <img src="{{ asset('image/google.png') }}" alt="Google logo" class="inline w-6 h-6 mr-2">
                            Google
                        </a>
                    </div>
                </form>
            </div>

            <!-- Link ke halaman register -->
            <p class="text-sm p-2">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-500 font-semibold">
                    Sign Up
                </a>
            </p>
        </div>
    </div>
</body>

</html>
