<?php
namespace Database\Factories;
use App\Models\TokenUjian;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TokenUjianFactory extends Factory
{
    protected $model = TokenUjian::class;
    public function definition(): array
    {
        return [
            'token' => Str::random(32),
            'berlaku_hingga' => now()->addHours(2),
            'status' => 'aktif',
        ];
    }
}