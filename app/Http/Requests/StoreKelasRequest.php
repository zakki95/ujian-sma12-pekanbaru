<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreKelasRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()->can('create', \App\Models\Kelas::class); }
    public function rules(): array {
        return [
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas',
            'tingkat' => 'required|in:X,XI,XII',
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
            'wali_kelas_id' => 'nullable|exists:pengguna,id',
        ];
    }
    public function messages(): array {
        return ['tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2026/2027).'];
    }
}