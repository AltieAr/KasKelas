<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassAsset;
use Illuminate\Http\Request;

class ClassAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assets = ClassAsset::orderBy('tanggal_perolehan', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $assets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama_barang' => 'required|string|max:150',
            'jumlah' => 'required|integer',
            'kondisi' => 'required|in:baik,rusak,hilang',
            'tanggal_perolehan' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $asset = ClassAsset::create($validate);
        return response()->json([
           'status'=> 'succes',
           'data' => $asset
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assets = ClassAsset::findOrFail($id);
        return response()->json([
            'status' => 'succes',
            'data' => $assets
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $asset = ClassAsset::findOrFail($id);
        $validate = $request->validate([
            'nama_barang' => 'sometimes|string|max:150',
            'jumlah' => 'sometimes|integer|min:0',
            'kondisi'=> 'sometimes|in:baik,rusak,hilang',
            'tanggal_perolehan' => 'sometimes|date',
            'keterangan' => 'nullable|string'
        ]);
        $asset->update($validate);
        return response()->json([
            'status' => 'succes',
            'message' => 'Data di update'

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asset = ClassAsset::findOrFail($id);
        $asset->delete();
        return response()->json([
            'status' => 'succes',
            'message'=>'Data Berhasil di hapus'
        ]);

    }
}
