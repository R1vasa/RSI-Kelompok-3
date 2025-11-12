<?php

namespace App\Http\Controllers;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use App\Models\TransaksiOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumTransController extends Controller
{
    /**
     * Menampilkan daftar transaksi (pemasukan/pengeluaran) dari forum tertentu.
     *  
     * Fitur:
     * - Menampilkan seluruh transaksi berdasarkan forum.
     * - Dapat difilter berdasarkan rentang tanggal (format: "YYYY-MM-DD to YYYY-MM-DD").
     * - Menghitung total pemasukan, pengeluaran, dan saldo akhir.
     */
    public function index(Request $request, $slug)
    {
        // Ambil forum berdasarkan slug (jika tidak ditemukan, 404)
        $forums = ForumOrganisasi::where('slug', $slug)->firstOrFail();

        // Inisialisasi periode filter
        $periodeAwal = null;
        $periodeAkhir = null;

        // Ambil range tanggal dari request (contoh input: "2025-10-01 to 2025-10-28")
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $periodeAwal = $dates[0];
                $periodeAkhir = $dates[1];
            }
        }

        // Query transaksi milik forum
        $query = TransaksiOrganisasi::where('id_forum', $forums->id);

        // Jika ada filter tanggal, batasi data dengan rentang waktu
        if ($periodeAwal && $periodeAkhir) {
            $query->whereBetween('tgl_transaksi', [
                $periodeAwal . ' 00:00:00',
                $periodeAkhir . ' 23:59:59'
            ]);
        }

        // Urutkan transaksi berdasarkan tanggal terbaru
        $trans = $query->orderBy('tgl_transaksi', 'desc')->get();

        // Cek akses user apakah merupakan anggota forum
        $akses = AnggotaForum::where('id_users', Auth::id())
            ->where('id_forum', $forums->id)
            ->first();

        // Hitung total pemasukan dan pengeluaran
        $totalPemasukan = $trans->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $trans->where('jenis', 'pengeluaran')->sum('nominal');

        // Saldo akhir = pemasukan - pengeluaran
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Kirim seluruh data ke view
        return view('Pages.Forum.ForumTrans', compact(
            'forums',
            'trans',
            'akses',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir',
            'periodeAwal',
            'periodeAkhir'
        ));
    }

    /**
     * Menampilkan halaman form tambah transaksi baru.
     * 
     */
    public function indexAdd($slug)
    {
        // Ambil forum dan seluruh transaksi (jika ingin ditampilkan dalam tabel)
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $trans = TransaksiOrganisasi::where('id_forum', $forums->id)->get();

        return view('pages.Forum.AddTrans', compact('forums', 'trans'));
    }

    /**
     * Menampilkan halaman form edit transaksi tertentu.
     * 
     */
    public function indexUpdate($slug, $id)
    {
        // Ambil forum dan data transaksi yang akan diupdate
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $trans = TransaksiOrganisasi::find($id);

        return view('pages.Forum.UpdateTrans', compact('forums', 'trans'));
    }

    /**
     * Proses penambahan transaksi baru ke forum.
     * 
     * Langkah:
     * 1. Validasi input.
     * 2. Simpan transaksi ke database.
     * 3. Redirect ke halaman utama forum transaksi.
     */
    public function add(Request $request, $slug)
    {
        $forum = ForumOrganisasi::where('slug', $slug)->firstOrFail();

        // Validasi input transaksi
        $transaksi = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tgl_transaksi' => 'required|date',
        ]);

        // Simpan ke tabel transaksi_organisasi
        TransaksiOrganisasi::create([
            'id_forum' => $forum->id,
            'nama' => $transaksi['nama'],
            'jenis' => $transaksi['jenis'],
            'nominal' => $transaksi['nominal'],
            'deskripsi' => $transaksi['deskripsi'],
            'tgl_transaksi' => $transaksi['tgl_transaksi'],
        ]);

        // Redirect ke halaman forum transaksi
        return redirect()->route('forum.trans', $forum->slug)
            ->with('success', 'Transaksi baru berhasil ditambahkan!');
    }

    /**
     * Proses update (pembaruan) data transaksi.
     * 
     */
    public function update(Request $request, $slug, $id)
    {
        // Validasi input pengguna
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tgl_transaksi' => 'required|date',
        ]);

        // Cari transaksi dan pastikan ada
        $transaksi = TransaksiOrganisasi::where('id', $id)->findOrFail($id);

        // Update data transaksi
        $transaksi->update($validated);

        return redirect()->route('forum.trans', $slug)
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi berdasarkan ID.
     * 
     */
    public function delete($slug, $id)
    {
        // Cari transaksi dan hapus
        $transaksi = TransaksiOrganisasi::where('id', $id)->firstOrFail();
        $transaksi->delete();

        return redirect()->route('forum.trans', $slug)
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
