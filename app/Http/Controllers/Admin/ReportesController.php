<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tienda;
use App\Models\Product;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\Uniforme;
use App\Models\Utile;
use App\Models\Libro;
use App\Models\Instrumento;
use App\Models\Golosina;
use App\Models\Snack;

class ReportesController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware(
            'permission:ver-reporte',
            ['only' => [
                'index', 'misventas', 'todasfechas', 'ventasdelmes', 'comprasdelmes', 'cotizacionesdelmes', 'obtenerdatosgrafico', 'obtenerproductosmasv', 'obtenerproductoscantidad', 'productosindividuales', 'productosxkit',  'prodseparados', 'obtenerclientesmasc', 'clientescantidad', 'clientescosto', 'misclientescosto', 'obtenerbalance', 'devolverclientes', 'devolverclientescant', 'sumarcostoventa', 'obtenerproductos', 'obtenercotizaciones', 'obtenerventas', 'obteneringresos', 'numeroproductos', 'numerocotizaciones', 'numeroingresos', 'numeroventas', 'balancemensual', 'coninfocompleta', 'obtenerdatosproductosventa', 'obtenerdatosproductoscompra', 'datosproductos', 'infoproductos', 'resultadoventas', 'productosestandar', 'misproductosvendidos', 'datosrotacionstock', 'rotacionstock', 'detallecompras', 'sumarresultado', 'obtenermisventas', 'detalleventas', 'misproductoscomprados', 'obtenermiscompras', 'productosestandar2'
            ]]
        );
    }
    //vista index datos
    public function index()
    {
        $hoy = date('Y-m-d');
        $dia = date('d');
        $inicio =  date("Y-m-d", strtotime($hoy . "- $dia days"));

        $tiendas = Tienda::all();

        $ingresomes = $this->obteneringresos(-1, $hoy, $inicio, "-1");
        $ingresouniformes = $this->obteneringresos(-1, $hoy, $inicio, "UNIFORMES");
        $ingresolibros = $this->obteneringresos(-1, $hoy, $inicio, "LIBROS");
        $ingresoinstrumentos = $this->obteneringresos(-1, $hoy, $inicio, "INSTRUMENTOS");
        $ingresoutiles = $this->obteneringresos(-1, $hoy, $inicio, "UTILES");
        $ingresogolosinas = $this->obteneringresos(-1, $hoy, $inicio, "GOLOSINAS");
        $ingresosnacks = $this->obteneringresos(-1, $hoy, $inicio, "SNACKS");

        $ventames = $this->obtenerventas(-1, $hoy, $inicio, "-1");
        $ventauniformes = $this->obtenerventas(-1, $hoy, $inicio, "UNIFORMES");
        $ventalibros = $this->obtenerventas(-1, $hoy, $inicio, "LIBROS");
        $ventainstrumentos = $this->obtenerventas(-1, $hoy, $inicio, "INSTRUMENTOS");
        $ventautiles = $this->obtenerventas(-1, $hoy, $inicio, "UTILES");
        $ventagolosinas = $this->obtenerventas(-1, $hoy, $inicio, "GOLOSINAS");
        $ventasnacks = $this->obtenerventas(-1, $hoy, $inicio, "SNACKS");



        $ventas = $this->ventasdelmes('-1', $inicio, $hoy, "-1");
        $compras = $this->comprasdelmes('-1', $inicio, $hoy, "-1");


        $fechas = $this->todasfechas($ventas, $compras);
        $datosventas = $this->misventas($fechas, $ventas);
        $datoscompras = $this->misventas($fechas, $compras);

        //return $datosventas;
        return view(
            'admin.reporte.index',
            compact(
                'ingresomes',
                'ingresouniformes',
                'ingresolibros',
                'ingresoinstrumentos',
                'ingresoutiles',
                'ingresogolosinas',
                'ingresosnacks',

                'fechas',
                'datosventas',
                'datoscompras',

                'ventames',
                'ventauniformes',
                'ventalibros',
                'ventainstrumentos',
                'ventautiles',
                'ventagolosinas',
                'ventasnacks',

                'tiendas',
            )
        );
    }
    //obtener los datos para las tarjetas de ventas, compras, y cotizacion
    public function obtenerdatosgrafico($empresa, $fechainicio, $fechafin, $tabla)
    {
        $ventas = $this->ventasdelmes($empresa, $fechainicio, $fechafin, $tabla);
        $compras = $this->comprasdelmes($empresa, $fechainicio, $fechafin, $tabla);

        $fechas = $this->todasfechas($ventas, $compras);
        $datosventas = $this->misventas($fechas, $ventas);
        $datoscompras = $this->misventas($fechas, $compras);


        $misdatos = collect();
        $misdatos->put('fechas', $fechas);
        $misdatos->put('datosventas', $datosventas);
        $misdatos->put('datoscompras', $datoscompras);

        return $misdatos;
    }
    //-----------------------para los 4 cuadros del index de reportes--------------------
    public function obtenerbalance($idempresa, $fechainicio, $fechafin)
    {
        $hoy = $fechafin;
        $inicio =  $fechainicio;

        $ingresomes = $this->obteneringresos($idempresa, $hoy, $inicio, "-1");
        $ingresouniformes = $this->obteneringresos($idempresa, $hoy, $inicio, "UNIFORMES");
        $ingresolibros = $this->obteneringresos($idempresa, $hoy, $inicio, "LIBROS");
        $ingresoinstrumentos = $this->obteneringresos($idempresa, $hoy, $inicio, "INSTRUMENTOS");
        $ingresoutiles = $this->obteneringresos($idempresa, $hoy, $inicio, "UTILES");
        $ingresogolosinas = $this->obteneringresos($idempresa, $hoy, $inicio, "GOLOSINAS");
        $ingresosnacks = $this->obteneringresos($idempresa, $hoy, $inicio, "SNACKS");

        $ventames = $this->obtenerventas($idempresa, $hoy, $inicio, "-1");
        $ventauniformes = $this->obtenerventas($idempresa, $hoy, $inicio, "UNIFORMES");
        $ventalibros = $this->obtenerventas($idempresa, $hoy, $inicio, "LIBROS");
        $ventainstrumentos = $this->obtenerventas($idempresa, $hoy, $inicio, "INSTRUMENTOS");
        $ventautiles = $this->obtenerventas($idempresa, $hoy, $inicio, "UTILES");
        $ventagolosinas = $this->obtenerventas($idempresa, $hoy, $inicio, "GOLOSINAS");
        $ventasnacks = $this->obtenerventas($idempresa, $hoy, $inicio, "SNACKS");

        $resultados = collect();
        $resultados->put('ingresomes', $ingresomes);
        $resultados->put('ingresouniformes', $ingresouniformes);
        $resultados->put('ingresolibros', $ingresolibros);
        $resultados->put('ingresoinstrumentos', $ingresoinstrumentos);
        $resultados->put('ingresoutiles', $ingresoutiles);
        $resultados->put('ingresogolosinas', $ingresogolosinas);
        $resultados->put('ingresosnacks', $ingresosnacks);

        $resultados->put('ventames', $ventames);
        $resultados->put('ventauniformes', $ventauniformes);
        $resultados->put('ventalibros', $ventalibros);
        $resultados->put('ventainstrumentos', $ventainstrumentos);
        $resultados->put('ventautiles', $ventautiles);
        $resultados->put('ventagolosinas', $ventagolosinas);
        $resultados->put('ventasnacks', $ventasnacks);

        return $resultados;
    }
    //obtener los ingresos  de una empresa
    public function obteneringresos($idtienda, $hoy, $inicio, $tabla)
    {
        if ($tabla != "-1") {
            if ($idtienda != -1) {
                $ingresos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->where('i.tienda_id', '=', $idtienda)
                    ->where('di.tipo', '=', $tabla)
                    ->where('i.fecha', '<=', $hoy)
                    ->where('i.fecha', '>', $inicio)
                    ->sum('di.preciofinal');
            } else {
                $ingresos = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->where('di.tipo', '=', $tabla)
                    ->where('i.fecha', '<=', $hoy)
                    ->where('i.fecha', '>', $inicio)
                    ->sum('di.preciofinal');
            }
        } else {
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
        }
        return   $ingresos;
    }
    //obtener las ventas de una empresa
    public function obtenerventas($idtienda, $hoy, $inicio, $tabla)
    {
        if ($tabla != "-1") {
            if ($idtienda != -1) {
                $ventas = DB::table('ventas as i')
                    ->join('detalleventas as di', 'di.venta_id', '=', 'i.id')
                    ->where('i.tienda_id', '=', $idtienda)
                    ->where('di.tipo', '=', $tabla)
                    ->where('i.fecha', '<=', $hoy)
                    ->where('i.fecha', '>', $inicio)
                    ->sum('di.preciofinal');
            } else {
                $ventas = DB::table('ventas as i')
                    ->join('detalleventas as di', 'di.venta_id', '=', 'i.id')
                    ->where('di.tipo', '=', $tabla)
                    ->where('i.fecha', '<=', $hoy)
                    ->where('i.fecha', '>', $inicio)
                    ->sum('di.preciofinal');
            }
        } else {
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
        }

        return   $ventas;
    }
    //obtener las cotizaciones de una empresa


    //---obtener los datos para el reporte grafico
    public function misventas($fechas, $ventas)
    {
        $datosventas = [];
        for ($i = 0; $i < count($fechas); $i++) {
            $sum  = 0;
            for ($x = 0; $x < count($ventas); $x++) {
                if ($fechas[$i] == $ventas[$x]->fecha) {
                    $sum = $sum + $ventas[$x]->costoventa;
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
    public function todasfechas($ventas, $compras)
    {
        $fechas = [];
        for ($i = 0; $i < count($ventas); $i++) {
            $fechas[] = $ventas[$i]->fecha;
        }
        for ($i = 0; $i < count($compras); $i++) {
            $fechas[] = $compras[$i]->fecha;
        }
        $resultado = (array_unique($fechas));
        asort($resultado);
        return array_values($resultado);
    }
    //obtener las ventas del mes actual
    public function ventasdelmes($tienda, $inicio, $hoy, $tabla)
    {
        $ventas = "";
        if ($tienda != '-1') {
            if ($tabla != '-1') {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->where('v.tienda_id', '=', $tienda)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->where('dv.tipo', '=', $tabla)
                    ->groupBy('v.fecha', 'dv.tipo')
                    ->select('v.fecha', DB::raw('sum(dv.preciofinal) as costoventa'))
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->where('v.tienda_id', '=', $tienda)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('v.fecha')
                    ->select('v.fecha', DB::raw('sum(v.costoventa) as costoventa'))
                    ->get();
            }
        } else {
            if ($tabla != '-1') {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->where('dv.tipo', '=', $tabla)
                    ->groupBy('v.fecha', 'dv.tipo')
                    ->select('v.fecha', DB::raw('sum(dv.preciofinal) as costoventa'))
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('v.fecha')
                    ->select('v.fecha', DB::raw('sum(v.costoventa) as costoventa'))
                    ->get();
            }
        }

        return $ventas;
    }
    //obtener las compras del mes actual
    public function comprasdelmes($tienda, $inicio, $hoy, $tabla)
    {
        $compras = "";
        if ($tienda != '-1') {
            if ($tabla != '-1') {
                $compras = DB::table('ingresos as v')
                    ->join('detalleingresos as dv', 'dv.ingreso_id', '=', 'v.id')
                    ->where('v.tienda_id', '=', $tienda)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->where('dv.tipo', '=', $tabla)
                    ->groupBy('v.fecha', 'dv.tipo')
                    ->select('v.fecha', DB::raw('sum(dv.preciofinal) as costoventa'))
                    ->get();
            } else {
                $compras = DB::table('ingresos as v')
                    ->where('v.tienda_id', '=', $tienda)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('v.fecha')
                    ->select('v.fecha', DB::raw('sum(v.costoventa) as costoventa'))
                    ->get();
            }
        } else {
            if ($tabla != '-1') {
                $compras = DB::table('ingresos as v')
                    ->join('detalleingresos as dv', 'dv.ingreso_id', '=', 'v.id')
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->where('dv.tipo', '=', $tabla)
                    ->groupBy('v.fecha', 'dv.tipo')
                    ->select('v.fecha', DB::raw('sum(dv.preciofinal) as costoventa'))
                    ->get();
            } else {
                $compras = DB::table('ingresos as v')
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('v.fecha')
                    ->select('v.fecha', DB::raw('sum(v.costoventa) as costoventa'))
                    ->get();
            }
        }

        return $compras;
    }

    //--------------para los productos mas vendidos para el grafico---------------
    public function obtenerproductosmasv($empresa, $traer, $fechainicio, $fechafin, $tabla)
    {
        $productos = $this->obtenerproductoscantidad($empresa, $fechainicio, $fechafin, $tabla);

        $productosnombre = $this->productosindividuales($productos);

        $ordenados = $productosnombre->sortByDesc('cantidad');
        $ordenados20 = $ordenados->take($traer);
        $separados = $this->prodseparados($ordenados20->values()->all());
        return $separados;
    }

    //obtener los productos mas vendidos por cantidad
    public function obtenerproductoscantidad($empresa, $inicio, $hoy, $tabla)
    {
        $ventas = "";
        if ($empresa != '-1') {
            if ($tabla != '-1') {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->where('dv.tipo', '=', $tabla)
                    ->where('v.tienda_id', '=', $empresa)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('dv.producto_id', 'dv.tipo')
                    ->select(DB::raw('sum(dv.cantidad) as cantidad'), 'dv.producto_id as idproducto', 'dv.tipo')
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->where('v.tienda_id', '=', $empresa)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('dv.producto_id', 'dv.tipo')
                    ->select(DB::raw('sum(dv.cantidad) as cantidad'), 'dv.producto_id as idproducto', 'dv.tipo')
                    ->get();
            }
        } else {
            if ($tabla != '-1') {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->where('dv.tipo', '=', $tabla)
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('dv.producto_id', 'dv.tipo')
                    ->select(DB::raw('sum(dv.cantidad) as cantidad'), 'dv.producto_id as idproducto', 'dv.tipo')
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->where('v.fecha', '<=', $hoy)
                    ->where('v.fecha', '>=', $inicio)
                    ->groupBy('dv.producto_id', 'dv.tipo')
                    ->select(DB::raw('sum(dv.cantidad) as cantidad'), 'dv.producto_id as idproducto', 'dv.tipo')
                    ->get();
            }
        }

        return $ventas;
    }
    //obtener los productos de forma individual
    public function productosindividuales($productos)
    {
        $productosL = collect();
        for ($i = 0; $i < count($productos); $i++) {
            $producto = $this->nombreproducto($productos[$i]->idproducto, $productos[$i]->tipo);
            $prod = collect();
            $prod->put('producto', $producto->nombre);
            $prod->put('cantidad', $productos[$i]->cantidad);
            $productosL->push($prod);
        }
        return $productosL;
    }
    //obtener los productos de un kit
    public function nombreproducto($idproducto, $tipo)
    {
        if ($tipo == "UNIFORMES") {
            $producto = Uniforme::find($idproducto);
        } else if ($tipo == "LIBROS") {
            $producto = DB::table('libros as l')->where('l.id', '=', $idproducto)->select('l.titulo as nombre')->first();
            Libro::find($idproducto);
        } else if ($tipo == "INSTRUMENTOS") {
            $producto = Instrumento::find($idproducto);
        } else if ($tipo == "UTILES") {
            $producto = Utile::find($idproducto);
        } else if ($tipo == "GOLOSINAS") {
            $producto = Golosina::find($idproducto);
        } else if ($tipo == "SNACKS") {
            $producto = Snack::find($idproducto);
        }

        return $producto;
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
        $tiendas = Tienda::all();
        // $productos = Product::all();
        return view('admin.reporte.infoproductos', compact('tiendas'));
    }
    //obtener los datos de las ventas y compras
    public function datosproductos($fechainicio, $fechafin, $tienda, $producto)
    {
        $misventas = $this->obtenerdatosproductosventa($fechainicio, $fechafin, $tienda, $producto);

        $ventasconproductos = $this->ventasconproductos($misventas);
 
        return $ventasconproductos;
    }

    //obtener datos de los productos vendidos
    public function obtenerdatosproductosventa($fechainicio, $fechafin, $tienda, $producto)
    {
        $ventas = "";
        if ($tienda != "-1") {
            if ($producto != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('t.id', '=', $tienda)
                    ->where('dv.tipo', '=', $producto)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.cliente_id'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('t.id', '=', $tienda)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.cliente_id'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('dv.tipo', '=', $producto)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.cliente_id'
                    )
                    ->get();
            } else {
                $ventas = DB::table('ventas as v')
                    ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.cliente_id'
                    )
                    ->get();
            }
        }
        return $ventas;
    }

    //convertir el precio de las compras y ventas en soles
    public function ventasconproductos($ventas)
    {
        $totalventas = collect();
        for ($i = 0; $i < count($ventas); $i++) {
            $misventas = collect();
            $misventas->put("fecha", $ventas[$i]->fecha);
            $misventas->put("tienda", $ventas[$i]->tienda);
            $misventas->put("cantidad", $ventas[$i]->cantidad);
            $misventas->put("preciounitariomo", $ventas[$i]->preciounitariomo);
            $misventas->put("preciofinal", $ventas[$i]->preciofinal);
            if ($ventas[$i]->cliente_id != null) {
                $cliente = Cliente::find($ventas[$i]->cliente_id);
                $misventas->put("cliente", $cliente->nombre);
            } else {
                $misventas->put("cliente", "");
            }
            $nombreproducto = $this->nombrecompletoproducto($ventas[$i]->tipo, $ventas[$i]->producto_id);
            $misventas->put("producto", $nombreproducto);
            $totalventas->push($misventas);
        }

        return  $totalventas;
    }

    function nombrecompletoproducto($tipo, $idproducto)
    {
        if ($tipo == "UTILES") {
            $producto = DB::table('utiles as u')
                ->join('marcautils as mu', 'u.marcautil_id', '=', 'mu.id')
                ->join('colorutils as cu', 'u.colorutil_id', '=', 'cu.id')
                ->select('u.id', 'u.nombre', 'u.precio', 'u.stock1', 'u.stock2', 'u.stock3', 'mu.marcautil', 'cu.colorutil')
                ->where('u.id', '=', $idproducto)->first();
            $nombre = $producto->nombre . " marca: " . $producto->marcautil . " color: " . $producto->colorutil;
            return $nombre;
        } else if ($tipo == "UNIFORMES") {
            $producto = DB::table('uniformes as u')
                ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
                ->join('tallas as t', 'u.talla_id', '=', 't.id')
                ->join('colors as c', 'u.color_id', '=', 'c.id')
                ->select('u.id', 'u.nombre', 'u.genero', 'u.precio', 'u.stock1', 'u.stock2', 'u.stock3', 't.talla', 'c.color', 'tt.tela')
                ->where('u.id', '=', $idproducto)->first();
            $nombre = $producto->nombre . " : " . $producto->genero . " talla: " . $producto->talla . " color: " . $producto->color . " tela: " . $producto->tela;
            return $nombre;
        } else if ($tipo == "LIBROS") {
            $producto = DB::table('libros as u')
                ->join('tipopastas as tt', 'u.tipopasta_id', '=', 'tt.id')
                ->join('tipopapels as tp', 'u.tipopapel_id', '=', 'tp.id')
                ->join('formatos as f', 'u.formato_id', '=', 'f.id')
                ->join('edicions as e', 'u.edicion_id', '=', 'e.id')
                ->join('especializacions as es', 'u.especializacion_id', '=', 'es.id')
                ->select(
                    'u.autor',
                    'u.original',
                    'tt.tipopasta',
                    'tp.tipopapel',
                    'u.id',
                    'u.titulo',
                    'u.anio',
                    'u.precio',
                    'u.stock1',
                    'u.stock2',
                    'u.stock3',
                    'f.formato',
                    'e.edicion',
                    'es.especializacion'
                )
                ->where('u.id', '=', $idproducto)->first();
            $nombre = $producto->titulo . " autor: " . $producto->autor . " edicion: " . $producto->edicion . " anio: " . $producto->anio .
                " especializacion: " . $producto->especializacion . " pasta: " . $producto->tipopasta . " papel: " . $producto->tipopapel .
                " formato: " . $producto->formato . " : " . $producto->original;
            return $nombre;
        } else if ($tipo == "INSTRUMENTOS") {
            $producto = DB::table('instrumentos as i')
                ->join('marcas as m', 'i.marca_id', '=', 'm.id')
                ->join('modelos as mo', 'i.modelo_id', '=', 'mo.id')
                ->select('i.id', 'i.nombre', 'i.precio', 'i.stock1', 'i.stock2', 'i.stock3', 'i.garantia', 'm.marca', 'mo.modelo')
                ->where('i.id', '=', $idproducto)->first();
            $nombre = $producto->nombre . " marca: " . $producto->marca . " modelo: " . $producto->modelo;
            return $nombre;
        } else if ($tipo == "GOLOSINAS") {
            $producto = DB::table('golosinas as g') 
                ->select('g.nombre', 'g.peso')
                ->where('g.id', '=', $idproducto)->first();
            $nombre = $producto->nombre . " peso: " . $producto->peso . " gramos ";
            return $nombre;
        } else if ($tipo == "SNACKS") {
            $producto = DB::table('snacks as s')
                ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
                ->join('saborsnacks as sn', 's.saborsnack_id', '=', 'sn.id')
                ->select(
                    's.id',
                    's.nombre',
                    's.tamanio',
                    's.precio',
                    's.stock1',
                    's.stock2',
                    's.stock3',
                    'ms.marcasnack',
                    'sn.saborsnack'
                )
                ->where('s.id', '=', $idproducto)->first();
            $nombre = $producto->nombre . " marca: " . $producto->marcasnack . " sabor: " . $producto->saborsnack . " - " . $producto->tamanio;
            return $nombre;
        }
    }

    public function datoscompras()
    {
        $tiendas = Tienda::all();
        // $productos = Product::all();
        return view('admin.reporte.datoscompras', compact('tiendas'));
    }

     //obtener los datos de las ventas y compras
     public function datoscomprasproductos($fechainicio, $fechafin, $tienda, $producto)
     {
         $miscompras = $this->obtenerdatosproductoscompras($fechainicio, $fechafin, $tienda, $producto);
 
         $comprasconproductos = $this->comprasconproductos($miscompras);
 
 
         return $comprasconproductos;
     }

      //obtener datos de los productos vendidos
    public function obtenerdatosproductoscompras($fechainicio, $fechafin, $tienda, $producto)
    {
        $ingresos = "";
        if ($tienda != "-1") {
            if ($producto != "-1") {
                $ingresos = DB::table('ingresos as v')
                    ->join('detalleingresos as dv', 'dv.ingreso_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('t.id', '=', $tienda)
                    ->where('dv.tipo', '=', $producto)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.proveedor_id as cliente_id'
                    )
                    ->get();
            } else {
                $ingresos = DB::table('ingresos as v')
                    ->join('detalleingresos as dv', 'dv.ingreso_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('t.id', '=', $tienda)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.proveedor_id as cliente_id'
                    )
                    ->get();
            }
        } else {
            if ($producto != "-1") {
                $ingresos = DB::table('ingresos as v')
                    ->join('detalleingresos as dv', 'dv.ingreso_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->where('dv.tipo', '=', $producto)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.proveedor_id as cliente_id'
                    )
                    ->get();
            } else {
                $ingresos = DB::table('ingresos as v')
                    ->join('detalleingresos as dv', 'dv.ingreso_id', '=', 'v.id')
                    ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                    ->where('v.fecha', '<=', $fechafin)
                    ->where('v.fecha', '>=', $fechainicio)
                    ->select(
                        't.nombre as tienda',
                        'dv.cantidad',
                        'dv.preciounitariomo',
                        'dv.preciofinal',
                        'v.fecha',
                        'dv.producto_id',
                        'dv.tipo',
                        'v.proveedor_id as cliente_id'
                    )
                    ->get();
            }
        }
        return $ingresos;
    }

    //convertir el precio de las compras y ventas en soles
    public function comprasconproductos($ventas)
    {
        $totalventas = collect();
        for ($i = 0; $i < count($ventas); $i++) {
            $misventas = collect();
            $misventas->put("fecha", $ventas[$i]->fecha);
            $misventas->put("tienda", $ventas[$i]->tienda);
            $misventas->put("cantidad", $ventas[$i]->cantidad);
            $misventas->put("preciounitariomo", $ventas[$i]->preciounitariomo);
            $misventas->put("preciofinal", $ventas[$i]->preciofinal);
            if ($ventas[$i]->cliente_id != null) {
                $cliente = Proveedor::find($ventas[$i]->cliente_id);
                $misventas->put("cliente", $cliente->nombre);
            } else {
                $misventas->put("cliente", "");
            }
            $nombreproducto = $this->nombrecompletoproducto($ventas[$i]->tipo, $ventas[$i]->producto_id);
            $misventas->put("producto", $nombreproducto);
            $totalventas->push($misventas);
        }

        return  $totalventas;
    }















    //obtener los datos de los productos comprados
    public function obtenerdatosproductoscompra($fechainicio, $fechafin, $empresa, $producto)
    {
        $compras = "";
        if ($empresa != "-1") {
            if ($producto != "-1") {
                $compras = DB::table('ingresos as i')
                    ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
                    ->join('companies as e', 'i.tienda_id', '=', 'e.id')
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
                    ->join('companies as e', 'i.tienda_id', '=', 'e.id')
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
                    ->join('companies as e', 'i.tienda_id', '=', 'e.id')
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
                    ->join('companies as e', 'i.tienda_id', '=', 'e.id')
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
