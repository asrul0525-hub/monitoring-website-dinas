<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola OPD - Diskominfo HSU</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-800 flex min-h-screen">

    <!-- ================= SIDEBAR KIRI ================= -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 shadow-xl">
        <!-- Brand Header -->
        <div class="p-5 border-b border-slate-800 flex items-center space-x-3">
            <div class="bg-blue-600 p-2.5 rounded-xl shadow-md flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-xl text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-base tracking-wide leading-tight text-white">MONITORING OPD</h1>
                <p class="text-[11px] text-slate-400">Diskominfo HSU</p>
            </div>
        </div>

        <!-- Menu Navigasi Utama -->
        <div class="px-4 py-6 space-y-1.5 flex-1">
            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu Utama</p>

            <!-- Menu Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-slate-300 hover:bg-slate-800 hover:text-white font-medium text-sm transition">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                <span>Dashboard Status</span>
            </a>

            <!-- Menu Kelola OPD (Aktif) -->
            <a href="{{ route('opd.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg bg-blue-600 text-white font-medium text-sm transition shadow-sm">
                <i class="fa-solid fa-building-user w-5 text-center"></i>
                <span>Kelola OPD</span>
            </a>
        </div>

        <!-- Footer Sidebar & Action Sync -->
        <div class="p-4 border-t border-slate-800 space-y-3">
            <form action="{{ route('dashboard.sync') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white border border-rose-500/20 hover:border-rose-600 font-medium px-4 py-2.5 rounded-lg text-sm transition shadow flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    <span>Keluar / Logout</span>
                </button>
            </form>
            <div class="text-[11px] text-center text-slate-500">
                System Version 1.0 &bull; HSU
            </div>
        </div>
    </aside>
    <!-- ================= END SIDEBAR ================= -->

    <!-- ================= AREA KONTEN UTAMA ================= -->
    <main class="flex-1 ml-64 p-8 min-h-screen">

        <!-- Top Status Bar / Header Utama -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Kelola Data OPD & Website</h2>
                <p class="text-slate-500 text-sm mt-1">Manajemen daftar Organisasi Perangkat Daerah, URL domain, dan konfigurasi RSS feed.</p>
            </div>
            <div>
                <a href="{{ route('opd.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah OPD Baru</span>
                </a>
            </div>
        </div>

        <!-- Alert Notifikasi Flash Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm flex items-center">
                <i class="fa-solid fa-circle-check me-3 text-lg text-emerald-600"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-xl shadow-sm flex items-center">
                <i class="fa-solid fa-circle-exclamation me-3 text-lg text-rose-600"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
            <form action="{{ route('opd.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama OPD / singkatan..."
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

        <!-- Table Data OPD -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Daftar OPD Terdaftar</h3>
                <span class="text-xs text-slate-500">Total {{ $dinasList->count() }} OPD</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Nama Dinas / Instansi</th>
                            <th class="px-6 py-3">Klaster</th>
                            <th class="px-6 py-3">URL Website & RSS Feed</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dinasList as $dinas)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $dinas->nama_dinas }}</div>
                                <span class="inline-block mt-0.5 bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded font-mono">
                                    {{ $dinas->singkatan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-medium border border-blue-100">
                                    {{ $dinas->klaster ? $dinas->klaster->nama_klaster : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                <div>
                                    <a href="{{ $dinas->domain_url }}" target="_blank" class="text-xs text-blue-600 hover:underline inline-flex items-center">
                                        <i class="fa-solid fa-globe me-1 text-slate-400"></i> {{ $dinas->domain_url }}
                                    </a>
                                </div>
                                <div class="text-[11px] text-slate-400 inline-flex items-center">
                                    <i class="fa-solid fa-rss me-1 text-amber-500"></i> {{ $dinas->rss_url ?? 'RSS Otomatis' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('opd.edit', $dinas->id) }}" class="bg-amber-50 hover:bg-amber-100 text-amber-700 p-2 rounded-lg transition border border-amber-200" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <!-- Delete Button Form -->
                                    <form action="{{ route('opd.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus OPD {{ $dinas->nama_dinas }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-700 p-2 rounded-lg transition border border-rose-200" title="Hapus OPD">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-building-circle-xmark text-3xl mb-2"></i>
                                <p>Belum ada data OPD yang ditambahkan atau tidak sesuai filter.</p>
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

    </main>
    <!-- ================= END KONTEN UTAMA ================= -->

</body>
</html>
