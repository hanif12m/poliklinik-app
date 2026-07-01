<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::latest()->get();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'kemasan' => 'nullable|string', 
            'stok' => 'required|integer|min:0',
        ]);

        Obat::create([
            'nama_obat' => $request->nama_obat,
            'harga' => $request->harga,
            'kemasan' => $request->kemasan,
            'stok' => $request->stok,
        ]);

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil ditambahkan');
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_obat' => 'required',
            'harga' => 'required|numeric',
            'kemasan' => 'nullable|string', 
        ]);


        $obat = Obat::findOrFail($id);

        $obat->update([
            'nama_obat' => $request->nama_obat,
            'harga' => $request->harga,
            'kemasan' => $request->kemasan,
            'stok' => $request->stok, 
        ]);

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil diupdate');
    }

    public function destroy($id)
    {
        Obat::findOrFail($id)->delete();

        return redirect()->route('admin.obat.index')
            ->with('success', 'Obat berhasil dihapus');
    }
}