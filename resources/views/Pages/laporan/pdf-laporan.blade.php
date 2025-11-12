<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Pribadi</title>

    <!-- ============================================================
         🎨 STYLE LEMBAR LAPORAN TRANSAKSI PRIBADI
         Deskripsi:
         - Mengatur tampilan laporan PDF agar terlihat profesional dan mudah dibaca.
         - Format CSS inline digunakan karena PDF renderer (Dompdf) tidak mendukung file CSS eksternal.
    ============================================================ -->
    <style>
        /* 🔹 Gaya umum */
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            color: #333;
            margin: 30px;
        }

        /* 🔹 Judul dan subjudul */
        h1,
        h3 {
            text-align: center;
            margin-bottom: 4px;
        }

        /* 🔹 Keterangan periode laporan */
        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-bottom: 20px;
        }

        /* 🔹 Tabel utama */
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

        /* 🔹 Warna sel bergantian agar tabel mudah dibaca */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* 🔹 Kotak ringkasan keuangan */
        .summary {
            width: 60%;
            margin: 25px auto;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 10px;
        }

        /* 🔹 Kelas bantu perataan dan warna */
        .text-right {
            text-align: right;
        }

        .text-green {
            color: #2e8b57;
        }

        .text-red {
            color: #e74c3c;
        }

        /* 🔹 Footer di bagian bawah halaman */
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            margin-top: 30px;
        }

        /* 🔹 Garis pemisah antar bagian */
        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    {{-- ============================================================
         🧩 HEADER LAPORAN
         Menampilkan:
         - Judul laporan keuangan pribadi
         - Periode waktu transaksi
    ============================================================ --}}
    <h1>Laporan Transaksi Pribadi</h1>
    <p class="subtitle">Periode: {{ $periodeAwal }} s.d {{ $periodeAkhir }}</p>
    <hr>

    {{-- ============================================================
         📊 TABEL DAFTAR TRANSAKSI
         Menampilkan seluruh transaksi pribadi selama periode yang dipilih.
         Kolom mencakup:
         - Nomor urut
         - Judul transaksi
         - Kategori transaksi
         - Jenis (pemasukan/pengeluaran)
         - Jumlah (format rupiah)
         - Tanggal transaksi
    ============================================================ --}}
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
            {{-- 🔁 Perulangan untuk menampilkan data transaksi --}}
            @forelse ($transaksis as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $t->judul_transaksi }}</td>
                    <td>{{ $t->kategori->kategori }}</td>
                    <td>{{ ucfirst($t->jenis_transaksi) }}</td>

                    {{-- Menampilkan jumlah dengan warna sesuai jenis transaksi --}}
                    <td class="text-right">
                        @if ($t->jenis_transaksi === 'pemasukan')
                            <span class="text-green">
                                + Rp {{ number_format($t->jumlah_transaksi, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-red">
                                - Rp {{ number_format($t->jumlah_transaksi, 0, ',', '.') }}
                            </span>
                        @endif
                    </td>

                    {{-- Format tanggal menjadi format Indonesia singkat --}}
                    <td>{{ \Carbon\Carbon::parse($t->tgl_transaksi)->format('d M Y') }}</td>
                </tr>
            @empty
                {{-- Jika tidak ada transaksi dalam periode --}}
                <tr>
                    <td colspan="6" style="text-align:center; color:#888;">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ============================================================
         💰 RINGKASAN KEUANGAN PRIBADI
         Menampilkan total:
         - Pemasukan
         - Pengeluaran
         - Saldo akhir
         Data dihitung di controller dan dikirim ke view.
    ============================================================ --}}
    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Pemasukan</strong></td>
                <td class="text-right text-green">
                    Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td><strong>Total Pengeluaran</strong></td>
                <td class="text-right text-red">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td><strong>Saldo Akhir</strong></td>
                <td class="text-right">
                    <strong>Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </table>
    </div>

    {{-- ============================================================
         📄 FOOTER LAPORAN
         Menampilkan informasi sumber sistem dan waktu cetak otomatis.
    ============================================================ --}}
    <div class="footer">
        Dicetak otomatis melalui sistem <strong>FinTrack</strong><br>
        Pada {{ now()->format('d M Y H:i') }}
    </div>
</body>

</html>
