<?php

namespace App\Http\Controllers;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use App\Models\TransaksiOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumTransController extends Controller
{
    public function index($slug)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $trans = TransaksiOrganisasi::where('id_forum', $forums->id)->get();
        $akses = AnggotaForum::where('id_users', Auth::user()->id)->first();
        $totalPemasukan = $trans->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $trans->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        return view('Pages.Forum.ForumTrans', compact('forums', 'trans', 'akses', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
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
