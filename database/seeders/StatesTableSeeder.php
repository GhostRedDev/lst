<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['name' => 'Amazonas', 'code' => 'AMA'],
            ['name' => 'Anzoátegui', 'code' => 'ANZ'],
            ['name' => 'Apure', 'code' => 'APU'],
            ['name' => 'Aragua', 'code' => 'ARA'],
            ['name' => 'Barinas', 'code' => 'BAR'],
            ['name' => 'Bolívar', 'code' => 'BOL'],
            ['name' => 'Carabobo', 'code' => 'CAR'],
            ['name' => 'Cojedes', 'code' => 'COJ'],
            ['name' => 'Delta Amacuro', 'code' => 'DAM'],
            ['name' => 'Falcón', 'code' => 'FAL'],
            ['name' => 'Guárico', 'code' => 'GUA'],
            ['name' => 'Lara', 'code' => 'LAR'],
            ['name' => 'Mérida', 'code' => 'MER'],
            ['name' => 'Miranda', 'code' => 'MIR'],
            ['name' => 'Monagas', 'code' => 'MON'],
            ['name' => 'Nueva Esparta', 'code' => 'NES'],
            ['name' => 'Portuguesa', 'code' => 'POR'],
            ['name' => 'Sucre', 'code' => 'SUC'],
            ['name' => 'Táchira', 'code' => 'TAC'],
            ['name' => 'Trujillo', 'code' => 'TRU'],
            ['name' => 'La Guaira', 'code' => 'VAR'],
            ['name' => 'Yaracuy', 'code' => 'YAR'],
            ['name' => 'Zulia', 'code' => 'ZUL'],
            ['name' => 'Distrito Capital', 'code' => 'DC'],
        ];

        DB::table('states')->insert($states);
    }
}