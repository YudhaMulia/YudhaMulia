<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $mahasiswa = $this->route('mahasiswa');
        $id = is_object($mahasiswa) ? $mahasiswa->id : $mahasiswa;

        return [
            'nim' => 'required|string|max:255|unique:mahasiswa,nim,' . $id . ',id',
            'nama' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:mahasiswa,email,' . $id . ',id',
        ];
    }
}
