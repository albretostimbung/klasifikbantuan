@extends('layouts.dashboard')

@section('content')
    <div class="py-20 flex flex-wrap justify-center gap-12">
        <!-- Jumlah Masyarakat -->
        <div class="max-w-[400px] max-h-[400px] w-full bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-gray-600 text-lg font-medium">Jumlah Masyarakat</div>
                    <div class="text-gray-900 text-4xl font-bold">{{ $citizens->count() }}</div>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Jumlah Atribut -->
        <div class="max-w-[400px] max-h-[400px] w-full bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="text-gray-600 text-lg font-medium">Jumlah Atribut</div>
                    <div class="text-gray-900 text-4xl font-bold">{{ $attributes->count() }}</div>
                </div>
                <div class="bg-primary/10 p-3 rounded-lg">
                    <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                    </svg>
                </div>
            </div>
        </div>
        <!-- Grafik Klasifikasi Berdasarkan Kriteria -->
        <div class="max-w-[850px] max-h-[400px] w-full bg-white rounded-xl shadow-lg p-6">
            <div class="text-gray-600 text-lg font-medium mb-4">Grafik Klasifikasi Berdasarkan Kriteria</div>
            <canvas id="classificationChart"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Grafik Bar Klasifikasi Berdasarkan Kriteria
            fetch("{{ route('classification-data') }}") // Pastikan route sudah dibuat di Laravel
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.income_category);
                    const notEligibleData = data.map(item => item.not_eligible);
                    const eligibleData = data.map(item => item.eligible);

                    const ctx = document.getElementById('classificationChart').getContext('2d');
                    const classificationChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                    label: 'Tidak Layak',
                                    data: notEligibleData,
                                    backgroundColor: '#EF4444',
                                },
                                {
                                    label: 'Layak',
                                    data: eligibleData,
                                    backgroundColor: '#22C55E',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error("Error fetching data:", error));
        });
    </script>
@endpush
