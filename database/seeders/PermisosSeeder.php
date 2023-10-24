<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            //para roles
            'ver-rol',
            'crear-rol',
            'editar-rol',
            'eliminar-rol',
            //para usuarios
            'ver-usuario',
            'crear-usuario',
            'editar-usuario',
            'eliminar-usuario',
            //para golosinas
            'ver-golosina',
            'crear-golosina',
            'editar-golosina',
            'eliminar-golosina',
            //para instrumentos
            'ver-instrumento',
            'crear-instrumento',
            'editar-instrumento',
            'eliminar-instrumento',
            //para libros
            'ver-libro',
            'crear-libro',
            'editar-libro',
            'eliminar-libro',
            //para empresas
            'ver-proveedor',
            'crear-proveedor',
            'editar-proveedor',
            'eliminar-proveedor',
            //para clientes
            'ver-cliente',
            'crear-cliente',
            'editar-cliente',
            'eliminar-cliente',
            //para  snacks
            'ver-snack',
            'crear-snack',
            'editar-snack',
            'eliminar-snack',
            //para tienda 
            'ver-tienda',
            'crear-tienda',
            'editar-tienda',
            'eliminar-tienda',
            //para  compras o ingresos
            'ver-ingreso',
            'crear-ingreso',
            'editar-ingreso',
            'eliminar-ingreso',
            //para  ventas
            'ver-venta',
            'crear-venta',
            'editar-venta',
            'eliminar-venta',
            //para  lista de precios
            'ver-uniforme',
            'crear-uniforme',
            'editar-uniforme',
            'eliminar-uniforme', 
            //para carrocerias
            'ver-util',
            'crear-util',
            'editar-util',
            'eliminar-util',
             //historial
            'ver-historial',
            'eliminar-historial',

            //para  PRECIO FOB
            'ver-reporte',
            'ver-inventario',
            'editar-inventario',

        ];

        foreach ($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }
    }
}
