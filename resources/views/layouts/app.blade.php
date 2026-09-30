<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monitoring Website OPD - Kominfo HSU')</title>
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
            <a href="{{ route('dashboard') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('dashboard*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                <span>Dashboard Status</span>
            </a>

            <!-- Menu Kelola OPD -->
            <a href="{{ route('opd.index') }}"
               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('opd.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-building-user w-5 text-center"></i>
                <span>Kelola OPD</span>
            </a>
        </div>

        <!-- Footer Sidebar: Tombol Logout & Version -->
        <div class="p-4 border-t border-slate-800 space-y-3">
            @if (Route::has('logout'))
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white border border-rose-500/20 hover:border-rose-600 font-medium px-4 py-2.5 rounded-lg text-sm transition shadow flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-right-from-bracket text-xs"></i>
                        <span>Keluar / Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ url('/') }}"
                   class="w-full bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white border border-rose-500/20 hover:border-rose-600 font-medium px-4 py-2.5 rounded-lg text-sm transition shadow flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                    <span>Keluar / Logout</span>
                </a>
            @endif
            <div class="text-[11px] text-center text-slate-500">
                System Version 1.0 &bull; HSU
            </div>
        </div>
    </aside>

    <!-- ================= AREA KONTEN UTAMA ================= -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">

        <!-- ================= TOP HEADER BAR ================= -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-40 px-8 py-3.5 flex items-center justify-between shadow-sm">
            <!-- Breadcrumb / System Title -->
            <div class="flex items-center space-x-3 text-slate-500 text-sm">
                <i class="fa-solid fa-cubes text-blue-600"></i>
                <span class="text-slate-400">Sistem</span>
                <span>/</span>
                <span class="font-semibold text-slate-800">@yield('page_title', 'Dashboard Pemantauan Digital Dinas')</span>
            </div>

            <!-- Right Elements: Clock, Notification & Profile -->
            <div class="flex items-center space-x-6">
                <!-- Live Clock Widget (WITA) -->
                <div class="bg-blue-50 text-blue-800 text-xs font-medium px-4 py-2 rounded-full flex items-center space-x-2 border border-blue-100 shadow-sm">
                    <i class="fa-regular fa-clock text-blue-600"></i>
                    <span id="realtime-clock">Memuat waktu...</span>
                </div>

                <!-- Notification Bell Dropdown -->
                <div class="relative">
                    <button id="notifButton" type="button" class="p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-full transition relative focus:outline-none">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <!-- Indicator Badge -->
                        <span class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                            3
                        </span>
                    </button>

                    <!-- Dropdown Menu Notifikasi -->
                    <div id="notifDropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-xl border border-slate-200 z-50 overflow-hidden">
                        <!-- Dropdown Header -->
                        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <h5 class="font-bold text-xs text-slate-800 uppercase tracking-wider">Notifikasi Sistem</h5>
                            <span class="bg-rose-100 text-rose-700 text-[10px] font-bold px-2 py-0.5 rounded-full">3 Baru</span>
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                            <!-- Item 1 -->
                            <a href="{{ route('dashboard') }}?status=pasif" class="p-3.5 flex items-start space-x-3 hover:bg-slate-50 transition block">
                                <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">3 OPD Terdeteksi Pasif</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Website tidak ada pembaruan artikel lebih dari 30 hari.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">Baru saja</span>
                                </div>
                            </a>

                            <!-- Item 2 -->
                            <a href="{{ route('dashboard') }}" class="p-3.5 flex items-start space-x-3 hover:bg-slate-50 transition block">
                                <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-rotate text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">Sinkronisasi RSS Selesai</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Seluruh feed RSS OPD berhasil diperbarui.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">1 jam lalu</span>
                                </div>
                            </a>

                            <!-- Item 3 -->
                            <a href="{{ route('opd.index') }}" class="p-3.5 flex items-start space-x-3 hover:bg-slate-50 transition block">
                                <div class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fa-solid fa-globe text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">URL RSS Perlu Diperiksa</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Beberapa domain OPD mengembalikan error timeout.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">3 jam lalu</span>
                                </div>
                            </a>
                        </div>

                        <!-- Dropdown Footer -->
                        <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                Lihat Semua Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <!-- User Profile -->
                <div class="flex items-center space-x-3 border-l pl-5 border-slate-200">
                    <div class="w-9 h-9 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                        <i class="fa-solid fa-user-gear text-xs"></i>
                    </div>
                    <div class="text-left leading-tight">
                        <h4 class="font-bold text-xs text-slate-800">
                            Administrator
                        </h4>
                        <p class="text-[11px] text-blue-600 font-medium">Monitoring Kominfo HSU</p>
                    </div>
                </div>
            </div>
        </header>
        <!-- ================= END TOP HEADER BAR ================= -->

        <!-- Dynamic Main Content Area -->
        <main class="flex-1 p-8">
            <!-- Flash Session Alert -->
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

            <!-- Dynamic Content Slot -->
            @yield('content')
        </main>

    </div>

    <!-- Script Jam Real-Time (Format WITA) -->
    <script>
        function updateClock() {
            const now = new Date();

            // Ambil Hari dan Tanggal dalam format Bahasa Indonesia
            const dateOptions = {
                timeZone: 'Asia/Makassar', // Waktu Indonesia Tengah (WITA)
                weekday: 'long',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            };
            const dateString = new Intl.DateTimeFormat('id-ID', dateOptions).format(now);

            // Ambil Jam, Menit, dan Detik secara terpisah
            const timeOptions = {
                timeZone: 'Asia/Makassar',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            // Format waktu menjadi HH:mm:ss
            const timeParts = new Intl.DateTimeFormat('id-ID', timeOptions).formatToParts(now);
            let hours = '', minutes = '', seconds = '';

            timeParts.forEach(part => {
                if (part.type === 'hour') hours = part.value;
                if (part.type === 'minute') minutes = part.value;
                if (part.type === 'second') seconds = part.value;
            });

            // Gabungkan format secara presisi: Hari, Tanggal Bulan Tahun • HH:mm:ss WITA
            document.getElementById('realtime-clock').innerText = `${dateString} • ${hours}:${minutes}:${seconds} WITA`;
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>

    <script>
        // Toggle Dropdown Notifikasi
        const notifButton = document.getElementById('notifButton');
        const notifDropdown = document.getElementById('notifDropdown');

        if (notifButton && notifDropdown) {
            notifButton.addEventListener('click', function(e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
            });

            // Tutup dropdown saat mengeklik di luar area dropdown
            document.addEventListener('click', function(e) {
                if (!notifDropdown.contains(e.target) && !notifButton.contains(e.target)) {
                    notifDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
