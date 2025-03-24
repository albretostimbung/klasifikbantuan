<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Model</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Daftar Penerima Bantuan</h1>

    <table class="border-collapse border border-gray-500" style="font-size: 0.75rem;">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 text-left font-medium text-gray-500">No</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Nama</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Umur</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Penghasilan</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Pekerjaan</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Jumlah Tanggungan</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Status Pernikahan</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Pendidikan Terakhir</th>
                <th class="px-4 py-2 text-left font-medium text-gray-500">Status Tinggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarPenerimaBantuan as $index => $penerimaBantuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penerimaBantuan->citizen->name }}</td>
                    <td>{{ $penerimaBantuan->citizen->age }}</td>
                    <td>{{ 'Rp ' . number_format($penerimaBantuan->citizen->income, 0, ',', '.') }}</td>
                    <td>{{ $penerimaBantuan->citizen->occupation }}</td>
                    <td>{{ $penerimaBantuan->citizen->number_of_dependent }}</td>
                    <td>{{ $penerimaBantuan->citizen->marital_status ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $penerimaBantuan->citizen->last_education }}</td>
                    <td>{{ $penerimaBantuan->citizen->residence_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
