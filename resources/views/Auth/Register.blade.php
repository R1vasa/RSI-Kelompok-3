<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Pengaturan dasar halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Import font "Noto Sans" untuk heading -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    <!-- Import font "Poppins" untuk isi dan teks umum -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>

    <!-- Import Tailwind CSS melalui Laravel Vite -->
    @vite('resources/css/app.css')

    <!-- Definisi kelas font agar bisa digunakan ulang -->
    <style>
        .font-noto {
            font-family: 'Noto Sans', sans-serif;
        }

        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>

    <title>Register | FinTrack</title>
</head>

<body>
    <!-- Layout utama: dua kolom horizontal -->
    <div class="flex p-15 h-screen">

        <!-- Bagian kiri: form registrasi -->
        <div class="w-1/2 flex flex-col justify-center items-center">

            <!-- Judul dan deskripsi -->
            <h1 class="p-4 font-black font-noto text-2xl">FinTrack</h1>
            <h1 class="text-3xl font-black font-noto">
                Let's Start with <span class="text-blue-500">FinTrack</span>
            </h1>
            <p class="font-poppins text-sm">Please Register Your Account</p>

            <!-- Form registrasi -->
            <div>
                <form action="{{ route('register.create') }}" method="POST">
                    @csrf <!-- Token keamanan Laravel -->

                    <!-- Input Nama Lengkap / Username -->
                    <div class="pt-6">
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="text" name="nama" id="nama"
                            required placeholder="Username">
                        <!-- Pesan error validasi untuk nama -->
                        @if ($errors->has('nama'))
                            <p class="text-red-500 text-left">{{ $errors->first('nama') }}</p>
                        @endif
                    </div>

                    <!-- Input Email -->
                    <div class="pt-4">
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="email" name="email" id="email"
                            required placeholder="Email">
                        <!-- Pesan error validasi untuk email -->
                        @if ($errors->has('email'))
                            <p class="text-red-500 text-sm pb-0">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Input Password -->
                    <div class="pt-4">
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="password" name="password" id="password"
                            required placeholder="Password">
                        <!-- Pesan error validasi untuk password -->
                        @if ($errors->has('password'))
                            <p class="text-red-500 text-sm pb-0">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Tombol Register -->
                    <div class="pt-4">
                        <button class="w-sm bg-blue-500 p-4 rounded-xl text-white font-semibold cursor-pointer">
                            Register
                        </button>
                    </div>

                    <!-- Pemisah "atau daftar dengan" -->
                    <div class="flex items-center justify-center p-2">
                        <div class="w-12 border-t border-gray-300"></div>
                        <span class="mx-4 text-gray-500 text-sm">
                            or register with
                        </span>
                        <div class="w-12 border-t border-gray-300"></div>
                    </div>

                    <!-- Tombol daftar dengan Google -->
                    <div class="flex justify-center items-center">
                        <a href="{{ route('google.redirect') }}"
                            class="w-xs bg-white border-gray-300 border-1 p-4 rounded-xl text-black cursor-pointer font-medium flex items-center justify-center">
                            <img src="{{ asset('image/google.png') }}" alt="google logo" class="inline w-6 h-6 mr-2">
                            Google
                        </a>
                    </div>
                </form>
            </div>

            <!-- Link menuju halaman login -->
            <p class="text-sm p-2">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-500 font-semibold">
                    Sign In
                </a>
            </p>
        </div>

        <!-- Bagian kanan: ilustrasi dan logo -->
        <div class="flex flex-col w-1/2 font-bold text-4xl justify-center items-center">
            <!-- Logo aplikasi -->
            <div class="flex items-center">
                <img src="{{ asset('image/logo.png') }}" alt="FinTrack logo" class="h-12 w-12">
                <h1 class="p-4 font-black font-noto text-4xl">
                    Fin<span class="text-blue-500">Track</span>
                </h1>
            </div>

            <!-- Ilustrasi pendaftaran -->
            <img src="{{ asset('image/image-auth.png') }}" alt="Register Illustration"
                class="h-fit w-fit object-cover mt-2">
        </div>
    </div>
</body>

</html>
