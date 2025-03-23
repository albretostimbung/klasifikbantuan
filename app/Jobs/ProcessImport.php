<?php

namespace App\Jobs;

use App\Models\Citizen;
use App\Models\Attribute;
use App\Models\AttributeCitizen;
use League\Csv\Reader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $attributes;

    public function __construct($filePath, $attributes)
    {
        $this->filePath = Storage::path($filePath);
        $this->attributes = $attributes;
    }

    public function handle()
    {
        try {
            $csv = Reader::createFromPath($this->filePath);
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);

            $records = $csv->getRecords();
            $imported = 0;

            $header = $csv->getHeader();

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

            $mapped_headers = array_map(function($h) use ($header_mapping) {
                return $header_mapping[$h] ?? $h;
            }, $header);

            foreach ($records as $record) {
                $rowData = array_combine($mapped_headers, $record);

                $rowData['marital_status'] = $rowData['marital_status'] === 'Kawin' ? true : false;
                $rowData['income'] = str_replace(',', '', $rowData['income']);

                // Gunakan firstOrCreate untuk mencegah duplikasi
                $citizen = Citizen::firstOrCreate(
                    [
                        'name' => $rowData['name'],
                        'age' => $rowData['age']
                    ],
                    [
                        'income' => $rowData['income'],
                        'occupation' => $rowData['occupation'],
                        'number_of_dependent' => $rowData['number_of_dependent'],
                        'residence_status' => $rowData['residence_status'],
                        'last_education' => $rowData['last_education'],
                        'marital_status' => $rowData['marital_status']
                    ]
                );

                foreach ($this->attributes as $attribute) {
                    $key = $attribute->name;
                    if (isset($rowData[$key])) {
                        AttributeCitizen::updateOrCreate(
                            [
                                'attribute_id' => $attribute->id,
                                'citizen_id' => $citizen->id
                            ],
                            [
                                'value' => $rowData[$key]
                            ]
                        );
                    }
                }

                $imported++;
            }

            // Clean up the temporary file
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }
}