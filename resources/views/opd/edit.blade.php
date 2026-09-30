@extends('layouts.app')

@section('page_title', 'Edit Data OPD')

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

<div class="max-w-5xl mx-auto animate-fade-in-up space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-md shadow-amber-500/20">
                <i class="fa-solid fa-pen-to-square text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Data OPD</h2>
                <p class="text-slate-500 text-xs sm:text-sm mt-0.5">Perbarui informasi Organisasi Perangkat Daerah yang terdaftar.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('opd.index') }}"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium transition duration-200 active:scale-95">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8">
            <form action="{{ route('opd.update', $dinas->id) }}" method="POST" id="formEditOpd">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Dinas / Instansi -->
                    <div class="space-y-1.5 md:col-span-2 sm:col-span-1">
                        <label for="nama_dinas" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Nama Dinas / Instansi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-building text-sm"></i>
                            </div>
                            <input type="text" name="nama_dinas" id="nama_dinas" value="{{ old('nama_dinas', $dinas->nama_dinas) }}" required
                                placeholder="Contoh: Dinas Komunikasi dan Informatika"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200 @error('nama_dinas') border-rose-500 bg-rose-50/30 @enderror">
                        </div>
                        @error('nama_dinas')
                            <p class="text-xs text-rose-500 flex items-center mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Singkatan / Kode -->
                    <div class="space-y-1.5">
                        <label for="singkatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Singkatan / Kode OPD <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-font text-sm"></i>
                            </div>
                            <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan', $dinas->singkatan) }}" required
                                placeholder="Contoh: DISKOMINFO"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 uppercase placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200 @error('singkatan') border-rose-500 bg-rose-50/30 @enderror">
                        </div>
                        @error('singkatan')
                            <p class="text-xs text-rose-500 flex items-center mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Klaster -->
                    <div class="space-y-1.5">
                        <label for="klaster_id" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Klaster Dinas
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-layer-group text-sm"></i>
                            </div>
                            <select name="klaster_id" id="klaster_id"
                                class="w-full pl-10 pr-8 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200 appearance-none @error('klaster_id') border-rose-500 bg-rose-50/30 @enderror">
                                <option value="">-- Pilih Klaster Dinas --</option>
                                @if(isset($klasters))
                                    @foreach($klasters as $klaster)
                                        <option value="{{ $klaster->id }}" {{ old('klaster_id', $dinas->klaster_id) == $klaster->id ? 'selected' : '' }}>
                                            {{ $klaster->nama_klaster }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('klaster_id')
                            <p class="text-xs text-rose-500 flex items-center mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL Website Utama -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="url_website" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            URL Website Resmi Dinas
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-globe text-sm"></i>
                            </div>
                            <input type="url" name="url_website" id="url_website" value="{{ old('url_website', $dinas->url_website ?? $dinas->domain_url) }}"
                                placeholder="https://diskominfo.hsu.go.id"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition duration-200 @error('url_website') border-rose-500 bg-rose-50/30 @enderror">
                        </div>
                        <p class="text-[11px] text-slate-400">Gunakan format URL lengkap diawali dengan http:// atau https://</p>
                        @error('url_website')
                            <p class="text-xs text-rose-500 flex items-center mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Footer Tombol Aksi -->
                <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('opd.index') }}"
                       class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium transition duration-200 active:scale-95">
                        Batal
                    </a>
                    <button type="submit" id="btnSubmit"
                            class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-sm font-medium shadow-md shadow-amber-500/20 transition duration-200 active:scale-95">
                        <i class="fa-solid fa-floppy-disk text-xs"></i>
                        <span id="btnText">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('formEditOpd').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');

        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        btnText.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Menyimpan...';
    });
</script>
@endsection
