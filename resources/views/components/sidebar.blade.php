        <div class="bg-linear-to-t from-[#1C69E6] to-blue-500 h-dvh w-1/5 p-1 flex flex-col fixed">
            <div>
                <h1 class="font-noto text-2xl font-bold text-white p-4">FinTrack</h1>
                <hr class="border-t border-blue-400 opacity-100">
            </div>
            <div class="flex-1 overflow-y-auto">
                <div class="space-y-4 pl-2 font-noto font-semibold">
                    <h2 class="text-lg font-bold text-blue-200 mt-4">Keuangan Pribadi</h2>
                    <nav class="space-y-3 pl-4 text-white">
                        <a href="{{ route('transaksi.index') }}"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Transaksi</a>
                        <a href="/"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Anggaran</a>
                        <a href="{{ route('goals.index') }}"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Target
                            Goals</a>
                        <a href="/"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Laporan</a>
                    </nav>
                </div>

                <hr class="border-t border-blue-400 opacity-100 my-6">

                <div class="space-y-4 pl-2 font-noto font-semibold">
                    <h2 class="text-lg font-bold text-blue-200">Keuangan Organisasi</h2>
                    <nav class="space-y-2 pl-4 text-white">
                        <a href="{{ route('forum.index') }}"
                            class="block text-md font-medium hover:text-blue-200 transition duration-150">Forum
                            Organisasi</a>
                    </nav>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="flex justify-center items-center mt-auto p-4">
                @csrf
                <button type="submit" class="cursor-pointer bg-blue-400 px-4 py-3 rounded-full">logout</button>
            </form>
        </div>
