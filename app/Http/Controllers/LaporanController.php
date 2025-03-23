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

        // Ambil data akurasi dan confusion matrix
        $accuracy = $latestModelEvaluation->accuracy;
        $confusionMatrix = $latestModelEvaluation->conf_matrix;


        return view('laporan-export.index', compact('accuracy', 'confusionMatrix'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type');

        if ($request->get('id')) {
            $latestModelEvaluation = ModelEvaluation::find($request->get('id'));
        } else {
            $latestModelEvaluation = ModelEvaluation::latest()->first();
        }

        $accuracy = $latestModelEvaluation->accuracy;
        $confusionMatrix = $latestModelEvaluation->conf_matrix;

        if ($type === 'pdf') {
            return $this->exportPdf($accuracy, $confusionMatrix);
        }
    }

    private function exportPdf($accuracy, $confusionMatrix)
    {
        $pdf = Pdf::loadView('laporan-export.pdf', [
            'accuracy' => $accuracy,
            'confusionMatrix' => $confusionMatrix
        ]);
        return $pdf->download('report.pdf');
    }

    public function evaluations(Request $request)
    {
        $evaluations = ModelEvaluation::query();

        return DataTables::of($evaluations)
            ->editColumn('created_at', function($evaluation) {
                return $evaluation->created_at->format('d/m/Y H:i');
            })
            ->addColumn('action', function($evaluation) {
                return '<a href="'.route('laporan.export', ['type' => 'pdf', 'id' => $evaluation->id]).'" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">Export PDF</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
