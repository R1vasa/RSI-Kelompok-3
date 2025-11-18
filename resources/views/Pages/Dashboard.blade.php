@extends('Layout.layout')

@section('title', 'Dashboard')

@section('body')
    <div class="flex">
        <x-sidebar></x-sidebar>

        <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC] font-poppins overflow-visible">
            <div class="bg-white border-b border-gray-200 flex items-center justify-between px-6 py-4 mb-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 font-poppins">Dashboard</h1>
                    <p class="text-sm text-gray-500 font-poppins">Memantau aktivitas keuangan anda</p>
                </div>

                {{-- Bagian kanan header: tombol search dan profil --}}
                <div class="flex items-center gap-5">

                    {{-- 2️⃣ Input pencarian (hidden secara default) --}}
                    <input type="text" name="search_judul" id="search-input-field" form="filterForm"
                        placeholder="Cari & tekan Enter" value="{{ request('search_judul') }}"
                        class="hidden w-48 text-sm outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 border rounded-lg px-3 py-1.5 shadow-sm">

                    {{-- 3️⃣ Avatar dan info user login --}}
                    <div class="flex items-center gap-2">
                        <img class="w-8 h-8 rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=e0e7ff&color=4f46e5"
                            alt="Avatar">
                        <div>
                            <p class="text-sm font-medium text-gray-700 font-poppins">{{ Auth::user()->nama }}</p>
                            <p class="text-xs text-gray-500 font-poppins">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Section -->
            <div class="px-6">
                <div class="flex flex-wrap justify-center gap-8 mb-6">
                    <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-green-500">
                        <p class="text-gray-600 text-sm">PENDAPATAN (HARI INI)</p>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($todayIncome, 0, ',', '.') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Mingguan: Rp
                            {{ number_format($weeklyIncomeTotal, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-red-500">
                        <p class="text-gray-600 text-sm">PENGELUARAN (HARI INI)</p>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($todayExpense, 0, ',', '.') }}
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">Mingguan: Rp
                            {{ number_format($weeklyExpenseTotal, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-blue-500">
                        <p class="text-gray-600 text-sm">SISA UANG</p>
                        <h2 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white shadow-md rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Pendapatan Minggu Ini</h3>
                        <div class="w-full h-[350px]">
                            <canvas id="weeklyIncomeChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Perbandingan</h3>
                        <div class="w-full h-[350px]">
                            <canvas id="comparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const weeklyIncomeData = @json($weeklyIncomeData);
        const incomeTotal = {{ $totalIncome }};
        const expenseTotal = {{ $totalExpense }};
        const remainingTotal = {{ $saldo }};
        // Example Line Chart
        new Chart(document.getElementById('weeklyIncomeChart'), {
            type: 'line',
            data: {
                labels: ['6 Hari Lalu', '5 Hari Lalu', '4 Hari Lalu', '3 Hari Lalu', '2 Hari Lalu', 'Kemarin',
                    'Hari Ini'
                ],
                datasets: [{
                    label: 'Pendapatan',
                    data: weeklyIncomeData,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });

        // Example Doughnut Chart
        new Chart(document.getElementById('comparisonChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pendapatan', 'Pengeluaran', 'Sisa'],
                datasets: [{
                    data: [incomeTotal, expenseTotal, remainingTotal],
                    backgroundColor: ['#10B981', '#EF4444', '#3B82F6'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endsection
