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

        //Rutas de los Datos
        Route::controller(App\Http\Controllers\Admin\DatoController::class)->group(function () {
            Route::get('/dato/vertasacambio', 'vertasacambio');
            Route::get('/dato/actualizartasacambio/{tasacambio}/{fecha}/{id}', 'actualizartasacambio');
            Route::get('/dato/traertasasunat', 'vertraertasasunat');
            //Route::get('/dato/traerlistacambio', 'traerlistacambio');
        });
        //Rutas de las Categorias
        Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
            Route::get('/category', 'index')->name("categorias.index");
            Route::get('/category/{category_id}/delete', 'destroy');
            Route::get('/category/showcategoryrestore', 'showcategoryrestore');
            Route::get('/category/restaurar/{idregistro}', 'restaurar');
            //rutas para ajax
            Route::get('/category/addcategoria/{categoria}', 'addcategoria');
            Route::get('/category/updatecategoria/{id}/{categoria}', 'updatecategoria');
        });
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
            Route::get('/cliente/showrestore', 'showrestore');
            Route::get('/cliente/restaurar/{idregistro}', 'restaurar');
        });
        //Rutas de los Productos
        Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('producto.index');
            Route::get('/products/create', 'create');
            Route::post('/products', 'store');
            Route::get('/products/{product}/edit', 'edit');
            Route::put('/products/{product}', 'update');
            Route::get('/products/{product_id}/delete', 'destroy');
            Route::get('/products/show/{id}', 'show'); //ver  
            Route::get('/products/showrestore', 'showrestore');
            Route::get('/products/restaurar/{idregistro}', 'restaurar');
        });
        //Rutas de los Kits
        Route::controller(App\Http\Controllers\Admin\DetallekitController::class)->group(function () {
            Route::get('/kits', 'index')->name('kit.index');
            Route::get('/kits/create', 'create');
            Route::post('/kits', 'store');
            Route::get('/kits/{kit_id}/edit', 'edit');
            Route::put('/kits/{kit_id}', 'update');
            Route::get('/kits/{kit_id}/delete', 'destroy');
            Route::get('/kits/show/{kit_id}', 'show'); //ver   
            Route::get('/kits/showdetallekit/{kit_id}', 'showdetallekit'); //ver   
            Route::get('/kits/showdetallematerial/{carroceria}/{modelo}', 'traerdetallesredmaterial'); //ver   
            Route::get('/deletedetallekit/{id}', 'destroydetallekit');
            Route::get('/kits/showrestore', 'showrestore');
            Route::get('/kits/restaurar/{idregistro}', 'restaurar');
            Route::get('/kits/traermaterialyredes/{carroceria_id}/{modelo_id}', 'traermaterialyredes'); //ver   
            
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
        //Ruta del inventario
        Route::controller(App\Http\Controllers\Admin\InventarioController::class)->group(function () {
            Route::get('/inventario', 'index')->name('inventario.index');
            Route::get('/inventario2', 'index2')->name('inventario2.index');
            Route::get('/inventorystock', 'index3');
            Route::get('/inventario/create', 'create');
            Route::post('/inventario', 'store');
            Route::get('/inventario/{inventario_id}/edit', 'edit');
            Route::put('/inventario/{inventario_id}', 'update');
            Route::get('/inventario/{inventario_id}/delete', 'destroy');
            Route::get('/deletedetalleinventario/{id}', 'destroydetalleinventario');
            Route::get('/inventario/show/{id}', 'show'); //ver  
            Route::get('/inventario/showkits', 'showkits'); //ver  
            Route::get('/inventario/showrestore', 'showrestore');
            Route::get('/inventario/restaurar/{idregistro}', 'restaurar');
            Route::get('/inventario/showsinstock', 'showsinstock');
            Route::get('/inventario/nroeliminados', 'nroeliminados');
            Route::get('/inventario/numerosinstock', 'numerosinstock');
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
            Route::get('/venta/productosxempresa/{id}', 'productosxempresa'); //devuelve los productos con stock de una empresa
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
        //Ruta de la cotizacion
        Route::controller(App\Http\Controllers\Admin\CotizacionesController::class)->group(function () {
            Route::get('/cotizacion', 'index')->name('cotizacion.index');
            Route::get('/cotizacion/create', 'create');
            Route::post('/cotizacion', 'store');
            Route::get('/cotizacion/{cotizacion_id}/edit', 'edit');
            Route::put('/cotizacion/{cotizacion_id}', 'update');
            Route::get('cotizacion/{cotizacion_id}/delete', 'destroy');
            Route::get('/deletedetallecotizacion/{id}',  'destroydetallecotizacion');
            Route::get('/deletecondicion/{id}',  'destroycondicion');
            Route::get('/cotizacion/show/{id}', 'show'); //ver  
            Route::get('/cotizacion/showcondiciones/{id}', 'showcondiciones'); //ver  
            Route::get('/cotizacion/vendercotizacion/{id}',  'vendercotizacion');
            Route::get('/cotizacion/generarcotizacionpdf/{id}',  'generarcotizacionpdf');
            Route::get('/venta/productosxdetallexkitcotizacion/{iddetallekit}', 'productosxdetallexkitcotizacion'); //ver 
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
        //rutas de los roles
        Route::controller(App\Http\Controllers\Admin\RolController::class)->group(function () {
            Route::get('/rol', 'index')->name('rol.index');
            Route::get('/rol/create', 'create');
            Route::post('/rol', 'store');
            Route::get('/rol/{cliente}/edit', 'edit');
            Route::put('/rol/{cliente}', 'update');
            Route::get('/rol/{product_id}/delete', 'destroy');
        });
        //rutas de los historiales
        Route::controller(App\Http\Controllers\Admin\HistorialController::class)->group(function () {
            Route::get('/historial', 'index')->name('historial.index');
            Route::get('/historial/{historial_id}/delete', 'destroy');
            Route::get('/historial/limpiartabla', 'limpiartabla');
        });
        //Ruta de las ventas antiguas
        Route::controller(App\Http\Controllers\Admin\VentasantiguasController::class)->group(function () {
            Route::get('/ventasantiguas', 'index')->name('ventasantiguas.index');
            Route::get('ventasantiguas/{venta_id}/delete', 'destroy');
            Route::get('/ventasantiguas/show/{id}', 'show'); //ver  

        });
        //Rutas de la Lista de precios
        Route::controller(App\Http\Controllers\Admin\ListaprecioController::class)->group(function () {
            Route::get('/listaprecios', 'index')->name('listaprecio.index');
            Route::get('/listaprecios/create', 'create');
            Route::post('/listaprecios', 'store');
            Route::get('/listaprecios/clientesxproducto/{id}', 'clientesxproducto');
            Route::get('/listaprecios/{product}/edit', 'edit');
            Route::put('/listaprecios/{product}', 'update');
            Route::get('/listaprecios/{product_id}/delete', 'destroy');
            Route::get('/listaprecios/show/{id}', 'show'); //ver   
        });
        //Ruta de la orden de compra
        Route::controller(App\Http\Controllers\Admin\OrdencompraController::class)->group(function () {
            Route::get('/ordencompra', 'index')->name('ordencompras.index');
            Route::get('/ordencompra/create', 'create');
            Route::post('/ordencompra', 'store');
            Route::get('/ordencompra/{ordencompra_id}/edit', 'edit');
            Route::put('/ordencompra/{ordencompra_id}', 'update');
            Route::get('/ordencompra/{ordencompra_id}/delete', 'destroy');
            Route::get('/ordencompra/deletedetalleordencompra/{id}',  'destroydetalleordencompra');
            Route::get('/ordencompra/show/{id}', 'show'); //ver     
            Route::get('/ordencompra/generarordencomprapdf/{id}',  'generarordencomprapdf');
        });

        //-------------------rutas para los modelos de produccion -----------------------------
        //Rutas de los Modelos de los carros
        Route::controller(App\Http\Controllers\Admin\ModelocarroController::class)->group(function () {
            Route::get('/modelocarro', 'index')->name("modelocarros.index");
            Route::get('/modelocarro/{id_registro}/delete', 'destroy');
            Route::get('/modelocarro/showmodelocarrorestore', 'showmodelocarrorestore');
            Route::get('/modelocarro/restaurar/{idregistro}', 'restaurar');
            Route::get('/modelocarro/show/{idregistro}', 'show');
            //rutas para ajax
            Route::get('/modelocarro/addmodelo/{modelo}', 'addmodelo');
            Route::get('/modelocarro/updatemodelo/{id}/{modelo}', 'updatemodelo');
        });
        //Rutas de las Carrocerias 
        Route::controller(App\Http\Controllers\Admin\CarroceriaController::class)->group(function () {
            Route::get('/carroceria', 'index')->name("carrocerias.index");
            Route::get('/carroceria/{id_registro}/delete', 'destroy');
            Route::get('/carroceria/showcarroceriarestore', 'showcarroceriarestore');
            Route::get('/carroceria/restaurar/{idregistro}', 'restaurar');
            Route::get('/carroceria/show/{idregistro}', 'show');
            //rutas para ajax
            Route::get('/carroceria/addcarroceria/{carroceria}', 'addcarroceria');
            Route::get('/carroceria/updatecarroceria/{id}/{carroceria}', 'updatecarroceria');
        });
        //Ruta del material electrico
        Route::controller(App\Http\Controllers\Admin\MaterialelectricoController::class)->group(function () {
            Route::get('/materialelectrico', 'index')->name('materialelectrico.index');
            Route::get('/materialelectrico/create', 'create');
            Route::post('/materialelectrico', 'store');
            Route::get('/materialelectrico/{material_id}/edit', 'edit');
            Route::put('/materialelectrico/{material_id}', 'update');
            Route::get('/materialelectrico/{material_id}/delete', 'destroy');
            Route::get('/materialelectrico/deletedetallematerial/{id}',  'destroydetallematerial');
            Route::get('/materialelectrico/show/{id}', 'show'); //ver  
            Route::get('/materialelectrico/modeloxcarroceria/{id}', 'modeloxcarroceria'); //ver  
        });
        //Ruta del material electrico
        Route::controller(App\Http\Controllers\Admin\RedController::class)->group(function () {
            Route::get('/redes', 'index')->name('red.index');
            Route::get('/redes/create', 'create');
            Route::post('/redes', 'store');
            Route::get('/redes/{material_id}/edit', 'edit');
            Route::put('/redes/{material_id}', 'update');
            Route::get('/redes/{material_id}/delete', 'destroy');
            Route::get('/redes/deletedetallered/{id}',  'destroydetallered');
            Route::get('/redes/show/{id}', 'show'); //ver  
            Route::get('/redes/modeloxcarroceria/{id}', 'modeloxcarroceria'); //ver  
        });

        //Rutas de la Produccion de carros
        Route::controller(App\Http\Controllers\Admin\ProduccioncarroController::class)->group(function () {
            Route::get('/produccioncarro', 'index')->name("produccioncarros.index");
            Route::get('/produccioncarro/create', 'create');
            Route::post('/produccioncarro', 'store');
            Route::get('/produccioncarro/{id_registro}/edit', 'edit');
            Route::put('/produccioncarro/{id_registro}', 'update');
            Route::get('/produccioncarro/{id_registro}/delete', 'destroy');
            Route::get('/produccioncarro/show/{id_registro}', 'show');
            Route::get('/produccioncarro/showmateriales/{id_registro}', 'showmateriales');
            Route::get('/produccioncarro/showcarros/{id_registro}', 'showcarros');
            Route::get('/produccioncarro/showredes/{id_registro}', 'showredes');
            Route::get('/produccioncarro/deletematerialcarro/{id_registro}', 'destroymaterial');
            Route::get('/produccioncarro/deleteredcarro/{id_registro}', 'destroyred');
            Route::get('/produccioncarro/deletecarro/{id_registro}', 'destroycarro');
            Route::get('/produccioncarro/materialcarroceria/{id_carroceria}/{id_modelo}', 'materialcarroceria');
            Route::get('/produccioncarro/enviarmaterialelectrico/{id_carro}/{fecha}', 'enviarmaterialelectrico');
            Route::get('/produccioncarro/actualizarcantidadenviada/{id_carro}', 'actualizarcantidadenviada');
            Route::get('/produccioncarro/addmaterialadicional/{id_produccion}/{cantidad}/{id_producto}/{id_empresa}/{tipo}', 'addmaterialadicional');
            Route::get('/produccioncarro/addredadicional/{id_produccion}/{cantidad}/{id_producto}/{id_empresa}/{tipo}', 'addredadicional');
            Route::get('/produccioncarro/materialadicionalxproduccion/{id_produccion}/{id_carro}', 'materialadicionalxproduccion');
            Route::post('/produccioncarro/guardarenviomaterialA',  'guardarenviomaterialA'); 
            Route::get('/produccioncarro/cantEnviadoMA/{id_produccion}', 'cantEnviadoMA');
            Route::get('/produccioncarro/fechaMA/{id_carro}/{fecha}', 'fechaMA');
            Route::get('/produccioncarro/fechaRed/{id_carro}/{fecha}', 'fechaRed');
            Route::get('/produccioncarro/todoenviado/{id_produccion}', 'todoenviado');
            Route::get('/produccioncarro/redes/{id_carroceria}/{id_modelo}', 'redes');
            Route::get('/produccioncarro/cantEnviadoRed/{id_produccion}', 'cantEnviadoRed');
            Route::get('/produccioncarro/redesxproduccion/{id_produccion}/{id_carro}', 'redesxproduccion');
            Route::post('/produccioncarro/guardarenvioredes',  'guardarenvioredes');
            Route::post('/produccioncarro/addmaterialdetalle',  'addmaterialdetalle');
            Route::post('/produccioncarro/btnredesenviadas', 'btnredesenviadas');
            Route::get('/produccioncarro/productosxkitxmaterial/{id_kit}', 'productosxkitxmaterial');
            Route::get('/produccioncarro/actualizarfecharRedMat/{id_produccion}/{redmat}/{idregistro}', 'actualizarfecharRedMat');

            Route::post('/produccioncarro/crearfactura', 'crearfactura');
            Route::get('/produccioncarro/traercarros/{id_produccion}', 'traercarros');
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
        });
    });
});
