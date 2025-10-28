@extends('Layout.layout')

@section('title', 'Tren Keuangan')

@section('body')
<div class="flex">
    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC] p-8 font-poppins overflow-visible relative">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Tren Keuangan</h1>
        </div>

        {{-- Filter Periode --}}
        <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-100">
            <form method="GET" action="{{ route('grafik.index') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="font-medium text-gray-700">Bulan:</label>
                    <select name="bulan" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <label class="font-medium text-gray-700">Tahun:</label>
                    <select name="tahun" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        @foreach($tahunTersedia as $y)
                            <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all shadow-sm">
                    Pilih Periode
                </button>

                <button id="hideChartsBtn" type="button" class="bg-blue-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all shadow-sm">
                    Tutup Grafik
                </button>
            </form>
        </div>

        {{-- Jika tidak ada data --}}
        @if(!empty($message))
            <div class="bg-white text-center p-8 rounded-xl shadow-md border border-gray-100">
                <p class="text-gray-500">{{ $message }}</p>
            </div>
        @else
            {{-- Kontainer Grafik --}}
            <div id="chartsContainer" class="space-y-8">
                {{-- Grafik Pie --}}
                <div class="flex flex-wrap justify-center gap-8">
                    {{-- Pie Pemasukan --}}
                    <div class="bg-white shadow-md rounded-xl p-6 w-[360px] border border-gray-100 hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold mb-3 text-center text-blue-600">
                            Pemasukan per Kategori
                        </h3>
                        <div class="w-[280px] h-[280px] mx-auto">
                            <canvas id="pemasukanChart"></canvas>
                        </div>
                    </div>

                    {{-- Pie Pengeluaran --}}
                    <div class="bg-white shadow-md rounded-xl p-6 w-[360px] border border-gray-100 hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold mb-3 text-center text-blue-600">
                            Pengeluaran per Kategori
                        </h3>
                        <div class="w-[280px] h-[280px] mx-auto">
                            <canvas id="pengeluaranChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Line Chart --}}
                <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold text-center mb-5 text-blue-700">
                        Perbandingan Pemasukan & Pengeluaran Bulan {{ $bulan }}/{{ $tahun }}
                    </h3>
                    <div class="w-full h-[400px]">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labelsPemasukan = @json($labelsPemasukan);
    const dataPemasukan = @json($dataPemasukan);
    const labelsPengeluaran = @json($labelsPengeluaran);
    const dataPengeluaran = @json($dataPengeluaran);

    const colorPalette = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
        '#9966FF', '#FF9F40', '#2ecc71', '#e74c3c',
        '#3498db', '#f1c40f', '#1abc9c', '#9b59b6'
    ];

    const getColors = (length) => colorPalette.slice(0, length);

    // Pie Chart Pemasukan
    new Chart(document.getElementById('pemasukanChart'), {
        type: 'pie',
        data: {
            labels: labelsPemasukan,
            datasets: [{
                data: dataPemasukan,
                backgroundColor: getColors(labelsPemasukan.length),
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#333', font: { size: 12 } } }
            }
        }
    });

    // Pie Chart Pengeluaran
    new Chart(document.getElementById('pengeluaranChart'), {
        type: 'pie',
        data: {
            labels: labelsPengeluaran,
            datasets: [{
                data: dataPengeluaran,
                backgroundColor: getColors(labelsPengeluaran.length),
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color: '#333', font: { size: 12 } } }
            }
        }
    });

    // Line Chart Perbandingan
    const labelsHarian = @json($labelsHarian);
    const dataPemasukanHarian = @json($dataPemasukanHarian);
    const dataPengeluaranHarian = @json($dataPengeluaranHarian);

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: labelsHarian,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: dataPemasukanHarian,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: dataPengeluaranHarian,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 12 } } },
                tooltip: {
                    callbacks: {
                        label: (context) => context.dataset.label + ': Rp' + context.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (v) => 'Rp' + v.toLocaleString() }
                },
                x: { title: { display: true, text: 'Tanggal' } }
            }
        }
    });

    // Tombol Sembunyi/Tampilkan Grafik
    document.getElementById('hideChartsBtn')?.addEventListener('click', (e) => {
        const container = document.getElementById('chartsContainer');
        const isHidden = container.style.display === 'none';
        container.style.display = isHidden ? 'block' : 'none';
        e.target.textContent = isHidden ? 'Tutup Grafik' : 'Tampilkan Grafik';
    });
</script>
@endsection
