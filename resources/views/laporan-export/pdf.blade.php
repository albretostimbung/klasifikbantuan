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
    <h1>Laporan Model Klasifikasi</h1>

    <h2>Akurasi Model</h2>
    <p>{{ number_format($accuracy * 100, 2) }}%</p>

    <h2>Confusion Matrix</h2>
    <table>
        <thead>
            <tr>
                <th>Kelas</th>
                @foreach(['Predicted Tidak', 'Predicted Ya'] as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach(json_decode($confusionMatrix) as $index => $row)
                <tr>
                    <td>Aktual {{ $index == 0 ? 'Tidak' : 'Ya' }}</td>
                    @foreach($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
