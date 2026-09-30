<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah OPD Baru - Diskominfo HSU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-6">Tambah OPD / Dinas Baru</h2>

            <form action="{{ route('opd.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Dinas / OPD</label>
                    <input type="text" name="nama_dinas" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Singkatan / Akronim</label>
                        <input type="text" name="singkatan" placeholder="Contoh: DISKOMINFO" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Klaster OPD</label>
                        <select name="klaster_opd_id" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Klaster --</option>
                            @foreach($klasters as $klaster)
                                <option value="{{ $klaster->id }}">{{ $klaster->nama_klaster }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">URL Website Utama (Domain)</label>
                    <input type="url" name="domain_url" placeholder="https://diskominfo.hsu.go.id" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">URL RSS Feed</label>
                    <input type="url" name="rss_url" placeholder="https://diskominfo.hsu.go.id/feed" required class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama PIC / Penanggung Jawab</label>
                    <input type="text" name="pic_nama" placeholder="Bidang E-Government" class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('opd.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 text-sm font-medium rounded-lg">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">Simpan OPD</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>