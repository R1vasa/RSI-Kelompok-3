@extends('Layout.layout')

@section('title', 'Dashboard')

@section('body')
<div class="flex">
    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC] p-8 font-poppins overflow-visible">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Selamat Datang, {{ Auth::user()->nama }}</h1>

        <!-- Cards Section -->
        <div class="flex flex-wrap justify-center gap-8 mb-6">
            <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm">PENDAPATAN (HARI INI)</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h2>
                <p class="text-xs text-gray-500 mt-1">Mingguan: Rp {{ number_format($weeklyIncomeTotal, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white shadow-md rounded-xl p-5 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm">PENGELUARAN (HARI INI)</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($todayExpense, 0, ',', '.') }}</h2>
                <p class="text-xs text-gray-500 mt-1">Mingguan: Rp {{ number_format($weeklyExpenseTotal, 0, ',', '.') }}</p>
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
        labels: ['6 Hari Lalu', '5 Hari Lalu', '4 Hari Lalu', '3 Hari Lalu', '2 Hari Lalu', 'Kemarin', 'Hari Ini'],
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