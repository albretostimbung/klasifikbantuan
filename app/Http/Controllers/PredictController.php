<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\Predict;
use Symfony\Component\Process\Process;
use Yajra\DataTables\Facades\DataTables;

class PredictController extends Controller
{
    public function list()
    {
        $predicts = Predict::with(['citizen:id,name,age,income,occupation,number_of_dependent,residence_status,last_education,marital_status'])->select(['id', 'citizen_id', 'is_eligible'])->get();

        return DataTables::of($predicts)
            ->addColumn('action', function () {
                return '';  // Will be rendered by JavaScript
            })
            ->toJson();
    }

    public function show($id)
    {
        $citizen = Citizen::with('predict')->findOrFail($id);
        return response()->json($citizen);
    }

    public function runPythonScript()
    {
        $scriptPath = base_path('scripts/predict.py');
        $process = new Process(["python", $scriptPath]);

        // Set required environment variables for Python
        $process->setEnv([
            'PYTHONHASHSEED' => '0',
            'PYTHONIOENCODING' => 'utf-8',
            'PYTHONPATH' => base_path('scripts'),
            'SYSTEMROOT' => getenv('SYSTEMROOT'), // Required for Windows
            'PATH' => getenv('PATH')  // Pass system PATH
        ]);

        $process->run();

        // Ambil output dan ubah menjadi JSON yang rapi
        $output = trim($process->getOutput());
        $jsonData = json_decode($output, true); // Decode JSON

        if (!$process->isSuccessful()) {
            return response()->json(['error' => $process->getErrorOutput()], 500);
        }

        return response()->json(['message' => 'Prediksi selesai dan disimpan ke database.', 'data' => $jsonData]);
    }
}
