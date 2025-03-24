@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <section class="py-8">
        <div class="container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Laporan Model Klasifikasi</h1>
                <div class="flex gap-2">
                    @if ($latestModelEvaluation)
                        <a href="{{ route('laporan.export', ['type' => 'pdf']) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            Export PDF
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Akurasi Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Akurasi Model</h2>
                    <div class="text-3xl font-bold text-blue-600">
                        @if($latestModelEvaluation)
                            {{ number_format($latestModelEvaluation->accuracy * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Tingkat akurasi model klasifikasi terbaru</p>
                </div>

                <!-- Confusion Matrix Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Confusion Matrix</h2>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Kelas</th>
                                @foreach (['Prediksi Tidak', 'Prediksi Ya'] as $header)
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">{{ $header }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @if ($latestModelEvaluation)
                                @foreach (json_decode($latestModelEvaluation->conf_matrix) as $index => $row)
                                    <tr class="border-b">
                                        <td class="px-4 py-3 text-sm font-medium">Aktual {{ $index == 0 ? 'Tidak' : 'Ya' }}
                                        </td>
                                        @foreach ($row as $value)
                                            <td class="px-4 py-3 text-sm">{{ $value }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- History Evaluasi Model -->
            <div class="bg-white rounded-lg shadow overflow-hidden mt-8">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold">History Evaluasi Model</h2>
                </div>
                <div class="p-6">
                    <table id="evaluationsTable" class="w-full">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Akurasi</th>
                                <th>Confusion Matrix</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            const table = $('#evaluationsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('laporan.evaluations') }}",
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'accuracy',
                        name: 'accuracy',
                        render: function(data) {
                            return (data * 100).toFixed(2) + '%';
                        }
                    },
                    {
                        data: 'conf_matrix',
                        name: 'conf_matrix',
                        render: function(data) {
                            return JSON.stringify(data);
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                    }
                ],
                language: {
                    url: "{{ asset('vendor/datatables/i18n/id.json') }}"
                }
            });
        });
    </script>
@endpush
