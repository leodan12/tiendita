<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use App\Models\Proveedor;
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
use App\Models\Tienda;
use PDF;

class IngresoController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware(
            'permission:ver-ingreso|editar-ingreso|crear-ingreso|eliminar-ingreso',
            ['only' => ['index', 'show', 'showcreditos',  'generarfacturapdf']]
        );
        $this->middleware('permission:crear-ingreso', ['only' => ['create', 'store', 'create2', 'facturadisponible']]);
        $this->middleware('permission:editar-ingreso', ['only' => ['edit', 'update', 'destroydetalleingreso', 'misdetallesingreso']]);
        $this->middleware('permission:eliminar-ingreso', ['only' => ['destroy']]);
        $this->middleware(
            'permission:crear-ingreso|crear-cotizacion|crear-ingreso|editar-ingreso|editar-cotizacion|editar-ingreso|ver-ingreso|ver-ingreso|ver-cotizacion|eliminar-ingreso|eliminar-ingreso|eliminar-cotizacion',
            ['only' => [
                'productosxempresa', 'productosxkit', 'comboempresaproveedor', 'comboempresaproveedorvi',
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
            $ingresos = DB::table('ingresos as v')
                ->join('tiendas as t', 'v.tienda_id', '=', 't.id')
                ->select(
                    'v.id',
                    't.nombre as tienda',
                    'v.fecha',
                    'v.costoventa'
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
    //vista crear
    public function create()
    {
        $tiendas = Tienda::where('status', '=', '0')->get();
        $proveedors = Proveedor::where('status', '=', '0')->get();
        return view('admin.ingreso.create', compact('tiendas', 'proveedors'));
    }
    //funcion para registrar una ingreso
    public function store(Request $request)
    {
        //cramos un registro de ingreso
        $ingreso = new Ingreso;
        $ingreso->tienda_id = $request->tienda_id;
        $ingreso->fecha = $request->fecha;
        $ingreso->costoventa = $request->costoingreso;
        $ingreso->proveedor_id = $request->proveedor_id;
        //guardamos la ingreso y los detalles
        if ($ingreso->save()) {
            //obtenemos los detalles 
            $tipo = $request->Ltipo;
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo; 
            $tienda = Tienda::find($ingreso->tienda_id);
            if ($tipo !== null) {
                //recorremos los detalles
                for ($i = 0; $i < count($tipo); $i++) {
                    //creamos los detalles de la ingreso
                    $Detalleingreso = new Detalleingreso;
                    $Detalleingreso->tipo = $tipo[$i];
                    $Detalleingreso->ingreso_id = $ingreso->id;
                    $Detalleingreso->producto_id = $product[$i];
                    $Detalleingreso->cantidad = $cantidad[$i];
                    $Detalleingreso->preciounitariomo = $preciounitariomo[$i];
                    $Detalleingreso->preciofinal = $preciofinal[$i];
                    if ($Detalleingreso->save()) {
                        //$this->actualizarstock($product[$i], $company->id, $cantidad[$i], "RESTA");
                    }
                }
            }
            //termino de registrar la ingreso
            $this->crearhistorial('crear', $ingreso->id, $tienda->nombre, $ingreso->costoingreso, 'ingresos');
            return redirect('admin/ingreso')->with('message', 'Ingreso Agregada Satisfactoriamente');
        }
        return redirect('admin/ingreso')->with('message', 'No se Pudo Agregar el Ingreso');
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

    //vista editar una ingreso
    public function edit(int $ingreso_id)
    {
        $ingreso = Ingreso::findOrFail($ingreso_id);
        //$companies = Company::all();
        $tiendas = DB::table('tiendas as t')
            ->join('ingresos as v', 'v.tienda_id', '=', 't.id')
            ->select('t.id', 't.nombre')
            ->where('v.id', '=', $ingreso_id)
            ->get();
        $proveedors = DB::table('proveedors as c')

            ->select('c.id', 'c.nombre', 'c.ruc')
            //->where('v.id', '=', $ingreso_id)
            ->get();
        $detallesingreso = DB::table('detalleingresos as dv')
            ->join('ingresos as v', 'dv.ingreso_id', '=', 'v.id')
            ->select(
                'dv.tipo',
                'dv.id as iddetalleingreso',
                'dv.cantidad',
                'dv.preciounitariomo',
                'dv.preciofinal',
                'dv.producto_id',
                'v.id as idingreso'
            )
            ->where('v.id', '=', $ingreso_id)->get();
        $detalles = $this->detallesingreso($detallesingreso);
        //return $detalles;
        return view('admin.ingreso.edit', compact('ingreso', 'tiendas', 'proveedors', 'detalles'));
    }

    public function detallesingreso($detalles)
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
                    ->join('detalleingresos as dv', 'dv.producto_id', '=', 'u.id')
                    ->join('ingresos as v', 'dv.ingreso_id', '=', 'v.id')
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
            $detalle->put('iddetalleingreso', $detalles[$i]->iddetalleingreso);
            $detalle->put('cantidad', $detalles[$i]->cantidad);
            $detalle->put('preciounitariomo', $detalles[$i]->preciounitariomo);
            $detalle->put('preciofinal', $detalles[$i]->preciofinal);
            $detalle->put('producto_id', $detalles[$i]->producto_id);
            $detalle->put('idingreso', $detalles[$i]->idingreso);
            $datos->push($detalle);
        }
        return $datos;
    }
    //funcion para actualizar un registro de una ingreso
    public function update(Request $request, int $ingreso_id)
    {   //validamos los datos
         //cramos un registro de ingreso
         $ingreso =  Ingreso::find($ingreso_id);
         $ingreso->tienda_id = $request->tienda_id;
         $ingreso->fecha = $request->fecha;
         $ingreso->costoventa = $request->costoingreso;
         $ingreso->proveedor_id = $request->proveedor_id;
         //guardamos la ingreso y los detalles
         if ($ingreso->update()) {
             //obtenemos los detalles 
             $tipo = $request->Ltipo;
             $product = $request->Lproduct;
             $cantidad = $request->Lcantidad;
             $preciofinal = $request->Lpreciofinal;
             $preciounitariomo = $request->Lpreciounitariomo;
             $tienda = Tienda::find($ingreso->tienda_id);
             if ($tipo !== null) { 
                 //recorremos los detalles
                 for ($i = 0; $i < count($tipo); $i++) {
                     //creamos los detalles de la ingreso
                     $Detalleingreso = new Detalleingreso;
                     $Detalleingreso->tipo = $tipo[$i];
                     $Detalleingreso->ingreso_id = $ingreso->id;
                     $Detalleingreso->producto_id = $product[$i];
                     $Detalleingreso->cantidad = $cantidad[$i];
                     $Detalleingreso->preciounitariomo = $preciounitariomo[$i];
                     $Detalleingreso->preciofinal = $preciofinal[$i];
                     if ($Detalleingreso->save()) {
                         //$this->actualizarstock($product[$i], $company->id, $cantidad[$i], "RESTA");
                     }
                 }
             }
             //termino de registrar la ingreso
             $this->crearhistorial('editar', $ingreso->id, $tienda->nombre, $ingreso->costoingreso, 'ingresos');
             return redirect('admin/ingreso')->with('message', 'Ingreso Actualizado Satisfactoriamente');
         }
         return redirect('admin/ingreso')->with('message', 'No se Pudo Actualizar el Ingreso');
    }
    //funcion para mostrar los datos de la ingreso
    public function show($idingreso)
    {
        $datos = collect();
        $ingreso = Ingreso::findOrFail($idingreso);
        //$companies = Company::all();
        $tiendas = DB::table('tiendas as t')
            ->join('ingresos as v', 'v.tienda_id', '=', 't.id')
            ->select('t.id', 't.nombre')
            ->where('v.id', '=', $idingreso)
            ->first();
        $clientes="";
        $clientes = DB::table('proveedors as c') 
            ->join('ingresos as v','v.proveedor_id','=','c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            ->where('v.id', '=', $idingreso)
            ->first();
        $detallesingreso = DB::table('detalleingresos as dv')
            ->join('ingresos as v', 'dv.ingreso_id', '=', 'v.id')
            ->select(
                'dv.tipo',
                'dv.id as iddetalleingreso',
                'dv.cantidad',
                'dv.preciounitariomo',
                'dv.preciofinal',
                'dv.producto_id',
                'v.id as idingreso'
            )
            ->where('v.id', '=', $idingreso)->get();
        $detalles = $this->detallesingreso($detallesingreso);
        $datos->push($ingreso);
        $datos->push($tiendas);
        $datos->push($detalles);
        $datos->push($clientes);
        return $datos;
    }
    //funcion para mostrar las ingresos a credito
    public function showcreditos()
    {
        $creditosvencidos = DB::table('ingresos as v')
            ->join('companies as e', 'v.company_id', '=', 'e.id')
            ->join('proveedors as cl', 'v.proveedor_id', '=', 'cl.id')
            ->where('v.fechav', '!=', null)
            ->where('v.pagada', '=', 'NO')
            ->select(
                'v.id',
                'v.fecha',
                'e.nombre as nombreempresa',
                'cl.nombre as nombreproveedor',
                'v.moneda',
                'v.costoingreso',
                'v.pagada',
                'v.fechav',
                'v.factura',
                'v.formapago'
            )
            ->get();
        return $creditosvencidos;
    }
    //funcion para eliminar un registro de una ingreso
    public function destroy(int $ingreso_id)
    {
        $ingreso = Ingreso::find($ingreso_id);
        if ($ingreso) {
            try {
                $ingreso->delete();
                $this->crearhistorial('eliminar', $ingreso->id, $ingreso->fecha, $ingreso->costoingreso, 'ingresos');
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para eliminar un detalle de una ingreso
    public function destroydetalleingreso($id)
    {
        $detalleingreso = Detalleingreso::find($id);
        if ($detalleingreso) {
            $ingreso = DB::table('detalleingresos as dv')
                ->join('ingresos as v', 'dv.ingreso_id', '=', 'v.id')
                ->join('products as p', 'dv.product_id', '=', 'p.id')
                ->select(
                    'dv.cantidad',
                    'v.costoingreso',
                    'dv.preciofinal',
                    'v.id',
                    'v.company_id as idempresa',
                    'dv.product_id as idproducto',
                    'v.proveedor_id as idproveedor',
                    'p.tipo',
                )
                ->where('dv.id', '=', $id)->first();
            if ($ingreso->tipo == "kit") {
                $detalleingreso_kit = $this->productosxdetallexkit($id);
                for ($i = 0; $i < count($detalleingreso_kit); $i++) {
                    $this->actualizarstock($detalleingreso_kit[$i]->id, $ingreso->idempresa, ($detalleingreso_kit[$i]->cantidad * $ingreso->cantidad), "SUMA");
                }
            } else {
                $this->actualizarstock($ingreso->idproducto, $ingreso->idempresa, $ingreso->cantidad, "SUMA");
            }
            if ($detalleingreso->delete()) {
                //eliminamos el detalle y actualizamos el costo de la ingreso
                $costof = $ingreso->costoingreso;
                $detalle = $ingreso->preciofinal;
                $idingreso = $ingreso->id;
                $ingresoedit = Ingreso::findOrFail($idingreso);
                $ingresoedit->costoingreso = $costof - $detalle;
                $ingresoedit->update();
                $company = Company::find($ingreso->idempresa);
                $proveedor = Proveedor::find($ingreso->idproveedor);
                if ($proveedor && $company) {
                    $this->crearhistorial('editar', $ingreso->id, $company->nombre, $proveedor->nombre, 'ingresos');
                }
                return "1";
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
 

    //funcion para obtener los detalles de una ingreso
    public function misdetallesingreso($ingreso_id)
    {
        $detallesingreso = DB::table('detalleingresos as dv')
            ->join('ingresos as v', 'dv.ingreso_id', '=', 'v.id')
            ->join('products as p', 'dv.product_id', '=', 'p.id')
            ->select('dv.observacionproducto', 'p.tipo', 'p.moneda', 'dv.id as iddetalleingreso', 'dv.cantidad', 'dv.preciounitario', 'dv.preciounitariomo', 'dv.servicio', 'dv.preciofinal', 'p.id as idproducto', 'p.nombre as producto')
            ->where('v.id', '=', $ingreso_id)->get();
        return  $detallesingreso;
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
