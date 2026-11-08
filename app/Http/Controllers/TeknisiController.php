<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeknisiController extends Controller
{
    // MENAMPILKAN DATA TEKNISI
    public function index()
    {
        $teknisi = DB::table('tb_teknisi')->orderBy('id', 'DESC')->get();

        return view('teknisi.index', compact('teknisi'));
    }

    // FORM TAMBAH TEKNISI (Dialihkan kembali ke index karena pakai Modal)
    public function create()
    {
        return redirect()->route('teknisi.index');
    }

    // MENYIMPAN DATA TEKNISI
    public function store(Request $request)
    {
        $request->validate([
            'id_teknisi' => 'required|unique:tb_teknisi,id_teknisi',
            'nama'       => 'required',
            'jk'         => 'required',
            'department' => 'required',
            'no_hp'      => 'required',
            'alamat'     => 'required',
        ]);

        DB::table('tb_teknisi')->insert([
            'id_teknisi' => $request->id_teknisi,
            'nama'       => $request->nama,
            'jk'         => $request->jk,
            'department' => $request->department,
            'no_hp'      => $request->no_hp,
            'alamat'     => $request->alamat,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('teknisi.index')
            ->with('success', 'Data teknisi berhasil ditambahkan!');
    }

    // MENAMPILKAN DETAIL TEKNISI (Opsional, diarahkan ke index jika tidak pakai halaman detail)
    public function show(string $id)
    {
        return redirect()->route('teknisi.index');
    }

    // FORM EDIT TEKNISI (Karena pakai modal di index, cukup dialihkan)
    public function edit(string $id)
    {
        return redirect()->route('teknisi.index');
    }

    // MEMPERBARUI DATA TEKNISI
    public function update(Request $request, string $id)
    {
        $teknisi = DB::table('tb_teknisi')
            ->where('id', $id)
            ->first();

        abort_if(!$teknisi, 404);

        $request->validate([
            'id_teknisi' => 'required|string|max:50|unique:tb_teknisi,id_teknisi,' . $id . ',id',
            'nama'       => 'required',
            'jk'         => 'required',
            'department' => 'required',
            'no_hp'      => 'required',
            'alamat'     => 'required',
        ]);

        DB::table('tb_teknisi')
            ->where('id', $id)
            ->update([
                'id_teknisi' => $request->id_teknisi,
                'nama'       => $request->nama,
                'jk'         => $request->jk,
                'department' => $request->department,
                'no_hp'      => $request->no_hp,
                'alamat'     => $request->alamat,
                'updated_at' => now(),
            ]);

        return redirect()->route('teknisi.index')
            ->with('success', 'Data teknisi berhasil diperbarui!');
    }

    // MENGHAPUS DATA TEKNISI
    public function destroy(string $id)
    {
        $teknisi = DB::table('tb_teknisi')
            ->where('id', $id)
            ->first();

        abort_if(!$teknisi, 404);

        DB::table('tb_teknisi')
            ->where('id', $id)
            ->delete();

        return redirect()->route('teknisi.index')
            ->with('success', 'Data teknisi berhasil dihapus!');
    }
}