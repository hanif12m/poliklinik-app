<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('dokter.periksa-pasien.index') }}"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-sm rounded-2xl border border-slate-200">
        <div class="card-body p-8">

            <form action="{{ route('dokter.periksa-pasien.store') }}" method="POST">
                @csrf

                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">

                {{-- Pilih Obat --}}
                <div class="form-control mb-5">
                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Pilih Obat <span class="text-red-500">*</span>
                        </span>
                    </label>

                    <select id="select-obat"
                        class="select select-bordered w-full rounded-lg border-2 px-4">

                        <option value="">-- Pilih Obat --</option>

                        @foreach($obats as $obat)

                            <option
                                value="{{ $obat->id }}"
                                data-nama="{{ $obat->nama_obat }}"
                                data-harga="{{ $obat->harga }}"
                                data-stok="{{ $obat->stok }}"
                                {{ $obat->stok == 0 ? 'disabled' : '' }}>

                                {{ $obat->nama_obat }}
                                - Rp{{ number_format($obat->harga,0,',','.') }}
                                (Stok : {{ $obat->stok }})

                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Obat Terpilih --}}
                <div class="form-control mb-5">

                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Obat Terpilih
                        </span>
                    </label>

                    <ul id="obat-terpilih"
                        class="flex flex-col gap-3 mb-2 min-h-[48px]"></ul>

                    <input
                        type="hidden"
                        name="biaya_periksa"
                        id="biaya_periksa"
                        value="0">

                    <input
                        type="hidden"
                        name="obat_json"
                        id="obat_json">

                </div>

                {{-- Total Harga --}}
                <div class="form-control mb-5">

                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Total Harga Obat
                        </span>
                    </label>

                    <div
                        id="total-harga"
                        class="input input-bordered rounded-lg bg-slate-50 flex items-center font-bold">

                        Rp 0

                    </div>

                </div>

                {{-- Catatan --}}
                <div class="form-control mb-8">

                    <label class="label pb-1">
                        <span class="text-sm font-semibold text-gray-700">
                            Catatan
                        </span>
                    </label>

                    <textarea
                        name="catatan"
                        rows="4"
                        class="textarea textarea-bordered border-2 rounded-lg">{{ old('catatan') }}</textarea>

                </div>

                {{-- Button --}}
                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="btn bg-[#2d4499] hover:bg-[#1e2d6b] text-white">

                        <i class="fas fa-save"></i>

                        Simpan

                    </button>

                    <a href="{{ route('dokter.periksa-pasien.index') }}"
                        class="btn bg-slate-100 hover:bg-slate-200">

                        Batal

                    </a>

                </div>

            </form>

        </div>
    </div>

<script>

const selectObat = document.getElementById('select-obat');
const listObat = document.getElementById('obat-terpilih');
const inputBiaya = document.getElementById('biaya_periksa');
const inputObatJson = document.getElementById('obat_json');
const totalHargaEl = document.getElementById('total-harga');

let daftarObat = [];

// Tambah obat
selectObat.addEventListener('change', () => {

    const selectedOption = selectObat.options[selectObat.selectedIndex];

    const id = selectedOption.value;
    const nama = selectedOption.dataset.nama;
    const harga = parseInt(selectedOption.dataset.harga || 0);
    const stok = parseInt(selectedOption.dataset.stok || 0);

    if (!id || daftarObat.some(o => o.id == id)) {
        return;
    }

    daftarObat.push({
        id: parseInt(id),
        nama: nama,
        harga: harga,
        stok: stok,
        jumlah: 1
    });

    renderObat();

    selectObat.selectedIndex = 0;

});

// Render daftar obat
function renderObat() {

    listObat.innerHTML = '';

    let total = 0;

    daftarObat.forEach((obat, index) => {

        total += obat.harga * obat.jumlah;

        const item = document.createElement('li');

        item.className = "bg-slate-50 border border-slate-200 rounded-lg p-4";

        item.innerHTML = `
            <div class="flex justify-between items-start">

                <div>

                    <div class="font-semibold text-slate-800">
                        ${obat.nama}
                    </div>

                    <div class="text-sm text-slate-500">
                        Harga : Rp ${obat.harga.toLocaleString()}
                    </div>

                    <div class="text-sm text-slate-500">
                        Stok : ${obat.stok}
                    </div>

                    <div class="mt-2 flex items-center gap-2">

                        <label class="text-sm">
                            Jumlah
                        </label>

                        <input
                            type="number"
                            min="1"
                            max="${obat.stok}"
                            value="${obat.jumlah}"
                            onchange="ubahJumlah(${index}, this.value)"
                            class="border rounded px-2 py-1 w-20">

                    </div>

                    <div class="mt-2 font-semibold text-blue-600">
                        Subtotal :
                        Rp ${(obat.harga * obat.jumlah).toLocaleString()}
                    </div>

                </div>

                <button
                    type="button"
                    onclick="hapusObat(${index})"
                    class="btn btn-sm bg-red-500 hover:bg-red-600 text-white">

                    <i class="fas fa-trash"></i>

                </button>

            </div>
        `;

        listObat.appendChild(item);

    });

    inputBiaya.value = total;

    totalHargaEl.textContent = ` Rp ${total.toLocaleString()}`;

    inputObatJson.value = JSON.stringify(daftarObat);

}

// Ubah jumlah obat
function ubahJumlah(index, jumlah) {

    jumlah = parseInt(jumlah);

    if (isNaN(jumlah) || jumlah < 1) {
        jumlah = 1;
    }

    if (jumlah > daftarObat[index].stok) {

        alert("Jumlah melebihi stok yang tersedia!");

        jumlah = daftarObat[index].stok;

    }

    daftarObat[index].jumlah = jumlah;

    renderObat();

}

// Hapus obat
function hapusObat(index) {

    daftarObat.splice(index, 1);

    renderObat();

}


function ubahJumlah(index, jumlah){

    jumlah = parseInt(jumlah);

    if(isNaN(jumlah) || jumlah < 1){
        jumlah = 1;
    }

    if(jumlah > daftarObat[index].stok){
        jumlah = daftarObat[index].stok;
    }

    daftarObat[index].jumlah = jumlah;

    renderObat();
}

</script>

</x-layouts.app>