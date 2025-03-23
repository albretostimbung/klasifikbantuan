@extends('layouts.dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')
    <section class="py-8">
        <div class="container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Data Atribut</h1>
                <div class="flex gap-2">
                    <a href="{{ route('atribut.create') }}"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Tambah Data
                    </a>
                    {{-- add button Proses Klasifikasi, just show if citizen data is not null --}}
                    @if ($citizens->count() > 0)
                        <button type="button" onclick="showProcessModal()"
                            class="px-4 py-2 bg-blue-950 text-white rounded-lg hover:bg-primary-dark cursor-pointer">
                            Proses Klasifikasi
                        </button>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden px-4 py-4">
                <table id="attributeTable" class="w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Batas Minimum</th>
                            <th>Batas Maximum</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

    <!-- Process Modal -->
    <div id="processModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Proses Klasifikasi</h2>
            <p class="mb-4">Apakah Anda yakin ingin memproses klasifikasi?</p>
            <div class="flex justify-end gap-2">
                <button onclick="hideProcessModal()"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 cursor-pointer">Batal</button>
                <button id="processBtn" onclick="processClassification()"
                    class="px-4 py-2 bg-blue-950 text-white rounded-lg hover:bg-primary-dark cursor-pointer">Proses</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        // show process modal
        function showProcessModal() {
            const modal = document.getElementById('processModal');
            modal.classList.add('show');
            modal.classList.remove('hidden');
            modal.classList.add('block');
        }

        // hide process modal
        function hideProcessModal() {
            const modal = document.getElementById('processModal');
            modal.classList.remove('show');
            modal.classList.add('hidden');
            modal.classList.remove('block');
        }

        function processClassification() {
            // Show loading state
            const prosesBtn = document.getElementById('processBtn');
            const originalText = prosesBtn.innerHTML;
            prosesBtn.disabled = true;
            prosesBtn.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                    </div>
                `;

            fetch("{{ route('predict.run-script') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    Toastify({
                        text: "Klasifikasi berhasil diproses!",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#10B981",
                        stopOnFocus: true,
                        close: true,
                    }).showToast();

                    // Reload the page after successful processing
                    setTimeout(() => {
                        window.location.href = "{{ route('hasil-klasifikasi') }}";
                    }, 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: "Terjadi kesalahan saat memproses klasifikasi",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#EF4444",
                        stopOnFocus: true,
                        close: true,
                    }).showToast();
                });
        }


        $(document).ready(function() {
            const table = $('#attributeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('atribut.list') }}",
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'min_value',
                        name: 'min_value'
                    },
                    {
                        data: 'max_value',
                        name: 'max_value'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            return `<span class="px-2 py-1 text-xs rounded-full ${data === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${data === 1 ? 'Aktif' : 'Tidak Aktif'}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="flex items-center gap-2">
                                    <a href="{{ url('atribut') }}/${row.id}/edit" class="p-2 bg-yellow-500 text-white rounded hover:bg-yellow-600" title="Edit">
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
                    },
                ],
                language: {
                    url: "{{ asset('vendor/datatables/i18n/id.json') }}"
                }
            });

            window.confirmDelete = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    $.ajax({
                        url: `{{ route('atribut.destroy', '') }}/${id}`,
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

        });
    </script>
@endpush
