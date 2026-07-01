<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with([
            'pasien',
            'jadwalPeriksa',
            'periksas'
        ])
        ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
            $query->where('id_dokter', $dokterId);
        })
        ->orderBy('no_antrian')
        ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();

        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required|exists:daftar_poli,id',
            'obat_json'      => 'required|json',
            'catatan'        => 'nullable|string',
            'biaya_periksa'  => 'required|integer',
        ]);

        // Decode data obat beserta jumlahnya
        $daftarObat = json_decode($request->obat_json, true);

        // Cek stok terlebih dahulu
        foreach ($daftarObat as $item) {

            $obat = Obat::findOrFail($item['id']);

            if ($obat->stok < $item['jumlah']) {

                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'stok' => 'Stok obat "' . $obat->nama_obat . '" tidak mencukupi.'
                    ]);
            }
        }

        // Simpan data pemeriksaan
        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa'    => now(),
            'catatan'        => $request->catatan,
            'biaya_periksa'  => $request->biaya_periksa + 150000,
        ]);

        // Simpan detail obat dan kurangi stok
        foreach ($daftarObat as $item) {

            $obat = Obat::findOrFail($item['id']);

            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat'    => $obat->id,
                'jumlah'     => $item['jumlah'],
            ]);

            // Kurangi stok
            $obat->stok -= $item['jumlah'];
            $obat->save();
        }

        return redirect()
            ->route('dokter.periksa-pasien.index')
            ->with('success', 'Data pemeriksaan berhasil disimpan.');
    }
}