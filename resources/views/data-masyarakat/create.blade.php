@extends('layouts.dashboard')

@section('content')
    <section class="py-8">
        <div class="container px-4 mx-auto">
            <div class="w-full max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Tambah Data Masyarakat</h2>
                    <a href="{{ route('data-masyarakat.index') }}" class="text-gray-600 hover:text-gray-800">
                        Kembali
                    </a>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <form action="{{ route('data-masyarakat.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Age -->
                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Usia</label>
                                <input type="number" name="age" id="age" value="{{ old('age') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('age')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Occupation -->
                            <div>
                                <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('occupation')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Income -->
                            <div>
                                <label for="income" class="block text-sm font-medium text-gray-700 mb-2">Pendapatan</label>
                                <input type="number" name="income" id="income" value="{{ old('income') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('income')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Number of Dependents -->
                            <div>
                                <label for="number_of_dependent" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tanggungan</label>
                                <input type="number" name="number_of_dependent" id="number_of_dependent" value="{{ old('number_of_dependent') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('number_of_dependent')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Residence Status -->
                            <div>
                                <label for="residence_status" class="block text-sm font-medium text-gray-700 mb-2">Status Tempat Tinggal</label>
                                <select name="residence_status" id="residence_status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Pilih Status</option>
                                    <option value="Milik Sendiri" {{ old('residence_status') == 'Milik Sendiri' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="Sewa" {{ old('residence_status') == 'Sewa' ? 'selected' : '' }}>Sewa</option>
                                    <option value="Menumpang" {{ old('residence_status') == 'Menumpang' ? 'selected' : '' }}>Menumpang</option>
                                </select>
                                @error('residence_status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Education -->
                            <div>
                                <label for="last_education" class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                                <select name="last_education" id="last_education" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah" {{ old('last_education') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                    <option value="SD" {{ old('last_education') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('last_education') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('last_education') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="D3" {{ old('last_education') == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="S1" {{ old('last_education') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('last_education') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('last_education') == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('last_education')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Marital Status -->
                            <div>
                                <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">Status Pernikahan</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="marital_status" value="1" {{ old('marital_status') == '1' ? 'checked' : '' }} required
                                            class="form-radio text-primary focus:ring-primary">
                                        <span class="ml-2">Menikah</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="marital_status" value="0" {{ old('marital_status') == '0' ? 'checked' : '' }} required
                                            class="form-radio text-primary focus:ring-primary">
                                        <span class="ml-2">Belum Menikah</span>
                                    </label>
                                </div>
                                @error('marital_status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 pt-4">
                            <a href="{{ route('data-masyarakat.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
