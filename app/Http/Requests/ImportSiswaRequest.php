<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ImportSiswaRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()->peran === 'admin'; }
    public function rules(): array {
        return ['file_siswa' => 'required|file|mimes:xlsx,xls,csv|max:2048'];
    }
}