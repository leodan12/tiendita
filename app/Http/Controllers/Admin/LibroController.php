<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicion;
use App\Models\Especializacion;
use App\Models\Formato;
use App\Models\Libro;
use App\Models\Tipopapel;
use App\Models\Tipopasta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;



class LibroController extends Controller
{

    use HistorialTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $libros = DB::table('libros as l')
                ->join('formatos as f', 'l.formato_id', '=', 'f.id')
                ->join('tipopapels as tp', 'l.tipopapel_id', '=', 'tp.id')
                ->join('tipopastas as tpa', 'l.tipopasta_id', '=', 'tpa.id')
                ->join('edicions as e', 'l.edicion_id', '=', 'e.id')
                ->join('especializacions as es', 'l.especializacion_id', '=', 'es.id')
                ->select(
                    'l.id',
                    'l.titulo',
                    'l.autor',
                    'l.precio',
                    'l.anio',
                    'l.original',
                    'f.formato',
                    'tp.tipopapel',
                    'tpa.tipopasta',
                    'e.edicion',
                    'es.especializacion',
                )->where('l.status', '=', 0);
            return DataTables::of($libros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($libros) {
                    return view('admin.libros.botones', compact('libros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.libros.index');
    }

    public function create()
    {
        $formatos = Formato::all();
        $tipopapeles = Tipopapel::all();
        $tipopastas = Tipopasta::all();
        $ediciones = Edicion::all();
        $especializaciones = Especializacion::all();
        return view('admin.libros.create', compact('formatos', 'tipopapeles', 'tipopastas', 'ediciones', 'especializaciones'));
    }

    public function store(Request $request)
    {
        $libro = new Libro;
        $libro->titulo = $request->titulo;
        $libro->autor = $request->autor;
        $libro->anio = $request->anio;
        $libro->precio = $request->precio;
        $libro->original = $request->original;

        $formato = Formato::find($request->formato);
        if (!$formato) {
            $formato = Formato::where('formato', '=', $request->formato)->first();
        }
        $tipopapel = Tipopapel::find($request->tipopapel);
        if (!$tipopapel) {
            $tipopapel = Tipopapel::where('tipopapel', '=', $request->tipopapel)->first();
        }
        $tipopasta = Tipopasta::find($request->tipopasta);
        if (!$tipopasta) {
            $tipopasta = Tipopasta::where('tipopasta', '=', $request->tipopasta)->first();
        }
        $edicion = Edicion::find($request->edicion);
        if (!$edicion) {
            $edicion = Edicion::where('edicion', '=', $request->edicion)->first();
        }
        $especializacion = Especializacion::find($request->especializacion);
        if (!$especializacion) {
            $especializacion = Especializacion::where('especializacion', '=', $request->especializacion)->first();
        }
        $libro->formato_id = $formato->id;
        $libro->tipopapel_id = $tipopapel->id;
        $libro->tipopasta_id = $tipopasta->id;
        $libro->edicion_id = $edicion->id;
        $libro->especializacion_id = $especializacion->id;
        $libro->save();
        $this->crearhistorial('crear', $libro->id, $libro->nombre, '', 'libros');
        return redirect('admin/libros')->with('message', 'Libro Agregado Satisfactoriamente');
    }

    public function show($id)
    {
        $libros = DB::table('libros as l')
            ->join('formatos as f', 'l.formato_id', '=', 'f.id')
            ->join('tipopapels as tp', 'l.tipopapel_id', '=', 'tp.id')
            ->join('tipopastas as tpa', 'l.tipopasta_id', '=', 'tpa.id')
            ->join('edicions as e', 'l.edicion_id', '=', 'e.id')
            ->join('especializacions as es', 'l.especializacion_id', '=', 'es.id')
            ->select(
                'l.id',
                'l.titulo',
                'l.autor',
                'l.precio',
                'l.anio',
                'l.original',
                'f.formato',
                'tp.tipopapel',
                'tpa.tipopasta',
                'e.edicion',
                'es.especializacion',
                'l.stock1',
                'l.stock2',
                'l.stockmin',
            )->where('l.id', '=', $id)
            ->get();
        return $libros;
    }

    public function edit(string $id)
    {
        $libro = Libro::find($id);
        $formatos = Formato::all();
        $tipopapeles = Tipopapel::all();
        $tipopastas = Tipopasta::all();
        $ediciones = Edicion::all();
        $especializaciones = Especializacion::all();
        return view('admin.libros.edit', compact('formatos', 'tipopastas', 'tipopapeles', 'ediciones', 'especializaciones', 'libro'));
    }

    public function update(Request $request, string $id)
    {
        $libro = libro::find($id);
        $libro->titulo = $request->titulo;
        $libro->anio = $request->anio;
        $libro->autor = $request->autor;
        $libro->precio = $request->precio;
        $libro->formato_id = $request->formato;
        $libro->tipopapel_id = $request->tipopapel;
        $libro->tipopasta_id = $request->tipopasta;
        $libro->edicion_id = $request->edicion;
        $libro->especializacion_id = $request->especializacion;
        $libro->update();
        $this->crearhistorial('editar', $libro->id, $libro->nombre, '', 'libros');
        return redirect('admin/libros')->with('message', 'Libro Actualizado Satisfactoriamente');
    }

    public function destroy($id)
    {
        try {
            $libro = Libro::find($id);
            if (!$libro) {
                return "2";
            }
            $libro->delete();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }
    //funcion para añadir dato extra
    public function addformato($formato)
    {
        $registro =   Formato::find($formato);
        $registro1 =   Formato::where('formato', '=', $formato)->first();
        if (!$registro && !$registro1) {
            $reg = new Formato;
            $reg->formato = $formato;
            $reg->save();
            return "1";
        }
    }
    public function addtipopapel($tipopapel)
    {
        $registro =   Tipopapel::find($tipopapel);
        $registro1 =   Tipopapel::where('tipopapel', '=', $tipopapel)->first();
        if (!$registro && !$registro1) {
            $reg = new Tipopapel;
            $reg->tipopapel = $tipopapel;
            $reg->save();
            return "1";
        }
    }
    public function addtipopasta($tipopasta)
    {
        $registro =   Tipopasta::find($tipopasta);
        $registro1  =   Tipopasta::where('tipopasta', '=', $tipopasta)->first();
        if (!$registro && !$registro1) {
            $reg = new Tipopasta;
            $reg->tipopasta = $tipopasta;
            $reg->save();
            return "1";
        }
    }
    public function addedicion($edicion)
    {
        $registro =   Edicion::find($edicion);
        $registro1  =   Edicion::where('edicion', '=', $edicion)->first();
        if (!$registro && !$registro1) {
            $reg = new Edicion;
            $reg->edicion = $edicion;
            $reg->save();
            return "1";
        }
    }
    public function addespecializacion($especializacion)
    {
        $registro =   Especializacion::find($especializacion);
        $registro1  =   Especializacion::where('especializacion', '=', $especializacion)->first();
        if (!$registro && !$registro1) {
            $reg = new Especializacion;
            $reg->especializacion = $especializacion;
            $reg->save();
            return "1";
        }
    }

    //inventario
    public function inventariolibros(Request $request)
    {
        if ($request->ajax()) {
            $registros = DB::table('libros as l')
                ->join('formatos as f', 'l.formato_id', '=', 'f.id')
                ->join('tipopapels as tp', 'l.tipopapel_id', '=', 'tp.id')
                ->join('tipopastas as tt', 'l.tipopasta_id', '=', 'tt.id')
                ->join('edicions as e', 'l.edicion_id', '=', 'e.id')
                ->join('especializacions as es', 'l.especializacion_id', '=', 'es.id')
                ->select(
                    'l.id',
                    'l.titulo',
                    'f.formato',
                    'tp.tipopapel',
                    'tt.tipopasta',
                    'e.edicion',
                    'es.especializacion',
                    'l.original',
                    'l.anio',
                    'l.precio',
                    'l.autor',
                    'l.stock1',
                    'l.stock2',
                    'l.stockmin', 
                )->where('l.status', '=', 0);
            return DataTables::of($registros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($registros) {
                    return view('admin.inventarios.botones', compact('registros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.inventarios.libros');
    }

    public function updatestock(Request $request)
    {
        try {
            $libro =   Libro::find($request->idproducto);
            $libro->stock1 = $request->stock1;
            $libro->stock2 = $request->stock2;
            $libro->stockmin = $request->stockmin;
            $libro->update();
            return "1";
        } catch (\Throwable $th) {
            //throw $th;
        } 
    }
    public function numerosinstock()
    {
        $productossinstock = 0;

        $productossinstock = DB::table('libros')
            ->whereRaw('stock1 + stock2 < stockmin')
            ->where('status', '=', '0')
            ->count();
        return $productossinstock;
    }
    public function inventariolibros2()
    {
        return redirect('admin/inventariolibros')->with('verstock', 'Ver');
    }
    public function showsinstock()
    {
        $libros = DB::table('libros as l')
            ->join('formatos as f', 'l.formato_id', '=', 'f.id')
            ->join('tipopapels as tp', 'l.tipopapel_id', '=', 'tp.id')
            ->join('tipopastas as tpa', 'l.tipopasta_id', '=', 'tpa.id')
            ->join('edicions as e', 'l.edicion_id', '=', 'e.id')
            ->join('especializacions as es', 'l.especializacion_id', '=', 'es.id')
            ->select(
                'l.id',
                'l.titulo',
                'l.autor',
                'l.precio',
                'l.anio',
                'l.original',
                'f.formato',
                'tp.tipopapel',
                'tpa.tipopasta',
                'e.edicion',
                'es.especializacion',
                'l.stock1',
                'l.stock2',
                'l.stockmin',
            )->whereRaw('l.stock1 + l.stock2 < l.stockmin')
            ->where('l.status', '=', '0')
            ->get();
        return $libros; 
    }

    
    
}
