<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['寿司', '焼肉', '居酒屋', 'イタリアン', 'ラーメン'];

        foreach ($genres as $name) {
            Genre::updateOrCreate(['name' => $name], ['name' => $name]);
        }
    }
}
