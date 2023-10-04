<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Utile;
use App\Models\Marcautil;
use App\Models\Colorutil;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class UtilController extends Controller
{   use HistorialTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $utiles = DB::table('utiles as ut')
                ->join('marcautils as mu', 'ut.marcautil_id', '=', 'mu.id')
                ->join('colorutils as cu', 'ut.colorutil_id', '=', 'cu.id')
                ->select(
                    'ut.id',
                    'ut.nombre',
                    'mu.marcautil',
                    'cu.colorutil',
                    'ut.precio',
                )->where('ut.status', '=', 0);
            return DataTables::of($utiles)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($utiles) {
                    return view('admin.utiles.botones', compact('utiles'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.utiles.index');
    }

    public function create()
    {
        $marcautils = Marcautil::all()->where('status', '=', 0);
        $colorutils = Colorutil::all()->where('status', '=', 0);
        return view('admin.utiles.create', compact('marcautils','colorutils'));
    }

    public function store(Request $request)
    {
        $utile = new Utile;
        $utile->nombre = $request->nombre;
        $utile->precio = $request->precio;
        $marcautil = Marcautil::find($request->marcautil);
        if (!$marcautil) {
            $marcautil = Marcautil::where('marcautil', '=', $request->marcautil)->first();
        }
        $colorutil = Colorutil::find($request->colorutil);
        if (!$colorutil) {
            $colorutil = Colorutil::where('colorutil', '=', $request->colorutil)->first();
        }
        $utile->marcautil_id = $marcautil->id;
        $utile->colorutil_id = $colorutil->id;
        $utile->stock1 = 0;
        $utile->stock2 = 0;
        $utile->stockmin = 5;
        $utile->save();
        $this->crearhistorial('crear', $utile->id, $utile->nombre, '', 'utiles');
        return redirect('admin/utiles')->with('message', 'utile Agregado Satisfactoriamente');
    }

    public function show(string $id)
    {
        $utiles = DB::table('utiles as ut')
            ->join('marcautils as mu', 'ut.marcautil_id', '=', 'mu.id')
            ->join('colorutils as cu', 'ut.colorutil_id', '=', 'cu.id')
            ->select(
                'ut.id',
                'ut.nombre',
                'mu.marcautil',
                'cu.colorutil',
                'ut.precio',
                'ut.stock1',
                'ut.stock2',
                'ut.stockmin',
            )->where('ut.id', '=', $id)
            ->get();
        return $utiles;
    }

    public function edit(string $id)
    {
        $utile = Utile::find($id);
        $marcautils = Marcautil::all()->where('status', '=', 0);
        $colorutils = Colorutil::all()->where('status', '=', 0);
        return view('admin.utiles.edit', compact('marcautils','colorutils', 'utile'));
    }

    public function update(Request $request, string $id)
    {
        $utile = Utile::find($id);
        $utile->nombre = $request->nombre;
        $utile->precio = $request->precio;
        $utile->marcautil_id = $request->marcautil;
        $utile->colorutil_id = $request->colorutil;
        $utile->update();
        $this->crearhistorial('editar', $utile->id, $utile->nombre, '', 'utiles');
        return redirect('admin/utiles')->with('message', 'Util Actualizado Satisfactoriamente');
    }

    public function destroy(string $id)
    {
         try {
            $utile = Utile::find($id);
            if (!$utile) {
                return "2";
            }
            $utile->delete();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function addmarcautil($marcautil)
    {
        $registro =   Marcautil::find($marcautil);
        $registro1 =   Marcautil::where('marcautil', '=', $marcautil)->first();
        if (!$registro && !$registro1) {
            $reg = new Marcautil();
            $reg->marcautil = $marcautil;
            $reg->save();
            return "1";
        }
    }

    public function addcolorutil($colorutil)
    {
        $micolor =   Colorutil::find($colorutil);
        $micolor1  =   Colorutil::where('colorutil', '=', $colorutil)->first();
        if (!$micolor && !$micolor1) {
            $reg = new Colorutil();
            $reg->colorutil = $colorutil;
            $reg->save();
            return "1";
        }
    }

    public function inventarioutiles(Request $request)
    {
        if ($request->ajax()) {
            $registros = DB::table('utiles as ut')
            ->join('marcautils as mu', 'ut.marcautil_id', '=', 'mu.id')
            ->join('colorutils as cu', 'ut.colorutil_id', '=', 'cu.id')
            ->select(
                'ut.id',
                'ut.nombre',
                'mu.marcautil',
                'cu.colorutil',
                'ut.precio',
                'ut.stock1',
                'ut.stock2',
                'ut.stockmin',
                )->where('ut.status', '=', 0);
            return DataTables::of($registros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($registros) {
                    return view('admin.inventarios.botones', compact('registros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.inventarios.utiles');
    }

    public function updatestock(Request $request)
    {
        try {
            $utile =   Utile::find($request->idproducto);
            $utile->stock1 = $request->stock1;
            $utile->stock2 = $request->stock2;
            $utile->stockmin = $request->stockmin;
            $utile->update();
            return "1";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function numerosinstock()
    {
        $productossinstock = 0;

        $productossinstock = DB::table('utiles')
            ->whereRaw('stock1 + stock2 < stockmin')
            ->where('status', '=', '0')
            ->count();
        return $productossinstock;
    }
    public function inventarioutiles2()
    {
        return redirect('admin/inventarioutiles')->with('verstock', 'Ver');
    }

    public function showsinstock()
    {

        $utiles = DB::table('utiles as ut')
            ->join('marcautils as mu', 'ut.marcautil_id', '=', 'mu.id')
            ->join('colorutils as cu', 'ut.colorutil_id', '=', 'cu.id')
            ->select(
                'ut.id',
                'ut.nombre',
                'mu.marcautil',
                'cu.colorutil',
                'ut.precio',
                'ut.stock1',
                'ut.stock2',
                'ut.stockmin',
            )->whereRaw('ut.stock1 + ut.stock2 < ut.stockmin')
            ->where('ut.status', '=', '0')
            ->get();
        return $utiles;
    }
}
