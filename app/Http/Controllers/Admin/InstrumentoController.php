<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Tienda;
use App\Models\Instrumento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class InstrumentoController extends Controller
{   
    function __construct()
    {
        $this->middleware('permission:ver-instrumento|editar-instrumento|crear-instrumento|eliminar-instrumento', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-instrumento', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-instrumento', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-instrumento', ['only' => ['destroy']]); 
    }

    use HistorialTrait;
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $instrumentos = DB::table('instrumentos as i')
                ->join('marcas as m', 'i.marca_id', '=', 'm.id')
                ->join('modelos as mo', 'i.modelo_id', '=', 'mo.id')
                ->select(
                    'i.id',
                    'i.nombre',
                    'm.marca',
                    'mo.modelo',
                    'i.garantia',
                    'i.precio',
                )->where('i.status', '=', 0);
            return DataTables::of($instrumentos)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($instrumentos) {
                    return view('admin.instrumentos.botones', compact('instrumentos'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.instrumentos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $marcas = Marca::all()->where('status', '=', 0);
        $modelos = Modelo::all()->where('status', '=', 0);
        return view('admin.instrumentos.create', compact('marcas', 'modelos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $instrumento = new Instrumento;
        $instrumento->nombre = $request->nombre;
        $instrumento->precio = $request->precio;
        $instrumento->garantia = $request->garantia;
        $marca = Marca::find($request->marca);
        if (!$marca) {
            $marca = Marca::where('marca', '=', $request->marca)->first();
        }
        $modelo = Modelo::find($request->modelo);
        if (!$modelo) {
            $modelo = Modelo::where('modelo', '=', $request->modelo)->first();
        }
        $instrumento->marca_id = $marca->id;
        $instrumento->modelo_id = $modelo->id;
        $instrumento->stock1 = 0;
        $instrumento->stock2 = 0;
        $instrumento->stock3 = 0;
        $instrumento->stockmin = 5;
        $instrumento->save();
        $this->crearhistorial('crear', $instrumento->id, $instrumento->nombre, '', 'instrumentos');
        return redirect('admin/instrumentos')->with('message', 'Instrumento Agregado Satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instrumentos = DB::table('instrumentos as i')
            ->join('marcas as m', 'i.marca_id', '=', 'm.id')
            ->join('modeloS as mo', 'i.modelo_id', '=', 'mo.id')
            ->select(
                'i.id',
                'i.nombre',
                'm.marca',
                'mo.modelo',
                'i.garantia',
                'i.precio',
                'i.stock1',
                'i.stock2',
                'i.stock3',
                'i.stockmin',
            )->where('i.id', '=', $id)
            ->get();
        return $instrumentos;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $instrumento = Instrumento::find($id);
        $marcas = Marca::all()->where('status', '=', 0);
        $modelos = Modelo::all()->where('status', '=', 0);
        return view('admin.instrumentos.edit', compact('marcas', 'modelos','instrumento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $instrumento = instrumento::find($id);
        $instrumento->nombre = $request->nombre;
        $instrumento->garantia = $request->garantia;
        $instrumento->precio = $request->precio;
        $instrumento->marca_id = $request->marca;
        $instrumento->modelo_id = $request->modelo;
        $instrumento->update();
        $this->crearhistorial('editar', $instrumento->id, $instrumento->nombre, '', 'instrumentos');
        return redirect('admin/instrumentos')->with('message', 'Instrumento Actualizado Satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $instrumento = Instrumento::find($id);
            if (!$instrumento) {
                return "2";
            }
            $instrumento->delete();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }
    //funcion para añadir dato extra
    public function addmarca($marca)
    {
        $mimarca =   Marca::find($marca);
        $mimarca1 =   Marca::where('marca', '=', $marca)->first();
        if (!$mimarca && !$mimarca1) {
            $reg = new Marca;
            $reg->marca = $marca;
            $reg->save();
            return "1";
        }
    }
    public function addmodelo($modelo)
    {
        $mimodelo =   Modelo::find($modelo);
        $mimodelo1  =   Modelo::where('modelo', '=', $modelo)->first();
        if (!$mimodelo && !$mimodelo1) {
            $reg = new Modelo();
            $reg->modelo = $modelo;
            $reg->save();
            return "1";
        }
    }

    //inventario
    public function inventarioinstrumentos(Request $request)
    {
        $tiendas = Tienda::all();
        if ($request->ajax()) {
            $registros = DB::table('instrumentos as i')
                ->join('marcas as m', 'i.marca_id', '=', 'm.id')
                ->join('modelos as mo', 'i.modelo_id', '=', 'mo.id')
                ->select(
                    'i.id',
                    'i.nombre',
                    'm.marca',
                    'mo.modelo',
                    'i.garantia',
                    'i.precio',
                    'i.stock1',
                    'i.stock2',
                    'i.stock3',
                    'i.stockmin',
                )->where('i.status', '=', 0);
            return DataTables::of($registros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($registros) {
                    return view('admin.inventarios.botones', compact('registros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.inventarios.instrumentos',compact('tiendas'));
    }

    public function updatestock(Request $request)
    {
        try {
            $instrumento =   Instrumento::find($request->idproducto);
            $instrumento->stock1 = $request->stock1;
            $instrumento->stock2 = $request->stock2;
            $instrumento->stock3 = $request->stock3;
            $instrumento->stockmin = $request->stockmin;
            $instrumento->update();
            return "1";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function numerosinstock()
    {
        $productossinstock = 0;

        $productossinstock = DB::table('instrumentos as i')
            ->whereRaw('i.stock1 < i.stockmin OR i.stock2 < i.stockmin OR i.stock3 < i.stockmin')
            ->where('i.status', '=', '0')
            ->count();
        return $productossinstock;
    }

    public function inventarioinstrumentos2()
    {
        return redirect('admin/inventarioit')->with('verstock', 'Ver');
    }

    public function showsinstock()
    {

        $instrumentos = DB::table('instrumentos as i')
            ->join('marcas as m', 'i.marca_id', '=', 'm.id')
            ->join('modelos as mo', 'i.modelo_id', '=', 'mo.id')
            ->select(
                'i.id',
                'i.nombre',
                'm.marca',
                'mo.modelo',
                'i.garantia',
                'i.precio',
                'i.stock1',
                'i.stock2',
                'i.stock3',
                'i.stockmin',
            )->whereRaw('i.stock1 < i.stockmin OR i.stock2 < i.stockmin OR i.stock3 < i.stockmin')
            ->where('i.status', '=', '0')
            ->get();
        return $instrumentos;
    }
}
