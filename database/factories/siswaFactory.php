<?php

namespace Database\Factories;

use App\Models\siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class siswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 100,
            'nama_depan' => $this->$faker->name(),
            'nama_belakang' => $this->'',
            'jenis_kelamin' => $this->$faker->randomElement(['L','P']),
            'agama' => $this->$faker->randomElement(['Islam','Kristen','Katolik','Hindu','Budha']),
            'alamat' => $this->$faker->address(),
        ];
    }
}
