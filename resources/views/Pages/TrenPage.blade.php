@extends('Layout.layout')

@section('title', 'Tren Keuangan')

@section('body')
<div class="flex">
    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC] p-8 font-poppins overflow-visible relative">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">📈 Tren Keuangan</h1>
        </div>

        <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-100">
            <form method="GET" action="{{ route('grafik.index') }}" class="flex flex-wrap items-center gap-4">
                {{-- Bulan --}}
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan"
                        class="border border-gray-300 rounded-xl px-6 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 bg-white">
                        @foreach(range(1,12) as $m)
                            <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun"
                        class="border border-gray-300 rounded-xl px-6 py-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-700 bg-white">
                        @foreach($tahunTersedia as $y)
                            <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3 mt-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl shadow-sm transition-all text-sm font-medium">
                        <i class="fa-solid fa-filter mr-1"></i> Terapkan
                    </button>

                    <button id="hideChartsBtn" type="button"
                        class="bg-blue-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-xl shadow-sm transition-all text-sm font-medium">
                        Tutup Grafik
                    </button>
                </div>
            </form>
        </div>

        @if(!empty($message))
            <div class="bg-white text-center p-10 rounded-2xl shadow-md border border-gray-100">
                <p class="text-gray-500 text-lg">{{ $message }}</p>
            </div>
        @else
            <div id="chartsContainer" class="space-y-8">
                {{-- Grafik Pie --}}
                <div class="flex flex-wrap justify-center gap-8">
                    {{-- Pie Pemasukan --}}
                    <div class="bg-white shadow-md rounded-2xl p-6 w-[360px] border border-gray-100 hover:shadow-lg transition-all">
                        <h3 class="text-lg font-semibold mb-4 text-center text-black-600">
                            💰 Pemasukan per Kategori
                        </h3>
                        <div class="w-[280px] h-[280px] mx-auto">
                            <canvas id="pemasukanChart"></canvas>
                        </div>
                    </div>

                    {{-- Pie Pengeluaran --}}
                    <div class="bg-white shadow-md rounded-2xl p-6 w-[360px] border border-gray-100 hover:shadow-lg transition-all">
                        <h3 class="text-lg font-semibold mb-4 text-center text-black-600">
                            💸 Pengeluaran per Kategori
                        </h3>
                        <div class="w-[280px] h-[280px] mx-auto">
                            <canvas id="pengeluaranChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Line Chart --}}
                <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all">
                    <h3 class="text-xl font-semibold text-center mb-5 text-blue-700">
                        📊 Perbandingan Pemasukan & Pengeluaran Bulan {{ $bulan }}/{{ $tahun }}
                    </h3>
                    <div class="w-full h-[400px]">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labelsPemasukan = @json($labelsPemasukan);
    const dataPemasukan = @json($dataPemasukan);
    const labelsPengeluaran = @json($labelsPengeluaran);
    const dataPengeluaran = @json($dataPengeluaran);

    const colorPalette = [
        '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
        '#8B5CF6', '#14B8A6', '#EAB308', '#F97316',
        '#0EA5E9', '#22C55E', '#A855F7', '#F43F5E'
    ];

    const getColors = (len) => colorPalette.slice(0, len);

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
            plugins: {
                legend: { position: 'bottom', labels: { color: '#333', font: { size: 13 } } }
            },
            maintainAspectRatio: false,
        }
    });

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
            plugins: {
                legend: { position: 'bottom', labels: { color: '#333', font: { size: 13 } } }
            },
            maintainAspectRatio: false,
        }
    });

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
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Pengeluaran',
                    data: dataPengeluaranHarian,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 13 } } },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: Rp${context.parsed.y.toLocaleString()}`
                    }
                }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (v) => 'Rp' + v.toLocaleString() } },
                x: { title: { display: true, text: 'Tanggal' } }
            },
            maintainAspectRatio: false,
        }
    });

    document.getElementById('hideChartsBtn')?.addEventListener('click', (e) => {
        const container = document.getElementById('chartsContainer');
        const isHidden = container.style.display === 'none';
        container.style.display = isHidden ? 'block' : 'none';
        e.target.textContent = isHidden ? 'Tutup Grafik' : 'Tampilkan Grafik';
    });
</script>
@endsection
