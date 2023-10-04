<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tienda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use Illuminate\Support\Facades\DB; 
use Yajra\DataTables\DataTables; 
use App\Traits\HistorialTrait;

class TiendaController extends Controller
{
    //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-empresa|editar-empresa|crear-empresa|eliminar-empresa', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-empresa', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-empresa', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-empresa', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-empresa', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    { 
        if ($request->ajax()) {
            $tiendas = DB::table('tiendas as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.ubicacion',
                    'c.encargado',
                )->where('c.status', '=', 0);
            return DataTables::of($tiendas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($tiendas) {
                    return view('admin.tiendas.botones', compact('tiendas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.tiendas.index' );
    }
    //vista crear
    public function create()
    {
        return view('admin.tiendas.create');
    }
    //funcion para guardar un registro
    public function store(Request $request)
    {
        $tienda = new Tienda;
        $tienda->nombre = $request->nombre;
        $tienda->ubicacion = $request->ubicacion;
        $tienda->encargado = $request->encargado;
        $tienda->save();
 
        $this->crearhistorial('crear', $tienda->id, $tienda->nombre, null, 'empresas');
        return redirect('admin/tienda')->with('message', 'Tienda Agregada Satisfactoriamente');
    }

    
    //vista editar
    public function edit(Tienda $tienda)
    {
        return view('admin.tiendas.edit', compact('tienda'));
    }
    //funcion para actualizar un registro
    public function update(Request $request, $tienda)
    { 
        $tienda = Tienda::findOrFail($tienda);
        $tienda->nombre = $request->nombre;
        $tienda->ubicacion =  $request->ubicacion;
        $tienda->encargado =  $request->encargado;
        $tienda->update();
        $this->crearhistorial('editar', $tienda->id, $tienda->nombre, null, 'tiendas');
        return redirect('admin/tienda')->with('message', 'Tienda Actualizado Satisfactoriamente');
    }
    //funcion para mostrar el modeal ver registro
    public function show($id)
    {
        $tienda = DB::table('tiendas as c')
            ->select(
                'c.nombre',
                'c.ubicacion',
                'c.encargado' 
            )
            ->where('c.id', '=', $id)->first();

        return  $tienda;
    }
    //funcion para eliminar o solo ocultar registro
    public function destroy(int $idempresa)
    {
        $tienda = Tienda::find($idempresa);
        if ($tienda) {
            try {
                $tienda->delete();
                $this->crearhistorial('eliminar', $tienda->id, $tienda->nombre, null, 'tiendas');
                return "1";
            } catch (\Throwable $th) {
                $tienda->status = 1;
                $tienda->update();
                $this->crearhistorial('eliminar', $tienda->id, $tienda->nombre, null, 'tiendas');
                return "1";
            }
        } else {
            return "2";
        }
    } 
}
