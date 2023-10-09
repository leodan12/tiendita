<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venta;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Product;
use App\Models\Detalleventa;
use App\Models\DetalleKitventa;
use App\Models\Snack;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VentaFormRequest;
use Illuminate\Support\Collection;
use PDF;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Traits\ActualizarStockTrait;
use App\Models\Tienda;


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
                ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                ->select(
                    'v.id',
                    't.nombre as tienda',
                    'v.fecha',
                    'v.costoventa'
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
    //vista crear
    public function create()
    {
        $tiendas = Tienda::where('status', '=', '0')->get();
        $clientes = Cliente::where('status', '=', '0')->get();
        return view('admin.venta.create', compact('tiendas', 'clientes'));
    }
    //funcion para registrar una venta
    public function store(Request $request)
    {
        //cramos un registro de venta
        $venta = new Venta;
        $venta->tienda_id = $request->tienda_id;
        $venta->fecha = $request->fecha;
        $venta->costoventa = $request->costoventa;
        $venta->cliente_id = $request->cliente_id;
        //guardamos la venta y los detalles
        if ($venta->save()) {
            //obtenemos los detalles 
            $tipo = $request->Ltipo;
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo; 
            $tienda = Tienda::find($venta->tienda_id);
            if ($tipo !== null) {
                //recorremos los detalles
                for ($i = 0; $i < count($tipo); $i++) {
                    //creamos los detalles de la venta
                    $Detalleventa = new Detalleventa;
                    $Detalleventa->tipo = $tipo[$i];
                    $Detalleventa->venta_id = $venta->id;
                    $Detalleventa->producto_id = $product[$i];
                    $Detalleventa->cantidad = $cantidad[$i];
                    $Detalleventa->preciounitariomo = $preciounitariomo[$i];
                    $Detalleventa->preciofinal = $preciofinal[$i];
                    if ($Detalleventa->save()) {
                        //$this->actualizarstock($product[$i], $company->id, $cantidad[$i], "RESTA");
                    }
                }
            }
            //termino de registrar la venta
            $this->crearhistorial('crear', $venta->id, $tienda->nombre, $venta->costoventa, 'ventas');
            return redirect('admin/venta')->with('message', 'Venta Agregada Satisfactoriamente');
        }
        return redirect('admin/venta')->with('message', 'No se Pudo Agregar la Venta');
    }

    public function productosxtipo($tipo)
    {
        if ($tipo == "UTILES") {
            $productos = DB::table('utiles as u')
                ->join('marcautils as mu', 'u.marcautil_id', '=', 'mu.id')
                ->join('colorutils as cu', 'u.colorutil_id', '=', 'cu.id')
                ->select('u.id', 'u.nombre', 'u.precio', 'u.stock1', 'u.stock2', 'mu.marcautil', 'cu.colorutil')
                ->where('u.status', '=', '0')->get();
            return $productos;
        } else if ($tipo == "UNIFORMES") {
            $productos = DB::table('uniformes as u')
                ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
                ->join('tallas as t', 'u.talla_id', '=', 't.id')
                ->join('colors as c', 'u.color_id', '=', 'c.id')
                ->select('u.id', 'u.nombre', 'u.genero', 'u.precio', 'u.stock1', 'u.stock2', 't.talla', 'c.color', 'tt.tela')
                ->where('u.status', '=', '0')->get();
            return $productos;
        } else if ($tipo == "LIBROS") {
            $productos = DB::table('libros as u')
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
                    'f.formato',
                    'e.edicion',
                    'es.especializacion'
                )
                ->where('u.status', '=', '0')->get();
            return $productos;
        } else if ($tipo == "INSTRUMENTOS") {
            $productos = DB::table('instrumentos as i')
                ->join('marcas as m', 'i.marca_id', '=', 'm.id')
                ->join('modelos as mo', 'i.modelo_id', '=', 'mo.id')
                ->select('i.id', 'i.nombre', 'i.precio', 'i.stock1', 'i.stock2', 'i.garantia', 'm.marca', 'mo.modelo')
                ->where('i.status', '=', '0')->get();
            return $productos;
        } else if ($tipo == "GOLOSINAS") {
            $productos = DB::table('golosinas as g')
                ->where('g.status', '=', '0')->get();
            return $productos;
        } else if ($tipo == "SNACKS") {
            $productos = DB::table('snacks as s')
                ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
                ->join('saborsnacks as sn', 's.saborsnack_id', '=', 'sn.id')
                ->select(
                    's.id',
                    's.nombre',
                    's.tamanio',
                    's.precio',
                    's.stock1',
                    's.stock2',
                    'ms.marcasnack',
                    'sn.saborsnack'
                )
                ->where('s.status', '=', '0')->get();
            return $productos;
        }
    }

    //vista editar una venta
    public function edit(int $venta_id)
    {
        $venta = Venta::findOrFail($venta_id);
        //$companies = Company::all();
        $tiendas = DB::table('tiendas as t')
            ->join('ventas as v', 'v.tienda_id', '=', 't.id')
            ->select('t.id', 't.nombre')
            ->where('v.id', '=', $venta_id)
            ->get();
        $clientes = DB::table('clientes as c')

            ->select('c.id', 'c.nombre', 'c.ruc')
            //->where('v.id', '=', $venta_id)
            ->get();
        $detallesventa = DB::table('detalleventas as dv')
            ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
            ->select(
                'dv.tipo',
                'dv.id as iddetalleventa',
                'dv.cantidad',
                'dv.preciounitariomo',
                'dv.preciofinal',
                'dv.producto_id',
                'v.id as idventa'
            )
            ->where('v.id', '=', $venta_id)->get();
        $detalles = $this->detallesventa($detallesventa);
        //return $detalles;
        return view('admin.venta.edit', compact('venta', 'tiendas', 'clientes', 'detalles'));
    }

    public function detallesventa($detalles)
    {
        $datos = collect();
        for ($i = 0; $i < count($detalles); $i++) {
            $detalle = collect();
            if ($detalles[$i]->tipo == "UTILES") {
                $producto = DB::table('utiles as u')
                    ->join('marcautils as mu', 'u.marcautil_id', '=', 'mu.id')
                    ->join('colorutils as cu', 'u.colorutil_id', '=', 'cu.id')
                    ->select(
                        'u.id as idproducto',
                        'u.nombre',
                        'u.precio',
                        'u.stock1',
                        'u.stock2',
                        'mu.marcautil',
                        'cu.colorutil',
                    )
                    ->where('u.status', '=', '0')
                    ->where('u.id', '=', $detalles[$i]->producto_id)
                    ->first();

                $detalle->put('idproducto', $producto->idproducto);
                $detalle->put('nombre', $producto->nombre);
                $detalle->put('precio', $producto->precio);
                $detalle->put('stock1', $producto->stock1);
                $detalle->put('stock2', $producto->stock2);
                $detalle->put('marcautil', $producto->marcautil);
                $detalle->put('colorutil', $producto->colorutil);
            } else if ($detalles[$i]->tipo == "UNIFORMES") {
                $producto = DB::table('uniformes as u')
                    ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
                    ->join('tallas as t', 'u.talla_id', '=', 't.id')
                    ->join('colors as c', 'u.color_id', '=', 'c.id')
                    ->join('detalleventas as dv', 'dv.producto_id', '=', 'u.id')
                    ->join('ventas as v', 'dv.venta_id', '=', 'v.id')
                    ->select(
                        'u.id as idproducto',
                        'u.nombre',
                        'u.genero',
                        'u.precio',
                        'u.stock1',
                        'u.stock2',
                        't.talla',
                        'c.color',
                        'tt.tela',
                    )
                    ->where('u.status', '=', '0')
                    ->where('u.id', '=', $detalles[$i]->producto_id)
                    ->first();

                $detalle->put('idproducto', $producto->idproducto);
                $detalle->put('nombre', $producto->nombre);
                $detalle->put('precio', $producto->precio);
                $detalle->put('stock1', $producto->stock1);
                $detalle->put('stock2', $producto->stock2);
                $detalle->put('genero', $producto->genero);
                $detalle->put('talla', $producto->talla);
                $detalle->put('color', $producto->color);
                $detalle->put('tela', $producto->tela);
            } else if ($detalles[$i]->tipo == "LIBROS") {
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
                        'u.id as idproducto',
                        'u.titulo as nombre',
                        'u.anio',
                        'u.precio',
                        'u.stock1',
                        'u.stock2',
                        'f.formato',
                        'e.edicion',
                        'es.especializacion',
                    )
                    ->where('u.status', '=', '0')
                    ->where('u.id', '=', $detalles[$i]->producto_id)
                    ->first();

                $detalle->put('idproducto', $producto->idproducto);
                $detalle->put('autor', $producto->autor);
                $detalle->put('original', $producto->original);
                $detalle->put('tipopasta', $producto->tipopasta);
                $detalle->put('tipopapel', $producto->tipopapel);
                $detalle->put('nombre', $producto->nombre);
                $detalle->put('anio', $producto->anio);
                $detalle->put('precio', $producto->precio);
                $detalle->put('stock1', $producto->stock1);
                $detalle->put('stock2', $producto->stock2);
                $detalle->put('formato', $producto->formato);
                $detalle->put('edicion', $producto->edicion);
                $detalle->put('especializacion', $producto->especializacion);
            } else if ($detalles[$i]->tipo == "INSTRUMENTOS") {
                $producto = DB::table('instrumentos as i')
                    ->join('marcas as m', 'i.marca_id', '=', 'm.id')
                    ->join('modelos as mo', 'i.modelo_id', '=', 'mo.id')
                    ->select(
                        'i.id as idproducto',
                        'i.nombre',
                        'i.precio',
                        'i.stock1',
                        'i.stock2',
                        'i.garantia',
                        'm.marca',
                        'mo.modelo',
                    )
                    ->where('i.status', '=', '0')
                    ->where('i.id', '=', $detalles[$i]->producto_id)
                    ->first();

                $detalle->put('idproducto', $producto->idproducto);
                $detalle->put('nombre', $producto->nombre);
                $detalle->put('precio', $producto->precio);
                $detalle->put('stock1', $producto->stock1);
                $detalle->put('stock2', $producto->stock2);
                $detalle->put('garantia', $producto->garantia);
                $detalle->put('marca', $producto->marca);
                $detalle->put('modelo', $producto->modelo);
            } else if ($detalles[$i]->tipo == "GOLOSINAS") {
                $producto = DB::table('golosinas as g')
                    ->select(
                        'i.nombre',
                        'i.precio',
                        'i.peso',
                        'i.stock1',
                        'i.stock2',
                    )
                    ->where('g.status', '=', '0')
                    ->where('g.id', '=', $detalles[$i]->producto_id)
                    ->first();

                $detalle->put('idproducto', $producto->idproducto);
                $detalle->put('nombre', $producto->nombre);
                $detalle->put('precio', $producto->precio);
                $detalle->put('stock1', $producto->stock1);
                $detalle->put('stock2', $producto->stock2);
                $detalle->put('peso', $producto->peso);
            } else if ($detalles[$i]->tipo == "SNACKS") {
                $producto = DB::table('snacks as s')
                    ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
                    ->join('saborsnacks as sn', 's.saborsnack_id', '=', 'sn.id')
                    ->select(
                        's.id as idproducto',
                        's.nombre',
                        's.tamanio',
                        's.precio',
                        's.stock1',
                        's.stock2',
                        'ms.marcasnack',
                        'sn.saborsnack',
                    )
                    ->where('s.status', '=', '0')
                    ->where('s.id', '=', $detalles[$i]->producto_id)
                    ->first();

                $detalle->put('idproducto', $producto->idproducto);
                $detalle->put('nombre', $producto->nombre);
                $detalle->put('precio', $producto->precio);
                $detalle->put('stock1', $producto->stock1);
                $detalle->put('stock2', $producto->stock2);
                $detalle->put('tamanio', $producto->tamanio);
                $detalle->put('marcasnack', $producto->marcasnack);
                $detalle->put('saborsnack', $producto->saborsnack);
            }
            $detalle->put('tipo', $detalles[$i]->tipo);
            $detalle->put('iddetalleventa', $detalles[$i]->iddetalleventa);
            $detalle->put('cantidad', $detalles[$i]->cantidad);
            $detalle->put('preciounitariomo', $detalles[$i]->preciounitariomo);
            $detalle->put('preciofinal', $detalles[$i]->preciofinal);
            $detalle->put('producto_id', $detalles[$i]->producto_id);
            $detalle->put('idventa', $detalles[$i]->idventa);
            $datos->push($detalle);
        }
        return $datos;
    }
    //funcion para actualizar un registro de una venta
    public function update(Request $request, int $venta_id)
    {   //validamos los datos
         //cramos un registro de venta
         $venta =  Venta::find($venta_id);
         $venta->tienda_id = $request->tienda_id;
         $venta->fecha = $request->fecha;
         $venta->costoventa = $request->costoventa;
         $venta->cliente_id = $request->cliente_id;
         //guardamos la venta y los detalles
         if ($venta->update()) {
             //obtenemos los detalles 
             $tipo = $request->Ltipo;
             $product = $request->Lproduct;
             $cantidad = $request->Lcantidad;
             $preciofinal = $request->Lpreciofinal;
             $preciounitariomo = $request->Lpreciounitariomo;
             $tienda = Tienda::find($venta->tienda_id);
             if ($tipo !== null) { 
                 //recorremos los detalles
                 for ($i = 0; $i < count($tipo); $i++) {
                     //creamos los detalles de la venta
                     $Detalleventa = new Detalleventa;
                     $Detalleventa->tipo = $tipo[$i];
                     $Detalleventa->venta_id = $venta->id;
                     $Detalleventa->producto_id = $product[$i];
                     $Detalleventa->cantidad = $cantidad[$i];
                     $Detalleventa->preciounitariomo = $preciounitariomo[$i];
                     $Detalleventa->preciofinal = $preciofinal[$i];
                     if ($Detalleventa->save()) {
                         //$this->actualizarstock($product[$i], $company->id, $cantidad[$i], "RESTA");
                     }
                 }
             }
             //termino de registrar la venta
             $this->crearhistorial('editar', $venta->id, $tienda->nombre, $venta->costoventa, 'ventas');
             return redirect('admin/venta')->with('message', 'Venta Actualizada Satisfactoriamente');
         }
         return redirect('admin/venta')->with('message', 'No se Pudo Actualizar la Venta');
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
            try {
                $venta->delete();
                $this->crearhistorial('eliminar', $venta->id, $venta->fecha, $venta->costoventa, 'ventas');
                return "1";
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
}
