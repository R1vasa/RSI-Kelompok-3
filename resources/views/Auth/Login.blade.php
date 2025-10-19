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

<body>
    <div class="flex p-15 h-screen">
        <div class="flex flex-col w-1/2 text-white font-bold text-4xl">
            <img src="{{ asset('image/image-auth.png') }}" alt="" class="h-fit w-fit object-cover mt-auto">
        </div>
        <div class="w-1/2 text-center">
            <h1 class="p-4 font-black font-noto text-2xl">FinTrack</h1>
            <h1 class="text-3xl font-black font-noto">Welcome back, <span class="text-blue-500">Pal!</span>
            </h1>
            <p class="font-poppins text-sm">Please Login to Your Account</p>
            <div>
                <form action="{{ route('login.create') }}" method="POST" class="font-poppins">
                    @csrf
                    <div class="pt-6 p-4">
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="email" name="email" id="email"
                            required placeholder="Email">
                    </div>
                    <div>
                        <input class="p-4 w-sm bg-[#F8FAFC] rounded-xl" type="password" name="password" id="password"
                            required placeholder="Password">
                    </div>
                    <div class="pt-15">
                        <button
                            class="w-sm bg-[#3B82F6] p-4 rounded-xl text-white cursor-pointer font-semibold">Login</button>
                    </div>
                    <div class="flex items-center justify-center p-2">
                        <div class="w-12 border-t border-gray-300"></div>

                        <span class="mx-4 text-gray-500 text-sm">
                            or login with
                        </span>

                        <div class="w-12 border-t border-gray-300"></div>
                    </div>
                    <div>

                        <button
                            class="w-xs bg-white border-gray-300 border-1 p-4 rounded-xl text-black cursor-pointer font-medium"><img
                                src="{{ asset('image/google.png') }}" alt="google logo"
                                class="inline w-6 h-6 mr-2">Google</button>
                    </div>
                </form>
            </div>
            <p class="text-sm p-2">dont have an account? <a href="{{ route('register') }}"
                    class="text-[#3B82F6] font-semibold">Sign
                    Up</a></p>
        </div>
    </div>
</body>

</html>
