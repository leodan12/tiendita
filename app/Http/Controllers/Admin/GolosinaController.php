<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Golosina;
use App\Models\Talla;
use App\Models\Tipotela;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class GolosinaController extends Controller
{
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $golosinas = DB::table('golosinas as g') 
                ->select(
                    'g.id',
                    'g.nombre',
                    'g.precio',
                    'g.peso', 
                )->where('g.status', '=', 0);
            return DataTables::of($golosinas)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($golosinas) {
                    return view('admin.golosinas.botones', compact('golosinas'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.golosinas.index');
    }

    public function create()
    { 
        return view('admin.golosinas.create' );
    }

    public function store(Request $request)
    {
        $golosina = new Golosina;
        $golosina->nombre = $request->nombre;
        $golosina->peso = $request->peso;
        $golosina->precio = $request->precio;  
        $golosina->stock1 = 0;
        $golosina->stock2 = 0;
        $golosina->stockmin = 5;
        $golosina->save();
        $this->crearhistorial('crear', $golosina->id, $golosina->nombre, '', 'golosinas');
        return redirect('admin/golosinas')->with('message', 'Golosina Agregada Satisfactoriamente');
    }

    public function show($id)
    {
        $golosinas = DB::table('golosinas as u')
            ->select(
                'u.id',
                'u.nombre', 
                'u.precio',
                'u.peso',
                'u.stock1',
                'u.stock2',
                'u.stockmin',
            )->where('u.id', '=', $id)
            ->get();
        return $golosinas;
    }

    public function edit(string $id)
    {
        $golosina = Golosina::find($id); 
        return view('admin.golosinas.edit' ,compact('golosina'));
    }

    public function update(Request $request, string $id)
    {
        $golosina = Golosina::find($id);
        $golosina->nombre = $request->nombre;
        $golosina->peso = $request->peso;
        $golosina->precio = $request->precio; 
        $golosina->update();
        $this->crearhistorial('editar', $golosina->id, $golosina->nombre, '', 'golosinas');
        return redirect('admin/golosinas')->with('message', 'Golosina Actualizado Satisfactoriamente');
    }

    public function destroy($id)
    {
        try {
            $golosina = Golosina::find($id);
            if (!$golosina) {
                return "2";
            }
            $golosina->delete();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }
    //funcion para añadir dato extra
    public function addtalla($talla)
    {
        $registro =   Talla::find($talla);
        $registro1 =   Talla::where('talla', '=', $talla)->first();
        if (!$registro && !$registro1) {
            $reg = new Talla;
            $reg->talla = $talla;
            $reg->save();
            return "1";
        }
    }
    public function addtipotela($tipotela)
    {
        $tela =   Tipotela::find($tipotela);
        $tela1 =   Tipotela::where('tela', '=', $tipotela)->first();
        if (!$tela && !$tela1) {
            $reg = new Tipotela;
            $reg->tela = $tipotela;
            $reg->save();
            return "1";
        }
    }
    public function addcolor($color)
    {
        $micolor =   Color::find($color);
        $micolor1  =   Color::where('color', '=', $color)->first();
        if (!$micolor && !$micolor1) {
            $reg = new Color;
            $reg->color = $color;
            $reg->save();
            return "1";
        }
    }
    //inventario
    public function inventariogolosinas(Request $request)
    {
        if ($request->ajax()) {
            $registros = DB::table('golosinas as u') 
                ->select(
                    'u.id',
                    'u.nombre', 
                    'u.precio',
                    'u.peso',
                    'u.stock1',
                    'u.stock2',
                    'u.stockmin',
                )->where('u.status', '=', 0);
            return DataTables::of($registros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($registros) {
                    return view('admin.inventarios.botones', compact('registros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.inventarios.golosinas');
    }

    public function updatestock(Request $request)
    {
        try {
            $golosina =   Golosina::find($request->idproducto);
            $golosina->stock1 = $request->stock1;
            $golosina->stock2 = $request->stock2;
            $golosina->stockmin = $request->stockmin;
            $golosina->update();
            return "1";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function numerosinstock()
    {
        $productossinstock = 0;

        $productossinstock = DB::table('golosinas')
            ->whereRaw('stock1 + stock2 < stockmin')
            ->where('status', '=', '0')
            ->count();
        return $productossinstock;
    }
    public function inventariogolosinas2()
    {
        return redirect('admin/inventariogolosinas')->with('verstock', 'Ver');
    }
    public function showsinstock()
    {

        $golosinas = DB::table('golosinas as u') 
            ->select(
                'u.id',
                'u.nombre', 
                'u.peso',
                'u.precio',
                'u.stock1',
                'u.stock2',
                'u.stockmin',
            )->whereRaw('u.stock1 + u.stock2 < u.stockmin')
            ->where('u.status', '=', '0')
            ->get();
        return $golosinas;
    }
}
