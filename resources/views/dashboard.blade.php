@extends('layouts.app')

@section('page_title', 'Dashboard Pemantauan Digital Dinas')

@section('content')
    <!-- Top Status Bar / Header Utama -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Dashboard Status Website OPD</h2>
            <p class="text-slate-500 text-sm mt-1">Pemantauan keaktifan update berita dan artikel seluruh Organisasi Perangkat Daerah.</p>
        </div>
        <div>
            <form action="{{ route('dashboard.sync') }}" method="POST">
                @csrf
                <button type="submit"
                        onclick="this.disabled=true; this.form.submit();"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl text-sm transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-rotate text-xs"></i>
                    <span>Sinkronkan RSS</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Metric Cards / Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <!-- Total Dinas -->
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Website</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_dinas'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-600 text-xl">
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="bg-white p-5 rounded-xl border border-emerald-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Aktif (&le; 7 Hari)</p>
                    <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $stats['aktif'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Status Kurang Aktif -->
        <div class="bg-white p-5 rounded-xl border border-amber-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Kurang Aktif (8-30 Hari)</p>
                    <h3 class="text-2xl font-bold text-amber-700 mt-1">{{ $stats['kurang_aktif'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 text-xl">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>

        <!-- Status Pasif -->
        <div class="bg-white p-5 rounded-xl border border-rose-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Pasif (&gt; 30 Hari)</p>
                    <h3 class="text-2xl font-bold text-rose-700 mt-1">{{ $stats['pasif'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center text-rose-600 text-xl">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
            <div>
                <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="kurang_aktif" {{ request('status') == 'kurang_aktif' ? 'selected' : '' }}>Kurang Aktif</option>
                    <option value="pasif" {{ request('status') == 'pasif' ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                    <i class="fa-solid fa-filter me-1"></i> Filter
                </button>
                <a href="{{ route('dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2 px-4 rounded-lg text-sm transition flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Data Dinas -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Daftar Website Organisasi Perangkat Daerah</h3>
            <span class="text-xs text-slate-500">Menampilkan {{ $dinasList->count() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3">Nama Dinas / Instansi</th>
                        <th class="px-6 py-3">Klaster</th>
                        <th class="px-6 py-3">Postingan Terakhir</th>
                        <th class="px-6 py-3">Tanggal Post</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dinasList as $dinas)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $dinas->nama_dinas }} ({{ $dinas->singkatan }})</div>
                            <a href="{{ $dinas->domain_url }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center mt-1">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] me-1"></i> {{ $dinas->domain_url }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full font-medium">
                                {{ $dinas->klaster ? $dinas->klaster->nama_klaster : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            @if($dinas->last_post_title)
                                <a href="{{ $dinas->last_post_link }}" target="_blank" class="text-slate-800 hover:text-blue-600 line-clamp-2 transition">
                                    {{ $dinas->last_post_title }}
                                </a>
                            @else
                                <span class="text-slate-400 italic">Belum ada artikel terdeteksi</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                            @if($dinas->last_post_date)
                                <div>{{ $dinas->last_post_date->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $dinas->last_post_date->diffForHumans() }}</div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($dinas->status == 'aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    <span class="w-2 h-2 me-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif
                                </span>
                            @elseif($dinas->status == 'kurang_aktif')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                    <span class="w-2 h-2 me-1.5 bg-amber-500 rounded-full"></span> Kurang Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                    <span class="w-2 h-2 me-1.5 bg-rose-500 rounded-full"></span> Pasif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
                            <p>Tidak ada data dinas yang cocok dengan pencarian / filter Anda.</p>
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
