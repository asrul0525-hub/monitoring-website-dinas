@extends('layouts.app')

@section('page_title', 'Kelola Data Organisasi Perangkat Daerah')

@section('content')
    <!-- Top Header Halaman Kelola OPD -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Kelola Data OPD</h2>
            <p class="text-slate-500 text-sm mt-1">Kelola daftar Organisasi Perangkat Daerah, domain URL, dan feed RSS.</p>
        </div>
        <div>
            <a href="{{ route('opd.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition shadow-md flex items-center space-x-2">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah OPD Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('opd.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama dinas / singkatan..."
                    class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="klaster_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Klaster --</option>
                    @foreach($klasters as $klaster)
                        <option value="{{ $klaster->id }}" {{ request('klaster_id') == $klaster->id ? 'selected' : '' }}>
                            {{ $klaster->nama_klaster }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('opd.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2 px-4 rounded-lg text-sm transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Master Data OPD -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Daftar OPD / Instansi Terdaftar</h3>
            <span class="text-xs text-slate-500">Total {{ $dinasList->total() }} Data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Nama Dinas & Singkatan</th>
                        <th class="px-6 py-3">Klaster</th>
                        <th class="px-6 py-3">Domain Website & RSS</th>
                        <th class="px-6 py-3">PIC Dinas</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dinasList as $dinas)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $dinas->nama_dinas }}</div>
                            <span class="inline-block bg-slate-100 text-slate-700 text-[11px] font-semibold px-2 py-0.5 rounded mt-1">
                                {{ $dinas->singkatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $dinas->klaster ? $dinas->klaster->nama_klaster : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-y-1">
                            <div>
                                <a href="{{ $dinas->domain_url }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center">
                                    <i class="fa-solid fa-globe text-[11px] me-1.5 text-slate-400"></i> {{ $dinas->domain_url }}
                                </a>
                            </div>
                            <div>
                                <a href="{{ $dinas->rss_url }}" target="_blank" class="text-xs text-amber-600 hover:underline inline-flex items-center">
                                    <i class="fa-solid fa-rss text-[11px] me-1.5"></i> {{ $dinas->rss_url }}
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <div class="font-medium text-slate-800">{{ $dinas->pic_nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('opd.edit', $dinas->id) }}"
                                   class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg text-xs font-medium transition" title="Edit Data">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('opd.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data OPD ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-medium transition" title="Hapus Data">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
                            <p>Data OPD belum tersedia atau tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200">
            {{ $dinasList->links() }}
        </div>
    </div>
@endsection
