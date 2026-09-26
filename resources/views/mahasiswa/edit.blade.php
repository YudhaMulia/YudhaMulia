@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
<div class="container mx-auto p-4">
    <div class="max-w-2xl mx-auto">
        <div class="card bg-base-100 shadow-md p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Mahasiswa</h1>
            </div>

            <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-control mb-6">
                    <label class="label" for="nim">
                        <span class="label-text font-medium"><span class="text-red-500">*</span> NIM</span>
                        <span class="label-text-alt text-red-500">Wajib diisi</span>
                    </label>
                    <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim) }}" placeholder="Masukkan NIM..." class="input input-bordered w-full">
                    @error('nim')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label" for="nama">
                        <span class="label-text font-medium"><span class="text-red-500">*</span> Nama Lengkap</span>
                        <span class="label-text-alt text-red-500">Wajib diisi</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama) }}" placeholder="Masukkan nama lengkap..." class="input input-bordered w-full">
                    @error('nama')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label" for="program_studi">
                        <span class="label-text font-medium"><span class="text-red-500">*</span> Program Studi</span>
                        <span class="label-text-alt text-red-500">Wajib diisi</span>
                    </label>
                    <input type="text" id="program_studi" name="program_studi" value="{{ old('program_studi', $mahasiswa->program_studi) }}" placeholder="Masukkan program studi..." class="input input-bordered w-full">
                    @error('program_studi')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mb-6">
                    <label class="label" for="email">
                        <span class="label-text font-medium">Email</span>
                        <span class="label-text-alt text-gray-500">Opsional</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->email) }}" placeholder="Masukkan email (opsional)..." class="input input-bordered w-full">
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <div class="form-control mt-8">
                    <button type="submit" class="btn btn-primary w-full">Perbarui Mahasiswa</button>
                    <button type="button" onclick="window.location.href='{{ route('mahasiswa.index') }}'" class="btn btn-ghost mt-3 w-full">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection