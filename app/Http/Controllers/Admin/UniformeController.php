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
        $uniforme->talla_id = $request->talla;
        $uniforme->tipotela_id = $request->tipotela;
        $uniforme->color_id = $request->color;
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
        $registro1 =   Talla::where('talla','=',$talla)->first();
        if(!$registro && !$registro1){
            $reg = new Talla;
            $reg->talla = $talla;
            $reg->save();
            return "1";
        } 
    }
    public function addtipotela($tipotela)
    {
        $tela =   Tipotela::find($tipotela);
        $tela1 =   Tipotela::where('tela','=',$tipotela)->first();
        if(!$tela && !$tela1){
            $reg = new Tipotela;
            $reg->tela = $tipotela;
            $reg->save();
            return "1";
        } 
    }
    public function addcolor($color)
    {
        $micolor =   Color::find($color);
        $micolor1  =   Color::where('color','=',$color)->first();
        if(!$micolor && !$micolor1){
            $reg = new Color;
            $reg->color = $color;
            $reg->save();
            return "1";
        } 
    }
}
