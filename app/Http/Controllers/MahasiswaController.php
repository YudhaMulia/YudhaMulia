<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of mahasiswa with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $query = Mahasiswa::query();

        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('nim', 'like', '%' . $search . '%');
        }

        $mahasiswa = $query->paginate(10)
                           ->appends(['search' => $search]);

        return view('mahasiswa.index', compact('mahasiswa', 'search'));
    }

    /**
     * Show the form for creating a new mahasiswa.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created mahasiswa in storage.
     */
    public function store(StoreMahasiswaRequest $request)
    {
        Mahasiswa::create($request->validated());

        return redirect()->route('mahasiswa.index')
                       ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified mahasiswa.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified mahasiswa in storage.
     */
    public function update(UpdateMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($request->validated());

        return redirect()->route('mahasiswa.index')
                       ->with('success', 'Mahasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified mahasiswa from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
                       ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
