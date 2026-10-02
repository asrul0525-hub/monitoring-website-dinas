@extends('layouts.app')

@section('page_title', 'Dashboard Status Website OPD')

@section('content')
<!-- Import SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    .hover-elevate {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hover-elevate:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.08);
    }
</style>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header Dashboard Utama -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-500/20">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Dashboard Status Website OPD</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Pemantauan status dan keaktifan layanan website seluruh Organisasi Perangkat Daerah.</p>
            </div>
        </div>
        <div>
            <!-- Tombol Cek Status -->
            <button
                type="button"
                id="btnSync"
                onclick="refreshStatus(event)"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 inline-flex items-center gap-2 cursor-pointer">
                <i id="syncIcon" class="fas fa-sync-alt"></i>
                <span id="syncText">Cek Status Website</span>
            </button>
        </div>
    </div>

    <!-- Cards Summary Statistics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Website -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm hover-elevate relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Website</p>
                    <h3 id="stat-total" class="text-3xl font-extrabold text-slate-900 mt-1">{{ $totalWebsite ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-slate-900 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-globe text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-slate-500">
                <span class="font-medium">Terdaftar di sistem</span>
            </div>
        </div>

        <!-- Aktif / Online -->
        <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm hover-elevate relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Online / Aktif</p>
                    <h3 id="stat-aktif" class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $aktifCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-emerald-600 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block me-1.5 animate-pulse"></span>
                <span>Server merespon normal</span>
            </div>
        </div>

        <!-- Kurang Aktif / Lambat -->
        <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm hover-elevate relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Lambat / Warning</p>
                    <h3 id="stat-warning" class="text-3xl font-extrabold text-amber-600 mt-1">{{ $kurangAktifCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-amber-600 font-medium">
                <span>Respon lambat / isu jaringan</span>
            </div>
        </div>

        <!-- Pasif / Offline -->
        <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm hover-elevate relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Offline / Down</p>
                    <h3 id="stat-pasif" class="text-3xl font-extrabold text-rose-600 mt-1">{{ $pasifCount ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition duration-300">
                    <i class="fa-solid fa-circle-xmark text-lg"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-xs text-rose-600 font-medium">
                <span class="w-2 h-2 rounded-full bg-rose-500 inline-block me-1.5"></span>
                <span>Tidak dapat diakses</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <!-- Search -->
            <div class="relative md:col-span-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama dinas / singkatan..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200">
            </div>

            <!-- Klaster Filter -->
            <div class="relative">
                <select name="klaster_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200">
                    <option value="">-- Semua Klaster --</option>
                    @if(isset($klasters) && count($klasters) > 0)
                        @foreach($klasters as $klaster)
                            <option value="{{ $klaster->id }}" {{ request('klaster_id') == $klaster->id ? 'selected' : '' }}>
                                {{ $klaster->nama_klaster }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Status Server Filter -->
            <div class="relative">
                <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200">
                    <option value="">-- Semua Status Server --</option>
                    <option value="online" {{ request('status') == 'online' ? 'selected' : '' }}>Online (200 OK)</option>
                    <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>Warning / Slow</option>
                    <option value="offline" {{ request('status') == 'offline' ? 'selected' : '' }}>Offline / Down</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-xl text-xs transition duration-200 shadow-sm flex items-center justify-center space-x-1.5 active:scale-95">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                <a href="{{ route('dashboard') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2 px-3 rounded-xl text-xs transition duration-200 flex items-center justify-center active:scale-95" title="Reset Filter">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Table Website Status -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-server text-blue-600 text-sm"></i>
                <h3 class="font-bold text-slate-900 text-sm">Daftar Website Organisasi Perangkat Daerah</h3>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                Menampilkan {{ isset($dinasList) ?$dinasList->count() : 0 }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-700 uppercase tracking-wider font-bold text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Nama Dinas / Instansi</th>
                        <th class="px-6 py-3.5">Klaster</th>
                        <th class="px-6 py-3.5">HTTP Status</th>
                        <th class="px-6 py-3.5">Waktu Respon</th>
                        <th class="px-6 py-3.5">Pengecekan Terakhir</th>
                        <th class="px-6 py-3.5 text-center">Status Server</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($dinasList ?? [] as $dinas)
                        <tr id="row-dinas-{{ $dinas->id }}" class="hover:bg-slate-50/80 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm">{{ $dinas->nama_dinas }}</div>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded">
                                        {{ $dinas->singkatan }}
                                    </span>
                                    @if($dinas->url_website || $dinas->domain_url)
                                        <a href="{{ $dinas->url_website ?? $dinas->domain_url }}" target="_blank" class="text-blue-600 hover:underline">
                                            <i class="fa-solid fa-up-right-from-square text-[9px] me-1"></i> Buka Web
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-lg font-medium">
                                    {{ $dinas->klaster ? $dinas->klaster->nama_klaster : '-' }}
                                </span>
                            </td>

                            <!-- HTTP STATUS -->
                            <td class="px-6 py-4 font-semibold col-http-status">
                                @php $code = $dinas->http_status ?? $dinas->http_status_code; @endphp
                                @if($code === 200)
                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">200 OK</span>
                                @elseif($code === 0 || $code === null)
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px]">0 Timeout</span>
                                @else
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px]">{{ $code }} Error</span>
                                @endif
                            </td>

                            <!-- WAKTU RESPON -->
                            <td class="px-6 py-4 text-slate-700 font-medium col-response-time">
                                @php $respTime = $dinas->response_time ?? $dinas->response_time_ms; @endphp
                                @if(!is_null($respTime) && $respTime > 0)                                     {{$respTime }} ms
                                @else
                                    <span class="text-slate-400 font-normal">-</span>
                                @endif
                            </td>

                            <!-- PENGECEKAN TERAKHIR -->
                            <td class="px-6 py-4 text-slate-500 col-last-checked">
                                @php $lastCheck = $dinas->last_checked_at ?? $dinas->updated_at; @endphp
                                {{ $lastCheck ? \Carbon\Carbon::parse($lastCheck)->diffForHumans() : '-' }}
                            </td>

                            <!-- STATUS SERVER -->
                            <td class="px-6 py-4 text-center col-server-status">
                                @if(($dinas->status ?? 'online') == 'online')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5 animate-pulse"></span> ONLINE
                                    </span>
                                @elseif(($dinas->status ?? '') == 'warning')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 me-1.5"></span> SLOW
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> OFFLINE
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fa-solid fa-globe text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">Belum ada data dinas terdaftar</p>
                                <p class="text-xs text-slate-400 mt-1">Tambahkan data OPD terlebih dahulu di menu Kelola OPD.</p>
                            </td>
                        </tr>
                    @endforelse
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

<script>
    // 1. Handler Tombol Manual
    function refreshStatus(e) {
        if (e) e.preventDefault();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Memeriksa Status...',
                text: 'Sedang mengecek koneksi ke seluruh website OPD.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        fetchBackgroundData(true);
    }

    // 2. Main Fetch Function
    function fetchBackgroundData(isManualClick = false) {
        const btn = document.getElementById('btnSync');
        const icon = document.getElementById('syncIcon');
        const text = document.getElementById('syncText');

        if (btn) btn.style.pointerEvents = 'none';
        if (icon) icon.classList.add('fa-spin');
        if (text) text.innerText = 'Memeriksa...';

        fetch("{{ route('dinas.check-status-json') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server (HTTP ' + response.status + ')');
                }
                return response.json();
            })
            .then(res => {
                if (res.status === 'success') {
                    if (res.totalWebsite !== undefined) {
                        const el = document.getElementById('stat-total');
                        if (el) el.innerText = res.totalWebsite;
                    }
                    if (res.aktifCount !== undefined) {
                        const el = document.getElementById('stat-aktif');
                        if (el) el.innerText = res.aktifCount;
                    }
                    if (res.kurangAktifCount !== undefined) {
                        const el = document.getElementById('stat-warning');
                        if (el) el.innerText = res.kurangAktifCount;
                    }
                    if (res.pasifCount !== undefined) {
                        const el = document.getElementById('stat-pasif');
                        if (el) el.innerText = res.pasifCount;
                    }

                    if (res.data) {
                        const items = Array.isArray(res.data) ? res.data : (res.data.data || []);
                        items.forEach(dinas => {
                            const row = document.getElementById(`row-dinas-${dinas.id}`);
                            if (row) {
                                const httpCode = dinas.http_status ?? dinas.http_status_code;
                                const respTime = dinas.response_time ?? dinas.response_time_ms;

                                const colHttp = row.querySelector('.col-http-status');
                                if (colHttp) {
                                    if (httpCode === 200) {
                                        colHttp.innerHTML = `<span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[11px]">200 OK</span>`;
                                    } else if (httpCode === 0 || httpCode === null) {
                                        colHttp.innerHTML = `<span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px]">0 Timeout</span>`;
                                    } else {
                                        colHttp.innerHTML = `<span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px]">${httpCode} Error</span>`;
                                    }
                                }

                                const colResp = row.querySelector('.col-response-time');
                                if (colResp) {
                                    colResp.innerHTML = (respTime && respTime > 0)
                                        ? `${respTime} ms`
                                        : `<span class="text-slate-400 font-normal">-</span>`;
                                }

                                const colLast = row.querySelector('.col-last-checked');
                                if (colLast) colLast.innerText = 'baru saja';

                                const colStatus = row.querySelector('.col-server-status');
                                if (colStatus) {
                                    if (dinas.status === 'online') {
                                        colStatus.innerHTML = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5 animate-pulse"></span> ONLINE</span>`;
                                    } else if (dinas.status === 'warning') {
                                        colStatus.innerHTML = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 me-1.5"></span> SLOW</span>`;
                                    } else {
                                        colStatus.innerHTML = `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60"><span class="w-1.5 h-1.5 rounded-full bg-rose-500 me-1.5"></span> OFFLINE</span>`;
                                    }
                                }
                            }
                        });
                    }

                    if (isManualClick && typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengecekan Selesai!',
                            text: 'Status keaktifan seluruh website OPD berhasil diperbarui.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    throw new Error(res.message || 'Respon server tidak valid');
                }
            })
            .catch(err => {
                console.error('Error sync status:', err);
                if (isManualClick && typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memeriksa!',
                        text: err.message || 'Terjadi kesalahan saat menghubungkan ke server.',
                        confirmButtonColor: '#2563eb'
                    });
                }
            })
            .finally(() => {
                if (btn) btn.style.pointerEvents = 'auto';
                if (icon) icon.classList.remove('fa-spin');
                if (text) text.innerText = 'Cek Status Website';
            });
    }

    // 3. Auto Refresh setiap 30 detik
    setInterval(() => {
        fetchBackgroundData(false);
    }, 30000);
</script>
@endsection
