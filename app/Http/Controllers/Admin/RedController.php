<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Http\Requests\RedFormRequest;
use App\Models\Red;
use App\Models\Carroceria;
use App\Models\Modelocarro;
use App\Models\Product;
use App\Models\Detallered;

class RedController extends Controller
{
    use HistorialTrait;

    public function index(Request $request)
    {
        $datoseliminados = DB::table('reds as r')
            ->where('r.status', '=', 1)
            ->select('r.id')
            ->count();
        if ($request->ajax()) {
            $redes = DB::table('reds as r')
                ->join('carrocerias as c', 'r.carroceria_id', '=', 'c.id')
                ->join('modelocarros as mc', 'r.modelo_id', '=', 'mc.id')
                ->select(
                    'r.id',
                    'r.nombre',
                    'c.tipocarroceria as carroceria',
                    'mc.modelo',
                )->where('r.status', '=', 0);
            return DataTables::of($redes)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($redes) {
                    return view('admin.redes.botones', compact('redes'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.redes.index', compact('datoseliminados'));
    }

    public function create()
    {
        $productos = Product::all()->where('status', '=', 0);
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        return view('admin.redes.create', compact('carrocerias', 'modelos', 'productos'));
    }


    public function store(RedFormRequest $request)
    {
        $validatedData = $request->validated();

        $red = new Red;
        $red->carroceria_id = $validatedData['carroceria_id'];
        $red->nombre = $validatedData['nombre'];
        $red->modelo_id = $validatedData['modelo_id'];
        $red->status =  '0';
        if ($red->save()) {
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidad;
            $producto = $request->Lproduct;
            if ($producto !== null) {
                for ($i = 0; $i < count($producto); $i++) {
                    $detallered = new Detallered;
                    $detallered->cantidad = $cantidad[$i];
                    $detallered->unidad = $unidad[$i];
                    $detallered->red_id = $red->id;
                    $detallered->producto_id = $producto[$i];
                    $detallered->status = 0;
                    $detallered->save();
                }
            }
        }

        $this->crearhistorial('crear', $red->id, $red->nombre, null, 'redes');
        return redirect('admin/redes')->with('message', 'Registro Agregado Satisfactoriamente');
    }

    //funcion para mostrar los datos de un registro
    public function show($id)
    {
        $carroceria = DB::table('reds as r')
            ->join('carrocerias as c', 'r.carroceria_id', '=', 'c.id')
            ->join('modelocarros as mc', 'r.modelo_id', '=', 'mc.id')
            ->join('detallereds as dr', 'dr.red_id', '=', 'r.id')
            ->join('products as p', 'dr.producto_id', '=', 'p.id')
            ->select(
                'p.nombre',
                'c.tipocarroceria',
                'mc.modelo',
                'dr.cantidad',
                'dr.unidad',
                'c.id',
                'p.tipo',
                'p.id as idproducto',
                'r.nombre as nombrered'
            )
            ->where('r.id', '=', $id)
            ->get();
        return  $carroceria;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $red = Red::find($id);
        $productos = Product::all()->where('status', '=', 0);
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        $detalles = DB::table('reds as r')
            ->join('detallereds as dc', 'dc.red_id', '=', 'r.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->where('r.id', '=', $id)
            ->select(
                'dc.id',
                'r.id as idmaterial',
                'dc.cantidad',
                'dc.unidad',
                'dc.producto_id',
                'p.nombre',
                'p.tipo',
                'p.id as idproducto'
            )
            ->get();
        $detalleskit = DB::table('reds as r')
            ->join('detallereds as dc', 'dc.red_id', '=', 'r.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as dk', 'k.kitproduct_id', '=', 'dk.id')
            ->where('r.id', '=', $id)
            ->where('p.tipo', '=', 'kit')
            ->select(
                'dk.id as idproducto',
                'dk.nombre as producto',
                'k.cantidad',
                'k.preciounitariomo',
                'k.preciofinal',
                'k.product_id'
            )
            ->get();
        return view(
            'admin.redes.edit',
            compact('carrocerias', 'modelos', 'productos', 'detalles', 'red', 'detalleskit')
        );
    }

    public function update(RedFormRequest $request, $id)
    {
        $validatedData = $request->validated();

        $red = Red::find($id);
        $red->carroceria_id = $validatedData['carroceria_id'];
        $red->nombre = $validatedData['nombre'];
        $red->modelo_id = $validatedData['modelo_id'];
        $red->status =  '0';
        if ($red->update()) {
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidad;
            $producto = $request->Lproduct;
            if ($producto !== null) {
                for ($i = 0; $i < count($producto); $i++) {
                    $detallered = new Detallered;
                    $detallered->cantidad = $cantidad[$i];
                    $detallered->unidad = $unidad[$i];
                    $detallered->red_id = $red->id;
                    $detallered->producto_id = $producto[$i];
                    $detallered->status = 0;
                    $detallered->save();
                }
            }
        }

        $this->crearhistorial('editar', $red->id, $red->nombre, null, 'reds');
        return redirect('admin/redes')->with('message', 'Registro Actualizado Satisfactoriamente');
    }

    //funcion para eliminar un registro
    public function destroy($id)
    {
        $red = Red::find($id);
        if ($red) {
            try {
                $red->delete();
                return "1";
            } catch (\Throwable $th) {
                $red->status = 1;
                if ($red->update()) {
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }

    public function destroydetallered($iddetalle)
    {
        try {
            $detallered = Detallered::find($iddetalle);
            $detallered->delete();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function modeloxcarroceria($id)
    {
        $modelos = DB::table('modelocarros as mc')
            ->join('reds as r', 'r.modelo_id', '=', 'mc.id')
            ->select('mc.id', 'mc.modelo', 'r.carroceria_id as idcarroceria')
            ->where('r.carroceria_id', '=', $id)
            ->get();
        return $modelos;
    }
}
