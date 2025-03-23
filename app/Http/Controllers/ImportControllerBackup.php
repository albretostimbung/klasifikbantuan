<?php

namespace App\Http\Controllers;

use League\Csv\Reader;
use League\Csv\Writer;
use SplTempFileObject;
use App\Models\Citizen;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Models\AttributeCitizen;

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

        // Get active attributes for validation and mapping
        $attributes = Attribute::where('status', true)
            ->pluck('id', 'name')
            ->toArray();

        try {
            $csv = Reader::createFromPath($request->file('file')->getPathname());
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $imported = 0;

            $header = $csv->getHeader();

            // Map Indonesian headers to database fields
            $header_mapping = [
                'Nama' => 'name',
                'Usia' => 'age',
                'Penghasilan' => 'income',
                'Pekerjaan' => 'occupation',
                'Status Tempat Tinggal' => 'residence_status',
                'Pendidikan Terakhir' => 'last_education',
                'Status Perkawinan' => 'marital_status',
                'Jumlah Tanggungan' => 'number_of_dependent'
            ];

            // Transform header names
            $mapped_headers = array_map(function($h) use ($header_mapping) {
                return $header_mapping[$h] ?? $h;
            }, $header);

            foreach ($records as $record) {
                $rowData = array_combine($mapped_headers, $record);

                // Convert marital status to boolean
                $rowData['marital_status'] = $rowData['marital_status'] === 'Kawin' ? true : false;

                // Clean income value from comma thousand separator
                $rowData['income'] = str_replace(',', '', $rowData['income']);

                $citizen = Citizen::create([
                    'name' => $rowData['name'],
                    'age' => $rowData['age'],
                    'income' => $rowData['income'],
                    'occupation' => $rowData['occupation'],
                    'number_of_dependent' => $rowData['number_of_dependent'],
                    'residence_status' => $rowData['residence_status'],
                    'last_education' => $rowData['last_education'],
                    'marital_status' => $rowData['marital_status']
                ]);

                // Handle dynamic attributes
                $attributes = Attribute::where('status', true)->get();

                foreach ($attributes as $attribute) {
                    $key = $attribute->name;

                    // If attribute exists in CSV data
                    if (isset($rowData[$key])) {
                        AttributeCitizen::create([
                            'attribute_id' => $attribute->id,
                            'citizen_id' => $citizen->id,
                            'value' => $rowData[$key]
                        ]);
                    }
                }

                $imported++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengimport {$imported} data"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing data: ' . $e->getMessage()
            ], 500);
        }
    }
}
