<?php

namespace App\Http\Controllers;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use App\Models\KasOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumKasController extends Controller
{
    public function index($slug)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $kas = KasOrganisasi::where('id_forum', $forums->id)->get();
        $akses = AnggotaForum::where('id_users', Auth::user()->id)->first();
        return view('Pages.Forum.ForumKas', compact('forums', 'kas', 'akses'));
    }

    public function indexAdd($slug)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $kas = KasOrganisasi::where('id_forum', $forums->id)->get();
        return view('pages.Forum.addKas', compact('forums', 'kas'));
    }

    public function indexUpdate($slug, $id)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $kas = KasOrganisasi::find($id);
        return view('pages.Forum.UpdateKas', compact('forums', 'kas'));
    }

    public function add(Request $request, $slug)
    {
        $forum = ForumOrganisasi::where('slug', $slug)->first();

        $transaksi = $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tgl_transaksi_org' => 'required|date',
        ]);

        $creeate = KasOrganisasi::create([
            'id_forum' => $forum->id,
            'nama_transaksi' => $transaksi['nama_transaksi'],
            'jumlah' => $transaksi['jumlah'],
            'tgl_transaksi_org' => $transaksi['tgl_transaksi_org'],
        ]);

        return redirect()->route('forum.kas', $forum->slug)->with('success', 'Data kas berhasil ditambahkan.');
    }

    public function update(Request $request, $slug, $id)
    {
        $validated = $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tgl_transaksi_org' => 'required|date',
        ]);

        $kas = KasOrganisasi::where('id', $id)->findOrFail($id);
        $kas->update($validated);

        return redirect()->route('forum.kas', $slug)->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function delete($slug, $id)
    {

        $kas = KasOrganisasi::where('id', $id)->firstOrFail();
        $kas->delete();

        return redirect()->route('forum.kas', $slug)->with('success', 'Transaksi berhasil dihapus.');
    }
}
