<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venta;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Product;
use App\Models\Detalleventa;
use App\Models\Inventario;
use App\Models\Detalleingreso;
use App\Models\Ingreso;
use App\Models\Detalleinventario;
use App\Models\DetalleKitventa;
use App\Models\DetalleKitingreso;
use App\Models\Carro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VentaFormRequest;
use Illuminate\Support\Collection;
use PDF;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Traits\ActualizarStockTrait;
use Spatie\Backup\Tasks\Backup\Zip;

class VentaController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware(
            'permission:ver-venta|editar-venta|crear-venta|eliminar-venta',
            ['only' => ['index', 'show', 'showcreditos',  'generarfacturapdf']]
        );
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store', 'create2', 'facturadisponible']]);
        $this->middleware('permission:editar-venta', ['only' => ['edit', 'update', 'destroydetalleventa', 'misdetallesventa']]);
        $this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
        $this->middleware(
            'permission:crear-venta|crear-cotizacion|crear-ingreso|editar-venta|editar-cotizacion|editar-ingreso|ver-venta|ver-ingreso|ver-cotizacion|eliminar-venta|eliminar-ingreso|eliminar-cotizacion',
            ['only' => [
                'productosxempresa', 'productosxkit', 'comboempresacliente', 'comboempresaclientevi',
                'stockkitxempresa', 'stockxprodxempresa', 'facturadisponible'
            ]]
        );
    }
    use HistorialTrait;
    use ActualizarStockTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ventas = DB::table('ventas as v')
                ->join('clientes as c', 'v.cliente_id', '=', 'c.id')
                ->join('companies as e', 'v.company_id', '=', 'e.id')
                ->select(
                    'v.id',
                    'c.nombre as cliente',
                    'e.nombre as empresa',
                    'v.moneda',
                    'v.formapago',
                    'v.factura',
                    'v.costoventa',
                    'v.pagada',
                    'v.fecha',
                );
            return DataTables::of($ventas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($ventas) {
                    return view('admin.venta.botones', compact('ventas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.venta.index');
    }
    //funcion para que reaccione al index principal
    public function index2()
    {
        return redirect('admin/venta')->with('verstock', 'Ver');
    }
    //funcion para  obtener las ventas de los creditos 
    public function creditosxvencer()
    {
        $fechahoy = date('Y-m-d');
        $fechalimite =  date("Y-m-d", strtotime($fechahoy . "+ 7 days"));
        $creditosxvencer = DB::table('ventas as v')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->where('v.fechav', '!=', null)
            ->where('v.pagada', '=', 'NO')
            ->select(
                'v.id',
                'v.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'v.moneda',
                'v.costoventa',
                'v.pagada',
                'v.fechav',
                'v.factura',
                'v.formapago'
            )
            ->count();
        return $creditosxvencer;
    }
    //funcion para obtener las ventas sin numero de factura
    public function sinnumero()
    {
        $sinnumero = DB::table('ventas as v')
            ->where('v.factura', '=', null)
            ->select('v.id')
            ->count();
        return $sinnumero;
    }
    //vista crear
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = Product::all();
        return view('admin.venta.create', compact('companies', 'products', 'clientes'));
    }
    //funcion para registrar una venta
    public function store(VentaFormRequest $request)
    {   //validar datos
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $factura = $validatedData['factura'];
        $pagada = $validatedData['pagada'];
        if ($factura != null) {
            $nrofacturadisponible = $this->facturadisponible($company->id, $factura);
            if ($nrofacturadisponible == "NO") {
                return redirect()->back()->with('message', 'Numero de Factura YA Registrado');
            }
        }
        //cramos un registro de venta
        $venta = new Venta;
        $venta->company_id = $company->id;
        $venta->cliente_id = $cliente->id;
        $venta->fecha = $fecha;
        $venta->costoventa = $costoventa;
        $venta->formapago = $formapago;
        $venta->moneda = $moneda;
        $venta->factura = $factura;
        $venta->pagada = $pagada;
        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];
        $venta->tasacambio = $tasacambio;
        $venta->observacion = $observacion;
        if ($formapago == 'credito') {
            $venta->fechav = $fechav;
        }
        //datos del pago
        $venta->nrooc = $request->nrooc;
        $venta->guiaremision = $request->guiaremision;
        $venta->fechapago = $request->fechapago;
        $venta->constanciaretencion = $request->constanciaretencion;
        $venta->acuenta1 = $request->acuenta1;
        $venta->acuenta2 = $request->acuenta2;
        $venta->acuenta3 = $request->acuenta3;
        $venta->saldo = $request->saldo;

        $venta->retencion = $request->retencion;
        $venta->montopagado = $request->montopagado;
        //guardamos la venta y los detalles
        if ($venta->save()) {
            //guardamos la venta y vemos si viene de una cotizacion
            $idcotizacion = $request->idcotizacion;
            if ($idcotizacion != -1) {
                $cotizacion = Cotizacion::findOrFail($idcotizacion);
                $cotizacion->vendida = "SI";
                $cotizacion->update();
            }
            //obtenemos los detalles
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            //arrays de los productos vendidos de un kit
            $idkits = $request->Lidkit;
            $cantidadproductokit = $request->Lcantidadproductokit;
            $idproductokit = $request->Lidproductokit;
            if ($product !== null) {
                //recorremos los detalles
                for ($i = 0; $i < count($product); $i++) {
                    //creamos los detalles de la venta
                    $Detalleventa = new Detalleventa;
                    $Detalleventa->venta_id = $venta->id;
                    $Detalleventa->product_id = $product[$i];
                    $Detalleventa->cantidad = $cantidad[$i];
                    $Detalleventa->observacionproducto = $observacionproducto[$i];
                    $Detalleventa->preciounitario = $preciounitario[$i];
                    $Detalleventa->preciounitariomo = $preciounitariomo[$i];
                    $Detalleventa->servicio = $servicio[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if ($Detalleventa->save()) {
                        if ($idkits !== null) {
                            for ($x = 0; $x < count($idkits); $x++) {
                                if ($idkits[$x] == $product[$i]) {
                                    $venta_kit = new DetalleKitventa;
                                    $venta_kit->detalleventa_id = $Detalleventa->id;
                                    $venta_kit->kitproduct_id = $idproductokit[$x];
                                    $venta_kit->cantidad = $cantidadproductokit[$x];
                                    $venta_kit->save();
                                }
                            }
                        }
                        $miproductox = Product::find($product[$i]);
                        //vemos si el producto es un kit
                        if ($miproductox && $miproductox->tipo == "kit") {
                            $milistaproductos = $this->productosxdetallexkit($Detalleventa->id);
                            //obtenemos los productos de un kit
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $this->actualizarstock($milistaproductos[$j]->id, $company->id, ($milistaproductos[$j]->cantidad) * $cantidad[$i], "RESTA");
                            }
                        } else if ($miproductox && $miproductox->tipo == "estandar") {
                            //para el producto estandar
                            $this->actualizarstock($product[$i], $company->id, $cantidad[$i], "RESTA");
                        }
                    }
                }
            }
            //registrar tambien el ingreso al hacer una venta entre nuestras empresas
            if ($request->ingreso == "SI") {
                $empresa = DB::table('companies as c')
                    ->where('c.ruc', '=', $cliente->ruc)
                    ->select('c.id', 'c.ruc', 'c.nombre')
                    ->first();
                $client = DB::table('clientes as c')
                    ->where('c.ruc', '=', $company->ruc)
                    ->select('c.id', 'c.ruc', 'c.nombre')
                    ->first();
                $ingreso = new Ingreso;
                $ingreso->company_id = $empresa->id;
                $ingreso->cliente_id = $client->id;
                $ingreso->fecha = $fecha;
                $ingreso->costoventa = $costoventa;
                $ingreso->formapago = $formapago;
                $ingreso->moneda = $moneda;
                $ingreso->factura = $request->factura;
                $ingreso->pagada = $pagada;
                $ingreso->observacion = $observacion;
                if ($formapago == 'credito') {
                    $ingreso->fechav = $fechav;
                }
                $ingreso->tasacambio = $tasacambio;
                //guardamos la venta y los detalles
                if ($ingreso->save()) {
                    $this->crearhistorial('crear', $venta->id, $empresa->nombre, $client->nombre, 'ingresos');
                    //obtenemos los detalles
                    $product = $request->Lproduct;
                    $observacionproducto = $request->Lobservacionproducto;
                    $cantidad = $request->Lcantidad;
                    $preciounitario = $request->Lpreciounitario;
                    $servicio = $request->Lservicio;
                    $preciofinal = $request->Lpreciofinal;
                    $preciounitariomo = $request->Lpreciounitariomo;
                    //arrays de los productos vendidos de un kit
                    $idkits = $request->Lidkit;
                    $cantidadproductokit = $request->Lcantidadproductokit;
                    $idproductokit = $request->Lidproductokit;
                    if ($product !== null) {
                        //guardamos los detalles
                        for ($i = 0; $i < count($product); $i++) {
                            $Detalleingreso = new Detalleingreso;
                            $Detalleingreso->ingreso_id = $ingreso->id;
                            $Detalleingreso->product_id = $product[$i];
                            $Detalleingreso->observacionproducto = $observacionproducto[$i];
                            $Detalleingreso->cantidad = $cantidad[$i];
                            $Detalleingreso->preciounitario = $preciounitario[$i];
                            $Detalleingreso->preciounitariomo = $preciounitariomo[$i];
                            $Detalleingreso->servicio = $servicio[$i];
                            $Detalleingreso->preciofinal = $preciofinal[$i];
                            if ($Detalleingreso->save()) {
                                if ($idkits !== null) {
                                    for ($x = 0; $x < count($idkits); $x++) {
                                        if ($idkits[$x] == $product[$i]) {
                                            $venta_kit = new DetalleKitingreso;
                                            $venta_kit->detalleingreso_id = $Detalleingreso->id;
                                            $venta_kit->kitproduct_id = $idproductokit[$x];
                                            $venta_kit->cantidad = $cantidadproductokit[$x];
                                            $venta_kit->save();
                                        }
                                    }
                                }
                                $productb = Product::find($product[$i]);
                                $micliente = DB::table('clientes as c')
                                    ->where('c.id', '=', $cliente->id)
                                    ->select('c.id', 'c.ruc')
                                    ->first();
                                $miempresa = DB::table('companies as c')
                                    ->where('c.ruc', '=', $micliente->ruc)
                                    ->select('c.id', 'c.ruc')
                                    ->first();
                                //pacar cuanto el producto es un kit
                                if ($productb && $productb->tipo == "kit") { //buscamos los productos del kit
                                    $milistaproductos = $this->productosxdetallexkitingreso($Detalleingreso->id);
                                    for ($j = 0; $j < count($milistaproductos); $j++) {
                                        $this->actualizarstock($milistaproductos[$j]->id, $miempresa->id, ($milistaproductos[$j]->cantidad) * $cantidad[$i], "SUMA");
                                    }
                                }
                                //para cuando el producto es estandar
                                else if ($productb && $productb->tipo == "estandar") {
                                    $this->actualizarstock($product[$i], $miempresa->id, $cantidad[$i], "SUMA");
                                }
                                //para actualizar el precio maximo y minimo del producto
                                if ($productb) {
                                    if ($moneda == $productb->moneda) {
                                        if ($preciounitariomo[$i] > $productb->NoIGV) {
                                            $productb->maximo = $preciounitariomo[$i];
                                        } else  if ($preciounitariomo[$i] < $productb->NoIGV) {
                                            $productb->minimo = $preciounitariomo[$i];
                                        }
                                    } else if ($moneda == "dolares" && $productb->moneda == "soles") {
                                        if ($preciounitariomo[$i] > round(($productb->NoIGV) / $tasacambio, 2)) {
                                            $productb->maximo = round($preciounitariomo[$i] * $tasacambio, 2);
                                        } else  if ($preciounitariomo[$i] < round(($productb->NoIGV) / $tasacambio, 2)) {
                                            $productb->minimo = round($preciounitariomo[$i] * $tasacambio, 2);
                                        }
                                    } else if ($moneda == "soles" && $productb->moneda == "dolares") {
                                        if ($preciounitariomo[$i] > round(($productb->NoIGV) * $tasacambio, 2)) {
                                            $productb->maximo = round($preciounitariomo[$i] / $tasacambio, 2);
                                        } else  if ($preciounitariomo[$i] < round(($productb->NoIGV) * $tasacambio, 2)) {
                                            $productb->minimo = round($preciounitariomo[$i] / $tasacambio, 2);
                                        }
                                    }
                                    $productb->save();
                                }
                                //fin del guardar detalle
                            }
                        }
                    }
                }
            }
            //termino de registrar la venta
            $this->crearhistorial('crear', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
            return redirect('admin/venta')->with('message', 'Venta Agregada Satisfactoriamente');
        }
        return redirect('admin/venta')->with('message', 'No se Pudo Agregar la Venta');
    }
    //para buscar los productos vendidos en un detalle kit
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
    //para crear un detalle inventario
    public function creardetalleinventario($idempresa, $idproducto)
    {
        $inv3 = DB::table('inventarios as i')
            ->where('i.product_id', '=', $idproducto)
            ->select('i.id')
            ->first();

        $detalle2 = new Detalleinventario;
        $detalle2->company_id = $idempresa;
        $detalle2->inventario_id = $inv3->id;
        $detalle2->stockempresa = 0;
        $detalle2->status = 0;
        $detalle2->save();

        return $detalle2;
    }

    //vista create 2 para registrar una venta a partir de una cotizacion
    public function create2(int $idcotizacion)
    {
        $venta = Cotizacion::findOrFail($idcotizacion);
        $companies = DB::table('companies as c')
            ->join('cotizacions as cot', 'cot.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('cot.id', '=', $idcotizacion)
            ->get();
        $clientes = DB::table('clientes as c')
            ->join('cotizacions as cot', 'cot.cliente_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('cot.id', '=', $idcotizacion)
            ->get();
        //detalles de la venta;
        $detallesventa = DB::table('detallecotizacions as dc')
            ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
            ->join('products as p', 'dc.product_id', '=', 'p.id')
            ->select(
                'dc.observacionproducto',
                'p.tipo',
                'p.moneda',
                'dc.id as iddetalleventa',
                'dc.cantidad',
                'dc.preciounitario',
                'dc.preciounitariomo',
                'dc.servicio',
                'dc.preciofinal',
                'p.id as idproducto',
                'p.nombre as producto'
            )
            ->where('c.id', '=', $idcotizacion)->get();
        //verificamos el stock disponible para realizar la venta        
        $prodstock = collect();
        for ($i = 0; $i < count($detallesventa); $i++) {
            if ($detallesventa[$i]->tipo == "estandar") { //si es estandar
                $inventario = DB::table('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $venta->company_id)
                    ->where('i.product_id', '=', $detallesventa[$i]->idproducto)
                    ->select('di.stockempresa', 'di.company_id', 'i.product_id')
                    ->first();
                $stock = collect();
                $stock->put('idproducto', $detallesventa[$i]->idproducto);
                $stock->put('producto', $detallesventa[$i]->producto);
                $stock->put('stockdisponible', $inventario->stockempresa);
                $stock->put('stockrequerido', $detallesventa[$i]->cantidad);
                $prodstock->push($stock);
            } else if ($detallesventa[$i]->tipo == "kit") { //si es un kit
                $misproductos = $this->productosxkit($detallesventa[$i]->idproducto);
                //obtenemos los productos del kit y obtenemos el stock de cada producto
                for ($j = 0; $j < count($misproductos); $j++) {
                    $inventario = DB::table('inventarios as i')
                        ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                        ->join('companies as c', 'di.company_id', '=', 'c.id')
                        ->where('c.id', '=', $venta->company_id)
                        ->where('i.product_id', '=', $misproductos[$j]->id)
                        ->select('di.stockempresa', 'di.company_id', 'i.product_id')
                        ->first();
                    $stock = collect();
                    $stock->put('idproducto', $misproductos[$j]->id);
                    $stock->put('producto', $misproductos[$j]->producto);
                    $stock->put('stockdisponible', $inventario->stockempresa);
                    $stock->put('stockrequerido', ($detallesventa[$i]->cantidad * $misproductos[$j]->cantidad));
                    $prodstock->push($stock);
                }
            }
        }
        //vamos a obtener los productos que no tienen stock suficiente
        $prodfaltantes = collect();
        $milista2 = $prodstock->unique('idproducto');
        $milistaprod = $milista2->values()->all();
        //return $milistaprod;
        for ($z = 0; $z < count($milistaprod); $z++) {
            $mistockrequerido = 0;
            for ($x = 0; $x < count($prodstock); $x++) {
                if ($milistaprod[$z]["idproducto"] == $prodstock[$x]["idproducto"]) {
                    $mistockrequerido = $mistockrequerido + $prodstock[$x]["stockrequerido"];
                }
            }
            if ($mistockrequerido > $milistaprod[$z]["stockdisponible"]) {
                $miprod = collect();
                $miprod->put('idproducto', $milistaprod[$z]["idproducto"]);
                $miprod->put('producto', $milistaprod[$z]["producto"]);
                $miprod->put('stockdisponible', $milistaprod[$z]["stockdisponible"]);
                $miprod->put('stockrequerido', ($mistockrequerido - $milistaprod[$z]["stockdisponible"]));
                $prodfaltantes->push($miprod);
            }
        }
        $detalleskit = DB::table('products as p')
            ->join('detalle_kitcotizacions as dkc', 'dkc.kitproduct_id', '=', 'p.id')
            ->join('detallecotizacions as dc', 'dkc.detallecotizacion_id', '=', 'dc.id')
            ->join('cotizacions as c', 'dc.cotizacion_id', '=', 'c.id')
            ->select('dkc.cantidad', 'p.nombre as producto', 'dc.id as iddetallecotizacion', 'p.id')
            ->where('c.id', '=', $idcotizacion)->get();

        return view('admin.venta.createventacotizacion', compact('prodfaltantes', 'venta', 'companies', 'clientes', 'detallesventa', 'detalleskit'));
    }
    //vista editar una venta
    public function edit(int $venta_id)
    {
        $venta = Venta::findOrFail($venta_id);
        //$companies = Company::all();
        $companies = DB::table('companies as c')
            ->join('ventas as v', 'v.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('v.id', '=', $venta_id)
            ->get();
        $clientes = DB::table('clientes as c')
            ->join('ventas as v', 'v.cliente_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('v.id', '=', $venta_id)
            ->get();
        $detallesventa = DB::table('detalleventas as dv')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select(
                'dv.observacionproducto',
                'p.tipo',
                'p.unidad',
                'p.moneda',
                'dv.id as iddetalleventa',
                'dv.cantidad',
                'dv.preciounitario',
                'dv.preciounitariomo',
                'dv.servicio',
                'dv.preciofinal',
                'p.id as idproducto',
                'p.nombre as producto'
            )
            ->where('v.id', '=', $venta_id)->get();
        $detalleskit = DB::table('products as p')
            ->join('detalle_kitventas as dkv', 'dkv.kitproduct_id', '=', 'p.id')
            ->join('detalleventas as dv', 'dkv.detalleventa_id', '=', 'dv.id')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->select('dkv.cantidad', 'p.nombre as producto', 'dv.id as iddetalleventa')
            ->where('v.id', '=', $venta_id)->get();
        return view('admin.venta.edit', compact('venta', 'companies', 'clientes', 'detallesventa', 'detalleskit'));
    }
    //funcion para actualizar un registro de una venta
    public function update(VentaFormRequest $request, int $venta_id)
    {   //validamos los datos
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $factura = $validatedData['factura'];
        $pagada = $validatedData['pagada'];
        //buscamos el registro y de guardamos los nuevos datos
        $venta =  Venta::findOrFail($venta_id);
        $ventaantigua = $venta;
        $venta->company_id = $company->id;
        $venta->cliente_id = $cliente->id;
        $venta->fecha = $fecha;
        $venta->costoventa = $costoventa;
        $venta->formapago = $formapago;
        $venta->moneda = $moneda;
        $venta->factura = $factura;
        $venta->pagada = $pagada;
        //no obligatorios
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];
        $venta->tasacambio = $tasacambio;
        $venta->observacion = $observacion;
        if ($formapago == 'credito') {
            $venta->fechav = $fechav;
        } elseif ($formapago == 'contado') {
            $venta->fechav = null;
        }
        //datos del pago
        $venta->nrooc = $request->nrooc;
        $venta->guiaremision = $request->guiaremision;
        $venta->fechapago = $request->fechapago;
        $venta->constanciaretencion = $request->constanciaretencion;
        $venta->acuenta1 = $request->acuenta1;
        $venta->acuenta2 = $request->acuenta2;
        $venta->acuenta3 = $request->acuenta3;
        $venta->saldo = $request->saldo;
        $venta->retencion = $request->retencion;
        $venta->montopagado = $request->montopagado;
        //guardamos la venta  
        if ($venta->update()) {
            if ($venta->produccion_id != null) {
                // if (($ventaantigua->factura == null || $ventaantigua == "")
                //     &&
                //     ($venta->factura != null && $venta->factura != "")
                // ) {
                $this->actualizarnumerofactura($venta);
                //}
            }

            //obtenemos los detalles
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            //arrays de los productos vendidos de un kit
            $idkits = $request->Lidkit;
            $cantidadproductokit = $request->Lcantidadproductokit;
            $idproductokit = $request->Lidproductokit;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    //creamos los detalles de la venta
                    $Detalleventa = new Detalleventa;
                    $Detalleventa->venta_id = $venta->id;
                    $Detalleventa->product_id = $product[$i];
                    $Detalleventa->cantidad = $cantidad[$i];
                    $Detalleventa->observacionproducto = $observacionproducto[$i];
                    $Detalleventa->preciounitario = $preciounitario[$i];
                    $Detalleventa->preciounitariomo = $preciounitariomo[$i];
                    $Detalleventa->servicio = $servicio[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if ($Detalleventa->save()) {
                        //registramos los detalles del kit vendidos
                        if ($idkits !== null) {
                            for ($x = 0; $x < count($idkits); $x++) {
                                if ($idkits[$x] == $product[$i]) {
                                    $venta_kit = new DetalleKitventa;
                                    $venta_kit->detalleventa_id = $Detalleventa->id;
                                    $venta_kit->kitproduct_id = $idproductokit[$x];
                                    $venta_kit->cantidad = $cantidadproductokit[$x];
                                    $venta_kit->save();
                                }
                            }
                        }
                        //buscamos el producto
                        $miproductox = Product::find($product[$i]);
                        if ($miproductox && $miproductox->tipo == "kit") { //cuando es un kit
                            $milistaproductos = $this->productosxdetallexkit($Detalleventa->id);
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $this->actualizarstock($milistaproductos[$j]->id, $company->id, ($milistaproductos[$j]->cantidad) * $cantidad[$i], "RESTA");
                            }
                        } else if ($miproductox && $miproductox->tipo == "estandar") {
                            //busacamos el inventario
                            $this->actualizarstock($product[$i], $company->id, $cantidad[$i], "RESTA");
                        }
                    }
                }
            }
            $this->crearhistorial('editar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
            return redirect('admin/venta')->with('message', 'Venta Actualizada Satisfactoriamente');
        } else {
            return redirect('admin/venta')->with('message', 'Venta NO Actualizada');
        }
    }
    //funcion para mostrar los datos de la venta
    public function show($id)
    {
        $venta = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('companies as c', 'v.company_id', '=', 'c.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select(
                'v.fecha',
                'v.factura',
                'v.formapago',
                'v.moneda',
                'v.costoventa',
                'v.fechav',
                'v.tasacambio',
                'v.observacion',
                'p.moneda as monedaproducto',
                'p.id as idproducto',
                'p.tipo',
                'c.nombre as company',
                'cl.nombre as cliente',
                'p.nombre as producto',
                'dv.cantidad',
                'dv.preciounitario',
                'dv.preciounitariomo',
                'dv.servicio',
                'dv.preciofinal',
                'dv.observacionproducto',
                'dv.id as iddetalleventa',
                'v.pagada',
                'v.nrooc',
                'v.guiaremision',
                'v.fechapago',
                'v.constanciaretencion',
                'v.acuenta1',
                'v.acuenta2',
                'v.acuenta3',
                'v.saldo',
                'v.retencion',
                'v.montopagado',
                'p.unidad'
            )
            ->where('v.id', '=', $id)->get();
        return  $venta;
    }
    //funcion para mostrar las ventas a credito
    public function showcreditos()
    {
        $creditosvencidos = DB::table('ventas as v')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('clientes as cl', 'v.cliente_id', '=', 'cl.id')
            ->where('v.fechav', '!=', null)
            ->where('v.pagada', '=', 'NO')
            ->select(
                'v.id',
                'v.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'v.moneda',
                'v.costoventa',
                'v.pagada',
                'v.fechav',
                'v.factura',
                'v.formapago'
            )
            ->get();
        return $creditosvencidos;
    }
    //funcion para eliminar un registro de una venta
    public function destroy(int $venta_id)
    {
        $venta = Venta::find($venta_id);
        if ($venta) {
            $detallesventa = DB::table('detalleventas as dv')
                ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->select('dv.cantidad', 'dv.product_id', 'p.tipo', 'p.id', 'dv.id as iddetalleventa')
                ->where('v.id', '=', $venta_id)->get();
            //obtenemos los detalles de la venta
            for ($i = 0; $i < count($detallesventa); $i++) {
                if ($detallesventa[$i]->tipo == "estandar") {
                    $this->actualizarstock($detallesventa[$i]->product_id, $venta->company_id, $detallesventa[$i]->cantidad, "SUMA");
                } else if ($detallesventa[$i]->tipo == "kit") {
                    //obtenemos los productos de un kit y  buscamos los inventarios de ada producto
                    $products = $this->productosxdetallexkit($detallesventa[$i]->iddetalleventa);
                    for ($x = 0; $x < count($products); $x++) {
                        $this->actualizarstock($products[$x]->id, $venta->company_id, $detallesventa[$i]->cantidad * $products[$x]->cantidad, "SUMA");
                    }
                }
            }
            try {
                $company = Company::find($venta->company_id);
                $cliente = Cliente::find($venta->cliente_id);
                $venta->delete();
                return "1";
                if ($cliente && $company) {
                    $this->crearhistorial('eliminar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
                }
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para eliminar un detalle de una venta
    public function destroydetalleventa($id)
    {
        $detalleventa = Detalleventa::find($id);
        if ($detalleventa) {
            $venta = DB::table('detalleventas as dv')
                ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->select(
                    'dv.cantidad',
                    'v.costoventa',
                    'dv.preciofinal',
                    'v.id',
                    'v.company_id as idempresa',
                    'dv.product_id as idproducto',
                    'v.cliente_id as idcliente',
                    'p.tipo',
                )
                ->where('dv.id', '=', $id)->first();
            if ($venta->tipo == "kit") {
                $detalleventa_kit = $this->productosxdetallexkit($id);
                for ($i = 0; $i < count($detalleventa_kit); $i++) {
                    $this->actualizarstock($detalleventa_kit[$i]->id, $venta->idempresa, ($detalleventa_kit[$i]->cantidad * $venta->cantidad), "SUMA");
                }
            } else {
                $this->actualizarstock($venta->idproducto, $venta->idempresa, $venta->cantidad, "SUMA");
            }
            if ($detalleventa->delete()) {
                //eliminamos el detalle y actualizamos el costo de la venta
                $costof = $venta->costoventa;
                $detalle = $venta->preciofinal;
                $idventa = $venta->id;
                $ventaedit = Venta::findOrFail($idventa);
                $ventaedit->costoventa = $costof - $detalle;
                $ventaedit->update();
                $company = Company::find($venta->idempresa);
                $cliente = Cliente::find($venta->idcliente);
                if ($cliente && $company) {
                    $this->crearhistorial('editar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
                }
                return "1";
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para guardar datos del pago de la factura
    public function guardardatospago(Request $request)
    {
        $venta = Venta::find($request->idventa);
        if ($venta) {
            try {
                $venta->pagada = $request->pagada;
                $venta->nrooc = $request->nrooc;
                $venta->guiaremision = $request->guiaremision;
                $venta->fechapago = $request->fechapago;
                $venta->constanciaretencion = $request->constanciaretencion;
                $venta->acuenta1 = $request->acuenta1;
                $venta->acuenta2 = $request->acuenta2;
                $venta->acuenta3 = $request->acuenta3;
                $venta->saldo = $request->saldo;
                $venta->retencion = $request->retencion;
                $venta->montopagado = $request->montopagado;
                $venta->update();
                $company = Company::find($venta->company_id);
                $cliente = Cliente::find($venta->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('editar', $venta->id, $company->nombre, $cliente->nombre, 'ventas');
                }
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para obetner los productos de una empresa
    public function productosxempresa($id)
    {
        $productosininventario = DB::table('products as p')
            ->where('p.status', '=', 0)
            ->where('p.tipo', '=', 'estandar')
            ->whereNotIn('p.id', function ($query) use ($id) {
                $query->select('i.product_id')
                    ->from('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('di.company_id', '=', $id);
            })
            ->get();

        for ($i = 0; $i < count($productosininventario); $i++) {
            $this->creardetalleinventario($id, $productosininventario[$i]->id);
        }
        $prod = DB::table('detalleinventarios as di')
            ->rightjoin('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->join('companies as c', 'di.company_id', '=', 'c.id')
            ->join('products as p', 'i.product_id', '=', 'p.id')
            ->select(
                'p.id',
                'p.nombre',
                'p.NoIGV',
                'p.moneda',
                'p.tipo',
                'di.stockempresa',
                'p.cantidad2',
                'p.precio2',
                'p.cantidad3',
                'p.precio3',
                'p.unidad',
                'p.codigo',
            )
            ->where('c.id', '=', $id)
            ->where('p.status', '=', 0)
            ->where('p.tipo', '=', "estandar")
            ->get();

        $kits = DB::table('products as p')
            ->where('p.status', '=', 0)
            ->where('p.tipo', '=', "kit")
            ->select(
                'p.id',
                'p.nombre',
                'p.NoIGV',
                'p.moneda',
                'p.tipo',
                'p.cantidad2',
                'p.precio2',
                'p.cantidad3',
                'p.precio3',
                'p.unidad',
                'p.codigo',
            )
            ->get();
        $miskits = collect();
        //recorremos los kits y sus productos para obtener el stock de cada producto en una empresa
        for ($i = 0; $i < count($kits); $i++) {
            $stockmin = 100000;
            $stockkit = 100000;
            $listakits = DB::table('products as p')
                ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
                ->where('k.product_id', '=', $kits[$i]->id)
                ->select(
                    'p.id as idkitproduct',
                    'p.nombre',
                    'p.NoIGV',
                    'p.moneda',
                    'p.tipo',
                    'k.id as idkit',
                    'k.cantidad',
                    'k.preciounitario',
                    'k.preciounitariomo',
                    'k.preciofinal',
                    'p.unidad'
                )
                ->get();
            for ($j = 0; $j < count($listakits); $j++) {
                $inventario = DB::table('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('i.product_id', '=', $listakits[$j]->idkitproduct)
                    ->where('di.company_id', '=', $id)
                    ->select('i.id as idinventario', 'di.stockempresa')
                    ->first();
                if (!$inventario) {
                    $this->creardetalleinventario($id, $listakits[$j]->idkitproduct);
                    $inventario = DB::table('inventarios as i')
                        ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                        ->where('i.product_id', '=', $listakits[$j]->idkitproduct)
                        ->where('di.company_id', '=', $id)
                        ->select('i.id as idinventario', 'di.stockempresa')
                        ->first();
                }
                $cantidad = 1;
                if ($listakits[$j]->cantidad > 0) { //validado para que la cantidad cantidad del kit pueda ser cero
                    $cantidad = $listakits[$j]->cantidad;
                }
                $stockkit = floor($inventario->stockempresa / $cantidad);
                if ($stockkit < $stockmin) {
                    $stockmin = $stockkit;
                }
            }

            $mikit = collect();
            $mikit->put('id', $kits[$i]->id);
            $mikit->put('nombre', $kits[$i]->nombre);
            $mikit->put('NoIGV', $kits[$i]->NoIGV);
            $mikit->put('moneda', $kits[$i]->moneda);
            $mikit->put('tipo', $kits[$i]->tipo);
            $mikit->put('stockempresa', $stockmin);
            $mikit->put('cantidad2', $kits[$i]->cantidad2);
            $mikit->put('precio2', $kits[$i]->precio2);
            $mikit->put('cantidad3', $kits[$i]->cantidad3);
            $mikit->put('precio3', $kits[$i]->precio3);
            $mikit->put('unidad', $kits[$i]->unidad);
            $mikit->put('codigo', $kits[$i]->codigo);
            $miskits->push($mikit);
        }
        $products = $prod->concat($miskits);
        return $products;
    }

    public function verstock(Request $request)
    {
        $cant = $request->cantidad;
        $idproducto = $request->idproducto;
        $idempresa = $request->idempresa;
        $cantidadmaxima = 50000;
        for ($i = 0; $i < count($cant); $i++) {
            $stock = DB::table('inventarios as i')
                ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                ->where('i.product_id', '=', $idproducto[$i])
                ->where('di.company_id', '=', $idempresa)
                ->select('i.id as idinventario', 'di.stockempresa')
                ->first();
            $stockproducto = intval(intval($stock->stockempresa) / intval($cant[$i]));
            if ($cantidadmaxima > $stockproducto) {
                $cantidadmaxima = $stockproducto;
            }
        }
        return $cantidadmaxima;
    }
    //obtenemos los productos de un kit
    public function productosxkit($kit_id)
    {
        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id', 'p.nombre as producto', 'k.cantidad')
            ->get();
        return  $productosxkit;
    }
    //obtenemos los clientes diferentes de la empresa seleccionada
    public function comboempresacliente($id)
    {
        $empresa = Company::find($id);
        $products = DB::table('clientes as c')
            ->select('c.id', 'c.nombre')
            ->where('c.ruc', '!=', $empresa->ruc)
            ->where('c.status', '=', '0')
            ->get();
        return $products;
    }
    //funcion para generar un pdf de la venta
    public function generarfacturapdf($id)
    {
        $vent = Venta::find($id);
        $empresa = Company::find($vent->company_id);
        $cliente = Cliente::find($vent->cliente_id);
        //obtenemos los datos de la venta y los detalles del kit
        $venta = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select(
                'v.id as idventa',
                'v.fecha',
                'p.nombre as nombreproducto',
                'dv.cantidad',
                'dv.preciounitariomo',
                'dv.preciounitario',
                'dv.observacionproducto',
                'dv.servicio',
                'dv.preciofinal',
                'v.moneda as monedaventa',
                'p.moneda as monedaproducto',
                'v.formapago',
                'v.factura',
                'v.costoventa',
                'v.tasacambio',
                'v.costoventa',
                'p.tipo',
                'dv.id as iddetalle'
            )
            ->where('v.id', '=', $id)->get();
        $detallekit = DB::table('ventas as v')
            ->join('detalleventas as dv', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select(
                'v.id as idventa',
                'k.cantidad',
                'pk.nombre',
                'dv.id as iddetalle'
            )
            ->where('v.id', '=', $id)->get();
        //return $venta;
        $pdf = PDF::loadView(
            'admin.venta.facturapdf',
            ["venta" => $venta, "empresa" => $empresa, "cliente" => $cliente, "detallekit" => $detallekit]
        );
        return $pdf->stream('venta.pdf');
    }
    //funcion para obtener el stock de un kit en una empresa
    public function stockkitxempresa($idkit)
    {
        $companies = Company::all();
        $milistaproductos = $this->productosxkit($idkit);
        $mistockkits = collect();
        for ($i = 0; $i < count($companies); $i++) {
            $existeinventario = 1;
            $stockminimo = 100000;
            for ($j = 0; $j < count($milistaproductos); $j++) {
                $inventario = DB::table('inventarios as i')
                    ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                    ->where('i.product_id', '=', $milistaproductos[$j]->id)
                    ->where('di.company_id', '=', $companies[$i]->id)
                    ->select('i.id as idinventario', 'di.stockempresa')
                    ->first();
                if ($inventario != null  &&  (floor($inventario->stockempresa / $milistaproductos[$j]->cantidad) != 0)) {
                    $stockkit = floor($inventario->stockempresa / $milistaproductos[$j]->cantidad);
                    if ($stockkit < $stockminimo) {
                        $stockminimo = $stockkit;
                    }
                } else {
                    $existeinventario = 0;
                }
            }
            if ($stockminimo != 100000 &&  $existeinventario == 1) {
                $mistocke = collect();
                $mistocke->put('id', $companies[$i]->id);
                $mistocke->put('empresa', $companies[$i]->nombre);
                $mistocke->put('stock', $stockminimo);
                $mistockkits->push($mistocke);
            }
        }
        return  $mistockkits;
    }
    //funcion para obtener el stock de un producto y de una empresa
    public function stockxprodxempresa($idkit, $idempresa)
    {
        $stockprod = collect();
        $milistaproductos = $this->productosxkit($idkit);
        for ($j = 0; $j < count($milistaproductos); $j++) {
            $inventario = DB::table('inventarios as i')
                ->join('detalleinventarios as di', 'di.inventario_id', '=', 'i.id')
                ->join('products as p', 'i.product_id', '=', 'p.id')
                ->where('p.id', '=', $milistaproductos[$j]->id)
                ->where('di.company_id', '=', $idempresa)
                ->select('p.id', 'p.nombre', 'di.stockempresa')
                ->first();
            if ($inventario) {
                $stockprod->push($inventario);
            }
        }
        return  $stockprod;
    }
    //funcion para obtener los clientes diferentes de la empresa
    public function comboempresaclientevi($id)
    {
        $empresa = Company::find($id);
        $clientes = DB::table('clientes as c')
            ->join('companies as com', 'com.ruc', '=', 'c.ruc')
            ->select('c.id', 'c.nombre')
            ->where('c.ruc', '!=', $empresa->ruc)
            ->where('c.status', '=', '0')
            ->get();
        return $clientes;
    }
    //funcion para verificar si un numero de factura se encuentra disponible
    public function facturadisponible($empresa, $factura)
    {
        $ventas = DB::table('ventas as v')
            ->where('v.company_id', '=', $empresa)
            ->where('v.factura', '=', $factura)
            ->select('v.id')
            ->get();
        if (count($ventas) > 0) {
            return "NO";
        } else {
            return "SI";
        }
    }
    //funcion para obtener los detalles de una venta
    public function misdetallesventa($venta_id)
    {
        $detallesventa = DB::table('detalleventas as dv')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select('dv.observacionproducto', 'p.tipo', 'p.moneda', 'dv.id as iddetalleventa', 'dv.cantidad', 'dv.preciounitario', 'dv.preciounitariomo', 'dv.servicio', 'dv.preciofinal', 'p.id as idproducto', 'p.nombre as producto')
            ->where('v.id', '=', $venta_id)->get();
        return  $detallesventa;
    }
    //funcion para obtener el stock total(en todas las empresas) de un kit
    public function stocktotalxkit($idkit)
    {
        $milistaproductos = $this->productosxkit($idkit);
        $stockkit = 100000;
        for ($j = 0; $j < count($milistaproductos); $j++) {
            $inventario = DB::table('inventarios as i')
                ->where('i.product_id', '=', $milistaproductos[$j]->id)
                ->select('i.id', 'i.stocktotal', 'i.product_id')
                ->first();
            $stockprod = 0;
            if ($inventario) {
                $stockprod = floor($inventario->stocktotal / $milistaproductos[$j]->cantidad);
            }
            if ($stockprod < $stockkit) {
                $stockkit = $stockprod;
            }
        }
        return  $stockkit;
    }
    //funcion para ver si un cliente tiene un precio especial de un producto
    public function precioespecial($idcliente, $idproducto)
    {
        $precio = DB::table('listaprecios as lp')
            ->where('cliente_id', '=', $idcliente)
            ->where('product_id', '=', $idproducto)->first();
        if ($precio) {
            return $precio;
        } else {
            return  'x';
        }
    }
    //funcion para obtener los ultimos 5 compras de un producto en una empresa
    public function listaprecioscompra($idproducto, $idempresa)
    {
        $lista = DB::table('detalleingresos as di')
            ->join('products as p', 'p.id', '=', 'di.product_id')
            ->join('ingresos as i', 'i.id', '=', 'di.ingreso_id')
            ->join('companies as c', 'c.id', '=', 'i.company_id')
            ->where('p.id', '=', $idproducto)
            ->where('c.id', '=', $idempresa)
            ->select(
                'p.nombre',
                'di.cantidad',
                'di.preciounitariomo',
                'i.fecha',
                'i.moneda as monedafactura',
                'p.moneda as monedaproducto',
                'i.tasacambio'
            )
            ->take(5)
            ->orderByDesc('i.fecha')
            ->get();
        $precioscompra = collect();
        $precio = "";
        $simboloP = "";
        for ($i = 0; $i < count($lista); $i++) {

            if ($lista[$i]->monedaproducto == "soles") {
                $simboloP = "S/. ";
            } else {
                $simboloP = "$ ";
            }
            if ($lista[$i]->monedafactura == $lista[$i]->monedaproducto) {
                $precio = $simboloP . $lista[$i]->preciounitariomo;
            } else if ($lista[$i]->monedafactura == "soles" && $lista[$i]->monedaproducto == "dolares") {
                $precio = $simboloP . (round(($lista[$i]->preciounitariomo / $lista[$i]->tasacambio), 2));
            } else if ($lista[$i]->monedafactura == "dolares" && $lista[$i]->monedaproducto == "soles") {
                $precio = $simboloP . (round(($lista[$i]->preciounitariomo * $lista[$i]->tasacambio), 2));
            }
            $compra = collect();
            $compra->put('fecha', $lista[$i]->fecha);
            $compra->put('cantidad', $lista[$i]->cantidad);
            $compra->put('precio', $precio);
            $compra->put('producto', $lista[$i]->nombre);
            $precioscompra->push($compra);
        }

        return $precioscompra->values()->all();
    }

    public function actualizarnumerofactura($miventa)
    {
        $carros = DB::table('carros as c')
            ->where('c.produccioncarro_id', '=', $miventa->produccion_id)
            ->select('c.id as idcarro', 'c.electrobus_id', 'c.delmy_id', 'c.otraempresa_id')
            ->get();

        for ($i = 0; $i < count($carros); $i++) {
            $micarro = Carro::find($carros[$i]->idcarro);
            if ($micarro->electrobus_id != null && $micarro->electrobus_id == $miventa->id) {
                $micarro->numeroE = $miventa->factura;
                $micarro->fechaE = date('Y-m-d');
                $micarro->update();
            } else if ($micarro->delmy_id != null && $micarro->delmy_id == $miventa->id) {
                $micarro->numeroD = $miventa->factura;
                $micarro->fechaD = date('Y-m-d');
                $micarro->update();
            } else if ($micarro->otraempresa_id != null && $micarro->otraempresa_id == $miventa->id) {
                $micarro->numeroO = $miventa->factura;
                $micarro->fechaO = date('Y-m-d');
                $micarro->update();
            }
        }
    }
}
