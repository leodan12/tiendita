<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteFormRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Ingreso;
use App\Models\Inventario;
use App\Models\Cotizacion;
use App\Models\Venta;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;


class ClienteController extends Controller
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
        $datoseliminados = DB::table('clientes as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();

        if ($request->ajax()) {

            $clientes = DB::table('clientes as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.telefono',
                    'c.ruc',
                    'c.email',
                    'c.direccion',
                )->where('c.status', '=', 0);
            return DataTables::of($clientes)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($clientes) {
                    return view('admin.cliente.botones', compact('clientes'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.cliente.index', compact('datoseliminados'));
    }
    //vista crear
    public function create()
    {
        return view('admin.cliente.create');
    }
    //funcion para guardar un registro
    public function store(ClienteFormRequest $request)
    {
        $validatedData = $request->validated();

        $cliente = new Cliente;
        $cliente->nombre = $validatedData['nombre'];
        $cliente->ruc = $validatedData['ruc'];
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->status = '0';
        $cliente->save();
        $this->crearhistorial('crear', $cliente->id, $cliente->nombre, null, 'clientes');
        return redirect('admin/cliente')->with('message', 'Cliente Agregado Satisfactoriamente');
    }
    //vista editar
    public function edit(Cliente $cliente)
    {
        return view('admin.cliente.edit', compact('cliente'));
    }
    //vista para actualixar el registro
    public function update(ClienteFormRequest $request, $cliente)
    {
        $validatedData = $request->validated();

        $cliente = Cliente::findOrFail($cliente);

        $cliente->nombre = $validatedData['nombre'];
        $cliente->ruc = $validatedData['ruc'];
        $cliente->direccion = $request->direccion;
        $cliente->telefono = $request->telefono;
        $cliente->email = $request->email;
        $cliente->status =  '0';
        $cliente->update();
        $this->crearhistorial('editar', $cliente->id, $cliente->nombre, null, 'clientes');
        return redirect('admin/cliente')->with('message', 'Cliente Actualizado Satisfactoriamente');
    }
    //funcion para mostrar el modal ver registro
    public function show($id)
    {
        $cliente = DB::table('clientes as c')

            ->select('c.nombre', 'c.ruc', 'c.direccion', 'c.telefono', 'c.email')
            ->where('c.id', '=', $id)->first();

        return  $cliente;
    }
    //funcion para eliminar el o solo ocultar el registro
    public function destroy(int $cliente_id)
    {
        $cliente = Cliente::find($cliente_id);

        if ($cliente) {
            try {
                $cliente->delete();
                $this->crearhistorial('eliminar', $cliente->id, $cliente->nombre, null, 'clientes');
                return "1";
            } catch (\Throwable $th) {
                $cliente->status = 1;
                $cliente->update();
                $this->crearhistorial('eliminar', $cliente->id, $cliente->nombre, null, 'clientes');
                return "1";
            }
        } else {
            return "2";
        }
    }
    //funcion para ver los registros eliminados qu se pueden restaurar
    public function showrestore()
    {
        $empresas   = DB::table('clientes as c')
            ->where('c.status', '=', 1)
            ->select(
                'c.id',
                'c.nombre',
                'c.ruc',
                'c.telefono',
                'c.email',
                'c.direccion',
            )->get();


        return $empresas->values()->all();
    }
    //funcion para restaurar el registro eliminado
    public function restaurar($idregistro)
    {
        $registro = Cliente::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                $this->crearhistorial('restaurar', $registro->id, $registro->nombre, null, 'clientes');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
