<?php

namespace App\Http\Controllers;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use App\Models\KasOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumKasController extends Controller
{
    /**
     * Tampilkan halaman daftar kas (keuangan) untuk forum tertentu.
     * 
     * Logika:
     * - Ambil data forum berdasarkan slug.
     * - Ambil seluruh data kas dari forum tersebut.
     * - Cek akses user (apakah user anggota forum).
     */
    public function index($slug)
    {
        // Ambil data forum berdasarkan slug
        $forums = ForumOrganisasi::where('slug', $slug)->first();

        // Ambil seluruh transaksi kas terkait forum
        $kas = KasOrganisasi::where('id_forum', $forums->id)->get();

        // Cek apakah user terdaftar sebagai anggota forum
        $akses = AnggotaForum::where('id_users', Auth::user()->id)->first();

        // Kirim data ke view
        return view('Pages.Forum.ForumKas', compact('forums', 'kas', 'akses'));
    }

    /**
     * Menampilkan form untuk menambah data kas baru.
     * 
     * Logika:
     * - Ambil data forum berdasarkan slug.
     * - Ambil seluruh data kas terkait (jika ingin ditampilkan dalam tabel preview).
     */
    public function indexAdd($slug)
    {
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $kas = KasOrganisasi::where('id_forum', $forums->id)->get();

        return view('pages.Forum.addKas', compact('forums', 'kas'));
    }

    /**
     * Menampilkan form untuk mengedit data kas yang sudah ada.
     * 
     */
    public function indexUpdate($slug, $id)
    {
        // Ambil forum dan transaksi kas yang akan diubah
        $forums = ForumOrganisasi::where('slug', $slug)->first();
        $kas = KasOrganisasi::find($id);

        return view('pages.Forum.UpdateKas', compact('forums', 'kas'));
    }

    /**
     * Proses penambahan data kas baru.
     * Langkah:
     * - Validasi input form.
     * - Simpan data transaksi ke tabel `kas_organisasi`.
     */
    public function add(Request $request, $slug)
    {
        // Ambil data forum berdasarkan slug
        $forum = ForumOrganisasi::where('slug', $slug)->first();

        // Validasi input pengguna
        $transaksi = $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tgl_transaksi_org' => 'required|date',
        ]);

        // Simpan data ke tabel kas_organisasi
        KasOrganisasi::create([
            'id_forum' => $forum->id,
            'nama_transaksi' => $transaksi['nama_transaksi'],
            'jumlah' => $transaksi['jumlah'],
            'tgl_transaksi_org' => $transaksi['tgl_transaksi_org'],
        ]);

        // Redirect ke halaman daftar kas dengan pesan sukses
        return redirect()->route('forum.kas', $forum->slug)->with('success', 'Data kas berhasil ditambahkan.');
    }

    /**
     * Proses pembaruan data kas yang sudah ada.
     * 
     */
    public function update(Request $request, $slug, $id)
    {
        // Validasi input pengguna
        $validated = $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tgl_transaksi_org' => 'required|date',
        ]);

        // Ambil data kas berdasarkan ID (pastikan ada)
        $kas = KasOrganisasi::where('id', $id)->findOrFail($id);

        // Update data kas sesuai input
        $kas->update($validated);

        // Redirect kembali dengan notifikasi sukses
        return redirect()->route('forum.kas', $slug)->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus data kas berdasarkan ID.
     * 
     */
    public function delete($slug, $id)
    {
        // Temukan data kas dan hapus
        $kas = KasOrganisasi::where('id', $id)->firstOrFail();
        $kas->delete();

        // Kembali ke halaman kas dengan pesan sukses
        return redirect()->route('forum.kas', $slug)->with('success', 'Transaksi berhasil dihapus.');
    }
}
