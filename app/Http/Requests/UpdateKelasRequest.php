<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKelasRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()->can('update', $this->route('kelas')); }
    public function rules(): array {
        return [
            'nama_kelas' => 'required|string|max:50|unique:kelas,nama_kelas,' . $this->route('kelas')->id,
            'tingkat' => 'required|in:X,XI,XII',
            'tahun_ajaran' => 'required|string|regex:/^\d{4}\/\d{4}$/',
        ];
    }
}