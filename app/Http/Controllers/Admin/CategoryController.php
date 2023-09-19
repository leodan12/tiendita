<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class CategoryController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-categoria|editar-categoria|crear-categoria|eliminar-categoria', ['only' => ['index']]);
        $this->middleware('permission:crear-categoria', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-categoria', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-categoria', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-categoria', ['only' => ['showcategoryrestore', 'restaurar']]);
    }

    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        $datoseliminados = DB::table('categories as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();
        if ($request->ajax()) {
            $categorias = DB::table('categories as c')
                ->select(
                    'c.id',
                    'c.nombre',
                )->where('c.status', '=', 0);
            return DataTables::of($categorias)
                ->addColumn('accion', 'Accion')
                ->editColumn('accion', function ($categorias) {
                    return view('admin.category.botones', compact('categorias'));
                })
                ->rawColumns(['accion'])
                ->make(true);
        }

        return view('admin.category.index', compact('datoseliminados'));
    }

    //agregar modelo con ajax
    public function addcategoria($nombre)
    {
        try {
            $categoria = new Category;
            $categoria->nombre = $nombre;
            $categoria->status = '0';
            $categoria->save();
            $this->crearhistorial('crear', $categoria->id, $categoria->nombre, null, 'categories');
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }
    //actualizar modelo con ajax
    public function updatecategoria($id, $nombre)
    {
        try {
            $categoria = Category::find($id);
            $categoria->nombre = $nombre;
            $categoria->status = '0';
            $categoria->update();
            $this->crearhistorial('editar', $categoria->id, $categoria->nombre, null, 'categories');
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    //funcion para eliminar o solo ocultar un registro 
    public function destroy(int $idcategoria)
    {
        $category = Category::find($idcategoria);
        if ($category) {

            try {
                $category->delete();
                $this->crearhistorial('eliminar', $category->id, $category->nombre, null, 'categorias');
                return "1";
            } catch (\Throwable $th) {
                $category->status = 1;
                $category->update();
                $this->crearhistorial('eliminar', $category->id, $category->nombre, null, 'categorias');
                return "1";
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar los registros eliminados que se pueden restaurar
    public function showcategoryrestore()
    {
        $categorias =  Category::all()
            ->where('status', '=', 1);

        return $categorias->values()->all();
    }
    //funcion para restaurar el registro eliminado
    public function restaurar($idregistro)
    {
        $categoria = Category::find($idregistro);
        if ($categoria) {
            $categoria->status = 0;
            if ($categoria->update()) {
                $this->crearhistorial('restaurar', $categoria->id, $categoria->nombre, null, 'categorias');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
