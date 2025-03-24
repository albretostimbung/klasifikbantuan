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

    <h2>Nama Model</h2>
    <p>{{ $latestModelEvaluation->name }}</p>

    <h2>Akurasi Model</h2>
    <p>{{ number_format($latestModelEvaluation->accuracy * 100, 2) }}%</p>

    <h2>Precision Model</h2>
    <p>{{ number_format($latestModelEvaluation->model_precision * 100, 2) }}%</p>

    <h2>Recall Model</h2>
    <p>{{ number_format($latestModelEvaluation->model_recall * 100, 2) }}%</p>

    <h2>F1 Score Model</h2>
    <p>{{ number_format($latestModelEvaluation->model_f1_score * 100, 2) }}%</p>

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
            @foreach(json_decode($latestModelEvaluation->conf_matrix) as $index => $row)
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
