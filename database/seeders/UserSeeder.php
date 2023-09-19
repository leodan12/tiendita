<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{

    public function run()
    {
        $usuarioadmin = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
        ]);
        $roladmin = Role::create([
            'name' => 'Administrador',
        ]);
        $permisos = Permission::pluck('id', 'id')->all();
        $roladmin->syncPermissions($permisos);
        $usuarioadmin->assignRole([$roladmin->id]);



        $usuariouser = User::create([
            'name' => 'usuario1',
            'email' => 'usuario1@gmail.com',
            'password' => '$2y$10$x605uXRasyIZuY9sbJlyiOCPPUnBvPpm4X5ERa7JrzD9CNXjTIGQW',
        ]);
        $roluser =  Role::create([
            'name' => 'Usuario',
        ]);
        $permisosuser =  [
            'ver-categoria', 'crear-categoria', 'editar-categoria', 'eliminar-categoria',
            'ver-producto', 'crear-producto', 'editar-producto', 'eliminar-producto',
            'ver-kit', 'crear-kit', 'editar-kit', 'eliminar-kit',
            'ver-empresa', 'crear-empresa', 'editar-empresa', 'eliminar-empresa',
            'ver-cliente', 'crear-cliente',  'editar-cliente',  'eliminar-cliente',
            'ver-inventario', 'crear-inventario', 'editar-inventario', 'eliminar-inventario',
            'ver-cotizacion', 'crear-cotizacion', 'editar-cotizacion', 'eliminar-cotizacion',
            'ver-ingreso', 'crear-ingreso', 'editar-ingreso', 'eliminar-ingreso',
            'ver-venta', 'crear-venta', 'editar-venta', 'eliminar-venta',
            'ver-lista-precios', 'crear-lista-precios', 'editar-lista-precios', 'eliminar-lista-precios',
            'ver-modelo-carro', 'crear-modelo-carro', 'editar-modelo-carro', 'eliminar-modelo-carro',
            'ver-carroceria', 'crear-carroceria', 'editar-carroceria', 'eliminar-carroceria',
            'ver-produccion-carro', 'crear-produccion-carro', 'editar-produccion-carro', 'eliminar-produccion-carro',
            'ver-orden-compra', 'crear-orden-compra', 'editar-orden-compra', 'eliminar-orden-compra',

            'ver-reporte',
            'recuperar-categoria', 'recuperar-producto',   'recuperar-kit', 'recuperar-empresa', 'recuperar-cliente',
            'recuperar-inventario', 'recuperar-modelo-carro', 'recuperar-carroceria',
        ];

        $roluser->syncPermissions($permisosuser);
        $usuariouser->assignRole([$roluser->id]);
    }
}
