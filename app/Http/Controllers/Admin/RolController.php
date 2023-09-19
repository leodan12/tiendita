<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Traits\HistorialTrait;
use Yajra\DataTables\DataTables;

class RolController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-rol|editar-rol|crear-rol|eliminar-rol', ['only' => ['index']]);
        $this->middleware('permission:crear-rol', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-rol', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-rol', ['only' => ['destroy']]);
    }
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $roles = DB::table('roles as r')
                ->select(
                    'r.id',
                    'r.name',
                );
            return DataTables::of($roles)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($roles) {
                    return view('admin.roles.botones', compact('roles'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.roles.index');
    }
    //vista crear
    public function create()
    {
        $permisos = Permission::get();
        return view('admin.roles.create', compact('permisos'));
    }
    //funcion para guardar un registro de rol
    public function store(Request $request)
    {

        $this->validate($request, ['name' => 'required', 'permission' => 'required']);
        $role = Role::create(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permission'));
        $this->crearhistorial('crear', $role->id, $role->name, null, 'roles');
        return redirect()->route('rol.index');
    }
    //vista editar
    public function edit($id)
    {
        $role = Role::find($id);
        $permisos = Permission::get();
        $rolePermission = DB::table('role_has_permissions as rhp')
            ->where('rhp.role_id', '=', $id)
            //->pluck('rhp.permission_id', 'rhp.permission_id')
            ->select('rhp.permission_id')
            ->get();
        //return $rolePermission ;
        return view('admin.roles.edit', compact('role', 'permisos', 'rolePermission'));
    }
    //funcion para actualizar un registro de rol
    public function update(Request $request, $id)
    {
        $this->validate($request, ['name' => 'required', 'permission' => 'required']);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));
        $this->crearhistorial('editar', $role->id, $role->name, null, 'roles');
        return redirect()->route('rol.index');
    }
    //funcion para eliminar un rol
    public function destroy($id)
    {
        $role = Role::find($id);
        if ($role) {
            try {
                $role->delete();
                $this->crearhistorial('eliminar', $role->id, $role->name, null, 'roles');
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
