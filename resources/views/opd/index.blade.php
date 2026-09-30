@extends('layouts.app')

@section('page_title', 'Kelola Data Organisasi Perangkat Daerah')

@section('content')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<div class="space-y-6 animate-fade-in-up">
    <!-- Top Header Halaman Kelola OPD -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                <i class="fa-solid fa-building text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Data OPD</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Kelola daftar Organisasi Perangkat Daerah, Klaster, dan URL website utama.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('opd.create') }}"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium shadow-md shadow-blue-500/20 transition duration-200 active:scale-95">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah OPD Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('opd.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama dinas / singkatan..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200">
            </div>
            <div class="relative">
                <select name="klaster_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200">
                    <option value="">-- Semua Klaster --</option>
                    @if(isset($klasters))
                        @foreach($klasters as $klaster)
                            <option value="{{ $klaster->id }}" {{ request('klaster_id') == $klaster->id ? 'selected' : '' }}>
                                {{ $klaster->nama_klaster }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl text-xs transition duration-200 shadow-sm flex items-center justify-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('opd.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2 px-3 rounded-xl text-xs transition duration-200 flex items-center justify-center active:scale-95" title="Reset Filter">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Master Data OPD -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-list text-blue-600 text-sm"></i>
                <h3 class="font-bold text-slate-900 text-sm">Daftar OPD / Instansi Terdaftar</h3>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                Total {{ isset($dinasList) && method_exists($dinasList, 'total') ? $dinasList->total() : (isset($dinasList) ? $dinasList->count() : 0) }} Data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 uppercase tracking-wider font-bold text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Dinas & Singkatan</th>
                        <th class="px-6 py-3.5">Klaster Dinas</th>
                        <th class="px-6 py-3.5">URL Website Resmi</th>
                        <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if(isset($dinasList) && $dinasList->count() > 0)
                        @foreach($dinasList as $dinas)
                        <tr class="hover:bg-slate-50/80 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $dinas->nama_dinas }}</div>
                                <span class="inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded mt-1">
                                    {{ $dinas->singkatan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-lg font-medium">
                                    {{ $dinas->klaster ? $dinas->klaster->nama_klaster : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($dinas->url_website || $dinas->domain_url)
                                    <a href="{{ $dinas->url_website ?? $dinas->domain_url }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center text-xs font-medium">
                                        <i class="fa-solid fa-globe text-[11px] me-1.5 text-slate-400"></i> {{ $dinas->url_website ?? $dinas->domain_url }}
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <a href="{{ route('opd.edit', $dinas->id) }}"
                                       class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg text-xs font-medium transition duration-200 active:scale-95" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('opd.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data OPD ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-medium transition duration-200 active:scale-95" title="Hapus Data">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-folder-open text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Data OPD belum tersedia atau tidak ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Silakan klik tombol "Tambah OPD Baru" di atas untuk menambahkan data.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if(isset($dinasList) && method_exists($dinasList, 'links'))
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $dinasList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
