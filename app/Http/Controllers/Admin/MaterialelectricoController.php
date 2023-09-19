<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Http\Requests\MaterialelectricoFormRequest;
use App\Models\Materialelectrico;
use App\Models\Carroceria;
use App\Models\Detallecarroceria;
use App\Models\Modelocarro;
use App\Models\Product;

class MaterialelectricoController extends Controller
{
    use HistorialTrait;

    public function index(Request $request)
    {
        $datoseliminados = DB::table('materialelectricos as me')
            ->where('me.status', '=', 1)
            ->select('me.id')
            ->count();
        if ($request->ajax()) {
            $materiales = DB::table('materialelectricos as me')
                ->join('carrocerias as c', 'me.carroceria_id', '=', 'c.id')
                ->join('modelocarros as mc', 'me.modelo_id', '=', 'mc.id')
                ->select(
                    'me.id',
                    'me.nombre',
                    'c.tipocarroceria as carroceria',
                    'mc.modelo',
                )->where('me.status', '=', 0);
            return DataTables::of($materiales)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($materiales) {
                    return view('admin.materialelectrico.botones', compact('materiales'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.materialelectrico.index', compact('datoseliminados'));
    }

    public function create()
    {
        $productos = Product::all()->where('status', '=', 0);
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        return view('admin.materialelectrico.create', compact('carrocerias', 'modelos', 'productos'));
    }

    public function store(MaterialelectricoFormRequest $request)
    {
        $validatedData = $request->validated();

        $materialelectrico = new Materialelectrico;
        $materialelectrico->carroceria_id = $validatedData['carroceria_id'];
        $materialelectrico->nombre = $validatedData['nombre'];
        $materialelectrico->modelo_id = $validatedData['modelo_id'];
        $materialelectrico->status =  '0';
        if ($materialelectrico->save()) {
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidad;
            $producto = $request->Lproduct;
            if ($producto !== null) {
                for ($i = 0; $i < count($producto); $i++) {
                    $detallematerial = new Detallecarroceria;
                    $detallematerial->cantidad = $cantidad[$i];
                    $detallematerial->unidad = $unidad[$i];
                    $detallematerial->materialelectrico_id = $materialelectrico->id;
                    $detallematerial->producto_id = $producto[$i];
                    $detallematerial->status = 0;
                    $detallematerial->save();
                }
            }
        }

        $this->crearhistorial('crear', $materialelectrico->id, $materialelectrico->nombre, null, 'materialelectricos');
        return redirect('admin/materialelectrico')->with('message', 'Registro Agregado Satisfactoriamente');
    }

    public function edit( $id)
    {
        $material = Materialelectrico::find($id);
        $productos = Product::all()->where('status', '=', 0);
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        $detalles = DB::table('materialelectricos as me')
            ->join('detallecarrocerias as dc', 'dc.materialelectrico_id', '=', 'me.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->where('me.id', '=', $id)
            ->select(
                'dc.id',
                'me.id as idmaterial',
                'dc.cantidad',
                'dc.unidad',
                'dc.producto_id',
                'p.nombre',
                'p.tipo',
                'p.id as idproducto'
            )
            ->get();
        $detalleskit = DB::table('materialelectricos as me')
            ->join('detallecarrocerias as dc', 'dc.materialelectrico_id', '=', 'me.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as dk', 'k.kitproduct_id', '=', 'dk.id')
            ->where('me.id', '=', $id)
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
            'admin.materialelectrico.edit',
            compact('carrocerias', 'modelos', 'productos', 'detalles', 'material', 'detalleskit')
        );
    }

    public function update(MaterialelectricoFormRequest $request, $id)
    {
        $validatedData = $request->validated();

        $materialelectrico = Materialelectrico::find($id);
        $materialelectrico->carroceria_id = $validatedData['carroceria_id'];
        $materialelectrico->nombre = $validatedData['nombre'];
        $materialelectrico->modelo_id = $validatedData['modelo_id'];
        $materialelectrico->status =  '0';
        if ($materialelectrico->update()) {
            $cantidad = $request->Lcantidad;
            $unidad = $request->Lunidad;
            $producto = $request->Lproduct;
            if ($producto !== null) {
                for ($i = 0; $i < count($producto); $i++) {
                    $detallematerial = new Detallecarroceria;
                    $detallematerial->cantidad = $cantidad[$i];
                    $detallematerial->unidad = $unidad[$i];
                    $detallematerial->materialelectrico_id = $materialelectrico->id;
                    $detallematerial->producto_id = $producto[$i];
                    $detallematerial->status = 0;
                    $detallematerial->save();
                }
            }
        }

        $this->crearhistorial('editar', $materialelectrico->id, $materialelectrico->nombre, null, 'materialelectricos');
        return redirect('admin/materialelectrico')->with('message', 'Registro Actualizado Satisfactoriamente');
    }

    //funcion para mostrar los datos de un registro
    public function show($id)
    {
        $carroceria = DB::table('materialelectricos as me')
            ->join('carrocerias as c', 'me.carroceria_id', '=', 'c.id')
            ->join('modelocarros as mc', 'me.modelo_id', '=', 'mc.id')
            ->join('detallecarrocerias as dc', 'dc.materialelectrico_id', '=', 'me.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->select(
                'p.nombre',
                'c.tipocarroceria',
                'mc.modelo',
                'dc.cantidad',
                'dc.unidad',
                'c.id',
                'p.tipo',
                'p.id as idproducto',
                'me.nombre as nombrematerial'
            )
            ->where('me.id', '=', $id)
            ->get();
        return  $carroceria;
    }

    //funcion para eliminar un registro
    public function destroy($id)
    {
        $material = Materialelectrico::find($id);
        if ($material) {
            try {
                $material->delete();
                return "1";
            } catch (\Throwable $th) {
                $material->status = 1;
                if ($material->update()) {
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }

    //funcion para eliminar un detalle de un rergistro
    public function destroydetallematerial($id)
    {
        $detalle = Detallecarroceria::find($id);
        if ($detalle) {
            try {
                $detalle->delete();
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }

    public function modeloxcarroceria($id)
    {
        $modelos = DB::table('modelocarros as mc')
            ->join('materialelectricos as me', 'me.modelo_id', '=', 'mc.id')
            ->select('mc.id', 'mc.modelo', 'me.carroceria_id as idcarroceria')
            ->where('me.carroceria_id', '=', $id)
            ->get();
        return $modelos;
    }
}
