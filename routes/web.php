<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



//Auth::routes();
Auth::routes(["register" => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::prefix('admin')->middleware(['auth'])->group(function () {

        Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'inicio']);
 
         
        //Rutas de las empresas
        Route::controller(App\Http\Controllers\Admin\CompanyController::class)->group(function () {
            Route::get('/company', 'index')->name('empresas.index');
            Route::get('/company/create', 'create');
            Route::post('/company', 'store');
            Route::get('/company/{company}/edit', 'edit');
            Route::put('/company/{company}', 'update');
            Route::get('/company/show/{id}', 'show'); //ver   
            Route::get('/company/{company_id}/delete', 'destroy');
            Route::get('/company/showrestore', 'showrestore');
            Route::get('/company/restaurar/{idregistro}', 'restaurar');

            Route::get('/company/sininventario/{id}', 'sininventario');
            
            //--------------------------------------------------------------------

            //rutas de los roles
            Route::controller(App\Http\Controllers\Admin\RolController::class)->group(function () {
                Route::get('/rol', 'index')->name('rol.index');
                Route::get('/rol/create', 'create');
                Route::post('/rol', 'store');
                Route::get('/rol/{cliente}/edit', 'edit');
                Route::put('/rol/{cliente}', 'update');
                Route::get('/rol/{product_id}/delete', 'destroy');
            });
            //Ruta de los Usuarios
            Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
                Route::get('/users', 'index')->name('usuario.index');
                Route::get('/users/create', 'create');
                Route::post('/users', 'store');
                Route::get('/users/{user_id}/edit', 'edit');
                Route::put('/users/{user_id}', 'update');
                Route::get('/users/{user_id}/delete', 'destroy');
            });
            //rutas de los historiales
            Route::controller(App\Http\Controllers\Admin\HistorialController::class)->group(function () {
                Route::get('/historial', 'index')->name('historial.index');
                Route::get('/historial/{historial_id}/delete', 'destroy');
                Route::get('/historial/limpiartabla', 'limpiartabla');
            });
            //Ruta de la venta
            Route::controller(App\Http\Controllers\Admin\VentaController::class)->group(function () {
                Route::get('/venta', 'index')->name('venta.index');
                Route::get('/venta2', 'index2')->name('venta2.index');
                Route::get('/venta/create', 'create');
                Route::post('/venta', 'store');
                Route::get('/venta/{venta_id}/edit', 'edit');
                Route::get('/venta/create2/{idcotizacion}', 'create2');
                Route::put('/venta/{venta_id}', 'update');
                Route::get('venta/{venta_id}/delete', 'destroy');
                Route::get('/deletedetalleventa/{id}',  'destroydetalleventa');
                Route::get('/venta/show/{id}', 'show'); //ver  
                Route::get('/venta/showcreditos', 'showcreditos'); //ver   creditos
                Route::get('/venta/comboempresacliente/{id}', 'comboempresacliente'); //para no seleccionar en una venta la misma empresa y cliente  
                Route::get('/venta/productosxtipo/{tipo}', 'productosxtipo'); //devuelve los productos con stock de una empresa
                Route::post('/venta/guardardatospago',  'guardardatospago');
                Route::get('/venta/generarfacturapdf/{id}',  'generarfacturapdf');
                Route::get('/venta/productosxkit/{id}', 'productosxkit'); //ver  
                Route::get('/venta/stockkitxempresa/{id}', 'stockkitxempresa'); //ver  
                Route::get('/venta/stockxprodxempresa/{idproducto}/{idempresa}', 'stockxprodxempresa'); //ver  
                Route::get('/venta/comboempresaclientevi/{id}', 'comboempresaclientevi');
                Route::get('/venta/facturadisponible/{empresa}/{factura}', 'facturadisponible');
                Route::get('/venta/misdetallesventa/{idventa}', 'misdetallesventa'); //ver  
                Route::get('/venta/stocktotalxkit/{id}', 'stocktotalxkit'); //ver  
                Route::get('/venta/sinnumero', 'sinnumero');
                Route::get('/venta/creditosxvencer', 'creditosxvencer');
                Route::get('/venta/precioespecial/{idcliente}/{idproducto}', 'precioespecial');
                Route::get('/venta/listaprecioscompra/{idproducto}/{idempresa}', 'listaprecioscompra');
                Route::post('/venta/verstock', 'verstock');
                Route::get('/venta/productosxdetallexkit/{iddetallekit}', 'productosxdetallexkit'); //ver 
            });
            //Ruta de ingresos
            Route::controller(App\Http\Controllers\Admin\IngresoController::class)->group(function () {
                Route::get('/ingreso', 'index')->name('ingreso.index');
                Route::get('/ingreso2', 'index2')->name('ingreso2.index');
                Route::get('/ingreso/create', 'create');
                Route::post('/ingreso', 'store');
                Route::get('/ingreso/{ingreso_id}/edit', 'edit');
                Route::put('/ingreso/{ingreso_id}', 'update');
                Route::get('ingreso/{ingreso_id}/delete', 'destroy');
                Route::get('/deletedetalleingreso/{id}', 'destroydetalleingreso');
                Route::get('/ingreso/show/{id}', 'show'); //ver  
                Route::get('/ingreso/showcreditos', 'showcreditos'); //ver   creditos
                Route::get('/ingreso/pagarfactura/{id}',  'pagarfactura');
                Route::get('/ingreso/sinnumero', 'sinnumero');
                Route::get('/ingreso/creditosxvencer', 'creditosxvencer');
                Route::get('/ingreso/productosxdetallexkitingreso/{iddetallekit}', 'productosxdetallexkitingreso'); //ver 
                Route::post('/ingreso/guardardatospago',  'guardardatospago');
            });
             
            //Ruta de los reportes
            Route::controller(App\Http\Controllers\Admin\ReportesController::class)->group(function () {
                Route::get('/reporte', 'index');
                Route::get('/reporte/obtenerbalance/{idempresa}', 'obtenerbalance');
                Route::get('/reporte/balancemensualinicio', 'balancemensual');
                Route::get('/reporte/obtenerdatosgrafico/{idempresa}/{fechainicio}/{fechafin}', 'obtenerdatosgrafico');
                Route::get('/reporte/obtenerproductosmasv/{idempresa}/{traer}/{fechainicio}/{fechafin}', 'obtenerproductosmasv');
                Route::get('/reporte/obtenerclientesmasc/{idempresa}/{tipo}/{traer}/{fechainicio}/{fechafin}', 'obtenerclientesmasc');

                Route::get('/reporte/tabladatos', 'infoproductos');
                Route::get('/reporte/datosproductos/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datosproductos');

                Route::get('/reporte/rotacionstock', 'rotacionstock');
                Route::get('/reporte/datosrotacionstock/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datosrotacionstock');
                Route::get('/reporte/detallecompras/{fechainicio}/{fechafin}/{empresa}/{producto}', 'detallecompras');
                Route::get('/reporte/detalleventas/{fechainicio}/{fechafin}/{empresa}/{producto}', 'detalleventas');

                Route::get('/reporte/cobrovent', 'cobroventas');
                Route::get('/reporte/datoscobroventas/{fechainicio}/{fechafin}/{empresa}/{cliente}', 'datoscobroventas');
                Route::get('/reporte/pagocompras', 'pagocompras');
                Route::get('/reporte/datospagocompras/{fechainicio}/{fechafin}/{empresa}/{cliente}', 'datospagocompras');

                Route::get('/reporte/precioscompra', 'precioscompra');
                Route::get('/reporte/datoslistaprecioscompra/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datoslistaprecioscompra');

                //ruta para los graficos de las ventas
                Route::get('/reporte/datosgraficoventas/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datosgraficoventas');
                Route::get('/reporte/datosgraficocompras/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datosgraficocompras');
                //rutapara los precios especiales
                Route::get('/reporte/precioespecial', 'precioespecial');
                Route::get('/reporte/listapreciosespeciales/{cliente}/{producto}', 'listapreciosespeciales');
            });

            //---------------------------------------------------------rutas de tiendita --------------------------------------------
            Route::controller(App\Http\Controllers\Admin\UniformeController::class)->group(function () {
                Route::get('/uniformes', 'index')->name('uniforme.index');
                Route::get('/uniformes/create', 'create');
                Route::post('/uniformes', 'store');
                Route::get('/uniformes/{uniforme_id}/edit', 'edit');
                Route::put('/uniformes/{uniforme_id}', 'update');
                Route::get('/uniformes/{uniforme_id}/delete', 'destroy');
                Route::get('/uniformes/show/{id}', 'show'); //ver   
                //ruta PARA AÑADIR TELA,TALLA Y COLOR EXTRA
                Route::get('/uniformes/addtalla/{talla}', 'addtalla');
                Route::get('/uniformes/addtipotela/{tipotela}', 'addtipotela');
                Route::get('/uniformes/addcolor/{color}', 'addcolor');
                //ruta para ver el inventario
                Route::get('/inventariouniformes', 'inventariouniformes')->name('inventariouniforme.index');
                Route::post('/uniformes/updatestock', 'updatestock');
                //ver sin stock
                Route::get('/uniformes/numerosinstock', 'numerosinstock');
                Route::get('/inventariouniformes2', 'inventariouniformes2')->name('inventariouniformes2.index');
                Route::get('/uniformes/showsinstock', 'showsinstock');
            });
            Route::controller(App\Http\Controllers\Admin\LibroController::class)->group(function () {
                Route::get('/libros', 'index')->name('libro.index');
                Route::get('/libros/create', 'create');
                Route::post('/libros', 'store');
                Route::get('/libros/{libro_id}/edit', 'edit');
                Route::put('/libros/{libro_id}', 'update');
                Route::get('/libros/{libro_id}/delete', 'destroy');
                Route::get('/libros/show/{id}', 'show'); //ver   
                //ruta PARA AÑADIR formato,tipopapel,tipopasta,edicion y especializacion
                Route::get('/libros/addformato/{formato}', 'addformato');
                Route::get('/libros/addtipopapel/{tipopapel}', 'addtipopapel');
                Route::get('/libros/addtipopasta/{tipopasta}', 'addtipopasta');
                Route::get('/libros/addedicion/{edicion}', 'addedicion');
                Route::get('/libros/addespecializacion/{especializacion}', 'addespecializacion');
                //ruta para ver el inventario
                Route::get('/inventariolibros', 'inventariolibros')->name('inventariolibro.index');
                Route::post('/libros/updatestock', 'updatestock');
                //ver sin stock
                Route::get('/libros/numerosinstock', 'numerosinstock');
                Route::get('/inventariolibros2', 'inventariolibros2')->name('inventariolibros2.index');
                Route::get('/libros/showsinstock', 'showsinstock');
            });
            Route::controller(App\Http\Controllers\Admin\InstrumentoController::class)->group(function () {
                Route::get('/instrumentos', 'index')->name('instrumento.index');
                Route::get('/instrumentos/create', 'create');
                Route::post('/instrumentos', 'store');
                Route::get('/instrumentos/{instrumento_id}/edit', 'edit');
                Route::put('/instrumentos/{instrumento_id}', 'update');
                Route::get('/instrumentos/{instrumento_id}/delete', 'destroy');
                Route::get('/instrumentos/show/{id}', 'show'); //ver   
                //ruta PARA AÑADIR MARCA Y MODELO
                Route::get('/instrumentos/addmarca/{marca}', 'addmarca');
                Route::get('/instrumentos/addmodelo/{modelo}', 'addmodelo');
                //ruta para ver el inventario
                Route::get('/inventarioinstrumentos', 'inventarioinstrumentos')->name('inventarioinstrumento.index');
                Route::post('/instrumentos/updatestock', 'updatestock');
                //ver sin stock
                Route::get('/instrumentos/numerosinstock', 'numerosinstock');
                Route::get('/inventarioinstrumentos2', 'inventarioinstrumentos2')->name('inventarioinstrumentos2.index');
                Route::get('/instrumentos/showsinstock', 'showsinstock');
            });
            Route::controller(App\Http\Controllers\Admin\UtilController::class)->group(function () {
                Route::get('/utiles', 'index')->name('utiles.index');
                Route::get('/utiles/create', 'create');
                Route::post('/utiles', 'store');
                Route::get('/utiles/{utile_id}/edit', 'edit');
                Route::put('/utiles/{utile_id}', 'update');
                Route::get('/utiles/{utile_id}/delete', 'destroy');
                Route::get('/utiles/show/{id}', 'show'); //ver   
                //ruta PARA AÑADIR MARCA Y MODELO
                Route::get('/utiles/addmarcautil/{marcautil}', 'addmarcautil');
                Route::get('/utiles/addcolorutil/{coloutil}', 'addcolorutil');
                //ruta para ver el inventario
                Route::get('/inventarioutiles', 'inventarioutiles')->name('inventarioutiles.index');
                Route::post('/utiles/updatestock', 'updatestock');
                //ver sin stock
                Route::get('/utiles/numerosinstock', 'numerosinstock');
                Route::get('/inventarioutiles2', 'inventarioutiles2')->name('inventarioutiles2.index');
                Route::get('/utiles/showsinstock', 'showsinstock');
            });
            Route::controller(App\Http\Controllers\Admin\GolosinaController::class)->group(function () {
                Route::get('/golosinas', 'index')->name('golosinas.index');
                Route::get('/golosinas/create', 'create');
                Route::post('/golosinas', 'store');
                Route::get('/golosinas/{utile_id}/edit', 'edit');
                Route::put('/golosinas/{utile_id}', 'update');
                Route::get('/golosinas/{utile_id}/delete', 'destroy');
                Route::get('/golosinas/show/{id}', 'show'); //ver   
                //ruta PARA AÑADIR MARCA Y MODELO
                Route::get('/golosinas/addmarcautil/{marcautil}', 'addmarcautil');
                Route::get('/golosinas/addcolorutil/{coloutil}', 'addcolorutil');
                //ruta para ver el inventario
                Route::get('/inventariogolosinas', 'inventariogolosinas')->name('inventariogolosinas.index');
                Route::post('/golosinas/updatestock', 'updatestock');
                //ver sin stock
                Route::get('/golosinas/numerosinstock', 'numerosinstock');
                Route::get('/inventariogolosinas2', 'inventariogolosinas2')->name('inventariogolosinas2.index');
                Route::get('/golosinas/showsinstock', 'showsinstock');
            });
            Route::controller(App\Http\Controllers\Admin\SnackController::class)->group(function () {
                Route::get('/snacks', 'index')->name('snacks.index');
                Route::get('/snacks/create', 'create');
                Route::post('/snacks', 'store');
                Route::get('/snacks/{snack_id}/edit', 'edit');
                Route::put('/snacks/{snack_id}', 'update');
                Route::get('/snacks/{snack_id}/delete', 'destroy');
                Route::get('/snacks/show/{id}', 'show'); //ver   
                //ruta PARA AÑADIR MARCA Y MODELO
                Route::get('/snacks/addmarcasnack/{marcasnack}', 'addmarcasnack');
                Route::get('/snacks/addsaborsnack/{saborsnack}', 'addsaborsnack');
                //ruta para ver el inventario
                Route::get('/inventariosnacks', 'inventariosnacks')->name('inventariosnacks.index');
                Route::post('/snacks/updatestock', 'updatestock');
                //ver sin stock
                Route::get('/snacks/numerosinstock', 'numerosinstock');
                Route::get('/inventariosnacks2', 'inventariosnacks2')->name('inventariosnacks2.index');
                Route::get('/snacks/showsinstock', 'showsinstock');
            });
            //Rutas de las empresas
            Route::controller(App\Http\Controllers\Admin\TiendaController::class)->group(function () {
                Route::get('/tienda', 'index')->name('tiendas.index');
                Route::get('/tienda/create', 'create');
                Route::post('/tienda', 'store');
                Route::get('/tienda/{tienda}/edit', 'edit');
                Route::put('/tienda/{tienda}', 'update');
                Route::get('/tienda/show/{id}', 'show'); //ver   
                Route::get('/tienda/{tienda_id}/delete', 'destroy');
            });
            //Ruta de los clientes
            Route::controller(App\Http\Controllers\Admin\ClienteController::class)->group(function () {
                Route::get('/cliente', 'index')->name('cliente.index');
                Route::get('/cliente/create', 'create');
                Route::post('/cliente', 'store');
                Route::get('/cliente/{cliente}/edit', 'edit');
                Route::put('/cliente/{cliente}', 'update');
                Route::get('/cliente/show/{id}', 'show'); //ver
                Route::get('/cliente/{product_id}/delete', 'destroy');
            });
            //Ruta de los proveedores
            Route::controller(App\Http\Controllers\Admin\ProveedorController::class)->group(function () {
                Route::get('/proveedor', 'index')->name('proveedores.index');
                Route::get('/proveedor/create', 'create');
                Route::post('/proveedor', 'store');
                Route::get('/proveedor/{proveedor}/edit', 'edit');
                Route::put('/proveedor/{proveedor}', 'update');
                Route::get('/proveedor/show/{id}', 'show'); //ver
                Route::get('/proveedor/{product_id}/delete', 'destroy');
                Route::get('/proveedor/showrestore', 'showrestore');
                Route::get('/proveedor/restaurar/{idregistro}', 'restaurar');
            });
        });
    });
});
