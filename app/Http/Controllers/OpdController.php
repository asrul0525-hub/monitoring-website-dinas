<?php

namespace App\Http\Controllers;

use App\Models\Dinas;
use App\Models\KlasterOpd;
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

        // Ambil data semua klaster
        $klasters = KlasterOpd::all();

        return view('opd.index', compact('dinasList', 'klasters'));
    }

    // Tampilkan Form Tambah OPD
    public function create()
    {
        $klasters = KlasterOpd::all();
        return view('opd.create', compact('klasters'));
    }

    // Simpan Data OPD Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_dinas'  => 'required|string|max:255',
            'singkatan'   => 'required|string|max:50',
            'klaster_id'  => 'nullable|exists:klaster_opds,id',
            'url_website' => 'required|url',
        ]);

        Dinas::create([
            'nama_dinas'     => $request->nama_dinas,
            'singkatan'      => $request->singkatan,
            'klaster_opd_id' => $request->klaster_id,
            'domain_url'     => $request->url_website, // Disimpan ke kolom domain_url
            'status'         => 'offline', // Status default awal sebelum dicek server
        ]);

        return redirect()->route('opd.index')->with('success', 'Data OPD berhasil ditambahkan!');
    }

    // Tampilkan Form Edit OPD
    public function edit($id)
    {
        $dinas = Dinas::findOrFail($id);
        $klasters = KlasterOpd::all();
        return view('opd.edit', compact('dinas', 'klasters'));
    }

    // Update Data OPD
    public function update(Request $request, $id)
    {
        $dinas = Dinas::findOrFail($id);

        $request->validate([
            'nama_dinas'  => 'required|string|max:255',
            'singkatan'   => 'required|string|max:50',
            'klaster_id'  => 'nullable|exists:klaster_opds,id',
            'url_website' => 'required|url',
        ]);

        $dinas->update([
            'nama_dinas'     => $request->nama_dinas,
            'singkatan'      => $request->singkatan,
            'klaster_opd_id' => $request->klaster_id,
            'domain_url'     => $request->url_website,
            'status'         => 'pasif',
        ]);

        return redirect()->route('opd.index')->with('success', 'Data OPD berhasil diperbarui!');
    }

    // Hapus Data OPD
    public function destroy($id)
    {
        $dinas = Dinas::findOrFail($id);
        $dinas->delete();

        return redirect()->route('opd.index')->with('success', 'Data OPD berhasil dihapus!');
    }
}
