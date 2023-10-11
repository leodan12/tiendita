<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Snack;
use App\Models\Marcasnack;
use App\Models\Saborsnack;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Models\Tienda;

class SnackController extends Controller
{   use HistorialTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $snacks = DB::table('snacks as s')
                ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
                ->join('saborsnacks as ss', 's.saborsnack_id', '=', 'ss.id')
                ->select(
                    's.id',
                    's.nombre',
                    's.tamanio',
                    's.fechavencimiento',
                    'ms.marcasnack',
                    'ss.saborsnack',
                    's.precio',
                )->where('s.status', '=', 0);
            return DataTables::of($snacks)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($snacks) {
                    return view('admin.snacks.botones', compact('snacks'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.snacks.index');
    }

    public function create()
    {
        $marcasnacks = Marcasnack::all()->where('status', '=', 0);
        $saborsnacks = Saborsnack::all()->where('status', '=', 0);
        return view('admin.snacks.create', compact('marcasnacks','saborsnacks'));
    }

    public function store(Request $request)
    {
        $snack = new Snack;
        $snack->nombre = $request->nombre;
        $snack->precio = $request->precio;
        $snack->tamanio = $request->tamanio;
        $snack->fechavencimiento = $request->fechavencimiento;
        $snack->fechavencimiento2 = $request->fechavencimiento;
        $marcasnack = Marcasnack::find($request->marcasnack);
        if (!$marcasnack) {
            $marcasnack = Marcasnack::where('marcasnack', '=', $request->marcasnack)->first();
        }
        $saborsnack = Saborsnack::find($request->saborsnack);
        if (!$saborsnack) {
            $saborsnack = Saborsnack::where('saborsnack', '=', $request->saborsnack)->first();
        }
        $snack->marcasnack_id = $marcasnack->id;
        $snack->saborsnack_id = $saborsnack->id;
        $snack->stock1 = 0;
        $snack->stock2 = 0;
        $snack->stock3 = 0;
        $snack->stockmin = 5;
        $snack->save();
        $this->crearhistorial('crear', $snack->id, $snack->nombre, '', 'snacks');
        return redirect('admin/snacks')->with('message', 'Snack Agregado Satisfactoriamente');
    }

    public function show(string $id)
    {
        $snacks = DB::table('snacks as s')
            ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
            ->join('saborsnacks as ss', 's.saborsnack_id', '=', 'ss.id')
            ->select(
                's.id',
                's.nombre',
                'ms.marcasnack',
                'ss.saborsnack',
                's.precio',
                's.tamanio',
                's.fechavencimiento',
                's.stock1',
                's.stock2',
                's.stock3',
                's.stockmin',
            )->where('s.id', '=', $id)
            ->get();
        return $snacks;
    }

    public function edit(string $id)
    {
        $snack = Snack::find($id);
        $marcasnacks = Marcasnack::all()->where('status', '=', 0);
        $saborsnacks = Saborsnack::all()->where('status', '=', 0);
        return view('admin.snacks.edit', compact('marcasnacks','saborsnacks', 'snack'));
    }

    public function update(Request $request, string $id)
    {
        $snack = Snack::find($id);
        $snack->nombre = $request->nombre;
        $snack->precio = $request->precio;
        $snack->tamanio = $request->tamanio;
        $snack->fechavencimiento = $request->fechavencimiento;
        $snack->marcasnack_id = $request->marcasnack;
        $snack->saborsnack_id = $request->saborsnack;
        $snack->update();
        $this->crearhistorial('editar', $snack->id, $snack->nombre, '', 'snacks');
        return redirect('admin/snacks')->with('message', 'Util Actualizado Satisfactoriamente');
    }

    public function destroy(string $id)
    {
         try {
            $snack = Snack::find($id);
            if (!$snack) {
                return "2";
            }
            $snack->delete();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function addmarcasnack($marcasnack)
    {
        $registro =   Marcasnack::find($marcasnack);
        $registro1 =   Marcasnack::where('marcasnack', '=', $marcasnack)->first();
        if (!$registro && !$registro1) {
            $reg = new Marcasnack();
            $reg->marcasnack = $marcasnack;
            $reg->save();
            return "1";
        }
    }

    public function addsaborsnack($saborsnack)
    {
        $micolor =   Saborsnack::find($saborsnack);
        $micolor1  =   Saborsnack::where('saborsnack', '=', $saborsnack)->first();
        if (!$micolor && !$micolor1) {
            $reg = new Saborsnack();
            $reg->saborsnack = $saborsnack;
            $reg->save();
            return "1";
        }
    }

    public function inventariosnacks(Request $request)
    { $tiendas = Tienda::all();
        if ($request->ajax()) {
            $registros = DB::table('snacks as s')
            ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
            ->join('saborsnacks as ss', 's.saborsnack_id', '=', 'ss.id')
            ->select(
                's.id',
                's.nombre',
                's.tamanio',
                's.fechavencimiento',
                'ms.marcasnack',
                'ss.saborsnack',
                's.precio',
                's.stock1',
                's.stock2',
                's.stock3',
                's.stockmin',
                )->where('s.status', '=', 0);
            return DataTables::of($registros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($registros) {
                    return view('admin.inventarios.botones', compact('registros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.inventarios.snacks',compact('tiendas'));
    }

    public function updatestock(Request $request)
    {
        try {
            $snack =   Snack::find($request->idproducto);
            $snack->stock1 = $request->stock1;
            $snack->stock2 = $request->stock2;
            $snack->stock3 = $request->stock3;
            $snack->stockmin = $request->stockmin;
            $snack->update();
            return "1";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function numerosinstock()
    {
        $productossinstock = 0; 
        $productossinstock = DB::table('snacks as s')
            ->whereRaw('s.stock1 < s.stockmin OR s.stock2 < s.stockmin OR s.stock3 < s.stockmin')
            ->where('s.status', '=', '0')
            ->count();
        return $productossinstock;
    }
    public function inventariosnacks2()
    {
        return redirect('admin/inventariosnacks')->with('verstock', 'Ver');
    }

    public function showsinstock()
    {

        $snacks = DB::table('snacks as s')
            ->join('marcasnacks as ms', 's.marcasnack_id', '=', 'ms.id')
            ->join('saborsnacks as ss', 's.saborsnack_id', '=', 'ss.id')
            ->select(
                's.id',
                's.nombre',
                'ms.marcasnack',
                'ss.saborsnack',
                's.precio',
                's.tamanio',
                's.fechavencimiento',
                's.stock1',
                's.stock2',
                's.stock3',
                's.stockmin',
            )->whereRaw('s.stock1 < s.stockmin OR s.stock2 < s.stockmin OR s.stock3 < s.stockmin')
            ->where('s.status', '=', '0')
            ->get();
        return $snacks;
    }
}
