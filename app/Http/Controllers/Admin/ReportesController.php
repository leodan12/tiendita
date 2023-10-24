<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\Product;
use App\Models\Cliente;

class ReportesController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware(
            'permission:ver-reporte',
            ['only' => [
                'index', 'misventas', 'todasfechas', 'ventasdelmes', 'comprasdelmes', 'cotizacionesdelmes', 'obtenerdatosgrafico', 'obtenerproductosmasv', 'obtenerproductoscantidad', 'productosindividuales', 'productosxkit', 'sumaproductos', 'prodseparados', 'obtenerclientesmasc', 'clientescantidad', 'clientescosto', 'misclientescosto', 'obtenerbalance', 'devolverclientes', 'devolverclientescant', 'sumarcostoventa', 'obtenerproductos', 'obtenercotizaciones', 'obtenerventas', 'obteneringresos', 'numeroproductos', 'numerocotizaciones', 'numeroingresos', 'numeroventas', 'balancemensual', 'coninfocompleta', 'obtenerdatosproductosventa', 'obtenerdatosproductoscompra', 'datosproductos', 'infoproductos', 'resultadoventas', 'productosestandar', 'misproductosvendidos', 'datosrotacionstock', 'rotacionstock', 'detallecompras', 'sumarresultado', 'obtenermisventas', 'detalleventas', 'misproductoscomprados', 'obtenermiscompras', 'productosestandar2'
            ]]
        );
    }
    //vista index datos
    public function index()
    {
        $hoy = date('Y-m-d');
        $dias = date('d');
        $dias = $dias - 1;
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dias days"));
        $tiendas = Tienda::all();

        $ingresomes = $this->obteneringresos(-1, date('d'));
        $ingresosemana = $this->obteneringresos(-1, date('w'));
        $ingresodia = $this->obteneringresos(-1, 1);

        $ventames = $this->obtenerventas(-1, date('d'));
        $ventasemana = $this->obtenerventas(-1, date('w'));
        $ventadia = $this->obtenerventas(-1, 1);



        $ventas = $this->ventasdelmes('-1', $inicio, $hoy);
        $compras = $this->comprasdelmes('-1', $inicio, $hoy);


        $fechas = $this->todasfechas($ventas, $compras, $cotizacions);
        $datosventas = $this->misventas($fechas, $ventas);
        $datoscompras = $this->misventas($fechas, $compras);
        $datoscotizacions = $this->misventas($fechas, $cotizacions);

        //return $datosventas;
        return view(
            'admin.reporte.index',
            compact(
                'fechas',
                'datosventas',
                'datoscompras',
                'datoscotizacions',
                'ingresomes',
                'ingresosemana',
                'ingresodia',
                'ventames',
                'ventasemana',
                'ventadia',
                'cotizacionmes',
                'cotizacionsemana',
                'cotizaciondia',
                'companies',
                'productomes',
                'productominimo',
                'productosinstock',
            )
        );
    }
    //obtener los datos para las tarjetas de ventas, compras, y cotizacion
    public function obtenerdatosgrafico($empresa, $fechainicio, $fechafin)
    {
        $ventas = $this->ventasdelmes($empresa, $fechainicio, $fechafin);
        $compras = $this->comprasdelmes($empresa, $fechainicio, $fechafin);
        $cotizacions = $this->cotizacionesdelmes($empresa, $fechainicio, $fechafin);

        $fechas = $this->todasfechas($ventas, $compras, $cotizacions);
        $datosventas = $this->misventas($fechas, $ventas);
        $datoscompras = $this->misventas($fechas, $compras);
        $datoscotizacions = $this->misventas($fechas, $cotizacions);

        $misdatos = collect();
        $misdatos->put('fechas', $fechas);
        $misdatos->put('datosventas', $datosventas);
        $misdatos->put('datoscompras', $datoscompras);
        $misdatos->put('datoscotizacions', $datoscotizacions);

        return $misdatos;
    }
    //-----------------------para los 4 cuadros del index de reportes--------------------
    public function obtenerbalance($idempresa)
    {
        $ingresomes = $this->obteneringresos($idempresa, date('d'));
        $ingresosemana = $this->obteneringresos($idempresa, date('w'));
        $ingresodia = $this->obteneringresos($idempresa, 1);

        $ventames = $this->obtenerventas($idempresa, date('d'));
        $ventasemana = $this->obtenerventas($idempresa, date('w'));
        $ventadia = $this->obtenerventas($idempresa, 1);

        $cotizacionmes = $this->obtenercotizaciones($idempresa, date('d'));
        $cotizacionsemana = $this->obtenercotizaciones($idempresa, date('w'));
        $cotizaciondia = $this->obtenercotizaciones($idempresa, 1);

        $productomes = $this->obtenerproductos($idempresa, "-1");
        $productominimo = $this->obtenerproductos($idempresa, "minimo");
        $productosinstock = $this->obtenerproductos($idempresa, "sin");


        $resultados = collect();
        $resultados->put('ingresomes', $ingresomes);
        $resultados->put('ingresosemana', $ingresosemana);
        $resultados->put('ingresodia', $ingresodia);
        $resultados->put('ventames', $ventames);
        $resultados->put('ventasemana', $ventasemana);
        $resultados->put('ventadia', $ventadia);
        $resultados->put('cotizacionmes', $cotizacionmes);
        $resultados->put('cotizacionsemana', $cotizacionsemana);
        $resultados->put('cotizaciondia', $cotizaciondia);
        $resultados->put('productomes', $productomes);
        $resultados->put('productominimo', $productominimo);
        $resultados->put('productosinstock', $productosinstock);

        return $resultados;
    }
    //obtener los ingresos  de una empresa
    public function obteneringresos($idtienda, $dia)
    {
        $hoy = date('Y-m-d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dia days"));

        if ($idtienda != -1) {
            $ingresos = DB::table('ingresos as i')
                ->where('i.tienda_id', '=', $idtienda)
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->sum('i.costoventa');
        } else {
            $ingresos = DB::table('ingresos as i')
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->sum('i.costoventa');
        }
        return   $ingresos;
    }
    //obtener las ventas de una empresa
    public function obtenerventas($idtienda, $dia)
    {
        $hoy = date('Y-m-d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dia days"));
        if ($idtienda != -1) {
            $ventas = DB::table('ventas as i')
                ->where('i.tienda_id', '=', $idtienda)
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->sum('i.costoventa');
        } else {
            $ventas = DB::table('ventas as i')
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>', $inicio)
                ->sum('i.costoventa');
        }
        return   $ventas;
    }
    //obtener las cotizaciones de una empresa

    //obtener los productos de una empresa
    public function obtenerproductos($idempresa, $stock)
    {
        $productos = 0;
        if ($stock == '-1') {
            if ($idempresa == '-1') {
                $productos = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->count();
            } else {
                $productos = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $idempresa)
                    ->count();
            }
        } else if ($stock == 'minimo') {
            $cont = 0;
            if ($idempresa == '-1') {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stocktotal <= $prod[$i]->stockminimo) {
                        $cont++;
                    }
                }
            } else {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $idempresa)
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stockempresa <= $prod[$i]->stockminimo) {
                        $cont++;
                    }
                }
            }
            $productos = $cont;
        } else if ($stock == 'sin') {
            $cont = 0;
            if ($idempresa == '-1') {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stocktotal == 0) {
                        $cont++;
                    }
                }
            } else {
                $prod = DB::table('products as p')
                    ->join('inventarios as i', 'i.product_id', '=', 'p.id')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $idempresa)
                    ->get();
                for ($i = 0; $i < count($prod); $i++) {
                    if ($prod[$i]->stockempresa == 0) {
                        $cont++;
                    }
                }
            }
            $productos = $cont;
        }
        return $productos;
    }

    //---obtener los datos para el reporte grafico
    public function misventas($fechas, $ventas)
    {
        $datosventas = [];
        for ($i = 0; $i < count($fechas); $i++) {
            $sum  = 0;
            for ($x = 0; $x < count($ventas); $x++) {
                if ($fechas[$i] == $ventas[$x]->fecha) {
                    if ($ventas[$x]->moneda == 'dolares') {
                        $sum = $sum + (round($ventas[$x]->costoventa * $ventas[$x]->tasacambio, 2));
                    } else {
                        $sum = $sum + $ventas[$x]->costoventa;
                    }
                }
            }
            if ($sum == 0) {
                $datosventas[] = "";
            } else {
                $datosventas[] = round($sum, 2); //redondear a 2 cifras
            }
        }
        return $datosventas;
    }
    //para compras , ventas y cotizaciones
    public function todasfechas($ventas, $compras, $cotizaciones)
    {
        $fechas = [];
        for ($i = 0; $i < count($ventas); $i++) {
            $fechas[] = $ventas[$i]->fecha;
        }
        for ($i = 0; $i < count($compras); $i++) {
            $fechas[] = $compras[$i]->fecha;
        }
        for ($i = 0; $i < count($cotizaciones); $i++) {
            $fechas[] = $cotizaciones[$i]->fecha;
        }
        $resultado = (array_unique($fechas));
        asort($resultado);
        return array_values($resultado);
    }
    //obtener las ventas del mes actual
    public function ventasdelmes($empresa, $inicio, $hoy)
    {
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->groupBy('v.fecha', 'v.moneda', 'v.tasacambio')
                ->select('v.fecha', 'v.moneda', 'v.tasacambio', DB::raw('sum(v.costoventa) as costoventa'))
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->groupBy('v.fecha', 'v.moneda', 'v.tasacambio')
                ->select('v.fecha', 'v.moneda', 'v.tasacambio', DB::raw('sum(v.costoventa) as costoventa'))
                ->get();
        }

        return $ventas;
    }
    //obtener las compras del mes actual
    public function comprasdelmes($empresa, $inicio, $hoy)
    {
        $compras = "";
        if ($empresa != '-1') {
            $compras = DB::table('ingresos as i')
                ->where('i.company_id', '=', $empresa)
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>=', $inicio)
                ->groupBy('i.fecha', 'i.moneda', 'i.tasacambio')
                ->select('i.fecha', 'i.moneda', 'i.tasacambio', DB::raw('sum(i.costoventa) as costoventa'))
                ->get();
        } else {
            $compras = DB::table('ingresos as i')
                ->where('i.fecha', '<=', $hoy)
                ->where('i.fecha', '>=', $inicio)
                ->groupBy('i.fecha', 'i.moneda', 'i.tasacambio')
                ->select('i.fecha', 'i.moneda', 'i.tasacambio', DB::raw('sum(i.costoventa) as costoventa'))
                ->get();
        }

        return $compras;
    }
    //obtener las cotizaciones del mes
    public function cotizacionesdelmes($empresa, $inicio, $hoy)
    {
        $cotizaciones = "";
        if ($empresa != '-1') {
            $cotizaciones = DB::table('cotizacions as c')
                ->where('c.company_id', '=', $empresa)
                ->where('c.fecha', '<=', $hoy)
                ->where('c.fecha', '>=', $inicio)
                ->groupBy('c.fecha', 'c.moneda', 'c.tasacambio')
                ->select('c.fecha', 'c.moneda', 'c.tasacambio', DB::raw('sum(c.costoventasinigv) as costoventa'))
                ->get();
        } else {
            $cotizaciones = DB::table('cotizacions as c')
                ->where('c.fecha', '<=', $hoy)
                ->where('c.fecha', '>=', $inicio)
                ->groupBy('c.fecha', 'c.moneda', 'c.tasacambio')
                ->select('c.fecha', 'c.moneda', 'c.tasacambio', DB::raw('sum(c.costoventasinigv) as costoventa'))
                ->get();
        }
        return $cotizaciones;
    }

    //--------------para los productos mas vendidos para el grafico---------------
    public function obtenerproductosmasv($empresa, $traer, $fechainicio, $fechafin)
    {
        $productos = $this->obtenerproductoscantidad($empresa, $fechainicio, $fechafin);
        $productosind = $this->productosindividuales($productos);
        $micantidadproductos = $this->sumaproductos($productosind);
        $ordenados = $micantidadproductos->sortByDesc('cantidad');
        $ordenados20 = $ordenados->take($traer);
        $separados = $this->prodseparados($ordenados20->values()->all());
        return $separados;
    }
    //obtener los productos mas vendidos por cantidad
    public function obtenerproductoscantidad($empresa, $inicio, $hoy)
    {
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->groupBy('p.tipo', 'p.nombre', 'p.id', 'dv.id')
                ->select('p.tipo', 'p.nombre', 'p.id', DB::raw('sum(dv.cantidad) as cantidad'), 'dv.id as iddetalleventa')
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->groupBy('p.tipo', 'p.nombre', 'p.id', 'dv.id')
                ->select('p.tipo', 'p.nombre', 'p.id', DB::raw('sum(dv.cantidad) as cantidad'), 'dv.id as iddetalleventa')
                ->get();
        }

        return $ventas;
    }
    //obtener los productos de forma individual
    public function productosindividuales($productos)
    {
        $productosL = collect();
        for ($i = 0; $i < count($productos); $i++) {
            if ($productos[$i]->tipo == "estandar") {
                $prod = collect();
                $prod->put('producto', $productos[$i]->nombre);
                $prod->put('cantidad', $productos[$i]->cantidad);
                $productosL->push($prod);
            } else {
                $detalleprod = $this->productosxdetallexkit($productos[$i]->iddetalleventa);
                for ($x = 0; $x < count($detalleprod); $x++) {
                    $prod = collect();
                    $prod->put('producto', $detalleprod[$x]->producto);
                    $prod->put('cantidad', ($detalleprod[$x]->cantidad) * $productos[$i]->cantidad);
                    $productosL->push($prod);
                }
            }
        }
        return $productosL;
    }
    //obtener los productos de un kit
    public function productosxkit($kit_id)
    {
        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id', 'p.nombre as producto', 'k.cantidad', 'k.preciounitariomo', 'p.tasacambio', 'p.moneda')
            ->get();
        return $productosxkit;
    }
    //para obetner los productos de un detalle venta
    public function productosxdetallexkit($detalleventa_id)
    {
        $productosxkit_venta = DB::table('products as p')
            ->join('detalle_kitventas as dkv', 'dkv.kitproduct_id', '=', 'p.id')
            ->where('dkv.detalleventa_id', '=', $detalleventa_id)
            ->select('p.id', 'p.nombre as producto', 'dkv.cantidad')
            ->get();
        return  $productosxkit_venta;
    }
    //para buscar los productos vendidos en un detalle kit
    public function productosxdetallexkitingreso($detalleingreso_id)
    {
        $productosxkit_ingreso = DB::table('products as p')
            ->join('detalle_kitingresos as dki', 'dki.kitproduct_id', '=', 'p.id')
            ->where('dki.detalleingreso_id', '=', $detalleingreso_id)
            ->select('p.id', 'p.nombre as producto', 'dki.cantidad')
            ->get();
        return  $productosxkit_ingreso;
    }
    //sumar la cantidad de los productos
    public function sumaproductos($productos)
    {
        $misproductos = collect();
        $unique = $productos->unique('producto');
        $misproductosunicos = $unique->values()->all();
        for ($i = 0; $i < count($misproductosunicos); $i++) {
            $cont = 0;
            for ($x = 0; $x < count($productos); $x++) {
                if ($misproductosunicos[$i]['producto'] == $productos[$x]['producto']) {
                    $cont = $cont + $productos[$x]['cantidad'];
                }
            }
            $miprod = collect();
            $miprod->put('producto', $misproductosunicos[$i]['producto']);
            $miprod->put('cantidad', $cont);
            $misproductos->push($miprod);
        }
        return $misproductos;
    }
    //agregar los productos por separado a una coleccion
    public function prodseparados($productos)
    {
        $misdatos = collect();
        $prods = [];
        $cant = [];
        for ($i = 0; $i < count($productos); $i++) {
            $prods[] = $productos[$i]['producto'];
            $cant[] = $productos[$i]['cantidad'];
        }
        $misdatos->put('productos', $prods);
        $misdatos->put('cantidades', $cant);
        return $misdatos;
    }
    //------------- obtener los clientes con mas compras -----------------
    public function obtenerclientesmasc($empresa, $tipo, $traer, $fechainicio, $fechafin)
    {
        $datoscliente = "";
        if ($tipo == "cantidad") {
            $clientescantidad = $this->clientescantidad($empresa, $fechainicio, $fechafin);
            $ordenados =   $clientescantidad->sortByDesc('compras');
            $misclientes  = $ordenados->take($traer);
            $clientes = $misclientes->values()->all();
            $datoscliente = $this->devolverclientescant($clientes);
        } else {
            $clientes = $this->clientescosto($empresa, $fechainicio, $fechafin);
            $clientesunicos = $this->misclientescosto($clientes);
            $clienteorder =  $clientesunicos->sortByDesc('costo');
            $clientetake = $clienteorder->take($traer);
            $client = $clientetake->values()->all();
            $datoscliente = $this->devolverclientes($client);
        }
        return  $datoscliente;
    }
    //obtener los clientes con mas compras por cantidad
    public function clientescantidad($empresa, $inicio, $hoy)
    {
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->groupBy('c.nombre')
                ->select('c.nombre', DB::raw('count(v.id) as compras'))
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->groupBy('c.nombre')
                ->select('c.nombre', DB::raw('count(v.id) as compras'))
                ->get();
        }
        return $ventas;
    }
    //obtener los clientes con mas compras pero por codto
    public function clientescosto($empresa, $inicio, $hoy)
    {
        $ventas = "";
        if ($empresa != '-1') {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.company_id', '=', $empresa)
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->select('c.nombre', 'v.moneda', 'tasacambio', 'v.costoventa')
                ->get();
        } else {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->where('v.fecha', '<=', $hoy)
                ->where('v.fecha', '>=', $inicio)
                ->select('c.nombre', 'v.moneda', 'tasacambio', 'v.costoventa')
                ->get();
        }
        return $ventas;
    }
    //obtener los clientes con mas compras por costo
    public function misclientescosto($clientes)
    {
        $unicos = $clientes->unique('nombre');
        $clientesunicos = $unicos->values()->all();
        $misclientes = collect();
        for ($i = 0; $i < count($clientesunicos); $i++) {
            $sum = 0;
            for ($x = 0; $x < count($clientes); $x++) {
                if ($clientesunicos[$i]->nombre == $clientes[$x]->nombre) {
                    if ($clientes[$x]->moneda == "dolares") {
                        $sum = $sum + round(($clientes[$x]->costoventa) * $clientes[$x]->tasacambio, 2);
                    } else {
                        $sum = $sum + ($clientes[$x]->costoventa);
                    }
                }
            }
            $miclient = collect();
            $miclient->put('cliente', $clientesunicos[$i]->nombre);
            $miclient->put('costo', round($sum, 2));
            $misclientes->push($miclient);
        }
        return $misclientes;
    }
    //devolverc la cantidad de los clientes
    public function devolverclientescant($clientes)
    {
        $misdatos = collect();
        $cliente = [];
        $cant = [];
        for ($i = 0; $i < count($clientes); $i++) {
            $cliente[] = $clientes[$i]->nombre;
            $cant[] = $clientes[$i]->compras;
        }
        $misdatos->put('clientes', $cliente);
        $misdatos->put('costos', $cant);
        return $misdatos;
    }
    //funcion para obtener los clientes con mas ventas
    public function devolverclientes($clientes)
    {
        $misdatos = collect();
        $cliente = [];
        $costo = [];
        for ($i = 0; $i < count($clientes); $i++) {
            $cliente[] = $clientes[$i]['cliente'];
            $costo[] = $clientes[$i]['costo'];
        }
        $misdatos->put('clientes', $cliente);
        $misdatos->put('costos', $costo);
        return $misdatos;
    }

    //------------------------para los cuadros de inicio de sesion-----------------
    //balacen con los datos de compras, ventas y cotizaciones del inicio de sesion
    public function balancemensual()
    {
        $fecha = date('Y-m-d');
        $dia = date('d');
        $inicio =  date("Y-m-d", strtotime($fecha . "- $dia days"));
        $numeroventas = $this->numeroventas($inicio);
        $numerocompras = $this->numerocompras($inicio);
        $sinstocks = $this->productossinstock($inicio);

        //return $productosin;

        $datos = collect();
        $datos->put('ventas', $numeroventas);
        $datos->put('compras', $numerocompras);
        $datos->put('stocks', $sinstocks);
        return $datos;
    }
    //obtener el numero de ventas realizadas a credito y contado
    public function numeroventas($inicio)
    {
        $datos = collect();
        $ventas = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->select('dv.tipo', DB::raw('SUM(dv.cantidad) as cantidad'))
            ->where('v.fecha', '>=', $inicio)
            ->groupBy('dv.tipo')
            ->get();
        foreach ($ventas as $venta) {
            $datos->put($venta->tipo, $venta->cantidad);
        }
        return   $datos;
    }
    public function numerocompras($inicio)
    {
        $datos = collect();
        $ingresos = DB::table('ingresos as i')
            ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
            ->select('di.tipo', DB::raw('SUM(di.cantidad) as cantidad'))
            ->where('i.fecha', '>=', $inicio)
            ->groupBy('di.tipo')
            ->get();
        foreach ($ingresos as $ingreso) {
            $datos->put($ingreso->tipo, $ingreso->cantidad);
        }
        return   $datos;
    }
    public function productossinstock($inicio)
    {
        $datos = collect();
        $registro = DB::table('utiles as r')
            ->count();
        $datos->put('UTILES', $registro);
        $registro1 = DB::table('libros as r')
            ->count();
        $datos->put('LIBROS', $registro1);
        $registro2 = DB::table('uniformes as r')
            ->count();
        $datos->put('UNIFORMES', $registro2);
        $registro3 = DB::table('instrumentos as r')
            ->count();
        $datos->put('INSTRUMENTOS', $registro3);
        $registro4 = DB::table('golosinas as r')
            ->count();
        $datos->put('GOLOSINAS', $registro4);
        $registro5 = DB::table('snacks as r')
            ->count();
        $datos->put('SNACKS', $registro5);

        return   $datos;
    }

    //--------------para traer datos de las ventas y compras de los productos por empresa o producto y fechas-------------------------
    //vista inicio de reporte de ventas y compras
    public function infoproductos()
    {
        $companies = Company::all();
        // $productos = Product::all();
        return view('admin.reporte.infoproductos', compact('companies'));
    }
    //obtener los datos de las ventas y compras
    public function datosproductos($fechainicio, $fechafin, $empresa, $producto)
    {
        $miscompras = $this->obtenerdatosproductoscompra($fechainicio, $fechafin, $empresa, $producto);
        $misventas = $this->obtenerdatosproductosventa($fechainicio, $fechafin, $empresa, $producto);

        $kitsventas = $this->todoskits($fechainicio, $fechafin, $empresa, "venta");
        $kitscompras = $this->todoskits($fechainicio, $fechafin, $empresa, "compra");

        $productokits = $this->todosestandarkit($kitscompras, $kitsventas, $producto);

        $datos = $this->coninfocompleta($miscompras, $misventas);

        $unidos = $datos->concat($productokits);

        $unidosensoles = $this->ventasycomprasensoles($unidos);

        return $unidosensoles;
    }
    //convertir el precio de las compras y ventas en soles
    public function ventasycomprasensoles($datos)
    {
        $todoslosdatos = collect();
        for ($i = 0; $i < count($datos); $i++) {
            $preciof = 0;
            $preciou = 0;
            if ($datos[$i]['moneda'] == 'dolares') {
                $preciof = round($preciof + round($datos[$i]['preciofinal'] * $datos[$i]['tasacambio'], 2), 2);
                $preciou = round($preciou + round($datos[$i]['preciounitariomo'] * $datos[$i]['tasacambio'], 2), 2);
            } else {
                $preciof = round($preciof + $datos[$i]['preciofinal'], 2);
                $preciou = round($preciou + $datos[$i]['preciounitariomo'], 2);
            }
            $micompra = collect();
            $micompra->put('compraventa', $datos[$i]['compraventa']);
            $micompra->put('empresa', $datos[$i]['empresa']);
            $micompra->put('factura', $datos[$i]['factura']);
            $micompra->put('cliente', $datos[$i]['cliente']);
            $micompra->put('producto', $datos[$i]['producto']);
            $micompra->put('cantidad', $datos[$i]['cantidad']);
            $micompra->put('preciounitariomo', $preciou);
            $micompra->put('preciofinal', $preciof);
            $micompra->put('moneda', "soles");
            $micompra->put('fecha', $datos[$i]['fecha']);
            $micompra->put('tipo', $datos[$i]['tipo']);

            $todoslosdatos->push($micompra);
        }
        return  $todoslosdatos;
    }
    //obtener los datos de los productos comprados
    public function obtenerdatosproductoscompra($fechainicio, $fechafin, $empresa, $producto)
    {
        $compras = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            } else {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            } else {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.tipo',
                        'i.tasacambio',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            }
        }
        return $compras;
    }
    //obtener datos de los productos vendidos
    public function obtenerdatosproductosventa($fechainicio, $fechafin, $empresa, $producto)
    {
        $ventas = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.tipo',
                        'v.tasacambio',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            }
        }
        return $ventas;
    }
    //unir los datos de las venctas con las compras
    public function coninfocompleta($miscompras, $misventas)
    {
        $todoslosdatos = collect();
        for ($i = 0; $i < count($miscompras); $i++) {
            $micompra = collect();
            $micompra->put('compraventa', 'INGRESO');
            $micompra->put('empresa', $miscompras[$i]->empresa);
            $micompra->put('factura', $miscompras[$i]->factura);
            $micompra->put('cliente', $miscompras[$i]->cliente);
            $micompra->put('producto', $miscompras[$i]->producto);
            $micompra->put('cantidad', $miscompras[$i]->cantidad);
            $micompra->put('preciounitariomo', $miscompras[$i]->preciounitariomo);
            $micompra->put('preciofinal', $miscompras[$i]->preciofinal);
            $micompra->put('moneda', $miscompras[$i]->moneda);
            $micompra->put('fecha', $miscompras[$i]->fecha);
            $micompra->put('tipo', $miscompras[$i]->tipo);
            $micompra->put('tasacambio', $miscompras[$i]->tasacambio);

            $todoslosdatos->push($micompra);
        }
        for ($x = 0; $x < count($misventas); $x++) {
            $miventa = collect();
            $miventa->put('compraventa', 'VENTA');
            $miventa->put('empresa', $misventas[$x]->empresa);
            $miventa->put('factura', $misventas[$x]->factura);
            $miventa->put('cliente', $misventas[$x]->cliente);
            $miventa->put('producto', $misventas[$x]->producto);
            $miventa->put('cantidad', $misventas[$x]->cantidad);
            $miventa->put('preciounitariomo', $misventas[$x]->preciounitariomo);
            $miventa->put('preciofinal', $misventas[$x]->preciofinal);
            $miventa->put('moneda', $misventas[$x]->moneda);
            $miventa->put('fecha', $misventas[$x]->fecha);
            $miventa->put('tipo', $misventas[$x]->tipo);
            $miventa->put('tasacambio', $misventas[$x]->tasacambio);
            $todoslosdatos->push($miventa);
        }
        return $todoslosdatos;
    }
    //obtener todos los kits de las compras y ventas
    public function todoskits($fechainicio, $fechafin, $empresa, $compraventa)
    {
        $registros = "";
        if ($compraventa == "compra") {
            if ($empresa != "-1") {
                $registros = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.id as idproducto',
                        'i.tasacambio',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            } else {
                $registros = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'di.cantidad',
                        'di.preciounitariomo',
                        'di.preciofinal',
                        'i.moneda',
                        'i.fecha',
                        'i.factura',
                        'p.id as idproducto',
                        'i.tasacambio',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            }
        } else if ($compraventa == "venta") {
            if ($empresa != "-1") {
                $registros = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.id as idproducto',
                        'v.tasacambio',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            } else {
                $registros = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', 'kit')
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.moneda',
                        'v.fecha',
                        'v.factura',
                        'p.id as idproducto',
                        'v.tasacambio',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            }
        }
        return $registros;
    }
    //pasar la lista de kits a productos estandar
    public function todosestandarkit($compras, $ventas, $idproducto)
    {
        $resultados = collect();
        for ($i = 0; $i < count($compras); $i++) {
            $prodkit = $this->productosxdetallexkitingreso($compras[$i]->iddetalleventa);
            $mikit = DB::table('products as p')
                ->where('p.id', '=', $compras[$i]->idproducto)
                ->select(
                    'p.nombre as producto',
                    'p.id',
                    'p.moneda',
                    'p.tasacambio',
                    'p.NoIGV',
                )
                ->first();
            $miproducto = Product::find($idproducto);
            for ($k = 0; $k < count($prodkit); $k++) {
                if ($prodkit[$k]->id == $idproducto) {
                    $preciounit = 0;
                    $preciofinal = 0;
                    if ($compras[$i]->moneda == $miproducto->moneda) {
                        $preciounit = $miproducto->NoIGV;
                        $preciofinal = round($miproducto->NoIGV * $prodkit[$k]->cantidad * $compras[$i]->cantidad, 2);
                    } else
                    if ($compras[$i]->moneda == "soles" && $miproducto->moneda == "dolares") {
                        $preciounit = round($miproducto->NoIGV * $mikit->tasacambio, 2);
                        $preciofinal = round($miproducto->NoIGV * $prodkit[$k]->cantidad * $compras[$i]->cantidad * $mikit->tasacambio, 2);
                    } else if ($compras[$i]->moneda == "dolares" && $miproducto->moneda == "soles") {
                        $preciounit = round($miproducto->NoIGV / $mikit->tasacambio, 2);
                        $preciofinal = round(($miproducto->NoIGV * $prodkit[$k]->cantidad * $compras[$i]->cantidad) / $mikit->tasacambio, 2);
                    }
                    $prod = collect();
                    $prod->put('compraventa', 'INGRESO');
                    $prod->put('empresa', $compras[$i]->empresa);
                    $prod->put('factura', $compras[$i]->factura);
                    $prod->put('cliente', $compras[$i]->cliente);
                    $prod->put('producto', $prodkit[$k]->producto);
                    $prod->put('cantidad', $prodkit[$k]->cantidad * $compras[$i]->cantidad);
                    $prod->put('preciounitariomo', $preciounit);
                    $prod->put('preciofinal', $preciofinal);
                    $prod->put('moneda', $compras[$i]->moneda);
                    $prod->put('fecha', $compras[$i]->fecha);
                    $prod->put('tasacambio', $compras[$i]->tasacambio);
                    $prod->put('tipo', "kit");
                    $resultados->push($prod);
                }
            }
        }
        for ($i = 0; $i < count($ventas); $i++) {
            $prodkit = $this->productosxdetallexkit($ventas[$i]->iddetalleventa);
            $mikit = DB::table('products as p')
                ->where('p.id', '=', $ventas[$i]->idproducto)
                ->select(
                    'p.nombre as producto',
                    'p.id',
                    'p.moneda',
                    'p.tasacambio',
                    'p.NoIGV',
                )
                ->first();
            $miproducto = Product::find($idproducto);
            for ($k = 0; $k < count($prodkit); $k++) {
                if ($prodkit[$k]->id == $idproducto) {
                    $preciounit = 0;
                    $preciofinal = 0;
                    if ($ventas[$i]->moneda == $miproducto->moneda) {
                        $preciounit = $miproducto->NoIGV;
                        $preciofinal = round($miproducto->NoIGV * $prodkit[$k]->cantidad * $ventas[$i]->cantidad, 2);
                    } else
                    if ($ventas[$i]->moneda == "soles" && $miproducto->moneda == "dolares") {
                        $preciounit = round($miproducto->NoIGV * $mikit->tasacambio, 2);
                        $preciofinal = round($miproducto->NoIGV * $prodkit[$k]->cantidad * $ventas[$i]->cantidad * $mikit->tasacambio, 2);
                    } else if ($ventas[$i]->moneda == "dolares" && $miproducto->moneda == "soles") {
                        $preciounit = round($miproducto->NoIGV / $mikit->tasacambio, 2);
                        $preciofinal = round(($miproducto->NoIGV * $prodkit[$k]->cantidad * $ventas[$i]->cantidad) / $mikit->tasacambio, 2);
                    }
                    $prod = collect();
                    $prod->put('compraventa', 'VENTA');
                    $prod->put('empresa', $ventas[$i]->empresa);
                    $prod->put('factura', $ventas[$i]->factura);
                    $prod->put('cliente', $ventas[$i]->cliente);
                    $prod->put('producto', $prodkit[$k]->producto);
                    $prod->put('cantidad', $prodkit[$k]->cantidad * $ventas[$i]->cantidad);
                    $prod->put('preciounitariomo', $preciounit);
                    $prod->put('preciofinal', $preciofinal);
                    $prod->put('moneda', $ventas[$i]->moneda);
                    $prod->put('fecha', $ventas[$i]->fecha);
                    $prod->put('tasacambio', $ventas[$i]->tasacambio);
                    $prod->put('tipo', "kit");
                    $resultados->push($prod);
                }
            }
        }
        return $resultados;
    }
    //------------------------------para obtener la rotacion del inventario--------------------------------------------
    //vista de rotacion del stock
    public function rotacionstock()
    {
        $companies = Company::all();
        $productos = Product::all()->where('tipo', '=', 'estandar');
        return view('admin.reporte.rotacionstock', compact('companies', 'productos'));
    }
    //obtener los datos para la rotacion del stock de productos
    public function datosrotacionstock($fechainicio, $fechafin, $empresa, $producto)
    {
        $misventas = $this->misproductosvendidos($fechainicio, $fechafin, $empresa, $producto);
        //return $misventas;
        $misventasestandar = $this->productosestandar($misventas, $producto, 'venta');
        //return $misventasestandar;
        $miresultadoventas = $this->resultadoventas($misventasestandar, 'venta');
        $miscompras = $this->misproductoscomprados($fechainicio, $fechafin, $empresa, $producto);
        $miscomprasestandar = $this->productosestandar($miscompras, $producto, 'compra');
        $miresultadocompras = $this->resultadoventas($miscomprasestandar, 'compra');

        //return $misventas;
        $resultadostotales = $miresultadoventas->concat($miresultadocompras);

        return $resultadostotales;
    }
    //obtener los productos vendidos
    public function misproductosvendidos($fechainicio, $fechafin, $empresa, $producto)
    {
        $misproductos = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $productos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->join('detalle_kitventas as dkv', 'dkv.detalleventa_id', '=', 'dv.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', "kit")
                    ->where('dkv.kitproduct_id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->join('detalle_kitventas as dkv', 'dkv.detalleventa_id', '=', 'dv.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', "kit")
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $productos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->join('detalle_kitventas as dkv', 'dkv.detalleventa_id', '=', 'dv.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', "kit")
                    ->where('dkv.kitproduct_id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('products as p', 'dv.product_id', '=', 'p.id')
                    ->join('detalle_kitventas as dkv', 'dkv.detalleventa_id', '=', 'dv.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', "kit")
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'dv.cantidad',
                        'v.moneda',
                        'v.tasacambio',
                        'dv.preciofinal',
                        'dv.preciounitariomo',
                        'v.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'dv.id as iddetalleventa'
                    )
                    ->get();
            }
        }
        return $misproductos;
    }
    //psar los kits a productos estandar
    public function productosestandar($misventas, $producto, $compraventa)
    {
        $resultado = collect();
        for ($i = 0; $i < count($misventas); $i++) {
            if ($misventas[$i]->tipo == 'kit') {
                $misprod = "";
                if ($compraventa == "venta") {
                    $misprod = $this->productosxdetallexkit($misventas[$i]->iddetalleventa);
                } else {
                    $misprod = $this->productosxdetallexkitingreso($misventas[$i]->iddetalleventa);
                }
                for ($x = 0; $x < count($misprod); $x++) {
                    $newproduct = Product::find($misprod[$x]->id); //para poner los datos del producto nuevo que buscamos
                    $costoprod = 0;
                    $costoprod =  $newproduct->NoIGV;
                    if ($misventas[$i]->moneda == $newproduct->moneda) {
                        $costoprod = $costoprod;
                    } else if ($misventas[$i]->moneda == 'dolares' && $newproduct->moneda = 'soles') {
                        $costoprod = round($costoprod / $misventas[$i]->tasacambio, 2);
                    } else if ($misventas[$i]->moneda == 'soles' && $newproduct->moneda = 'dolares') {
                        $costoprod = round($costoprod * $misventas[$i]->tasacambio, 2);
                    }
                    if ($newproduct->id == $producto) {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoprod);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $venta->put('idempresa', $misventas[$i]->idempresa);
                        $venta->put('idproducto', $newproduct->id);
                        $resultado->push($venta);
                    }
                    if ($producto == "-1") {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoprod);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $venta->put('idempresa', $misventas[$i]->idempresa);
                        $venta->put('idproducto', $newproduct->id);
                        $resultado->push($venta);
                    }
                }
            } else {
                $venta = collect();
                $venta->put('empresa', $misventas[$i]->empresa);
                $venta->put('producto', $misventas[$i]->producto);
                $venta->put('cantidad', $misventas[$i]->cantidad);
                $venta->put('moneda', $misventas[$i]->moneda);
                $venta->put('tasacambio', $misventas[$i]->tasacambio);
                $venta->put('preciofinal', $misventas[$i]->preciounitariomo);
                $venta->put('fecha', $misventas[$i]->fecha);
                $venta->put('idempresa', $misventas[$i]->idempresa);
                $venta->put('idproducto', $misventas[$i]->idproducto);
                $resultado->push($venta);
            }
        }
        return $resultado;
    }
    //obtener los datos finales de las ventas y compras unidas
    public function resultadoventas($misventas, $compraventa)
    {
        $resultado = collect();
        $unicas = $misventas->unique(function ($item) {
            return $item['empresa'] . $item['producto'];
        });
        $misventasunicas = $unicas->values()->all();
        for ($x = 0; $x < count($misventasunicas); $x++) {
            $sumcant = 0;
            $sumcosto = 0;
            $minimo = 100000;
            $maximo = 0;
            for ($i = 0; $i < count($misventas); $i++) {
                if (
                    $misventas[$i]['producto'] == $misventasunicas[$x]['producto'] &&
                    $misventas[$i]['empresa'] == $misventasunicas[$x]['empresa']
                ) {
                    if ($misventas[$i]['moneda'] == "soles") {
                        $sumcosto = round($sumcosto + round(($misventas[$i]['cantidad'] * $misventas[$i]['preciofinal']) / $misventas[$i]['tasacambio'], 2), 2);
                        if ($maximo < round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2)) {
                            $maximo = round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2);
                        }
                        if ($minimo > round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2)) {
                            $minimo = round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2);
                        }
                    } else {
                        $sumcosto = round($sumcosto + round($misventas[$i]['cantidad'] * $misventas[$i]['preciofinal'], 2), 2);
                        if ($maximo < $misventas[$i]['preciofinal']) {
                            $maximo = $misventas[$i]['preciofinal'];
                        }
                        if ($minimo > $misventas[$i]['preciofinal']) {
                            $minimo = $misventas[$i]['preciofinal'];
                        }
                    }
                    $sumcant = $sumcant + $misventas[$i]['cantidad'];
                }
            }
            $producto = collect();
            $producto->put('empresa', $misventasunicas[$x]['empresa']);
            $producto->put('compraventa', $compraventa);
            $producto->put('producto', $misventasunicas[$x]['producto']);
            $producto->put('cantidad', $sumcant);
            $producto->put('maximo', $maximo);
            $producto->put('minimo', $minimo);
            $producto->put('preciofinal', $sumcosto);
            $producto->put('moneda', "dolares");
            $producto->put('fecha', $misventasunicas[$x]['fecha']);
            $producto->put('idempresa', $misventasunicas[$x]['idempresa']);
            $producto->put('idproducto', $misventasunicas[$x]['idproducto']);
            $resultado->push($producto);
        }
        return $resultado;
    }
    //obtener los productos comprados en una empresa,fecha
    public function misproductoscomprados($fechainicio, $fechafin, $empresa, $producto)
    {
        $misproductos = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $productos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->join('detalle_kitingresos as dki', 'dki.detalleingreso_id', '=', 'di.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', "kit")
                    ->where('dki.kitproduct_id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->join('detalle_kitingresos as dki', 'dki.detalleingreso_id', '=', 'di.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('p.tipo', '=', "kit")
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $productos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->join('detalle_kitingresos as dki', 'dki.detalleingreso_id', '=', 'di.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', "kit")
                    ->where('dki.kitproduct_id', '=', $producto)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
                $misproductos = $productos->concat($miskits);
            } else {
                $misproductos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
                $miskits = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('products as p', 'di.product_id', '=', 'p.id')
                    ->join('detalle_kitingresos as dki', 'dki.detalleingreso_id', '=', 'di.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('p.tipo', '=', "kit")
                    ->select(
                        'e.nombre as empresa',
                        'p.nombre as producto',
                        'di.cantidad',
                        'i.moneda',
                        'i.tasacambio',
                        'di.preciofinal',
                        'di.preciounitariomo',
                        'i.fecha',
                        'p.tipo',
                        'p.id as idproducto',
                        'e.id as idempresa',
                        'di.id as iddetalleventa'
                    )
                    ->get();
            }
        }
        return $misproductos;
    }
    //----------------obtener nuevos datos de ventas------------------
    public function detalleventas($fechainicio, $fechafin, $empresa, $producto)
    {
        $misventas = $this->obtenermisventas($fechainicio, $fechafin,  $empresa, $producto);
        $misventasestandar = $this->productosestandar2($misventas, $producto, 'venta');
        $resultado  = $this->sumarresultado($misventasestandar, 'venta');
        return $resultado;
    }
    //obtener las ventas de los productos y kits de una empresa en una fecha
    public function obtenermisventas($fechainicio, $fechafin, $empresa, $producto)
    {
        $misproductos = "";
        $productos = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->where('v.fecha', '<=', $fechafin)
            ->where('v.fecha', '>=', $fechainicio)
            ->where('e.id', '=', $empresa)
            ->where('p.id', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'dv.cantidad',
                'v.moneda',
                'v.tasacambio',
                'dv.preciofinal',
                'v.fecha',
                'p.tipo',
                'p.id as idproducto',
                'dv.id as iddetalleventa',
                'cl.id as idcliente'
            )
            ->get();
        $miskits = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->join('detalle_kitventas as dkv', 'dkv.detalleventa_id', '=', 'dv.id')
            ->where('v.fecha', '<=', $fechafin)
            ->where('v.fecha', '>=', $fechainicio)
            ->where('e.id', '=', $empresa)
            ->where('p.tipo', '=', "kit")
            ->where('dkv.kitproduct_id', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'dv.cantidad',
                'v.moneda',
                'v.tasacambio',
                'dv.preciofinal',
                'v.fecha',
                'p.tipo',
                'p.id as idproducto',
                'dv.id as iddetalleventa',
                'cl.id as idcliente'
            )
            ->get();

        if (count($productos) > 0) {
            $misproductos = $productos->concat($miskits);
        } else {
            $misproductos = $miskits;
        }

        return $misproductos;
    }
    //sumar los precios de las ventas o compras y paasar a dolares
    public function sumarresultado($misventas, $compraventa)
    {
        $resultado = collect();
        $unica = $misventas->unique('cliente');
        $unicaempresa = $unica->values()->all();
        for ($x = 0; $x < count($unicaempresa); $x++) {
            $sumcant = 0;
            $sumcosto = 0;
            for ($i = 0; $i < count($misventas); $i++) {
                if ($misventas[$i]['idcliente'] == $unicaempresa[$x]['idcliente']) {
                    if ($misventas[$i]['moneda'] == "dolares") {
                        $sumcosto = round($sumcosto + $misventas[$i]['preciofinal'], 2);  //+ round($misventas[$i]['tasacambio'] * $misventas[$i]['preciofinal'], 2);
                    } else {
                        $sumcosto = round($sumcosto + round($misventas[$i]['preciofinal'] / $misventas[$i]['tasacambio'], 2), 2);
                    }
                    $sumcant = $sumcant + $misventas[$i]['cantidad'];
                }
            }
            $producto = collect();
            $producto->put('empresa', $unicaempresa[$x]['empresa']);
            $producto->put('cliente', $unicaempresa[$x]['cliente']);
            $producto->put('compraventa', $compraventa);
            $producto->put('producto', $unicaempresa[$x]['producto']);
            $producto->put('cantidad', $sumcant);
            $producto->put('preciofinal', $sumcosto);
            $producto->put('moneda', "dolares");
            $producto->put('fecha', $unicaempresa[$x]['fecha']);
            $resultado->push($producto);
        }
        return $resultado;
    }
    //-----------------obtener nuevos datos de las compras------------------
    public function detallecompras($fechainicio, $fechafin, $empresa, $producto)
    {
        $miscompras = $this->obtenermiscompras($fechainicio, $fechafin, $empresa, $producto);
        $miscomprasestandar = $this->productosestandar2($miscompras, $producto, "compra");
        $resultado  = $this->sumarresultado($miscomprasestandar, 'compra');

        return $resultado;
    }
    //obtener las compras de los prductos y kits de una empresa en una fecha
    public function obtenermiscompras($fechainicio, $fechafin, $empresa, $producto)
    {
        $productos = DB::table('ingresos as i')
            ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->where('i.fecha', '<=', $fechafin)
            ->where('i.fecha', '>=', $fechainicio)
            ->where('e.id', '=', $empresa)
            ->where('p.id', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'di.cantidad',
                'i.moneda',
                'i.tasacambio',
                'di.preciofinal',
                'i.fecha',
                'p.tipo',
                'p.id as idproducto',
                'di.id as iddetalleventa',
                'cl.id as idcliente'
            )
            ->get();
        $miskits = DB::table('ingresos as i')
            ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->join('detalle_kitingresos as dki', 'dki.detalleingreso_id', '=', 'di.id')
            ->where('i.fecha', '<=', $fechafin)
            ->where('i.fecha', '>=', $fechainicio)
            ->where('e.id', '=', $empresa)
            ->where('p.tipo', '=', "kit")
            ->where('dki.kitproduct_id', '=', $producto)
            ->select(
                'e.nombre as empresa',
                'p.nombre as producto',
                'cl.nombre as cliente',
                'di.cantidad',
                'i.moneda',
                'i.tasacambio',
                'di.preciofinal',
                'i.fecha',
                'p.tipo',
                'p.id as idproducto',
                'di.id as iddetalleventa',
                'cl.id as idcliente'
            )
            ->get();
        $misproductos = $productos->concat($miskits);
        return $misproductos;
    }
    //pasar los kits comprados a productos estandar
    public function productosestandar2($misventas, $producto, $compraventa)
    {
        $resultado = collect();
        for ($i = 0; $i < count($misventas); $i++) {
            if ($misventas[$i]->tipo == 'kit') {
                $misprod = "";
                if ($compraventa == "venta") {
                    $misprod = $this->productosxdetallexkit($misventas[$i]->iddetalleventa);
                } else {
                    $misprod = $this->productosxdetallexkitingreso($misventas[$i]->iddetalleventa);
                }
                for ($x = 0; $x < count($misprod); $x++) {
                    $newproducto = Product::find($misprod[$x]->id);
                    $costoventa = $newproducto->NoIGV;
                    $costoventa = round(($misventas[$i]->cantidad * $misprod[$x]->cantidad) * $newproducto->NoIGV, 2);
                    if ($misventas[$i]->moneda == $newproducto->moneda) {
                        $costoventa = $costoventa;
                    } else if ($misventas[$i]->moneda == 'dolares' && $newproducto->moneda = 'soles') {
                        $costoventa = round($costoventa / $misventas[$i]->tasacambio, 2);
                    } else if ($misventas[$i]->moneda == 'soles' && $newproducto->moneda = 'dolares') {
                        $costoventa = round($costoventa * $misventas[$i]->tasacambio, 2);
                    }
                    if ($misprod[$x]->id == $producto) {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cliente', $misventas[$i]->cliente);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoventa);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $venta->put('idcliente', $misventas[$i]->idcliente);
                        $venta->put('idproducto', $newproducto->id);
                        $resultado->push($venta);
                    }
                    if ($producto == "-1") {
                        $venta = collect();
                        $venta->put('empresa', $misventas[$i]->empresa);
                        $venta->put('producto', $misprod[$x]->producto);
                        $venta->put('cliente', $misventas[$i]->cliente);
                        $venta->put('cantidad', $misventas[$i]->cantidad * $misprod[$x]->cantidad);
                        $venta->put('moneda', $misventas[$i]->moneda);
                        $venta->put('tasacambio', $misventas[$i]->tasacambio);
                        $venta->put('preciofinal', $costoventa);
                        $venta->put('fecha', $misventas[$i]->fecha);
                        $venta->put('idcliente', $misventas[$i]->idcliente);
                        $venta->put('idproducto', $newproducto->id);
                        $resultado->push($venta);
                    }
                }
            } else {
                $venta = collect();
                $venta->put('empresa', $misventas[$i]->empresa);
                $venta->put('producto', $misventas[$i]->producto);
                $venta->put('cliente', $misventas[$i]->cliente);
                $venta->put('cantidad', $misventas[$i]->cantidad);
                $venta->put('moneda', $misventas[$i]->moneda);
                $venta->put('tasacambio', $misventas[$i]->tasacambio);
                $venta->put('preciofinal', $misventas[$i]->preciofinal);
                $venta->put('fecha', $misventas[$i]->fecha);
                $venta->put('idcliente', $misventas[$i]->idcliente);
                $venta->put('idproducto', $misventas[$i]->idproducto);
                $resultado->push($venta);
            }
        }
        return $resultado;
    }
    // vista reporte de cobro de las ventas
    public function cobroventas()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        return view('admin.reporte.cobroventas', compact('companies', 'clientes'));
    }
    //obtener los datos sobre los cobros de las ventas para mostrar en la vista de reportes
    public function datoscobroventas($fechainicio, $fechafin, $empresa, $cliente)
    {
        $ventas = "";
        if ($empresa != "-1") {
            if ($cliente != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('cl.id', '=', $cliente)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'v.factura',
                        'v.nrooc',
                        'v.fecha',
                        'v.fechav',
                        'v.guiaremision',
                        'v.costoventa',
                        'v.moneda',
                        'v.acuenta1',
                        'v.acuenta2',
                        'v.acuenta3',
                        'v.saldo',
                        'v.constanciaretencion',
                        'v.retencion',
                        'v.montopagado',
                        'v.fechapago',
                        'v.formapago',
                        'v.pagada'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'v.factura',
                        'v.nrooc',
                        'v.fecha',
                        'v.fechav',
                        'v.guiaremision',
                        'v.costoventa',
                        'v.moneda',
                        'v.acuenta1',
                        'v.acuenta2',
                        'v.acuenta3',
                        'v.saldo',
                        'v.constanciaretencion',
                        'v.retencion',
                        'v.montopagado',
                        'v.fechapago',
                        'v.formapago',
                        'v.pagada'
                    )
                    ->get();
            }
        } else {
            if ($cliente != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('cl.id', '=', $cliente)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'v.factura',
                        'v.nrooc',
                        'v.fecha',
                        'v.fechav',
                        'v.guiaremision',
                        'v.costoventa',
                        'v.moneda',
                        'v.acuenta1',
                        'v.acuenta2',
                        'v.acuenta3',
                        'v.saldo',
                        'v.constanciaretencion',
                        'v.retencion',
                        'v.montopagado',
                        'v.fechapago',
                        'v.formapago',
                        'v.pagada'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('companies as e', 'v.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'v.factura',
                        'v.nrooc',
                        'v.fecha',
                        'v.fechav',
                        'v.guiaremision',
                        'v.costoventa',
                        'v.moneda',
                        'v.acuenta1',
                        'v.acuenta2',
                        'v.acuenta3',
                        'v.saldo',
                        'v.constanciaretencion',
                        'v.retencion',
                        'v.montopagado',
                        'v.fechapago',
                        'v.formapago',
                        'v.pagada'
                    )
                    ->get();
            }
        }
        $ventascredito = $ventas->where('formapago', 'credito');
        $ventascontado = $ventas->where('formapago', 'contado');
        $ventascontadoconretencion = $ventascontado->where('retencion', '!=', null);
        $concatenated = $ventascredito->concat($ventascontadoconretencion);
        return $concatenated->values()->all();
    }
    // vista reporte del pago de las compras realizadas
    public function pagocompras()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        return view('admin.reporte.pagocompras', compact('companies', 'clientes'));
    }
    //obtener los datos de los pagos de las compras para mostrar en el reporte
    public function datospagocompras($fechainicio, $fechafin, $empresa, $cliente)
    {
        $ingresos = "";
        if ($empresa != "-1") {
            if ($cliente != "-1") {
                $ingresos = DB::table('ingresos as i')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->where('cl.id', '=', $cliente)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.factura',
                        'i.nrooc',
                        'i.fecha',
                        'i.fechav',
                        'i.guiaremision',
                        'i.costoventa',
                        'i.moneda',
                        'i.acuenta1',
                        'i.acuenta2',
                        'i.acuenta3',
                        'i.saldo',
                        'i.montopagado',
                        'i.fechapago',
                        'i.formapago',
                        'i.pagada'
                    )
                    ->get();
            } else {
                $ingresos = DB::table('ingresos as i')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('e.id', '=', $empresa)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.factura',
                        'i.nrooc',
                        'i.fecha',
                        'i.fechav',
                        'i.guiaremision',
                        'i.costoventa',
                        'i.moneda',
                        'i.acuenta1',
                        'i.acuenta2',
                        'i.acuenta3',
                        'i.saldo',
                        'i.montopagado',
                        'i.fechapago',
                        'i.formapago',
                        'i.pagada'
                    )
                    ->get();
            }
        } else {
            if ($cliente != "-1") {
                $ingresos = DB::table('ingresos as i')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('cl.id', '=', $cliente)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.factura',
                        'i.nrooc',
                        'i.fecha',
                        'i.fechav',
                        'i.guiaremision',
                        'i.costoventa',
                        'i.moneda',
                        'i.acuenta1',
                        'i.acuenta2',
                        'i.acuenta3',
                        'i.saldo',
                        'i.montopagado',
                        'i.fechapago',
                        'i.formapago',
                        'i.pagada'
                    )
                    ->get();
            } else {
                $ingresos = DB::table('ingresos as i')
                    ->join('companies as e', 'i.company_id', '=', 'e.id')
                    ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
                    ->where('i.fecha', '<=', $fechafin)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->select(
                        'e.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.factura',
                        'i.nrooc',
                        'i.fecha',
                        'i.fechav',
                        'i.guiaremision',
                        'i.costoventa',
                        'i.moneda',
                        'i.acuenta1',
                        'i.acuenta2',
                        'i.acuenta3',
                        'i.saldo',
                        'i.montopagado',
                        'i.fechapago',
                        'i.formapago',
                        'i.pagada'
                    )
                    ->get();
            }
        }
        $ingresoscredito = $ingresos->where('formapago', 'credito');
        return $ingresoscredito->values()->all();
    }

    //----------para la vista de la lista de los precios de compra-------------------------
    //vista inicio de reporte de precios de compras
    public function precioscompra()
    {
        $companies = Company::all();
        $productos = Product::all();
        return view('admin.reporte.listaprecioscompra', compact('companies', 'productos'));
    }

    public function datoslistaprecioscompra($fechainicio, $fechafin, $empresa, $producto)
    {
        $precios = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $precios = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'i.id', '=', 'di.ingreso_id')
                    ->join('companies as c', 'c.id', '=', 'i.company_id')
                    ->join('clientes as cl', 'cl.id', '=', 'i.cliente_id')
                    ->join('products as p', 'p.id', '=', 'di.product_id')
                    ->where('c.id', '=', $empresa)
                    ->where('p.id', '=', $producto)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('i.fecha', '<=', $fechafin)
                    ->select(
                        'p.nombre as producto',
                        'i.fecha',
                        'c.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.moneda',
                        'di.cantidad',
                        'di.preciounitariomo'
                    )
                    ->get();
            } else {
                $precios = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'i.id', '=', 'di.ingreso_id')
                    ->join('companies as c', 'c.id', '=', 'i.company_id')
                    ->join('clientes as cl', 'cl.id', '=', 'i.cliente_id')
                    ->join('products as p', 'p.id', '=', 'di.product_id')
                    ->where('c.id', '=', $empresa)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('i.fecha', '<=', $fechafin)
                    ->select(
                        'p.nombre as producto',
                        'i.fecha',
                        'c.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.moneda',
                        'di.cantidad',
                        'di.preciounitariomo'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $precios = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'i.id', '=', 'di.ingreso_id')
                    ->join('companies as c', 'c.id', '=', 'i.company_id')
                    ->join('clientes as cl', 'cl.id', '=', 'i.cliente_id')
                    ->join('products as p', 'p.id', '=', 'di.product_id')
                    ->where('p.id', '=', $producto)
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('i.fecha', '<=', $fechafin)
                    ->select(
                        'p.nombre as producto',
                        'i.fecha',
                        'c.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.moneda',
                        'di.cantidad',
                        'di.preciounitariomo'
                    )
                    ->get();
            } else {
                $precios = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'i.id', '=', 'di.ingreso_id')
                    ->join('companies as c', 'c.id', '=', 'i.company_id')
                    ->join('clientes as cl', 'cl.id', '=', 'i.cliente_id')
                    ->join('products as p', 'p.id', '=', 'di.product_id')
                    ->where('i.fecha', '>=', $fechainicio)
                    ->where('i.fecha', '<=', $fechafin)
                    ->select(
                        'p.nombre as producto',
                        'i.fecha',
                        'c.nombre as empresa',
                        'cl.nombre as cliente',
                        'i.moneda',
                        'di.cantidad',
                        'di.preciounitariomo'
                    )
                    ->get();
            }
        }

        return $precios;
    }

    //----------para la vista de la lista de los precios especiales-------------------------
    //vista inicio de reporte de precios especiales
    public function precioespecial()
    {
        $clientes = Cliente::all();
        $productos = Product::all();
        return view('admin.reporte.preciosespeciales', compact('clientes', 'productos'));
    }

    public function listapreciosespeciales($cliente, $producto)
    {
        $precios = "";
        if ($cliente != "-1") {
            if ($producto != "-1") {
                $precios = DB::table('listaprecios as lp')
                    ->join('clientes as cl', 'cl.id', '=', 'lp.cliente_id')
                    ->join('products as p', 'p.id', '=', 'lp.product_id')
                    ->where('cl.id', '=', $cliente)
                    ->where('p.id', '=', $producto)
                    ->select(
                        'p.nombre as producto',
                        'lp.preciounitariomo',
                        'lp.moneda',
                        'cl.nombre as cliente',
                    )
                    ->get();
            } else {
                $precios = DB::table('listaprecios as lp')
                    ->join('clientes as cl', 'cl.id', '=', 'lp.cliente_id')
                    ->join('products as p', 'p.id', '=', 'lp.product_id')
                    ->where('cl.id', '=', $cliente)
                    ->select(
                        'p.nombre as producto',
                        'lp.preciounitariomo',
                        'lp.moneda',
                        'cl.nombre as cliente',
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $precios = DB::table('listaprecios as lp')
                    ->join('clientes as cl', 'cl.id', '=', 'lp.cliente_id')
                    ->join('products as p', 'p.id', '=', 'lp.product_id')
                    ->where('p.id', '=', $producto)
                    ->select(
                        'p.nombre as producto',
                        'lp.preciounitariomo',
                        'lp.moneda',
                        'cl.nombre as cliente',
                    )
                    ->get();
            } else {
                $precios = DB::table('listaprecios as lp')
                    ->join('clientes as cl', 'cl.id', '=', 'lp.cliente_id')
                    ->join('products as p', 'p.id', '=', 'lp.product_id')
                    ->select(
                        'p.nombre as producto',
                        'lp.preciounitariomo',
                        'lp.moneda',
                        'cl.nombre as cliente',
                    )
                    ->get();
            }
        }

        return $precios;
    }

    //----------------para los graficos de ventas y compras con barras y lineas -----------------

    public function datosgraficoventas($fechainicio, $fechafin, $empresa, $producto)
    {
        $misventas = $this->obtenerdatosproductosventa($fechainicio, $fechafin, $empresa, $producto);
        return $this->sumardatos($misventas, $empresa);
    }
    public function datosgraficocompras($fechainicio, $fechafin, $empresa, $producto)
    {
        $miscompras = $this->obtenerdatosproductoscompra($fechainicio, $fechafin, $empresa, $producto);
        return $this->sumardatos($miscompras, $empresa);
    }
    //AGRUPAMOS LOS DATOS
    public function sumardatos($misventas, $empresa)
    {
        $datos = collect();
        $unicos = $misventas->unique('cliente');
        $unicosclientes = $unicos->values()->all();

        $unicas = $misventas->unique('empresa');
        $unicasempresas = $unicas->values()->all();

        $clientes = collect();
        for ($x = 0; $x < count($unicosclientes); $x++) {
            $clientes->push($unicosclientes[$x]->cliente);
        }
        $datos->push($clientes);
        $empresas = collect();
        for ($x = 0; $x < count($unicasempresas); $x++) {
            $empresas->push($unicasempresas[$x]->empresa);
        }
        $datos->push($empresas);
        $producto = $this->datosxempresa($unicosclientes, $unicasempresas, $misventas);
        $datos->push($producto);

        return $datos;
    }
    //LOS DATOS DE LA CANTIDAD DE VENTAS POR CADA EMPRESA Y CLIENTE
    public function datosxempresa($unicosclientes, $unicasempresas, $misventas)
    {
        $productos = collect();
        $producto = collect();
        for ($x = 0; $x < count($unicosclientes); $x++) {
            $sumacant = 0;
            for ($i = 0; $i < count($misventas); $i++) {
                if ($unicosclientes[$x]->cliente == $misventas[$i]->cliente) {
                    $sumacant += $misventas[$i]->cantidad;
                }
            }
            $producto->push($sumacant);
        }
        $productos->push($producto);
        for ($z = 0; $z < count($unicasempresas); $z++) {
            $producto = collect();
            for ($x = 0; $x < count($unicosclientes); $x++) {
                $sumacant = 0;
                for ($i = 0; $i < count($misventas); $i++) {
                    if (
                        $unicosclientes[$x]->cliente == $misventas[$i]->cliente &&
                        $unicasempresas[$z]->empresa == $misventas[$i]->empresa
                    ) {
                        $sumacant += $misventas[$i]->cantidad;
                    }
                }
                $producto->push($sumacant);
            }
            $productos->push($producto);
        }

        return $productos;
    }
}
