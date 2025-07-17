<?php

namespace Database\Seeders;

use App\Models\Salidas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salidas::factory(10)->create();
    }
}
