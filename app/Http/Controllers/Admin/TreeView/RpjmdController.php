<?php

namespace App\Http\Controllers\Admin\TreeView;

use App\Events\UserLogged;
use App\Http\Controllers\Controller;
use App\Models\TabelRpjmd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RpjmdController extends Controller
{

    public function index()
    {
        $categories = TabelRpjmd::all();

        return view('admin.treeview.rpjmd', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'parent_id' =>  ['required', 'numeric', 'exists:tabel_rpjmd,id'],
            'nama_menu' => ['required', 'string', 'max:100']
        ]);

        $validated['skpd_id'] = Auth::user()->skpd->id;

        TabelRpjmd::create($validated);

        event(new UserLogged($request->user(), 'Menambahkan menu treeview RPJMD'));

        return back()->with('alert-success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $tabelRpjmd = TabelRpjmd::findOrFail($id);
        $categories = TabelRpjmd::all();

        return view('admin.treeview.rpjmd_edit', compact('categories', 'tabelRpjmd'));
    }

    public function update(Request $request, $id)
    {
        $tabelRpjmd = TabelRpjmd::findOrFail($id);

        $validated = $this->validate($request, [
            'parent_id' =>  ['required', 'numeric', 'exists:tabel_rpjmd,id'],
            'nama_menu' => ['required', 'string',  'max:100']
        ]);

        $tabelRpjmd->update($validated);

        event(new UserLogged($request->user(), 'Mengubah menu treeview RPJMD'));

        return back()->with('alert-success', 'Menu treeview RPJMD berhasil diupdate');
    }

    public function destroy(Request $request, $id)
    {
        $tabelRpjmd = TabelRpjmd::findOrFail($id);
        $tabelRpjmd->delete();

        event(new UserLogged($request->user(), 'Menghapus menu treeview RPJMD'));

        return back()->with('alert-success', 'Menu treeview RPJMD berhasil dihapus');
    }
}
