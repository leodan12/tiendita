<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyFormRequest;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use App\Models\Ingreso;
use App\Models\Venta;
use App\Models\Detalleinventario;
use App\Models\Cotizacion;
use App\Traits\HistorialTrait;

class CompanyController extends Controller
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
        $datoseliminados = DB::table('companies as c')
            ->where('c.status', '=', 1)
            ->select('c.id')
            ->count();
        if ($request->ajax()) {
            $empresas = DB::table('companies as c')
                ->select(
                    'c.id',
                    'c.nombre',
                    'c.ruc',
                    'c.telefono',
                )->where('c.status', '=', 0);
            return DataTables::of($empresas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($empresas) {
                    return view('admin.company.botones', compact('empresas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.company.index', compact('datoseliminados'));
    }
    //vista crear
    public function create()
    {
        return view('admin.company.create');
    }
    //funcion para guardar un registro
    public function store(CompanyFormRequest $request)
    {
        $validatedData = $request->validated();
        $company = new Company;
        $company->nombre = $validatedData['nombre'];
        $company->ruc = $validatedData['ruc'];
        $company->direccion = $request->direccion;
        $company->telefono = $request->telefono;
        $company->email = $request->email;
        $company->tipocuentasoles = $request->tipocuentasoles;
        $company->numerocuentasoles = $request->numerocuentasoles;
        $company->ccisoles = $request->ccisoles;
        $company->tipocuentadolares = $request->tipocuentadolares;
        $company->numerocuentadolares = $request->numerocuentadolares;
        $company->ccidolares = $request->ccidolares;
        $company->status = '0';

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $imagen = $request->file('logo');
            $nombreimagen = "logo" . Str::slug($validatedData['nombre']) . "." . $imagen->guessExtension();
            $ruta = public_path("logos");
            if ($imagen->move($ruta, $nombreimagen)) {
                $company->logo = $nombreimagen;
            }
        }
        $company->save();

        $this->sininventario($company->id);

        $this->crearhistorial('crear', $company->id, $company->nombre, null, 'empresas');
        return redirect('admin/company')->with('message', 'Compañia Agregada Satisfactoriamente');
    }

    public function sininventario($idcompany)
    {
        $companyId = $idcompany; // ID de la compañía específica

        $inventarios = DB::table('inventarios as i')
            ->leftJoin('detalleinventarios as di', function ($join) use ($companyId) {
                $join->on('i.id', '=', 'di.inventario_id')
                    ->where('di.company_id', '=', $companyId);
            })
            ->whereNull('di.id')
            ->select('i.product_id', 'i.stocktotal', 'di.company_id', 'i.id as idinventario')
            ->get();
 
        foreach ($inventarios as $inventario) {
            $detalle = new Detalleinventario();
            $detalle->company_id = $idcompany;
            $detalle->inventario_id = $inventario->idinventario;
            $detalle->stockempresa = 0;
            $detalle->save();
        }
        //return $inventories;
    }
    //vista editar
    public function edit(Company $company)
    {
        return view('admin.company.edit', compact('company'));
    }
    //funcion para actualizar un registro
    public function update(CompanyFormRequest $request, $company)
    {
        $validatedData = $request->validated();
        $company = Company::findOrFail($company);
        $company->nombre = $validatedData['nombre'];
        $company->ruc = $validatedData['ruc'];
        $company->direccion = $request->direccion;
        $company->telefono = $request->telefono;
        $company->email = $request->email;
        $company->tipocuentasoles = $request->tipocuentasoles;
        $company->numerocuentasoles = $request->numerocuentasoles;
        $company->ccisoles = $request->ccisoles;
        $company->tipocuentadolares = $request->tipocuentadolares;
        $company->numerocuentadolares = $request->numerocuentadolares;
        $company->ccidolares = $request->ccidolares;

        $company->status =  '0';
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $imagen = $request->file('logo');
            $path = public_path('logos/' . $company->logo);
            if (File::exists($path)) {
                File::delete($path);
            }
            $nombreimagen = "logo" . Str::slug($validatedData['nombre']) . "." . $imagen->guessExtension();
            $ruta = public_path("logos");
            if ($imagen->move($ruta, $nombreimagen)) {
                $company->logo = $nombreimagen;
            }
        }
        $company->update();
        $this->crearhistorial('editar', $company->id, $company->nombre, null, 'empresas');
        return redirect('admin/company')->with('message', 'Compañia Actualizado Satisfactoriamente');
    }
    //funcion para mostrar el modeal ver registro
    public function show($id)
    {
        $company = DB::table('companies as c')
            ->select(
                'c.nombre',
                'c.ruc',
                'c.direccion',
                'c.telefono',
                'c.email',
                'c.logo',
                'c.tipocuentasoles',
                'c.numerocuentasoles',
                'c.ccisoles',
                'c.tipocuentadolares',
                'c.numerocuentadolares',
                'c.ccidolares'
            )
            ->where('c.id', '=', $id)->first();

        return  $company;
    }
    //funcion para eliminar o solo ocultar registro
    public function destroy(int $idempresa)
    {
        $company = Company::find($idempresa);
        if ($company) {
            $company2 = $company;
            try {
                $company->delete();
                $path = public_path('logos/' . $company2->logo);
                if (File::exists($path)) {
                    File::delete($path);
                }
                $this->crearhistorial('eliminar', $company->id, $company->nombre, null, 'empresas');
                return "1";
            } catch (\Throwable $th) {
                $company->status = 1;
                $company->update();
                $this->crearhistorial('eliminar', $company->id, $company->nombre, null, 'empresas');
                return "1";
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar el registro eliminado
    public function showrestore()
    {
        $empresas   = DB::table('companies as c')
            ->where('c.status', '=', 1)
            ->select(
                'c.id',
                'c.nombre',
                'c.ruc',
                'c.telefono',
            )->get();
        return $empresas;
    }
    //funcion para restaurar un registro eliminado
    public function restaurar($idregistro)
    {
        $registro = Company::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                $this->crearhistorial('restaurar', $registro->id, $registro->nombre, null, 'empresas');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
