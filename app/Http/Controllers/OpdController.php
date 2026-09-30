<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\KlasterOpd; // Menggunakan model KlasterOpd
use App\Services\RssSyncService;
use Illuminate\Http\Request;

class OpdController extends Controller
{
    // Tampilkan Halaman Daftar OPD (dengan pencarian & filter klaster)
    public function index(Request $request)
    {
        $query = Dinas::with('klaster');

        // Fitur Pencarian (Nama Dinas / Singkatan)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_dinas', 'like', '%' . $request->search . '%')
                  ->orWhere('singkatan', 'like', '%' . $request->search . '%');
            });
        }

        // Fitur Filter Berdasarkan Klaster
        if ($request->filled('klaster_id')) {
            $query->where('klaster_opd_id', $request->klaster_id);
        }

        $dinasList = $query->latest()->paginate(10);

        // Ambil data semua klaster menggunakan KlasterOpd
        $klasters = KlasterOpd::all();

        // Kirim $dinasList dan $klasters ke view
        return view('opd.index', compact('dinasList', 'klasters'));
    }

    // Tampilkan Form Tambah OPD
    public function create()
    {
        $klasters = KlasterOpd::all();
        return view('opd.create', compact('klasters'));
    }

    // Simpan Data OPD Baru
    public function store(Request $request, RssSyncService $rssService) // <-- Diberi RssSyncService $rssService
    {
        $request->validate([
            'nama_dinas'     => 'required|string|max:255',
            'singkatan'      => 'required|string|max:50',
            'klaster_opd_id' => 'required|exists:klaster_opds,id',
            'domain_url'     => 'required|url',
            'rss_url'        => 'required|url',
            'pic_nama'       => 'nullable|string|max:255',
        ]);

        // 1. Simpan dan tampung hasil pembuatan ke variabel $dinas
        $dinas = Dinas::create([
            'nama_dinas'     => $request->nama_dinas,
            'singkatan'      => $request->singkatan,
            'klaster_opd_id' => $request->klaster_opd_id,
            'domain_url'     => $request->domain_url,
            'rss_url'        => $request->rss_url,
            'pic_nama'       => $request->pic_nama,
            'status'         => 'pasif', // Status default sebelum di-sync
        ]);

        // 2. Jalankan sinkronisasi RSS instan
        if (!empty($dinas->rss_url)) {
            $rssService->syncDinas($dinas);
        }

        return redirect()->route('opd.index')->with('success', 'Data OPD berhasil ditambahkan dan disinkronkan!');
    }

    // Tampilkan Form Edit OPD
    public function edit($id)
    {
        $dinas = Dinas::findOrFail($id);
        $klasters = KlasterOpd::all();
        return view('opd.edit', compact('dinas', 'klasters'));
    }

    // Update Data OPD
    public function update(Request $request, $id, RssSyncService $rssService) // <-- Diberi RssSyncService $rssService
    {
        $dinas = Dinas::findOrFail($id);

        $request->validate([
            'nama_dinas'     => 'required|string|max:255',
            'singkatan'      => 'required|string|max:50',
            'klaster_opd_id' => 'required|exists:klaster_opds,id',
            'domain_url'     => 'required|url',
            'rss_url'        => 'required|url',
            'pic_nama'       => 'nullable|string|max:255',
        ]);

        $dinas->update($request->all());

        // Jalankan sinkronisasi RSS instan menggunakan data yang baru diperbarui
        if (!empty($dinas->rss_url)) {
            $rssService->syncDinas($dinas);
        }

        return redirect()->route('opd.index')->with('success', 'Data OPD berhasil diperbarui dan disinkronkan!');
    }

    // Hapus Data OPD
    public function destroy($id)
    {
        $dinas = Dinas::findOrFail($id);
        $dinas->delete();

        return redirect()->route('opd.index')->with('success', 'Data OPD berhasil dihapus!');
    }
}
