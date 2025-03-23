<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Citizen;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AtributController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $citizens = Citizen::all();
        return view('atribut-klasifikasi.index', compact('citizens'));
    }

    public function list()
    {
        $attributes = Attribute::select(['id', 'name', 'min_value', 'max_value', 'status']);

        return DataTables::of($attributes)
            ->addColumn('action', function() {
                return '';  // Will be rendered by JavaScript
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('atribut-klasifikasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'min_value' => 'nullable|numeric',
                'max_value' => 'nullable|numeric',
                'status' => 'required|boolean'
            ]);
            Attribute::create($validated);
            return redirect()->route('atribut.index')
                ->with('success', 'Data atribut berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('atribut-klasifikasi.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $attribute = Attribute::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'min_value' => 'nullable|numeric',
                'max_value' => 'nullable|numeric',
                'status' => 'required|boolean'
            ]);
            $attribute->update($validated);
            return redirect()->route('atribut.index')
                ->with('success', 'Data atribut berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $attribute = Attribute::findOrFail($id);
            $attribute->delete();
            return response()->json(['message' => 'Data atribut berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 422);
        }
    }
}
