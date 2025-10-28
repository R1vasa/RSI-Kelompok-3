<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Pribadi</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #333;
            margin: 30px;
        }

        h1,
        h3 {
            text-align: center;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
        }

        th {
            background: #e8f4ff;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .summary {
            width: 60%;
            margin: 25px auto;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .text-green {
            color: #2e8b57;
        }

        .text-red {
            color: #e74c3c;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }

        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    {{-- Header --}}

    <h1>Laporan Transaksi Pribadi</h1>
    <p class="subtitle">Periode: {{ $periodeAwal }} s.d {{ $periodeAkhir }}</p>
    <hr>

    {{-- Tabel Transaksi --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->judul_transaksi }}</td>
                    <td>{{ $t->kategori->kategori }}</td>
                    <td>{{ ucfirst($t->jenis_transaksi) }}</td>
                    <td class="text-right">
                        @if ($t->jenis_transaksi === 'pemasukan')
                            <span class="text-green">+ Rp {{ number_format($t->jumlah_transaksi, 0, ',', '.') }}</span>
                        @else
                            <span class="text-red">- Rp {{ number_format($t->jumlah_transaksi, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#888;">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Ringkasan --}}
    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Pemasukan</strong></td>
                <td class="text-right text-green">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Pengeluaran</strong></td>
                <td class="text-right text-red">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Saldo Akhir</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer">
        Dicetak otomatis melalui sistem <strong>FinTrack</strong><br>
        Pada {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>
