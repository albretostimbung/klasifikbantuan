@extends('layouts.dashboard')

@section('content')
    <section class="py-8">
        <div class="container px-4 mx-auto">
            <div class="w-full max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Tambah Data Atribut</h2>
                    <a href="{{ route('atribut.index') }}" class="text-gray-600 hover:text-gray-800">
                        Kembali
                    </a>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <form action="{{ route('atribut.store') }}" method="POST" class="space-y-6">
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

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg">
                                        <input type="radio" name="status" value="1" checked required class="w-4 h-4">
                                        <p class="ml-2">Aktif</p>
                                    </label>
                                    <label class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg">
                                        <input type="radio" name="status" value="0" required class="w-4 h-4">
                                        <p class="ml-2">Tidak Aktif</p>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Min Value -->
                            <div>
                                <label for="min_value" class="block text-sm font-medium text-gray-700 mb-2">Batas Minimum</label>
                                <input type="number" name="min_value" id="min_value" value="{{ old('min_value') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('min_value')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Value -->
                            <div>
                                <label for="max_value" class="block text-sm font-medium text-gray-700 mb-2">Batas Maximum</label>
                                <input type="number" name="max_value" id="max_value" value="{{ old('max_value') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary">
                                @error('max_value')
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
