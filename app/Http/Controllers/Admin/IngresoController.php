<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use App\Models\Company;
use App\Models\Ingreso;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Detalleingreso;
use App\Models\Detalleinventario;
use App\Models\DetalleKitingreso;
use App\Models\Inventario;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\IngresoFormRequest;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Traits\ActualizarStockTrait;

class IngresoController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware(
            'permission:ver-ingreso|editar-ingreso|crear-ingreso|eliminar-ingreso',
            ['only' => ['index', 'show', 'showcreditos', 'productosxkit']]
        );
        $this->middleware('permission:crear-ingreso', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-ingreso', ['only' => ['edit', 'update', 'destroydetalleingreso']]);
        $this->middleware('permission:eliminar-ingreso', ['only' => ['destroy']]);
    }
    use HistorialTrait;
    use ActualizarStockTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ingresos = DB::table('ingresos as i')
                ->join('clientes as c', 'i.cliente_id', '=', 'c.id')
                ->join('companies as e', 'i.company_id', '=', 'e.id')
                ->select(
                    'i.id',
                    'c.nombre as cliente',
                    'e.nombre as empresa',
                    'i.moneda',
                    'i.formapago',
                    'i.factura',
                    'i.costoventa',
                    'i.pagada',
                    'i.fecha',
                );
            return DataTables::of($ingresos)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($ingresos) {
                    return view('admin.ingreso.botones', compact('ingresos'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.ingreso.index');
    }
    //funcion para redirigir al index principal pero que nos muestre el modal de ingresos por pagar
    public function index2()
    {
        return redirect('admin/ingreso')->with('verstock', 'Ver');
    }
    //funcion para ver cuantos ingresos no tienen numero de factura
    public function sinnumero()
    {
        $sinnumero = DB::table('ingresos as i')
            ->where('i.factura', '=', null)
            ->select('i.id')
            ->count();
        return $sinnumero;
    }
    //funcion para ver cuantos ingresos a credito estan por vencer
    public function creditosxvencer()
    {
        $creditosxvencer = DB::table('ingresos as i')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->where('i.fechav', '!=', null)
            ->where('i.pagada', '=', 'NO')
            ->select(
                'i.id',
                'i.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'i.moneda',
                'i.costoventa',
                'i.pagada',
                'i.fechav',
                'i.factura',
                'i.formapago'
            )
            ->count();
        return $creditosxvencer;
    }
    //vista crear
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = DB::table('products as p')
            ->select('p.id', 'p.nombre', 'p.NoIGV', 'p.moneda', 'p.tipo', 'p.NoIGV', 'p.unidad', 'p.preciocompra', 'p.codigo')
            ->where('p.status', '=', 0)
            ->get();
        return view('admin.ingreso.create', compact('companies', 'products', 'clientes'));
    }
    //funcion para guardar un ingreso
    public function store(IngresoFormRequest $request)
    {   //se realizan las validaciones
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $pagada = $validatedData['pagada'];
        //se crea el registro de ingreso
        $ingreso = new Ingreso;
        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $request->factura;
        $ingreso->pagada = $pagada;
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];
        $ingreso->observacion = $observacion;
        if ($formapago == 'credito') {
            $ingreso->fechav = $fechav;
        }
        $ingreso->tasacambio = $tasacambio;
        //datos del pago
        $ingreso->nrooc = $request->nrooc;
        $ingreso->guiaremision = $request->guiaremision;
        $ingreso->fechapago = $request->fechapago;
        $ingreso->acuenta1 = $request->acuenta1;
        $ingreso->acuenta2 = $request->acuenta2;
        $ingreso->acuenta3 = $request->acuenta3;
        $ingreso->saldo = $request->saldo;
        $ingreso->montopagado = $request->montopagado;
        //guardamos el ingreso y los detalles
        if ($ingreso->save()) {
            //detalles
            $product = $request->Lproduct;
            $observacionproducto = $request->Lobservacionproducto;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            $preciocompranuevo = $request->Lpreciocompranuevo;
            //arrays de los productos vendidos de un kit
            $idkits = $request->Lidkit;
            $cantidadproductokit = $request->Lcantidadproductokit;
            $idproductokit = $request->Lidproductokit;
            if ($product !== null) {
                //recorremos los detalles
                for ($i = 0; $i < count($product); $i++) {
                    //creamos y guardamos los detalles
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
                                    $ingreso_kit = new DetalleKitingreso;
                                    $ingreso_kit->detalleingreso_id = $Detalleingreso->id;
                                    $ingreso_kit->kitproduct_id = $idproductokit[$x];
                                    $ingreso_kit->cantidad = $cantidadproductokit[$x];
                                    $ingreso_kit->save();
                                }
                            }
                        }
                        $this->actualizarprecio($preciocompranuevo[$i], $product[$i]);
                        $productb = Product::find($product[$i]);
                        //para cuanto el producto es un kit se busca los productos de ese kit
                        if ($productb && $productb->tipo == "kit") {
                            $milistaproductos = $this->productosxdetallexkitingreso($Detalleingreso->id);
                            //recorremos la lista de productos de un kit y actualizamos el stock del inventarios
                            //para cada producto
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                //obtenemos el stock de la empresa
                                $this->actualizarstock($milistaproductos[$j]->id, $company->id, ($milistaproductos[$j]->cantidad) * $cantidad[$i], "SUMAR");
                            }
                        } else if ($productb && $productb->tipo == "estandar") { //para cuando el producto es estandar
                            //se obtienen el stock de la empresa
                            $this->actualizarstock($product[$i], $company->id, $cantidad[$i], "SUMAR");
                        }
                        //para actualizar el precio maximo y minimo del producto comprado
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
                    }
                }
            }
            $this->crearhistorial('crear', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
            return redirect('admin/ingreso')->with('message', 'Ingreso Agregado Satisfactoriamente');
        }
        return redirect('admin/ingreso')->with('message', 'No se Pudo Agregar el Ingreso');
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
    //guardar los datos de pago
    public function guardardatospago(Request $request)
    {
        $ingreso = Ingreso::find($request->idventa);
        if ($ingreso) {
            try {
                $ingreso->pagada = $request->pagada;
                $ingreso->nrooc = $request->nrooc;
                $ingreso->guiaremision = $request->guiaremision;
                $ingreso->fechapago = $request->fechapago;
                $ingreso->acuenta1 = $request->acuenta1;
                $ingreso->acuenta2 = $request->acuenta2;
                $ingreso->acuenta3 = $request->acuenta3;
                $ingreso->saldo = $request->saldo;
                $ingreso->montopagado = $request->montopagado;
                $ingreso->update();
                $company = Company::find($ingreso->company_id);
                $cliente = Cliente::find($ingreso->cliente_id);
                if ($cliente && $company) {
                    $this->crearhistorial('editar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                }
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //vista editar
    public function edit(int $ingreso_id)
    {
        $clientes = Cliente::all();
        $products = Product::all()->where('status', '=', 0);
        $companies = DB::table('companies as c')
            ->join('ingresos as i', 'i.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('i.id', '=', $ingreso_id)
            ->get();
        $ingreso = Ingreso::findOrFail($ingreso_id);
        $detallesingreso = DB::table('detalleingresos as di')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->select(
                'di.observacionproducto',
                'p.tipo',
                'p.moneda',
                'di.id as iddetalleingreso',
                'di.cantidad',
                'di.preciounitario',
                'di.preciounitariomo',
                'di.servicio',
                'di.preciofinal',
                'p.id as idproducto',
                'p.nombre as producto'
            )
            ->where('i.id', '=', $ingreso_id)->get();
        $detalleskit = DB::table('products as p')
            ->join('detalle_kitingresos as dki', 'dki.kitproduct_id', '=', 'p.id')
            ->join('detalleingresos as di', 'dki.detalleingreso_id', '=', 'di.id')
            ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
            ->select('dki.cantidad', 'p.nombre as producto', 'di.id as iddetalleingreso')
            ->where('i.id', '=', $ingreso_id)->get();
        return view('admin.ingreso.edit', compact('products', 'ingreso', 'companies', 'clientes', 'detalleskit', 'detallesingreso'));
    }
    //funcion para mostrar un registro de un ingreso
    public function show($id)
    {
        $ingreso = DB::table('ingresos as i')
            ->join('detalleingresos as di', 'di.ingreso_id', '=', 'i.id')
            ->join('companies as c', 'i.company_id', '=', 'c.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->join('products as p', 'di.product_id', '=', 'p.id')
            ->select(
                'i.fecha',
                'i.factura',
                'i.formapago',
                'i.moneda',
                'i.costoventa',
                'i.fechav',
                'i.tasacambio',
                'i.observacion',
                'i.moneda',
                'c.nombre as company',
                'cl.nombre as cliente',
                'p.nombre as producto',
                'di.cantidad',
                'di.preciounitario',
                'di.preciounitariomo',
                'di.servicio',
                'di.preciofinal',
                'di.observacionproducto',
                'di.id as iddetalleingreso',
                'p.moneda as monedaproducto',
                'i.pagada',
                'p.tipo',
                'p.id as idproducto',
                'i.nrooc',
                'i.guiaremision',
                'i.fechapago',
                'i.acuenta1',
                'i.acuenta2',
                'i.acuenta3',
                'i.saldo',
                'i.montopagado',
            )
            ->where('i.id', '=', $id)->get();
        return  $ingreso;
    }
    //funcion para actualizar un registro del ingreso
    public function update(IngresoFormRequest $request, int $ingreso_id)
    {   //valida los datos recibidos
        $validatedData = $request->validated();
        $company = Company::findOrFail($validatedData['company_id']);
        $cliente = Cliente::findOrFail($validatedData['cliente_id']);
        $fecha = $validatedData['fecha'];
        $moneda = $validatedData['moneda'];
        $costoventa = $validatedData['costoventa'];
        $formapago = $validatedData['formapago'];
        $pagada = $validatedData['pagada'];
        //buscamos el registro y asignamos los nuevos datos
        $ingreso =  Ingreso::findOrFail($ingreso_id);
        $ingreso->company_id = $company->id;
        $ingreso->cliente_id = $cliente->id;
        $ingreso->fecha = $fecha;
        $ingreso->costoventa = $costoventa;
        $ingreso->formapago = $formapago;
        $ingreso->moneda = $moneda;
        $ingreso->factura = $request->factura;
        $ingreso->pagada = $pagada;
        $observacion = $validatedData['observacion'];
        $tasacambio = $validatedData['tasacambio'];
        $fechav = $validatedData['fechav'];
        $ingreso->observacion = $observacion;
        if ($formapago == 'credito') {
            $ingreso->fechav = $fechav;
        } elseif ($formapago == 'contado') {
            $ingreso->fechav = null;
        }
        $ingreso->tasacambio = $tasacambio;
        //datos del pago
        $ingreso->nrooc = $request->nrooc;
        $ingreso->guiaremision = $request->guiaremision;
        $ingreso->fechapago = $request->fechapago;
        $ingreso->acuenta1 = $request->acuenta1;
        $ingreso->acuenta2 = $request->acuenta2;
        $ingreso->acuenta3 = $request->acuenta3;
        $ingreso->saldo = $request->saldo;
        $ingreso->montopagado = $request->montopagado;
        //guardamos la venta y los detalles
        if ($ingreso->update()) {
            //los detalles del ingreso
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $observacionproducto = $request->Lobservacionproducto;
            $preciounitario = $request->Lpreciounitario;
            $servicio = $request->Lservicio;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            $preciocompranuevo = $request->Lpreciocompranuevo;
            //arrays de los productos vendidos de un kit
            $idkits = $request->Lidkit;
            $cantidadproductokit = $request->Lcantidadproductokit;
            $idproductokit = $request->Lidproductokit;
            if ($product !== null) {
                //recorremos los detalles para guardarlos
                for ($i = 0; $i < count($product); $i++) {
                    //creamos y asignamos datos a un detalle
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
                                    $ingreso_kit = new DetalleKitingreso;
                                    $ingreso_kit->detalleingreso_id = $Detalleingreso->id;
                                    $ingreso_kit->kitproduct_id = $idproductokit[$x];
                                    $ingreso_kit->cantidad = $cantidadproductokit[$x];
                                    $ingreso_kit->save();
                                }
                            }
                        }
                        $this->actualizarprecio($preciocompranuevo[$i], $product[$i]);
                        $productb = Product::find($product[$i]);
                        //pacar cuanto el producto es un kit
                        if ($productb && $productb->tipo == "kit") {
                            $milistaproductos = $this->productosxdetallexkitingreso($Detalleingreso->id);
                            //recorremos los productos del kit
                            for ($j = 0; $j < count($milistaproductos); $j++) {
                                $this->actualizarstock($milistaproductos[$j]->id, $company->id, ($milistaproductos[$j]->cantidad) * $cantidad[$i], "SUMA");
                            }
                        }
                        //para cuando el producto es estandar
                        else if ($productb && $productb->tipo == "estandar") {
                            $this->actualizarstock($product[$i], $company->id, $cantidad[$i], "SUMA");
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
                $this->crearhistorial('editar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
            }
            return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
        }
    }
    //funcion para eliminar un registro de ingreso
    public function destroy(int $ingreso_id)
    {
        $ingreso = Ingreso::find($ingreso_id);
        if ($ingreso) {
            //obtenemos los detalles del ingreso
            $detallesingreso = DB::table('detalleingresos as di')
                ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
                ->join('products as p', 'di.product_id', '=', 'p.id')
                ->select('di.cantidad', 'di.product_id', 'p.tipo', 'p.id', 'di.id as iddetalleingreso')
                ->where('i.id', '=', $ingreso_id)->get();
            //recorremos los detalles
            for ($i = 0; $i < count($detallesingreso); $i++) {
                if ($detallesingreso[$i]->tipo == "estandar") {
                    $this->actualizarstock($detallesingreso[$i]->product_id, $ingreso->company_id, $detallesingreso[$i]->cantidad, "RESTA");
                } else if ($detallesingreso[$i]->tipo == "kit") {
                    //si el producto es un kit entonces obtenemos los productos de ese kit
                    $products = $this->productosxdetallexkitingreso($detallesingreso[$i]->iddetalleingreso);
                    for ($x = 0; $x < count($products); $x++) {
                        $this->actualizarstock($products[$x]->id, $ingreso->company_id, $detallesingreso[$i]->cantidad * $products[$x]->cantidad, "RESTA");
                    }
                }
            }
            //borramos el registro del ingreso
            try {
                $company = Company::find($ingreso->company_id);
                $cliente = Cliente::find($ingreso->cliente_id);
                $ingreso->delete();
                if ($cliente && $company) {
                    $this->crearhistorial('eliminar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                }
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar los ingresos a credito
    public function showcreditos()
    {
        $creditosvencidos = DB::table('ingresos as i')
            ->join('companies as e', 'i.company_id', '=', 'e.id')
            ->join('clientes as cl', 'i.cliente_id', '=', 'cl.id')
            ->where('i.fechav', '!=', null)
            ->where('i.pagada', '=', 'NO')
            ->select(
                'i.id',
                'i.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombrecliente',
                'i.moneda',
                'i.costoventa',
                'i.pagada',
                'i.fechav',
                'i.factura',
                'i.formapago'
            )
            ->get();
        return $creditosvencidos;
    }

    //funcion para eliminar el detalle del ingreso
    public function destroydetalleingreso($id)
    {
        $detalleingreso = Detalleingreso::find($id);
        if ($detalleingreso) {
            //buscamos el detalle que se va eliminar
            $midetalle = $detalleingreso;
            $ingreso = DB::table('detalleingresos as di')
                ->join('ingresos as i', 'di.ingreso_id', '=', 'i.id')
                ->join('products as p', 'di.product_id', '=', 'p.id')
                ->select(
                    'di.cantidad',
                    'i.costoventa',
                    'di.preciofinal',
                    'i.id',
                    'di.product_id as idproducto',
                    'i.company_id as idempresa',
                    'i.cliente_id as idcliente',
                    'p.tipo',
                    'di.id as iddetalleingreso'
                )
                ->where('di.id', '=', $id)
                ->first();
            if ($ingreso->tipo == "kit") {
                $milistaproductos = $this->productosxdetallexkitingreso($ingreso->iddetalleingreso);
                //recorremos la lista de productos del kit
                for ($j = 0; $j < count($milistaproductos); $j++) {
                    $this->actualizarstock($milistaproductos[$j]->id, $ingreso->idempresa, ($milistaproductos[$j]->cantidad) * $midetalle->cantidad, "RESTA");
                }
            } else if ($ingreso->tipo == "estandar") {
                $this->actualizarstock($ingreso->idproducto, $ingreso->idempresa, $ingreso->cantidad, "RESTA");
            }
            if ($detalleingreso->delete()) {
                //eliminamos el ingreso y actualizamos los precios
                $costof = $ingreso->costoventa;
                $detalle = $ingreso->preciofinal;
                $idingreso = $ingreso->id;
                $ingresoedit = Ingreso::findOrFail($idingreso);
                $ingresoedit->costoventa = $costof - $detalle;
                $ingresoedit->update();
                $company = Company::find($ingreso->idempresa);
                $cliente = Cliente::find($ingreso->idcliente);
                if ($cliente && $company) {
                    $this->crearhistorial('editar', $ingreso->id, $company->nombre, $cliente->nombre, 'ingresos');
                }
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para obtener los productos de un kit
    public function productosxkit($kit_id)
    {
        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id', 'p.nombre as producto', 'k.cantidad')
            ->get();
        return  $productosxkit;
    }
    //funcion para actualizar el precio de un producto al realizar una compra
    public function actualizarprecio($precio, $producto_id)
    {
        $producto = Product::find($producto_id);
        if ($producto) {
            $producto->preciocompra = $precio;
            $producto->update();
        }
    }
}
