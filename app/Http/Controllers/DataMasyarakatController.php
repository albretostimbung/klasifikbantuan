<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DataMasyarakatController extends Controller
{
    private const RULE_REQUIRED_STRING = 'required|string|max:255';
    private const RULE_REQUIRED_BOOLEAN = 'required|boolean';
    private const RULE_REQUIRED_NUMERIC = 'required|numeric|min:0';
    private const RULE_REQUIRED_INTEGER = 'required|integer|min:0';

    public function index()
    {
        return view('data-masyarakat.index');
    }

    public function list()
    {
        $citizens = Citizen::select(['id', 'name', 'age', 'income', 'occupation',
            'number_of_dependent', 'residence_status', 'last_education', 'marital_status']);

        return DataTables::of($citizens)
            ->addColumn('action', function() {
                return '';  // Will be rendered by JavaScript
            })
            ->toJson();
    }

    public function create()
    {
        return view('data-masyarakat.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => self::RULE_REQUIRED_STRING,
                'age' => self::RULE_REQUIRED_NUMERIC,
                'occupation' => self::RULE_REQUIRED_STRING,
                'income' => self::RULE_REQUIRED_NUMERIC,
                'number_of_dependent' => self::RULE_REQUIRED_INTEGER,
                'residence_status' => 'required|string|in:Milik Sendiri,Sewa,Menumpang',
                'last_education' => 'required|string|in:Tidak Sekolah,SD,SMP,SMA,D3,S1,S2,S3',
                'marital_status' => self::RULE_REQUIRED_BOOLEAN,
            ]);

            Citizen::create($validated);

            return redirect()->route('data-masyarakat.index')
                ->with('success', 'Data masyarakat berhasil disimpan');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $citizen = Citizen::findOrFail($id);
        return view('data-masyarakat.edit', compact('citizen'));
    }

    public function update(Request $request, $id)
    {
        $citizen = Citizen::findOrFail($id);

        $validated = $request->validate([
            'name' => self::RULE_REQUIRED_STRING,
            'age' => self::RULE_REQUIRED_NUMERIC,
            'occupation' => self::RULE_REQUIRED_STRING,
            'income' => self::RULE_REQUIRED_NUMERIC,
            'number_of_dependent' => self::RULE_REQUIRED_INTEGER,
            'residence_status' => 'required|string|in:Milik Sendiri,Sewa,Menumpang',
            'last_education' => 'required|string|in:Tidak Sekolah,SD,SMP,SMA,D3,S1,S2,S3',
            'marital_status' => self::RULE_REQUIRED_BOOLEAN,
        ]);

        try {
            $citizen->update($validated);
            return $this->sendResponse($request, 'Data berhasil diperbarui', $citizen);
        } catch (\Exception $e) {
            return $this->sendError($request, 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $citizen = Citizen::findOrFail($id);
            return response()->json($citizen);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $citizen = Citizen::findOrFail($id);
            $citizen->delete();
            return response()->json(['message' => 'Data masyarakat berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 422);
        }
    }

    private function sendResponse(Request $request, string $message, $data = null)
    {
        $response = ['message' => $message];
        if ($data) {
            $response['data'] = $data;
        }
        return $request->wantsJson() ? response()->json($response) : back()->with('success', $message);
    }

    private function sendError(Request $request, string $message)
    {
        return $request->wantsJson() ? response()->json(['message' => $message], 422) : back()->with('error', $message);
    }
}
