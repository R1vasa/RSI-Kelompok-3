@extends('Layout.layout')

@section('title', 'Tren Keuangan')

@section('body')
<div class="flex">
    <x-sidebar></x-sidebar>

    <div class="flex-1 ml-[20%] min-h-screen bg-[#F8FAFC] p-6 font-poppins overflow-visible relative">
        <h1 class="text-3xl font-semibold mb-6">Tren Keuangan</h1>

        {{-- Filter bulan dan tahun --}}
        <form method="GET" action="{{ route('grafik.index') }}" class="flex flex-wrap items-center gap-3 mb-8">
            <div>
                <label class="font-medium mr-2">Bulan:</label>
                <select name="bulan" class="border rounded p-2">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$m,1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="font-medium mr-2">Tahun:</label>
                <select name="tahun" class="border rounded p-2">
                    @foreach($tahunTersedia as $y)
                        <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Pilih Periode
            </button>
            <button id="hideChartsBtn" type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Tampilkan Grafik
            </button>
            
        </form>

        {{-- Jika tidak ada data --}}
        @if(!empty($message))
            <p class="text-center text-gray-500">{{ $message }}</p>
        @else

            <div id="chartsContainer">
                    <div class="flex flex-wrap justify-center gap-6">
                        {{-- Pie Chart Pemasukan --}}
                        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center w-[320px]">
                            <h3 class="text-lg font-semibold mb-3 text-center text-green-600">
                                Pemasukan per Kategori
                            </h3>
                            <div class="w-[240px] h-[240px]">
                                <canvas id="pemasukanChart"></canvas>
                            </div>
                        </div>

                        {{-- Pie Chart Pengeluaran --}}
                        <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center w-[320px]">
                            <h3 class="text-lg font-semibold mb-3 text-center text-red-600">
                                Pengeluaran per Kategori
                            </h3>
                            <div class="w-[240px] h-[240px]">
                                <canvas id="pengeluaranChart"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Line Chart Perbandingan --}}
                    <div class="bg-white shadow-md rounded-lg p-6 mt-10">
                        <h3 class="text-xl font-semibold text-center mb-4 text-blue-700">
                            Perbandingan Pemasukan dan Pengeluaran Bulan {{ $bulan }}/{{ $tahun }}
                        </h3>
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
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
        '#9966FF', '#FF9F40', '#2ecc71', '#e74c3c',
        '#3498db', '#f1c40f', '#1abc9c', '#9b59b6'
    ];

    const getColors = (length) => colorPalette.slice(0, length);

    // Chart Pemasukan
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
            maintainAspectRatio: false, // <- penting
            plugins: {
                title: {
                    display: true,
                    text: 'Pemasukan Bulan {{ $bulan }}/{{ $tahun }}',
                    font: { size: 16 }
                },
                legend: {
                    position: 'bottom',
                    labels: { color: '#333', font: { size: 12 } }
                }
            }
        }
    });

    // Chart Pengeluaran
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
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Pengeluaran Bulan {{ $bulan }}/{{ $tahun }}',
                    font: { size: 16 }
                },
                legend: {
                    position: 'bottom',
                    labels: { color: '#333', font: { size: 12 } }
                }
            }
        }
    });
    
    // Grafik Garis Perbandingan
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
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 } }
                },
                title: {
                    display: true,
                    text: 'Tren Harian Pemasukan & Pengeluaran',
                    font: { size: 16 }
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            return context.dataset.label + ': Rp' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: (v) => 'Rp' + v.toLocaleString() }
                },
                x: {
                    title: { display: true, text: 'Tanggal' },
                    ticks: {
                        callback: function(value, index) {
                            return index % 5 === 0 ? this.getLabelForValue(value) : '';
                        }
                    }
                }
            }
        }
    });

    // Tombol Tutup Grafik
    document.getElementById('hideChartsBtn')?.addEventListener('click', (e) => {
        const container = document.getElementById('chartsContainer');
        const isHidden = container.style.display === 'none';
        container.style.display = isHidden ? 'block' : 'none';
        e.target.textContent = isHidden ? 'Tutup Grafik' : 'Tampilkan Grafik';
    });


</script>
@endsection

