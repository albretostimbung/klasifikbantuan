<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelEvaluation;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function index()
    {
        $latestModelEvaluation = ModelEvaluation::latest()->first();

        return view('laporan-export.index', compact('latestModelEvaluation'));
    }

    public function exportLaporan(Request $request)
    {
        $latestModelEvaluation = ModelEvaluation::find($request->id);
        return $this->exportLaporanPdf($latestModelEvaluation);
    }

    public function exportPenerimaBantuan(Request $request)
    {
        $latestModelEvaluation = ModelEvaluation::find($request->id);
        return $this->exportPenerimaBantuanPdf($latestModelEvaluation);
    }

    private function exportLaporanPdf($latestModelEvaluation)
    {
        $pdf = Pdf::loadView('laporan-export.laporan-pdf', compact('latestModelEvaluation'));
        return $pdf->download('report.pdf');
    }

    private function exportPenerimaBantuanPdf($latestModelEvaluation)
    {
        $daftarPenerimaBantuan = $latestModelEvaluation->predicts()->where('is_eligible', true)->get();

        $pdf = Pdf::loadView('laporan-export.penerima-bantuan-pdf', compact('latestModelEvaluation', 'daftarPenerimaBantuan'));
        return $pdf->download('penerima-bantuan.pdf');
    }

    public function evaluations(Request $request)
    {
        $evaluations = ModelEvaluation::latest()->get();

        return DataTables::of($evaluations)
            ->editColumn('created_at', function ($evaluation) {
                return $evaluation->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', function ($evaluation) {
                return '<a href="' . route('laporan.export-penerima-bantuan', $evaluation->id) . '" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Export PDF</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
