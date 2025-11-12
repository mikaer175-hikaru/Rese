<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    public function run(): void
    {
        $areas = ['東京都', '大阪府', '福岡県'];

        foreach ($areas as $name) {
            Area::updateOrCreate(['name' => $name], ['name' => $name]);
        }
    }
}
