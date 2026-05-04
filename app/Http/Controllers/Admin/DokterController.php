<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    // ================= LIST DOKTER =================
    public function index()
    {
        $dokters = User::where('role', 'dokter')->with('poli')->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    // ================= FORM CREATE =================
    public function create()
    {
        $polis = Poli::all();
        return view('admin.dokter.create', compact('polis'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'alamat'  => 'required|string',
            'no_ktp'  => 'required|string|max:16|unique:users,no_ktp',
            'no_hp'   => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email'   => 'required|string|email|max:255|unique:users,email',
            'password'=> 'required|string|min:6',
        ]);

        User::create([
            'name'     => $request->nama,
            'alamat'   => $request->alamat,
            'no_ktp'   => $request->no_ktp,
            'no_hp'    => $request->no_hp,
            'id_poli'  => $request->id_poli,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'dokter',
        ]);

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter berhasil ditambahkan')
            ->with('type', 'success');
    }

    // ================= EDIT =================
    public function edit(User $dokter)
    {
        $polis = Poli::all();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    // ================= UPDATE =================
    public function update(Request $request, User $dokter)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'alamat'  => 'required|string',
            'no_ktp'  => 'required|string|max:16|unique:users,no_ktp,' . $dokter->id,
            'no_hp'   => 'required|string|max:15',
            'id_poli' => 'required|exists:poli,id',
            'email'   => 'required|string|email|max:255|unique:users,email,' . $dokter->id,
            'password'=> 'nullable|string|min:6',
        ]);

        $updateData = [
            'name'    => $request->nama,
            'alamat'  => $request->alamat,
            'no_ktp'  => $request->no_ktp,
            'no_hp'   => $request->no_hp,
            'id_poli' => $request->id_poli,
            'email'   => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $dokter->update($updateData);

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter berhasil diubah')
            ->with('type', 'success');
    }

    public function show(User $dokter)
    {
        return view('admin.dokter.show', compact('dokter'));
    }

    // ================= DELETE =================
    public function destroy(User $dokter)
    {
        $dokter->delete();

        return redirect()->route('dokter.index')
            ->with('message', 'Data Dokter berhasil dihapus')
            ->with('type', 'success');
    }
}