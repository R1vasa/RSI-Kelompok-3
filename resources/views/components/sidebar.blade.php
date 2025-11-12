<div class="bg-linear-to-t from-[#1C69E6] to-blue-500 h-dvh w-1/5 p-1 flex flex-col fixed">
    <div>

        <a href="{{ route('dashboard.index') }}" >
    <h1 class="font-poppins text-xl font-semibold text-white p-4">FinTrack</h1>
</a>
        
        <hr class="border-t border-blue-400 opacity-100">
    </div>
    <div class="flex-1 overflow-y-auto">


        <div class="space-y-4 pl-2 font-poppins font-semibold">
            <h2 class="text-base font-semibold text-blue-200 mt-4">Keuangan Pribadi</h2>
            <nav class="space-y-4 pl-4 text-white">

                <a href="{{ route('transaksi.index') }}"
                    class="flex items-center gap-2 text-md font-medium hover:text-blue-200 transition duration-150">
                    <img width="20" height="20"
                        src="https://img.icons8.com/forma-regular-filled/24/FFFFFF/data-in-both-directions--v2.png"
                        alt="data-in-both-directions--v2" />
                    Transaksi
                </a>

                <a href="{{ route('anggaran.index') }}"
                    class="flex items-center gap-2 text-md font-medium hover:text-blue-200 transition duration-150">
                    <img width="20" height="20"
                        src="https://img.icons8.com/forma-regular-filled/24/FFFFFF/money-bag.png" alt="money-bag" />
                    Anggaran
                </a>

                <a href="{{ route('goals.index') }}"
                    class="flex items-center gap-2 text-md font-medium hover:text-blue-200 transition duration-150">
                    <img width="20" height="20" src="https://img.icons8.com/material-rounded/24/FFFFFF/goal.png"
                        alt="goal" />
                    Target Goals</a>

                <a href="{{ route('grafik.index') }}"
                    class="flex items-center gap-2 text-md font-medium hover:text-blue-200 transition duration-150">
                    <img width="20" height="20"
                        src="https://img.icons8.com/forma-regular-filled/120/FFFFFF/document.png" alt="document" />
                    Tren Keuangan</a>
            </nav>
        </div>

        <hr class="border-t border-blue-400 opacity-100 my-6">

        {{-- DIUBAH: font-noto menjadi font-poppins --}}
        <div class="space-y-4 pl-2 font-poppins font-semibold">
            <h2 class="text-base font-semibold text-blue-200">Keuangan Organisasi</h2>
            <nav class="space-y-2 pl-4 text-white">

                <a href="{{ route('forum.index') }}"
                    class="flex items-center gap-2 text-md font-medium hover:text-blue-200 transition duration-150">
                    <img width="20" height="20"
                        src="https://img.icons8.com/windows/32/FFFFFF/group-foreground-selected.png"
                        alt="group-foreground-selected" />
                    Forum Organisasi</a>
            </nav>
        </div>
    </div>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST"
        class="flex justify-start items-center mt-auto p-8">
        @csrf
        <button id="logoutBtn" type="button"
            class="flex items-center gap-2 text-md font-medium text-white hover:text-blue-200 transition duration-150 cursor-pointer">
            <img width="20" height="20" src="https://img.icons8.com/fluency-systems-filled/24/FFFFFF/exit.png"
                alt="exit" />
            Log out
        </button>
    </form>
</div>

<!-- Modal Konfirmasi Logout -->
<div id="logoutModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg p-6 w-96 text-center">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Konfirmasi Logout</h2>
        <p class="text-gray-600 mb-6">Apakah kamu yakin ingin keluar dari akun ini?</p>
        <div class="flex justify-center gap-4">
            <button id="cancelLogout"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded-lg">Batal</button>
            <button id="confirmLogout"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg">Ya, Logout</button>
        </div>
    </div>
</div>

<script>
    const logoutBtn = document.getElementById('logoutBtn');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');
    const confirmLogout = document.getElementById('confirmLogout');
    const logoutForm = document.getElementById('logoutForm');

    // Saat tombol logout diklik, tampilkan modal
    logoutBtn.addEventListener('click', () => {
        logoutModal.classList.remove('hidden');
    });

    // Tutup modal jika batal
    cancelLogout.addEventListener('click', () => {
        logoutModal.classList.add('hidden');
    });

    // Jika konfirmasi logout, submit form
    confirmLogout.addEventListener('click', () => {
        logoutForm.submit();
    });

    // Tutup modal jika klik di luar box
    window.addEventListener('click', (e) => {
        if (e.target === logoutModal) {
            logoutModal.classList.add('hidden');
        }
    });
</script>
