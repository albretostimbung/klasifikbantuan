@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <section class="py-8">
        <div class="container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Data Masyarakat</h1>
                <div class="flex gap-4">
                    <a href="{{ route('data-masyarakat.create') }}"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Tambah Data
                    </a>
                    <button type="button" onclick="showImportModal()"
                        class="px-4 py-2 bg-blue-950 text-white rounded-lg hover:bg-primary-dark cursor-pointer">
                        Import Data
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden px-4 py-4">
                <table id="citizenTable" class="w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Pekerjaan</th>
                            <th>Pendidikan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="modal-content bg-white rounded-xl shadow-xl max-w-2xl w-full transform transition-all duration-300 scale-95 opacity-0">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Detail Data Masyarakat</h3>
                        <button type="button" onclick="closeDetailModal()"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Tutup</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Informasi Pribadi -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informasi Pribadi
                            </h4>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-name"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Usia</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-age"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Pernikahan</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-marital_status"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jumlah Tanggungan</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-number_of_dependent"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pendidikan Terakhir</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-last_education"></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Informasi Ekonomi & Sosial -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Informasi Ekonomi & Sosial
                            </h4>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pekerjaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-occupation"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Pendapatan per Bulan</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-income"></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Tempat Tinggal</dt>
                                    <dd class="mt-1 text-sm text-gray-900" id="detail-residence_status"></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- i want to add import modal, which is a modal that will be used to import data from a csv file and download template file csv --}}
    <div id="importModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 hidden transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="modal-content bg-white rounded-lg shadow overflow-hidden px-4 py-4">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Import Data</h2>
                    <button type="button" onclick="closeImportModal()"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none cursor-pointer">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <button type="button" onclick="downloadTemplate()" class="w-full btn bg-blue-600 hover:bg-blue-700 text-white mb-6 py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Template CSV
                    </button>

                    <form action="{{ route('data-masyarakat.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        <div class="space-y-4">
                            <div class="form-group">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Upload File CSV</label>
                                <div class="relative w-full">
                                    <input type="file" id="file" name="file" class="hidden" accept=".csv" onchange="updateFileName(this)">
                                    <label for="file" class="flex items-center justify-between w-full px-4 py-2.5 border border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition duration-200">
                                        <span id="file-name" class="text-gray-500 text-sm">Choose file...</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="w-full btn bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Import Data
                            </button>
                        </div>
                    </form>
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
                const url = `{{ route('data-masyarakat.show', ':id') }}`;
                fetch(url.replace(':id', id))
                    .then(response => response.json())
                    .then(data => {
                        // set data to modal
                        document.getElementById('detail-name').textContent = data.name;
                        document.getElementById('detail-occupation').textContent = data.occupation;
                        document.getElementById('detail-age').textContent = data.age;
                        document.getElementById('detail-marital_status').innerHTML = data.marital_status ? `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Menikah</span>` : `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Belum Menikah</span>`;
                        document.getElementById('detail-number_of_dependent').textContent = data.number_of_dependent;
                        document.getElementById('detail-last_education').textContent = data.last_education;
                        document.getElementById('detail-income').textContent = formatNumber(data.income);
                        document.getElementById('detail-residence_status').textContent = data.residence_status;
                        document.getElementById('detail-vehicle_ownership').innerHTML = data.vehicle_ownership ? `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Memiliki</span>` : `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Tidak Memiliki</span>`;
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


            function showImportModal() {
                const modal = document.getElementById('importModal');
                const modalContent = modal.querySelector('.modal-content');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 50);
            }

            function closeImportModal() {
                const modal = document.getElementById('importModal');
                const modalContent = modal.querySelector('.modal-content');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }

            function downloadTemplate() {
                window.location.href = "{{ route('data-masyarakat.template.download') }}";
            }

            function updateFileName(input) {
                const fileName = input.files[0]?.name || 'Choose file...';
                document.getElementById('file-name').textContent = fileName;
            }

            function handleImport(event) {
                event.preventDefault();
                const formData = new FormData(event.target);

                // Show loading state
                const submitBtn = event.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Importing...
                `;

                fetch("{{ route('data-masyarakat.import') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data imported successfully!');
                        closeImportModal();
                        $('#citizenTable').DataTable().ajax.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error importing data: ' + error.message);
                })
                .finally(() => {
                    // Reset form and button state
                    event.target.reset();
                    document.getElementById('file-name').textContent = 'Choose file...';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
            }

            $(document).ready(function() {
                // Set up form submit handler
                document.querySelector('#importModal form').addEventListener('submit', handleImport);

                const table = $('#citizenTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('data-masyarakat.list') }}",
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'occupation',
                            name: 'occupation'
                        },
                        {
                            data: 'last_education',
                            name: 'last_education'
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row) {
                                return `
                                <div class="flex items-center gap-2">
                                    <button onclick="showDetail(${row.id})" class="p-2 bg-blue-500 text-white rounded hover:bg-blue-600" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM12 6v6a3 3 0 00-3-3V6a3 3 0 013-3z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <a href="{{ url('data-masyarakat') }}/${row.id}/edit" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete(${row.id})" class="p-2 bg-red-500 text-white rounded hover:bg-red-600" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            `;
                            }
                        }
                    ],
                    language: {
                        url: "{{ asset('vendor/datatables/i18n/id.json') }}"
                    }
                });

                window.confirmDelete = function(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        $.ajax({
                            url: `{{ route('data-masyarakat.destroy', '') }}/${id}`,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                table.ajax.reload();
                                if (response && response.message) {
                                    Toastify({
                                        text: response.message,
                                        duration: 3000,
                                        gravity: "top",
                                        position: "right",
                                        backgroundColor: "#10B981",
                                        stopOnFocus: true,
                                        close: true,
                                    }).showToast();
                                }
                            },
                            error: function(xhr) {
                                const errorMessage = xhr.responseJSON?.message ||
                                    "Terjadi kesalahan saat menghapus data";
                                Toastify({
                                    text: errorMessage,
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#EF4444",
                                    stopOnFocus: true,
                                    close: true,
                                }).showToast();
                            }
                        });
                    }
                };

                // Close modal when clicking outside
                document.getElementById('detailModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeDetailModal();
                    }
                });

                // Close modal on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !document.getElementById('detailModal').classList.contains(
                            'hidden')) {
                        closeDetailModal();
                    }
                });

                // Close import modal when clicking outside
                document.getElementById('importModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeImportModal();
                    }
                });

                // Close import modal on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !document.getElementById('importModal').classList.contains(
                            'hidden')) {
                        closeImportModal();
                    }
                });
            });
        </script>
    @endpush
