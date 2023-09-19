<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ordencompra;
use App\Models\Detalleordencompra;
use App\Models\Company;
use App\Models\Cliente;
use App\Models\Condicion;
use App\Models\Product;
use App\Http\Requests\CotizacionFormRequest;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class OrdencompraController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:ver-orden-compra|editar-orden-compra|crear-orden-compra|eliminar-orden-compra',
            ['only' => ['index', 'show']]
        );
        $this->middleware('permission:crear-orden-compra', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-orden-compra', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:eliminar-orden-compra', ['only' => ['destroy']]);
    }
    use HistorialTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ordencompras = DB::table('ordencompras as oc')
                ->join('companies as e', 'oc.company_id', '=', 'e.id')
                ->join('clientes as cl', 'oc.cliente_id', '=', 'cl.id')
                ->select(
                    'oc.id',
                    'oc.numero',
                    'oc.fecha',
                    'e.nombre as empresa',
                    'cl.nombre as cliente',
                    'oc.observacion',
                );
            return DataTables::of($ordencompras)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($ordencompras) {
                    return view('admin.ordencompra.botones', compact('ordencompras'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.ordencompra.index');
    }
    //vista crear
    public function create()
    {
        $companies = Company::all();
        $clientes = Cliente::all();
        $products = DB::table('products as p')
            ->select('p.id', 'p.nombre', 'p.moneda', 'p.tipo', 'p.NoIGV', 'p.unidad', 'p.preciocompra','p.codigo')
            ->where('p.status', '=', 0)
            ->get();

        return view('admin.ordencompra.create', compact('companies', 'products', 'clientes'));
    }
    //funcion para guardar un registro
    public function store(Request $request)
    {
        $fechahoy = date('Y-m-d');
        $año = substr($fechahoy, 0, 4);
        $mes = substr($fechahoy, -5, 2);;
        $dia = substr($fechahoy, -2, 2);
        $fechanum =  $año . $mes . $dia;

        $company = Company::find($request->company_id);
        $cliente = Cliente::find($request->cliente_id);
        $nroordencompras = DB::table('ordencompras as oc')
            ->join('companies as e', 'oc.company_id', '=', 'e.id')
            ->where('e.id', '=', $company->id)
            ->select('oc.id', 'e.id as company_id')
            ->count();
        $nroultimaorden = 0;
        if ($nroordencompras > 0) {
            $nroultimaordencol = DB::table('ordencompras as oc')
                ->join('companies as e', 'oc.company_id', '=', 'e.id')
                ->where('e.id', '=', $company->id)
                ->select('oc.id', 'oc.numero')
                ->orderBy('oc.id', 'desc')
                ->first();
            //global  $nroultimaorden;
            $nroultimaorden = substr($nroultimaordencol->numero, 12);
        }
        $OrdenesConCeros = str_pad($nroultimaorden + 1, 3, "0", STR_PAD_LEFT);
        $EmpresaConCeros = str_pad($company->id, 2, "0", STR_PAD_LEFT);

        $fecha = $request->fecha;
        $moneda = $request->moneda;

        $ordencompra = new Ordencompra;
        $ordencompra->company_id = $company->id;
        $ordencompra->cliente_id = $cliente->id;
        $ordencompra->fecha = $fecha;
        $ordencompra->moneda = $request->moneda;
        $ordencompra->formapago = $request->formapago;
        if ($request->formapago == "credito") {
            $ordencompra->diascredito = $request->diascredito;
        }
        $ordencompra->numero = $fechanum . "-" . $EmpresaConCeros . "-" . $OrdenesConCeros;

        //no obligatorios  
        $ordencompra->observacion = $request->observacion;
        $ordencompra->persona = $request->persona;
        //guardamos la venta y los detalles
        if ($ordencompra->save()) {
            //traemos y guardamos los detalles  
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidadproducto;
            $observacionproducto = $request->Lobservacionproducto;
            $preciocompra = $request->Lpreciocompra;

            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detalleorden = new Detalleordencompra;
                    $Detalleorden->ordencompra_id = $ordencompra->id;
                    $Detalleorden->product_id = $product[$i];
                    $Detalleorden->cantidad = $cantidad[$i];
                    $Detalleorden->unidad = $unidad[$i];
                    $Detalleorden->observacionproducto = $observacionproducto[$i];
                    $Detalleorden->preciocompra = $preciocompra[$i];
                    $Detalleorden->save();
                }
            }

            $this->crearhistorial('crear', $ordencompra->id, $company->nombre, $cliente->nombre, 'ordencompras');
            return redirect('admin/ordencompra')->with('message', 'Orden de Compra Agregada Satisfactoriamente');
        }
        return redirect('admin/ordencompra')->with('message', 'No se pudo Agregar la Orden de Compra');
    }
    //funcion para mostrar un registro
    public function show($id)
    {
        $ordencompra = DB::table('ordencompras as oc')
            ->join('detalleordencompras as doc', 'doc.ordencompra_id', '=', 'oc.id')
            ->join('companies as e', 'oc.company_id', '=', 'e.id')
            ->join('clientes as cl', 'oc.cliente_id', '=', 'cl.id')
            ->join('products as p', 'doc.product_id', '=', 'p.id')
            ->select(
                'oc.fecha',
                'oc.numero',
                'oc.observacion',
                'e.nombre as company',
                'cl.nombre as cliente',
                'p.nombre as producto',
                'doc.cantidad',
                'doc.observacionproducto',
                'oc.persona',
                'p.tipo as tipo',
                'doc.unidad',
                'p.id as idproducto',
                'oc.moneda',
                'oc.formapago',
                'oc.diascredito',
                'doc.preciocompra',
            )
            ->where('oc.id', '=', $id)->get();
        return  $ordencompra;
    }
    //vista editar
    public function edit(int $ordencompra_id)
    {
        $ordencompra = Ordencompra::findOrFail($ordencompra_id);
        //$companies = Company::all();
        $companies = DB::table('companies as c')
            //->join('ordencompras as oc', 'oc.company_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.ruc')
            //->where('ct.id', '=', $ordencompra_id)
            ->get();
        $clientes = Cliente::all();
        $products = DB::table('products as p')
            ->select('p.id', 'p.nombre', 'p.moneda', 'p.tipo', 'p.NoIGV', 'p.unidad', 'p.preciocompra','p.codigo')
            ->get();
        $detallesordencompra = DB::table('detalleordencompras as doc')
            ->join('ordencompras as oc', 'doc.ordencompra_id', '=', 'oc.id')
            ->join('products as p', 'doc.product_id', '=', 'p.id')
            ->select(
                'doc.observacionproducto',
                'p.tipo',
                'doc.id as iddetalleordencompra',
                'doc.cantidad',
                'p.id as idproducto',
                'doc.unidad',
                'p.nombre as producto',
                'doc.preciocompra'
            )
            ->where('oc.id', '=', $ordencompra_id)->get();

        $detalleskit = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pv', 'k.product_id', '=', 'pv.id')
            ->join('detalleordencompras as doc', 'doc.product_id', '=', 'pv.id')
            ->join('ordencompras as oc', 'doc.ordencompra_id', '=', 'oc.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('oc.id', '=', $ordencompra_id)->get();

        return view('admin.ordencompra.edit', compact('detalleskit', 'products', 'ordencompra', 'companies', 'clientes', 'detallesordencompra'));
    }
    //funcion para actualizar un registro
    public function update(Request $request, int $ordencompra_id)
    {

        $company = Company::find($request->company_id);
        $cliente = Cliente::find($request->cliente_id);
        $fecha = $request->fecha;

        $ordencompra =  Ordencompra::findOrFail($ordencompra_id);
        $ordencompra->company_id = $company->id;
        $ordencompra->cliente_id = $cliente->id;
        $ordencompra->fecha = $fecha;
        $ordencompra->moneda = $request->moneda;
        $ordencompra->persona = $request->persona;
        $ordencompra->numero = $request->numero;
        $ordencompra->formapago = $request->formapago;
        if ($request->formapago == "credito") {
            $ordencompra->diascredito = $request->diascredito;
        }
        if ($request->formapago == "contado") {
            $ordencompra->diascredito = "";
        }
        $ordencompra->observacion = $request->observacion;
        //guardamos la venta y los detalles
        if ($ordencompra->update()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidadproducto;
            $observacionproducto = $request->Lobservacionproducto;

            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detalleordencompra = new Detalleordencompra;
                    $Detalleordencompra->ordencompra_id = $ordencompra->id;
                    $Detalleordencompra->product_id = $product[$i];
                    $Detalleordencompra->cantidad = $cantidad[$i];
                    $Detalleordencompra->unidad = $unidad[$i];
                    $Detalleordencompra->observacionproducto = $observacionproducto[$i];
                    $Detalleordencompra->save();
                }
            }

            $this->crearhistorial('editar', $ordencompra->id, $company->nombre, $cliente->nombre, 'ordencompras');
            return redirect('admin/ordencompra')->with('message', 'Orden Compra Actualizada Satisfactoriamente');
        }
        return redirect('admin/ordencompra')->with('message', 'No se pudo Actualizar la Orden Compra');
    }
    //funcion para eliminar un detalle de la cotizacion
    public function destroydetalleordencompra($id)
    {
        $detalleordencompra = Detalleordencompra::find($id);
        if ($detalleordencompra) {
            try {
                $detalleordencompra->delete();
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //eliminar un registro
    public function destroy($id)
    {
        $ordencompra = Ordencompra::find($id);
        if ($ordencompra) {
            try {
                $ordencompra->delete();
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //funcion para generar el pdf de la cotizacion
    public function generarordencomprapdf($idordencompra)
    {
        $orden = Ordencompra::findOrFail($idordencompra);
        $empresa = Company::findOrFail($orden->company_id);
        $cliente = Cliente::findOrFail($orden->cliente_id);

        $ordencompra = DB::table('ordencompras as oc')
            ->join('detalleordencompras as doc', 'doc.ordencompra_id', '=', 'oc.id')
            ->join('companies as e', 'oc.company_id', '=', 'e.id')
            ->join('clientes as cl', 'oc.cliente_id', '=', 'cl.id')
            ->join('products as p', 'doc.product_id', '=', 'p.id')
            ->select(
                'oc.fecha',
                'oc.numero',
                'oc.observacion',
                'oc.persona',
                'e.nombre as company',
                'cl.nombre as cliente',
                'p.nombre as producto',
                'doc.cantidad',
                'doc.unidad',
                'doc.observacionproducto',
                'p.tipo as tipo',
                'p.id as idproducto',
                'oc.formapago',
                'oc.diascredito',
                'oc.moneda',
            )
            ->where('oc.id', '=', $idordencompra)->get();
        $detallekit = DB::table('ordencompras as oc')
            ->join('detalleordencompras as doc', 'doc.ordencompra_id', '=', 'oc.id')
            ->join('companies as e', 'oc.company_id', '=', 'e.id')
            ->join('clientes as cl', 'oc.cliente_id', '=', 'cl.id')
            ->join('products as p', 'doc.product_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select(
                'pk.nombre as producto',
                'k.cantidad',
                'k.product_id as idkit',
            )
            ->where('oc.id', '=', $idordencompra)->get();

        $fechaletra = $this->obtenerFechaEnLetra($ordencompra[0]->fecha);
        //return  $detallekit;
        $pdf = PDF::loadView(
            'admin.ordencompra.ordencomprapdf',
            [
                "ordencompra" => $ordencompra, "empresa" => $empresa, "fechaletra" => $fechaletra,
                "cliente" => $cliente, "detallekit" => $detallekit
            ]
        );
        return $pdf->stream('venta.pdf');
    }
    //funcion para cambiar la fecha de numeros a letras para poner en el pdf
    function obtenerFechaEnLetra($fecha)
    {
        $num = date("j", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($fecha)) * 1) - 1];
        return $num . ' de ' . $mes . ' del ' . $anno;
    }
}
