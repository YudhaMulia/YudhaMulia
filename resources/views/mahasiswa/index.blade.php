@extends('layouts.app')

@section('title', 'Daftar Mahasiswa')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Mahasiswa</h1>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
    </div>

    <div class="card bg-base-100 shadow-md p-6 mb-6">
        <form action="{{ route('mahasiswa.index') }}" method="GET" class="flex items-end gap-4">
            <div class="form-control flex-grow">
                <label class="label" for="search">
                    <span class="label-text">Cari berdasarkan Nama atau NIM</span>
                </label>
                <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Masukkan nama atau NIM..." class="input input-bordered w-full">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-secondary">Cari</button>
                @if ($search)
                    <a href="{{ route('mahasiswa.index') }}" class="btn btn-ghost">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if ($mahasiswa->count() > 0)
        <div class="overflow-x-auto">
            <table class="table w-full bg-base-100 shadow-md rounded-lg">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Email</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mahasiswa as $m)
                        <tr>
                            <td>{{ $m->nim }}</td>
                            <td>{{ $m->nama }}</td>
                            <td>{{ $m->program_studi }}</td>
                            <td>{{ $m->email ?? '-' }}</td>
                            <td class="flex justify-center gap-2">
                                <a href="{{ route('mahasiswa.edit', $m->id) }}" class="btn btn-info btn-sm">Edit</a>
                                <button onclick="document.getElementById('delete_modal_{{ $m->id }}').showModal()" class="btn btn-error btn-sm">Hapus</button>
                                <dialog id="delete_modal_{{ $m->id }}" class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
                                        <p class="py-4">Apakah Anda yakin ingin menghapus mahasiswa dengan nama <span class="font-semibold">{{ $m->nama }}</span> (NIM: <span class="font-semibold">{{ $m->nim }}</span>)?</p>
                                        <div class="modal-action">
                                            <form action="{{ route('mahasiswa.destroy', $m->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error">Hapus</button>
                                            </form>
                                            <form method="dialog">
                                                <button class="btn">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </dialog>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-center">
            {{ $mahasiswa->links() }}
        </div>
    @else
        <div class="hero bg-base-200 min-h-64 rounded-lg">
            <div class="hero-content text-center">
                <div class="max-w-md">
                    <h3 class="text-2xl font-bold mb-4">Belum ada data mahasiswa</h3>
                    <p class="mb-5">Mulai dengan menambahkan mahasiswa baru.</p>
                    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection