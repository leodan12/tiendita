<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Uniforme;
use App\Models\Talla;
use App\Models\Tipotela;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Models\Tienda;

class UniformeController extends Controller
{
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $uniformes = DB::table('uniformes as u')
                ->join('tallas as t', 'u.talla_id', '=', 't.id')
                ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
                ->join('colors as c', 'u.color_id', '=', 'c.id')
                ->select(
                    'u.id',
                    'u.nombre',
                    't.talla',
                    'tt.tela',
                    'c.color',
                    'u.precio',
                    'u.genero',
                )->where('u.status', '=', 0);
            return DataTables::of($uniformes)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($uniformes) {
                    return view('admin.uniformes.botones', compact('uniformes'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.uniformes.index');
    }

    public function create()
    {
        $tallas = Talla::all()->where('status', '=', 0);
        $tipotelas = Tipotela::all()->where('status', '=', 0);
        $colores = Color::all()->where('status', '=', 0);
        return view('admin.uniformes.create', compact('tallas', 'tipotelas', 'colores'));
    }

    public function store(Request $request)
    {
        $uniforme = new Uniforme;
        $uniforme->nombre = $request->nombre;
        $uniforme->genero = $request->genero;
        $uniforme->precio = $request->precio;
        $talla = Talla::find($request->talla);
        if (!$talla) {
            $talla = Talla::where('talla', '=', $request->talla)->first();
        }
        $tipotela = Tipotela::find($request->tipotela);
        if (!$tipotela) {
            $tipotela = Tipotela::where('tela', '=', $request->tipotela)->first();
        }
        $color = Color::find($request->color);
        if (!$color) {
            $color = Color::where('color', '=', $request->color)->first();
        }
        $uniforme->talla_id = $talla->id;
        $uniforme->tipotela_id = $tipotela->id;
        $uniforme->color_id = $color->id;
        $uniforme->stock1 = 0;
        $uniforme->stock2 = 0;
        $uniforme->stock3 = 0;
        $uniforme->stockmin = 5;
        $uniforme->save();
        $this->crearhistorial('crear', $uniforme->id, $uniforme->nombre, '', 'uniformes');
        return redirect('admin/uniformes')->with('message', 'Uniforme Agregado Satisfactoriamente');
    }

    public function show($id)
    {
        $uniformes = DB::table('uniformes as u')
            ->join('tallas as t', 'u.talla_id', '=', 't.id')
            ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
            ->join('colors as c', 'u.color_id', '=', 'c.id')
            ->select(
                'u.id',
                'u.nombre',
                't.talla',
                'tt.tela',
                'c.color',
                'u.precio',
                'u.genero',
                'u.stock1',
                'u.stock2',
                'u.stock3',
                'u.stockmin',
            )->where('u.id', '=', $id)
            ->get();
        return $uniformes;
    }

    public function edit(string $id)
    {
        $uniforme = Uniforme::find($id);
        $tallas = Talla::all()->where('status', '=', 0);
        $tipotelas = Tipotela::all()->where('status', '=', 0);
        $colores = Color::all()->where('status', '=', 0);
        return view('admin.uniformes.edit', compact('tallas', 'tipotelas', 'colores', 'uniforme'));
    }

    public function update(Request $request, string $id)
    {
        $uniforme = Uniforme::find($id);
        $uniforme->nombre = $request->nombre;
        $uniforme->genero = $request->genero;
        $uniforme->precio = $request->precio;
        $uniforme->talla_id = $request->talla;
        $uniforme->tipotela_id = $request->tipotela;
        $uniforme->color_id = $request->color;
        $uniforme->update();
        $this->crearhistorial('editar', $uniforme->id, $uniforme->nombre, '', 'uniformes');
        return redirect('admin/uniformes')->with('message', 'Uniforme Actualizado Satisfactoriamente');
    }

    public function destroy($id)
    {
        try {
            $uniforme = Uniforme::find($id);
            if (!$uniforme) {
                return "2";
            }
            $uniforme->delete();
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
    public function inventariouniformes(Request $request)
    {
        $tiendas = Tienda::all();
        if ($request->ajax()) {
            $registros = DB::table('uniformes as u')
                ->join('tallas as t', 'u.talla_id', '=', 't.id')
                ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
                ->join('colors as c', 'u.color_id', '=', 'c.id')
                ->select(
                    'u.id',
                    'u.nombre',
                    't.talla',
                    'tt.tela',
                    'c.color',
                    'u.precio',
                    'u.genero',
                    'u.stock1',
                    'u.stock2',
                    'u.stock3',
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
        return view('admin.inventarios.uniformes', compact('tiendas'));
    }

    public function updatestock(Request $request)
    {
        try {
            $uniforme =   Uniforme::find($request->idproducto);
            $uniforme->stock1 = $request->stock1;
            $uniforme->stock2 = $request->stock2;
            $uniforme->stock3 = $request->stock3;
            $uniforme->stockmin = $request->stockmin;
            $uniforme->update();
            return "1";
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function numerosinstock()
    {
        $productossinstock = 0;

        $productossinstock = DB::table('uniformes as u')
            ->whereRaw('u.stock1 < u.stockmin OR u.stock2 < u.stockmin OR u.stock3 < u.stockmin')
            ->where('u.status', '=', '0')
            ->count();
        return $productossinstock;
    }
    public function inventariouniformes2()
    {
        return redirect('admin/inventariouniformes')->with('verstock', 'Ver');
    }
    public function showsinstock()
    {
        $uniformes = DB::table('uniformes as u')
            ->join('tallas as t', 'u.talla_id', '=', 't.id')
            ->join('tipotelas as tt', 'u.tipotela_id', '=', 'tt.id')
            ->join('colors as c', 'u.color_id', '=', 'c.id')
            ->select(
                'u.id',
                'u.nombre',
                't.talla',
                'tt.tela',
                'c.color',
                'u.precio',
                'u.genero',
                'u.stock1',
                'u.stock2',
                'u.stock3',
                'u.stockmin',
            )->whereRaw('u.stock1 < u.stockmin OR u.stock2 < u.stockmin OR u.stock3 < u.stockmin')
            ->where('u.status', '=', '0')
            ->get();
        return $uniformes;
    }
}
