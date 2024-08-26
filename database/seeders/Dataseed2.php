<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\cadetes;
use App\Models\administ;
use App\Models\clientes;

class Dataseed2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        cadetes::factory(1)->create();
        administ::factory(1)->create();
        clientes::factory(1)->create();
    }
}
