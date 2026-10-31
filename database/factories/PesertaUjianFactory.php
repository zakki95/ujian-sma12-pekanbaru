<?php
namespace Database\Factories;
use App\Models\PesertaUjian;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesertaUjianFactory extends Factory
{
    protected $model = PesertaUjian::class;
    public function definition(): array
    {
        return [
            'status' => 'terdaftar',
        ];
    }
}