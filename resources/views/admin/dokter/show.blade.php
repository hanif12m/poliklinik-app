<x-layouts.app title="Detail Dokter">

    <div class="mb-6">
        <h2 class="text-2xl font-bold">Detail Dokter</h2>
    </div>

    <div class="card p-6">
        <p><strong>Nama:</strong> {{ $dokter->nama }}</p>
        <p><strong>Email:</strong> {{ $dokter->email }}</p>
        <p><strong>No KTP:</strong> {{ $dokter->no_ktp }}</p>
        <p><strong>No HP:</strong> {{ $dokter->no_hp }}</p>
        <p><strong>Alamat:</strong> {{ $dokter->alamat }}</p>
        <p><strong>Poli:</strong> {{ $dokter->poli->nama_poli ?? '-' }}</p>
    </div>

    <a href="{{ route('admin.dokter.index') }}">Kembali</a>

</x-layouts.app>