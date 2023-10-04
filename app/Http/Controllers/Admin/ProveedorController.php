<?php

namespace App\Http\Controllers\Admin;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteFormRequest;
use Illuminate\Support\Facades\DB; 
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;


class ProveedorController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-cliente|editar-cliente|crear-cliente|eliminar-cliente', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-cliente', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-cliente', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-cliente', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-cliente', ['only' => ['showrestore', 'restaurar']]);
    }

    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {  
        if ($request->ajax()) { 
            $proveedores = DB::table('proveedors as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.telefono',
                    'c.ruc',
                    'c.email',
                    'c.direccion',
                )->where('c.status', '=', 0);
            return DataTables::of($proveedores)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($proveedores) {
                    return view('admin.proveedores.botones', compact('proveedores'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.proveedores.index');
    }
    //vista crear
    public function create()
    {
        return view('admin.proveedores.create');
    }
    //funcion para guardar un registro
    public function store(ClienteFormRequest $request)
    {
        $validatedData = $request->validated();

        $proveedor = new Proveedor;
        $proveedor->nombre = $validatedData['nombre'];
        $proveedor->ruc = $validatedData['ruc'];
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->status = '0';
        $proveedor->save();
        $this->crearhistorial('crear', $proveedor->id, $proveedor->nombre, null, 'proveedores');
        return redirect('admin/proveedor')->with('message', 'Cliente Agregado Satisfactoriamente');
    }
    //vista editar
    public function edit(Proveedor $proveedor)
    {
        return view('admin.proveedores.edit', compact('proveedor'));
    }
    //vista para actualixar el registro
    public function update(ClienteFormRequest $request, $proveedor)
    {
        $validatedData = $request->validated();

        $proveedor = Proveedor::findOrFail($proveedor);

        $proveedor->nombre = $validatedData['nombre'];
        $proveedor->ruc = $validatedData['ruc'];
        $proveedor->direccion = $request->direccion;
        $proveedor->telefono = $request->telefono;
        $proveedor->email = $request->email;
        $proveedor->status =  '0';
        $proveedor->update();
        $this->crearhistorial('editar', $proveedor->id, $proveedor->nombre, null, 'proveedores');
        return redirect('admin/proveedor')->with('message', 'Cliente Actualizado Satisfactoriamente');
    }
    //funcion para mostrar el modal ver registro
    public function show($id)
    {
        $proveedor = DB::table('proveedors as c') 
            ->select('c.nombre', 'c.ruc', 'c.direccion', 'c.telefono', 'c.email')
            ->where('c.id', '=', $id)->first();

        return  $proveedor;
    }
    //funcion para eliminar el o solo ocultar el registro
    public function destroy(int $proveedor_id)
    {
        $proveedor = Proveedor::find($proveedor_id);

        if ($proveedor) {
            try {
                $proveedor->delete();
                $this->crearhistorial('eliminar', $proveedor->id, $proveedor->nombre, null, 'proveedores');
                return "1";
            } catch (\Throwable $th) {
                $proveedor->status = 1;
                $proveedor->update();
                $this->crearhistorial('eliminar', $proveedor->id, $proveedor->nombre, null, 'proveedores');
                return "1";
            }
        } else {
            return "2";
        }
    }
   
}
