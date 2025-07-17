<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Cliente::factory(10)->create();
        // Puedes ajustar el número de clientes a crear según tus necesidades
        // Por ejemplo, si quieres crear 50 clientes, cambia 10 por 50
        // \App\Models\Cliente::factory(50)->create();
    }
}
