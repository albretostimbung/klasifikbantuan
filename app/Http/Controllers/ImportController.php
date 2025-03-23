<?php

namespace App\Http\Controllers;

use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;
use App\Models\Citizen;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeCitizen;
use App\Jobs\ProcessImport;

class ImportController extends Controller
{
    /**
     * Download CSV template with active attributes as headers
     */
    public function downloadTemplate()
    {
        // Get active attributes
        $attributes = Attribute::where('status', true)->get();

        // Create CSV writer
        $csv = Writer::createFromFileObject(new SplTempFileObject());

        // Prepare headers
        $headers = [];
        foreach ($attributes as $attribute) {
            $headers[] = $attribute->name;
        }

        // Set delimiter using ";"
        $csv->setDelimiter(";");

        // Insert headers
        $csv->insertOne($headers);

        // Set headers for download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_data_penerima_bantuan_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        return response((string) $csv, 200, $headers);
    }

    /**
     * Import citizens and their attributes from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $attributes = Attribute::where('status', true)->get();
        
        // Store the file temporarily with a unique name
        $filePath = $request->file('file')->storeAs('temp', 'import_'.time().'.csv');

        // Dispatch the job
        ProcessImport::dispatch($filePath, $attributes);

        return response()->json([
            'success' => true,
            'message' => 'Import process has been queued and will be processed in the background.'
        ]);
    }
}
