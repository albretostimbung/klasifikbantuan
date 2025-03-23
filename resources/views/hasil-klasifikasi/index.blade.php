@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <section class="py-8">
        <div class="container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Data Hasil Klasifikasi</h1>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden px-4 py-4">
                <table id="penerimaBantuanTable" class="w-full">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Model</th>
                            <th>Masyarakat</th>
                            <th>Penerima Bantuan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-gray-500 bg-opacity-50 transition-opacity duration-300">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="modal-content transform rounded-lg bg-white p-6 shadow-xl transition-all duration-300 opacity-0 scale-95 sm:w-full sm:max-w-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">Detail Masyarakat</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Nama</p>
                            <p id="detail-name" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pekerjaan</p>
                            <p id="detail-occupation" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Usia</p>
                            <p id="detail-age" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Pernikahan</p>
                            <p id="detail-marital_status" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Jumlah Tanggungan</p>
                            <p id="detail-number_of_dependent" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pendidikan Terakhir</p>
                            <p id="detail-last_education" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Penghasilan</p>
                            <p id="detail-income" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status Tempat Tinggal</p>
                            <p id="detail-residence_status" class="mt-1"></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kepemilikan Kendaraan</p>
                            <p id="detail-vehicle_ownership" class="mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        function formatNumber(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(number);
        }

        function showDetail(id) {
            const url = `{{ route('predict.show', ':id') }}`;
            fetch(url.replace(':id', id))
                .then(response => response.json())
                .then(data => {
                    // set data to modal using direct citizen data
                    document.getElementById('detail-name').textContent = data.name;
                    document.getElementById('detail-occupation').textContent = data.occupation;
                    document.getElementById('detail-age').textContent = data.age;
                    document.getElementById('detail-marital_status').innerHTML = data.marital_status ?
                        `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Menikah</span>` :
                        `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Belum Menikah</span>`;
                    document.getElementById('detail-number_of_dependent').textContent = data.number_of_dependent;
                    document.getElementById('detail-last_education').textContent = data.last_education;
                    document.getElementById('detail-income').textContent = formatNumber(data.income);
                    document.getElementById('detail-residence_status').textContent = data.residence_status;
                    document.getElementById('detail-vehicle_ownership').innerHTML = data.vehicle_ownership ?
                        `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Memiliki</span>` :
                        `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Tidak Memiliki</span>`;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });

            const modal = document.getElementById('detailModal');
            const modalContent = modal.querySelector('.modal-content');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const modalContent = modal.querySelector('.modal-content');
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('detailModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDetailModal();
                    }
                });
            }

            // Close modal on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closeDetailModal();
                }
            });
        });

        $(document).ready(function() {
            const table = $('#penerimaBantuanTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('predict.list') }}",
                columns: [
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'model_evaluation.name',
                        name: 'name',
                    },
                    {
                        data: 'citizen.name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center gap-2">
                                <div>${data}</div>
                                <button onclick="showDetail(${row.citizen_id})" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM12 6v6a3 3 0 00-3-3V6a3 3 0 013-3z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button></div>`;
                        }
                    },
                    {
                        data: 'is_eligible',
                        name: 'is_eligible',
                        render: function(data, type, row) {
                            return data === 1 ?
                                `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Ya</span>` :
                                `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Tidak</span>`;
                        }
                    },
                ],
                language: {
                    url: "{{ asset('vendor/datatables/i18n/id.json') }}"
                }
            });
        });
    </script>
@endpush
