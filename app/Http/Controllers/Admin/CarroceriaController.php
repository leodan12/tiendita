<?php

namespace App\Http\Controllers\Admin;

use App\Models\Carroceria;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Detallecarroceria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class CarroceriaController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-carroceria|editar-carroceria|crear-carroceria|eliminar-carroceria', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-carroceria', ['only' => ['addcarroceria']]);
        $this->middleware('permission:editar-carroceria', ['only' => ['updatecarroceria']]);
        $this->middleware('permission:eliminar-carroceria', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-carroceria', ['only' => ['showcarroceriarestore', 'restaurar']]);
    }

    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        $datoseliminados = DB::table('carrocerias as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();

        if ($request->ajax()) {
            $carrocerias = DB::table('carrocerias as c')
                ->select(
                    'c.id',
                    'c.tipocarroceria',
                )->where('c.status', '=', 0);
            return DataTables::of($carrocerias)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($carrocerias) {
                    return view('admin.carroceria.botones', compact('carrocerias'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.carroceria.index', compact('datoseliminados'));
    }
     
    //agregar modelo con ajax
    public function addcarroceria($tipocarroceria)
    {
        try {
            $carroceria = new Carroceria;
            $carroceria->tipocarroceria = $tipocarroceria;
            $carroceria->status = '0';
            $carroceria->save();
            $this->crearhistorial('crear', $carroceria->id, $carroceria->tipocarroceria, null, 'carrocerias');
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }
    //actualizar modelo con ajax
    public function updatecarroceria($id, $tipocarroceria)
    {
        try {
            $carroceria = Carroceria::find($id);
            $carroceria->tipocarroceria = $tipocarroceria;
            $carroceria->status = '0';
            $carroceria->update();
            $this->crearhistorial('editar', $carroceria->id, $carroceria->tipocarroceria, null, 'carrocerias');
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    //funcion para mostrar los datos de un registro
    public function show($id)
    {
        $carroceria = Carroceria::find($id);
        return  $carroceria;
    }
    
    //funcion para eliminar un registro
    public function destroy($id)
    {
        $carroceria = Carroceria::find($id);
        if ($carroceria) {
            try {
                $carroceria->delete();
                return "1";
            } catch (\Throwable $th) {
                $carroceria->status = 1;
                if ($carroceria->update()) {
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
    public function deletedetalle($id)
    {
        $detalle = Detallecarroceria::find($id);
        if ($detalle) {
            try {
                $detalle->delete();
                return "1";
            } catch (\Throwable $th) {
                $detalle->status = 1;
                if ($detalle->update()) {
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar los registros que se pueden restaurar
    public function showcarroceriarestore()
    {
        $carrocerias =  Carroceria::all()
            ->where('status', '=', 1);

        return $carrocerias->values()->all();
    }
    //funcion para restaurar el registro eliminado
    public function restaurar($idregistro)
    {
        $carroceria = Carroceria::find($idregistro);
        if ($carroceria) {
            $carroceria->status = 0;
            if ($carroceria->update()) {
                $this->crearhistorial('restaurar', $carroceria->id, $carroceria->nombre, null, 'carrocerias');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
