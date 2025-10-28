<?php

namespace App\Http\Controllers;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use App\Models\TransaksiOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumTransController extends Controller
{
    public function index(Request $request, $slug)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->firstOrFail();

        // Ambil periode dari request (contoh: "2025-10-01 to 2025-10-28")
        $periodeAwal = null;
        $periodeAkhir = null;

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $periodeAwal = $dates[0];
                $periodeAkhir = $dates[1];
            }
        }

        // Query transaksi forum
        $query = TransaksiOrganisasi::where('id_forum', $forums->id);

        // Jika ada filter tanggal, tambahkan whereBetween
        if ($periodeAwal && $periodeAkhir) {
            $query->whereBetween('tgl_transaksi', [$periodeAwal . ' 00:00:00', $periodeAkhir . ' 23:59:59']);
        }

        $trans = $query->orderBy('tgl_transaksi', 'desc')->get();

        $akses = AnggotaForum::where('id_users', Auth::id())
            ->where('id_forum', $forums->id)
            ->first();

        // Hitung total
        $totalPemasukan = $trans->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $trans->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

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

    public function indexAdd($slug)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $trans = TransaksiOrganisasi::where('id_forum', $forums->id)->get();
        return view('pages.Forum.AddTrans', compact('forums', 'trans'));
    }

    public function indexUpdate($slug, $id)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $trans = TransaksiOrganisasi::find($id);
        return view('pages.Forum.UpdateTrans', compact('forums', 'trans'));
    }

    public function add(Request $request, $slug)
    {
        $forum = ForumOrganisasi::where('slug', $slug)->firstOrFail();

        $transaksi = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tgl_transaksi' => 'required|date',
        ]);

        TransaksiOrganisasi::create([
            'id_forum' => $forum->id,
            'nama' => $transaksi['nama'],
            'jenis' => $transaksi['jenis'],
            'nominal' => $transaksi['nominal'],
            'deskripsi' => $transaksi['deskripsi'],
            'tgl_transaksi' => $transaksi['tgl_transaksi'],
        ]);
        return redirect()->route('forum.trans', $forum->slug)->with('success', 'Forum berhasil ditambahkan!');
    }

    public function update(Request $request, $slug, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'nominal' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
            'tgl_transaksi' => 'required|date',
        ]);

        $transaksi = TransaksiOrganisasi::where('id', $id)->findOrFail($id);
        $transaksi->update($validated);

        return redirect()->route('forum.trans', $slug)->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function delete($slug, $id)
    {

        $transaksi = TransaksiOrganisasi::where('id', $id)->firstOrFail();
        $transaksi->delete();

        return redirect()->route('forum.trans', $slug)->with('success', 'Transaksi berhasil dihapus.');
    }
}
