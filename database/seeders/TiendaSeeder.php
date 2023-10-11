<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tienda;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TiendaSeeder extends Seeder
{
    public function run()
    {
        Tienda::create([
            'nombre' => 'TIENDA NRO 1',
            'encargado' => 'sin encargado',
            'ubicacion' => 'sin direccion',
        ]);
        Tienda::create([
            'nombre' => 'TIENDA NRO 2',
            'encargado' => 'sin encargado',
            'ubicacion' => 'sin direccion',
        ]);
        Tienda::create([
            'nombre' => 'TIENDA NRO 3',
            'encargado' => 'sin encargado',
            'ubicacion' => 'sin direccion',
        ]);
    }

}