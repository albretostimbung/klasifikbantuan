<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                "name" => "Nama",
                "min_value" => null,
                "max_value" => null,
            ],
            [
                "name" => "Usia",
                "min_value" => 18,
                "max_value" => 65,
            ],
            [
                "name" => "Penghasilan",
                "min_value" => 0,
                "max_value" => 2000000,
            ],
            [
                "name" => "Pekerjaan",
                "min_value" => null,
                "max_value" => null,
            ],
            [
                "name" => "Status Tempat Tinggal",
                "min_value" => null,
                "max_value" => null,
            ],
            [
                "name" => "Pendidikan Terakhir",
                "min_value" => null,
                "max_value" => null,
            ],
            [
                "name" => "Kepemilikan Kendaraan",
                "min_value" => null,
                "max_value" => null,
            ],
            [
                "name" => "Status Perkawinan",
                "min_value" => null,
                "max_value" => null,
            ],
            [
                "name" => "Jumlah Tanggungan",
                "min_value" => 1,
                "max_value" => 5,
            ],
            [
                "name" => "Bantuan Sosial Sebelumnya",
                "min_value" => null,
                "max_value" => null,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create([
                'name' => $attribute['name'],
                'min_value' => $attribute['min_value'],
                'max_value' => $attribute['max_value'],
            ]);
        }
    }
}
