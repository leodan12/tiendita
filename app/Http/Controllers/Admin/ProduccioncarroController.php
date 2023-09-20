<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carro;
use App\Models\Modelocarro;
use App\Models\Carroceria;
use App\Models\Product;
use App\Models\Enviomaterial;
use App\Models\Produccioncarro;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;
use App\Http\Requests\ProduccioncarroFormRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Materialcarro;
use App\Models\DetalleKitmateriales;
use App\Models\Detalleventa;
use App\Models\Redcarro;
use App\Traits\ActualizarStockTrait;
use App\Models\Enviored;
use App\Models\Venta; 
use App\Models\DetalleKitventa;

class ProduccioncarroController extends Controller
{
    function __construct()
    {
        $this->middleware(
            'permission:ver-produccion-carro|editar-produccion-carro|crear-produccion-carro|eliminar-produccion-carro',
            ['only' => ['index', 'show']]
        );
        $this->middleware('permission:crear-produccion-carro', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-produccion-carro', ['only' => ['edit', 'update',]]);
        $this->middleware('permission:eliminar-produccion-carro', ['only' => ['destroy']]);
    }
    use HistorialTrait;
    use ActualizarStockTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $produccioncarros = DB::table('produccioncarros as pc')
                ->join('modelocarros as mc', 'pc.modelo_id', '=', 'mc.id')
                ->join('carrocerias as c', 'pc.carroceria_id', '=', 'c.id')
                ->select(
                    'pc.id',
                    'pc.cantidad',
                    'pc.nombre',
                    'c.tipocarroceria as carroceria',
                    'mc.modelo',
                    'pc.todoenviado',
                    'pc.facturado',
                );
            return DataTables::of($produccioncarros)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($produccioncarros) {
                    return view('admin.produccioncarro.botones', compact('produccioncarros'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.produccioncarro.index');
    }

    public function create()
    {
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        $empresas = Company::all()->where('status', '=', 0);
        $productos = Product::all()->where('status', '=', 0)->where('tipo', '!=', 'arnes');
        $nombreParcial = 'REDES';
        $red = Category::whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($nombreParcial) . '%'])->first();
        $redes = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select('c.nombre', 'p.nombre as producto', 'p.id as idproducto', 'p.tipo', 'p.unidad')
            ->where('c.nombre', '=', $red->nombre)
            ->where('p.tipo', '!=', 'arnes')
            ->get();
        //return $clientes;
        return view('admin.produccioncarro.create', compact('carrocerias', 'modelos', 'empresas', 'productos', 'redes'));
    }

    public function store(ProduccioncarroFormRequest $request)
    {
        //validar datos
        $validatedData = $request->validated();
        $cantidadcarros = $validatedData['cantidadcarros'];
        $nombre = $validatedData['nombre'];
        $carroceria_id = $validatedData['carroceria_id'];
        $modelo_id = $validatedData['modelo_id'];
        $todoenviado = $validatedData['todoenviado'];
        $facturado = $validatedData['facturado'];
        $descuento = $validatedData['descuento'];

        $produccion = new Produccioncarro;
        $produccion->cantidad = $cantidadcarros;
        $produccion->nombre = $nombre;
        $produccion->carroceria_id = $carroceria_id;
        $produccion->modelo_id = $modelo_id;
        $produccion->todoenviado = $todoenviado;
        $produccion->facturado = $facturado;
        $produccion->descuento = $descuento;

        if ($produccion->save()) {
            //para guardar los carros
            $numeroordenproduccion = $request->Lnumeroordenproduccion;
            $chasis = $request->Lchasis;
            $porcentajedescuento = $request->Lporcentajedescuento;
            $bonificacion = $request->Lbonificacion;
            $mesbonificacion = $request->Lmesbonificacion;
            if ($numeroordenproduccion !== null) {
                for ($i = 0; $i < count($numeroordenproduccion); $i++) {
                    $carro = new Carro;
                    $carro->nroordenp = $numeroordenproduccion[$i];
                    $carro->chasis = $chasis[$i];
                    $carro->porcentajedescuento = $porcentajedescuento[$i];
                    $carro->materialelectricoenv = '';
                    $carro->materialadicionalenv = '';
                    $carro->redenviada = '';
                    $carro->bonificacion = $bonificacion[$i];
                    $mesbono = "";
                    if (!$mesbonificacion[$i]) {
                        $carro->mesbonificacion = $mesbono;
                    } else {
                        $carro->mesbonificacion = $mesbonificacion[$i];
                    }
                    $carro->produccioncarro_id = $produccion->id;
                    $carro->ordencompra = '';
                    $carro->fechaE = '';
                    $carro->numeroE = '';
                    $carro->fechaD = '';
                    $carro->numeroD = '';
                    $carro->save();
                }
            }
            //para guardar los materiales
            $product = $request->Lproductm;
            $cantidad = $request->Lcantidadm;
            $cantidadenviada = $request->Lcantidadenviadam;
            $observacion = $request->Lobservacionm;
            $tipo = $request->Ltipom;
            $empresa = $request->Lempresam;

            //arrays de los productos vendidos de un kit
            $idkits = $request->Lidkit;
            $cantidadproductokit = $request->Lcantidadproductokit;
            $idproductokit = $request->Lidproductokit;

            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    //creamos los detalles del material
                    $materialcarro = new Materialcarro;
                    $materialcarro->producto_id = $product[$i];
                    $materialcarro->cantidad = $cantidad[$i];
                    $materialcarro->cantidadenviada = $cantidadenviada[$i];
                    $materialcarro->produccioncarro_id = $produccion->id;
                    $materialcarro->observacion = $observacion[$i];
                    $materialcarro->tipo = $tipo[$i];
                    $materialcarro->empresa_id = $empresa[$i];
                    if ($materialcarro->save()) {
                        //para guardar los productos de un kit de un material del carro
                        if ($idkits !== null) {
                            for ($x = 0; $x < count($idkits); $x++) {
                                if ($idkits[$x] == $product[$i]) {
                                    $detallematerial = new DetalleKitmateriales;
                                    $detallematerial->detallematerialcarro_id = $materialcarro->id;
                                    $detallematerial->product_id = $idproductokit[$x];
                                    $detallematerial->cantidad = $cantidadproductokit[$x];
                                    $detallematerial->save();
                                }
                            }
                        }
                    }
                }
            }
            //para guardar las redes
            $productred = $request->LproductredC;
            $cantidadred = $request->LcantidadredC;
            $cantidadenviadared = $request->LcantidadenviadaredC;
            $observacionred = $request->LobservacionredC;
            $tipored = $request->LtiporedC;
            $empresared = $request->LempresaredC;
            if ($productred !== null) {
                for ($i = 0; $i < count($productred); $i++) {
                    //creamos los detalles de la venta
                    $redcarro = new Redcarro;
                    $redcarro->producto_id = $productred[$i];
                    $redcarro->cantidad = $cantidadred[$i];
                    $redcarro->cantidadenviada = $cantidadenviadared[$i];
                    $redcarro->produccioncarro_id = $produccion->id;
                    $redcarro->observacion = $observacionred[$i];
                    $redcarro->tipo = $tipored[$i];
                    $redcarro->empresa_id = $empresared[$i];
                    $redcarro->save();
                }
            }
        }

        $this->crearhistorial('crear', $produccion->id, $produccion->nombre, '', 'produccioncarros');
        return redirect('admin/produccioncarro')->with('message', 'Produccion Agregada Satisfactoriamente');
    }

    public function show(string $id)
    {
        $produccion = DB::table('produccioncarros as pc')
            ->join('carrocerias as c', 'pc.carroceria_id', '=', 'c.id')
            ->join('modelocarros as m', 'pc.modelo_id', '=', 'm.id')
            ->select(
                'pc.id as idproduccioncarro',
                'pc.todoenviado',
                'pc.facturado',
                'pc.nombre',
                'pc.cantidad as numerocarros',
                'c.tipocarroceria as carroceria',
                'm.modelo',
                'pc.descuento',

            )
            ->where('pc.id', '=', $id)->get();

        return $produccion;
    }
    //funcion para mostrar las condiciones dentro del modal vercondiciones
    public function showmateriales($id)
    {
        $materiales = DB::table('materialcarros as mc')
            ->join('produccioncarros as pc', 'mc.produccioncarro_id', '=', 'pc.id')
            ->join('products as p', 'mc.producto_id', '=', 'p.id')
            ->join('companies as c', 'mc.empresa_id', '=', 'c.id')
            ->select(
                'pc.id as idproduccioncarro',
                'pc.cantidad as numerocarros',
                'mc.cantidad as cantidadproducto',
                'mc.cantidadenviada',
                'p.nombre as producto',
                'p.id as idproducto',
                'p.tipo',
                'mc.observacion',
                'mc.tipo as tipomaterial',
                'c.nombre as empresa',
                'mc.id as idmaterial'
            )
            ->where('pc.id', '=', $id)->get();
        return $materiales;
    }
    //funcion para mostrar las condiciones dentro del modal vercondiciones
    public function showcarros($id)
    {
        $carros = DB::table('produccioncarros as pc')
            ->join('carros as c', 'c.produccioncarro_id', '=', 'pc.id')
            ->select(
                'c.nroordenp',
                'c.chasis',
                'c.porcentajedescuento',
                'c.redenviada',
                'c.materialelectricoenv',
                'c.materialadicionalenv',
                'c.bonificacion',
                'c.mesbonificacion',
                'c.ordencompra',
                'c.fechaE',
                'c.numeroE',
                'c.fechaD',
                'c.numeroD',
                'c.id as idcarro',
                'c.fechaO',
                'c.numeroO',
                'c.empresaO',
            )
            ->where('pc.id', '=', $id)->get();

        return  $carros;
    }

    public function showredes($id)
    {
        $redes = DB::table('redcarros as rc')
            ->join('products as p', 'rc.producto_id', '=', 'p.id')
            ->join('produccioncarros as pc', 'rc.produccioncarro_id', '=', 'pc.id')
            ->join('companies as c', 'rc.empresa_id', '=', 'c.id')
            ->select(
                'pc.id as idproduccioncarro',
                'pc.cantidad as numerocarros',
                'rc.cantidad as cantidadproducto',
                'rc.cantidadenviada',
                'p.nombre as producto',
                'p.id as idproducto',
                'p.tipo',
                'rc.observacion',
                'rc.tipo as tipored',
                'c.nombre as empresa'
            )
            ->where('pc.id', '=', $id)->get();

        return $redes;
    }

    public function edit($id)
    {
        $produccioncarro = Produccioncarro::find($id);
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        $empresas = Company::all()->where('status', '=', 0);
        $productos = Product::all()->where('status', '=', 0)->where('tipo', '!=', 'arnes');
        $nombreParcial = 'REDES';
        $red = Category::whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($nombreParcial) . '%'])->first();
        $redes = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select('c.nombre', 'p.nombre as producto', 'p.id as idproducto', 'p.tipo', 'p.unidad', 'p.id')
            ->where('c.nombre', '=', $red->nombre)
            ->where('p.tipo', '!=', 'arnes')
            ->get();

        $materialcarros = DB::table('produccioncarros as pc')
            ->join('materialcarros as mc', 'mc.produccioncarro_id', '=', 'pc.id')
            ->join('products as p', 'mc.producto_id', '=', 'p.id')
            ->join('companies as c', 'mc.empresa_id', '=', 'c.id')
            ->select(
                'mc.cantidad',
                'mc.cantidadenviada',
                'p.tipo',
                'p.nombre as producto',
                'p.id as idproducto',
                'mc.id as idmaterialcarro',
                'pc.id as idproduccion',
                'pc.cantidad as cantidadcarros',
                'mc.observacion',
                'mc.tipo as tipomaterial',
                'c.id as idempresa',
                'c.nombre as nombreempresa'
            )
            ->where('pc.id', '=', $id)->get();

        $redcarros = DB::table('produccioncarros as pc')
            ->join('redcarros as rc', 'rc.produccioncarro_id', '=', 'pc.id')
            ->join('products as p', 'rc.producto_id', '=', 'p.id')
            ->join('companies as c', 'rc.empresa_id', "=", 'c.id')
            ->select(
                'rc.cantidad',
                'rc.cantidadenviada',
                'p.tipo',
                'p.nombre as producto',
                'p.id as idproducto',
                'rc.id as idredcarro',
                'pc.id as idproduccion',
                'pc.cantidad as cantidadcarros',
                'rc.observacion',
                'rc.tipo as tipored',
                'c.id as idempresa',
                'c.nombre as nombreempresa'
            )
            ->where('pc.id', '=', $id)->get();

        $detalleskitredes = DB::table('kits as k')
            ->join('products as p', 'k.kitproduct_id', '=', 'p.id')
            ->join('products as pv', 'k.product_id', '=', 'pv.id')
            ->join('redcarros as rc', 'rc.producto_id', '=', 'pv.id')
            ->join('produccioncarros as pc', 'rc.produccioncarro_id', '=', 'pc.id')
            ->select('k.cantidad', 'p.nombre as producto', 'k.product_id')
            ->where('pc.id', '=', $id)->get();

        $detalleskit = DB::table('produccioncarros as pc')
            ->join('materialcarros as mc', 'mc.produccioncarro_id', '=', 'pc.id')
            ->join('products as p', 'mc.producto_id', '=', 'p.id')
            ->join('detalle_kitmateriales as dkm', 'dkm.detallematerialcarro_id', '=', 'mc.id')
            ->join('products as pkm', 'dkm.product_id', '=', 'pkm.id')
            ->select('dkm.cantidad', 'pkm.nombre as producto', 'p.id as product_id')
            ->where('pc.id', '=', $id)->get();

        $carros = DB::table('produccioncarros as pc')
            ->join('carros as c', 'c.produccioncarro_id', '=', 'pc.id')
            ->select(
                'c.nroordenp',
                'c.chasis',
                'c.porcentajedescuento',
                'c.redenviada',
                'c.materialelectricoenv',
                'c.materialadicionalenv',
                'c.bonificacion',
                'c.mesbonificacion',
                'pc.id as idprodduccioncarro',
                'c.id as idcarro',
                'c.ordencompra',
                'c.fechaE',
                'c.numeroE',
                'c.fechaD',
                'c.numeroD',
                'c.fechaO',
                'c.numeroO',
                'c.empresaO',
            )
            ->where('pc.id', '=', $id)->get();

        return view(
            'admin.produccioncarro.edit',
            compact(
                'carrocerias',
                'modelos',
                'empresas',
                'productos',
                'produccioncarro',
                'materialcarros',
                'detalleskit',
                'carros',
                'redes',
                'redcarros',
                'detalleskitredes'
            )
        );
    }

    public function update(ProduccioncarroFormRequest $request, $id)
    {
        //validar datos
        $validatedData = $request->validated();
        $cantidadcarros = $validatedData['cantidadcarros'];
        $nombre = $validatedData['nombre'];
        $carroceria_id = $validatedData['carroceria_id'];
        $modelo_id = $validatedData['modelo_id'];
        $todoenviado = $validatedData['todoenviado'];
        $facturado = $validatedData['facturado'];
        $descuento = $validatedData['descuento'];
        $ordencompra = $validatedData['ordencompra'];

        $produccion = Produccioncarro::find($id);
        $produccion->nombre = $nombre;
        $produccion->todoenviado = $todoenviado;
        $produccion->facturado = $facturado;
        $produccion->descuento = $descuento;
        $produccion->ordencompra = $ordencompra;

        if ($produccion->update()) {
            //para guardar los carros
            $numeroordenproduccion = $request->Lnumeroordenproduccion;
            $chasis = $request->Lchasis;
            $porcentajedescuento = $request->Lporcentajedescuento;
            $bonificacion = $request->Lbonificacion;
            $mesbonificacion = $request->Lmesbonificacion;
            $listacarrosids = $request->Lcarrosids;
            $ordenescompra = $request->Lordencompra;
            $fechaE = $request->LfechaE;
            $numeroE = $request->LnumeroE;
            $fechaD = $request->LfechaD;
            $numeroD = $request->LnumeroD;
            //factura adicional
            $fechaO = $request->LfechaO;
            $numeroO = $request->LnumeroO;
            $empresaO = $request->LempresaO;

            if ($numeroordenproduccion !== null) {
                for ($i = 0; $i < count($numeroordenproduccion); $i++) {
                    $carro = Carro::find($listacarrosids[$i]);
                    $carro->nroordenp = $numeroordenproduccion[$i];
                    $carro->chasis = $chasis[$i];
                    $carro->porcentajedescuento = $porcentajedescuento[$i];
                    $carro->bonificacion = $bonificacion[$i];
                    $carro->mesbonificacion = $mesbonificacion[$i];
                    $carro->produccioncarro_id = $produccion->id;
                    $carro->ordencompra = $ordenescompra[$i];
                    $carro->fechaE = $fechaE[$i];
                    $carro->numeroE = $numeroE[$i];
                    $carro->fechaD = $fechaD[$i];
                    $carro->numeroD = $numeroD[$i];
                    //factura adicional
                    $carro->fechaO = $fechaO[$i];
                    $carro->numeroO = $numeroO[$i];
                    $carro->empresaO = $empresaO[$i];
                    $carro->update();
                }
            }
            //para guardar los materiales
            $listaids = $request->Lidsm;
            $observacion = $request->Lobservacionm;
            if ($listaids !== null) {
                for ($i = 0; $i < count($listaids); $i++) {
                    if ($listaids[$i] != "-1") {
                        $materialcarro = Materialcarro::find($listaids[$i]);
                        $materialcarro->observacion = $observacion[$i];
                        $materialcarro->update();
                    }
                }
            }
            //para guardar las redes
            $listaidsred = $request->Lidsred;
            $observacionred = $request->Lobservacionred;
            if ($listaidsred !== null) {
                for ($i = 0; $i < count($listaidsred); $i++) {
                    if ($listaidsred[$i] != "-1") {
                        $redcarro = Redcarro::find($listaidsred[$i]);
                        $redcarro->observacion = $observacionred[$i];
                        $redcarro->update();
                    }
                }
            }

            $this->crearhistorial('editar', $produccion->id, $produccion->nombre, '', 'produccioncarros');
            return redirect('admin/produccioncarro')->with('message', 'Registro Actualizado Satisfactoriamente');
        }
    }

    public function destroy($id)
    {
        $produccion = Produccioncarro::find($id);
        if ($produccion) {
            try {
                $produccion->delete();
                return "1";
            } catch (\Throwable $th) {
                $produccion->status = 1;
                if ($produccion->update()) {
                    return "1";
                } else {
                    return "0";
                }
            }
        } else {
            return "2";
        }
    }
    public function destroymaterial($id)
    {
        $detallematerial = Materialcarro::find($id);
        if ($detallematerial) {
            try {
                $detallematerial->delete();
                $this->actualizarfechamat($detallematerial->produccioncarro_id);
                $producto = Product::find($detallematerial->producto_id);
                if ($producto->tipo == "kit") {
                    $listap = $this->productosxkit($producto->id);
                    for ($x = 0; $x < count($listap); $x++) {
                        $this->actualizarstock($listap[$x]->idproducto, $detallematerial->empresa_id, ($detallematerial->cantidadenviada * $listap[$x]->cantidad), 'SUMA');
                    }
                } else {
                    $this->actualizarstock($detallematerial->producto_id, $detallematerial->empresa_id, $detallematerial->cantidadenviada, 'SUMA');
                }
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }

    public function actualizarfechamat($idproduccion)
    {
        $carros = DB::table('carros as c')
            ->where('c.produccioncarro_id', '=', $idproduccion)
            ->select('c.id')
            ->get();
        for ($i = 0; $i < count($carros); $i++) {
            $micarrox = $carros[$i]->id;
            $sinenviar = DB::table('enviomaterials')
                ->where(function ($query) use ($micarrox) {
                    $query->where('fecha', null)
                        ->orWhere('fecha', '');
                })
                ->where('carro_id',  $micarrox)
                ->count();

            $micarro = Carro::find($carros[$i]->id);
            if ($sinenviar >= 1) {
                $micarro->materialadicionalenv = "";
            } else {
                $micarro->materialadicionalenv = date('Y-m-d');
            }
            $micarro->update();
        }
    }

    public function destroyred($id)
    {
        $detallered = Redcarro::find($id);
        if ($detallered) {
            try {
                $detallered->delete();
                $this->actualizarfechared($detallered->produccioncarro_id);
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }

    public function actualizarfechared($idproduccion)
    {
        $carros = DB::table('carros as c')
            ->where('c.produccioncarro_id', '=', $idproduccion)
            ->select('c.id')
            ->get();
        for ($i = 0; $i < count($carros); $i++) {
            $micarrox = $carros[$i]->id;
            $sinenviar = DB::table('envioreds')
                ->where(function ($query) use ($micarrox) {
                    $query->where('fecha', null)
                        ->orWhere('fecha', '');
                })
                ->where('carro_id',  $micarrox)
                ->count();

            $micarro = Carro::find($carros[$i]->id);
            if ($sinenviar >= 1) {
                $micarro->redenviada = "";
            } else {
                $micarro->redenviada = date('Y-m-d');
            }
            $micarro->update();
        }
    }

    public function destroycarro($id)
    {
        $carro = Carro::find($id);
        if ($carro) {
            try {
                $carro->delete();
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    public function materialcarroceria($carroceria_id, $modelo_id)
    {
        $datoscarroceria = DB::table('materialelectricos as me')
            ->join('detallecarrocerias as dc', 'dc.materialelectrico_id', '=', 'me.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->select('me.id', 'dc.cantidad', 'dc.unidad', 'dc.producto_id', 'p.nombre as producto', 'p.tipo', 'p.id as producto_id')
            ->where('me.carroceria_id', '=', $carroceria_id)
            ->where('me.modelo_id', '=', $modelo_id)
            ->get();

        return $datoscarroceria;
    }
    public function vercarro($carro_id)
    {
        $micarrox = $carro_id;
        $sinenviar = DB::table('envioreds')
            ->where(function ($query) use ($micarrox) {
                $query->where('fecha', null)
                    ->orWhere('fecha', '');
            })
            ->where('carro_id',  $micarrox)
            ->count();
        return $sinenviar;
    }
    public function btnredesenviadas(Request $request)
    {
        $redes  =  collect();
        $ids = collect();
        $valores = collect();
        $idcarros = $request->carros;
        foreach ($idcarros as $carro) {
            $idcarro = $carro;
            if ($request->redomat == "mat") {
                $xenviar = DB::table('enviomaterials')
                    ->where(function ($query) use ($idcarro) {
                        $query->where('fecha', null)
                            ->orWhere('fecha', '');
                    })
                    ->where('carro_id', $idcarro)
                    ->count();
                if ($xenviar == 0) {
                    $ids->push($idcarro);
                    $valores->push("NEM");
                } else {
                    $ids->push($idcarro);
                    $valores->push("x");
                }
            } else if ($request->redomat == "red") {
                $xenviar = DB::table('envioreds')
                    ->where(function ($query) use ($idcarro) {
                        $query->where('fecha', null)
                            ->orWhere('fecha', '');
                    })
                    ->where('carro_id', $idcarro)
                    ->count();
                if ($xenviar == 0) {
                    $ids->push($idcarro);
                    $valores->push("NER");
                } else {
                    $ids->push($idcarro);
                    $valores->push("x");
                }
            }
        }
        $redes->push($ids);
        $redes->push($valores);
        return $redes;
    }

    public function redes($carroceria_id, $modelo_id)
    {
        $redes = DB::table('reds as r')
            ->join('detallereds as dr', 'dr.red_id', '=', 'r.id')
            ->join('products as p', 'dr.producto_id', '=', 'p.id')
            ->select('r.id', 'dr.cantidad', 'dr.unidad', 'dr.producto_id', 'p.nombre as producto', 'p.tipo', 'p.id as producto_id')
            ->where('r.carroceria_id', '=', $carroceria_id)
            ->where('r.modelo_id', '=', $modelo_id)
            ->get();

        return $redes;
    }

    public function enviarmaterialelectrico($carro_id, $fecha)
    {
        $carro = Carro::find($carro_id);
        if ($carro) {
            try {
                $carro->materialelectricoenv = $fecha;
                $carro->update();
                $this->actualizarcantidadenviadaME($carro_id);
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    public function actualizarcantidadenviadaME($carro_id)
    {
        $carro = Carro::find($carro_id);
        if ($carro) {
            try {
                $materialelectrico = DB::table('materialcarros as mc')
                    ->where('mc.produccioncarro_id', '=', $carro->produccioncarro_id)
                    ->where('mc.tipo', '=', 'E')
                    ->select('mc.id', 'mc.cantidad', 'mc.cantidadenviada', 'mc.producto_id')
                    ->get();
                $cantidades = $materialelectrico;
                for ($i = 0; $i < count($materialelectrico); $i++) {
                    $material = Materialcarro::find($materialelectrico[$i]->id);
                    $material->cantidadenviada = $cantidades[$i]->cantidad + $cantidades[$i]->cantidadenviada;
                    $material->update();

                    $prod = Product::find($cantidades[$i]->producto_id);
                    if ($prod->tipo == "kit") {
                        $listap = $this->productosxkitxmaterial($material->id);
                        for ($x = 0; $x < count($listap); $x++) {
                            $micantidadenviada = ($listap[$x]->cantidad * $cantidades[$i]->cantidad);
                            $this->actualizarstock($listap[$x]->idproducto, $material->empresa_id, $micantidadenviada, 'RESTA');
                        }
                    } else {
                        $this->actualizarstock($cantidades[$i]->producto_id, $material->empresa_id, $cantidades[$i]->cantidad, 'RESTA');
                    }
                }
                return "1";
            } catch (\Throwable $th) {
                return "0";
            }
        } else {
            return "2";
        }
    }
    //obtenemos los productos de un kit
    public function productosxkit($kit_id)
    {
        $productosxkit = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->where('k.product_id', '=', $kit_id)
            ->select('p.id as idproducto', 'p.nombre as producto', 'k.cantidad')
            ->get();
        return  $productosxkit;
    }
    public function addmaterialadicional($id_produccion, $cantidad, $id_producto, $id_empresa, $tipo)
    {
        try {
            $materialadicional = new Materialcarro;
            $materialadicional->produccioncarro_id = $id_produccion;
            $materialadicional->cantidadenviada = 0;
            $materialadicional->cantidad = $cantidad;
            $materialadicional->producto_id = $id_producto;
            $materialadicional->observacion = "";
            $materialadicional->tipo = $tipo;
            $materialadicional->empresa_id = $id_empresa;
            $materialadicional->save();
            return $materialadicional->id;
        } catch (\Throwable $th) {
            return "-1";
        }
    }

    public function addmaterialdetalle(Request $request)
    {
        try {
            $ids = $request->listaids;
            $cantidad = $request->listacant;
            $idmaterial = $request->idmaterial;
            for ($i = 0; $i < count($ids); $i++) {
                $detallekitmaterial = new DetalleKitmateriales;
                $detallekitmaterial->detallematerialcarro_id = $idmaterial;
                $detallekitmaterial->product_id = $ids[$i];
                $detallekitmaterial->cantidad = $cantidad[$i];
                $detallekitmaterial->save();
            }
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function actualizarfecharRedMat($idproduccion, $redomat, $idregistro)
    {
        if ($redomat == "red") {
            $carros = DB::table('carros as c')
                ->where('c.produccioncarro_id', '=', $idproduccion)
                ->select('c.id')
                ->get();
            for ($i = 0; $i < count($carros); $i++) {
                $micarro = Carro::find($carros[$i]->id);
                $micarro->redenviada = "";
                $micarro->update();
            }

            $carros = DB::table('carros as c')
                ->where('c.produccioncarro_id', '=', $idproduccion)
                ->select('c.id')
                ->get();
            foreach ($carros as $carro) {
                $id_carro_deseado = $carro->id;

                $redes = DB::table('redcarros as rc')
                    ->leftJoin('envioreds as erc', function ($join) use ($id_carro_deseado) {
                        $join->on('rc.id', '=', 'erc.red_id')
                            ->where('erc.carro_id', '=', $id_carro_deseado);
                    })
                    ->where('rc.produccioncarro_id', '=', $idproduccion)
                    ->whereNull('erc.red_id')
                    ->select('rc.id', 'rc.cantidad', 'rc.cantidadenviada')
                    ->get();
                for ($i = 0; $i < count($redes); $i++) {
                    $envio = new Enviored;
                    $envio->fecha = "";
                    $envio->carro_id = $carro->id;
                    $envio->red_id = $redes[$i]->id;
                    $envio->save();
                }
            }
        } else if ($redomat == "mat") {
            $numenv = 0;
            $carros = DB::table('carros as c')
                ->where('c.produccioncarro_id', '=', $idproduccion)
                ->select('c.id')
                ->get();

            for ($i = 0; $i < count($carros); $i++) {
                $micarro = Carro::find($carros[$i]->id);
                $micarro->materialadicionalenv = "";
                $micarro->update();
                if ($micarro->materialelectricoenv != null || $micarro->materialelectricoenv != '') {
                    $numenv++;
                }
            }
            if ($numenv > 0) {
                $registro = Materialcarro::find($idregistro);
                if ($registro->tipo == "E") {
                    $registro->cantidadenviada = $registro->cantidad * $numenv;
                    $registro->update();
                    $producto = Product::find($registro->producto_id);
                    if ($producto->tipo == "kit") {
                        $listap = $this->productosxkit($producto->id);
                        for ($x = 0; $x < count($listap); $x++) {
                            $this->actualizarstock($listap[$x]->idproducto, $registro->empresa_id, ($registro->cantidad * $listap[$x]->cantidad * $numenv), 'RESTA');
                        }
                    } else {
                        $this->actualizarstock($registro->producto_id, $registro->empresa_id, $registro->cantidad * $numenv, 'RESTA');
                    }
                }
            }

            $carros = DB::table('carros as c')
                ->where('c.produccioncarro_id', '=', $idproduccion)
                ->get();
            foreach ($carros as $carro) {
                $id_carro_deseado = $carro->id;
                $material = DB::table('materialcarros as mc')
                    ->leftJoin('enviomaterials as emc', function ($join) use ($id_carro_deseado) {
                        $join->on('mc.id', '=', 'emc.material_id')
                            ->where('emc.carro_id', '=', $id_carro_deseado);
                    })
                    ->where('mc.produccioncarro_id', '=', $idproduccion)
                    ->where('mc.tipo', '=', 'A')
                    ->whereNull('emc.material_id')
                    ->select('mc.id', 'mc.cantidad', 'mc.cantidadenviada')
                    ->get();
                for ($i = 0; $i < count($material); $i++) {
                    $envio = new Enviomaterial;
                    $envio->fecha = "";
                    $envio->carro_id = $carro->id;
                    $envio->material_id = $material[$i]->id;
                    $envio->save();
                }
            }
        }
    }

    public function addredadicional($id_produccion, $cantidad, $id_producto, $id_empresa, $tipo)
    {
        try {
            $redadicional = new Redcarro;
            $redadicional->produccioncarro_id = $id_produccion;
            $redadicional->cantidadenviada = 0;
            $redadicional->cantidad = $cantidad;
            $redadicional->producto_id = $id_producto;
            $redadicional->empresa_id = $id_empresa;
            $redadicional->observacion = "";
            $redadicional->tipo = $tipo;
            $redadicional->save();
            return $redadicional->id;
        } catch (\Throwable $th) {
            return "-1";
        }
    }

    public function guardarenviomaterialA(Request $request)
    {
        try {
            $ids = $request->listaids;
            $fechas = $request->listafechas;
            for ($i = 0; $i < count($ids); $i++) {
                $envio = Enviomaterial::find($ids[$i]);
                if (!$envio->fecha && $fechas[$i]) {
                    $material = Materialcarro::find($envio->material_id);
                    if ($material) {
                        $material->cantidadenviada = $material->cantidadenviada + $material->cantidad;
                        $material->update();
                        $this->actualizarstockmaterialadicional($material, "RESTA");
                    }
                } else if ($envio->fecha   && !$fechas[$i]) {
                    $material = Materialcarro::find($envio->material_id);
                    if ($material) {
                        $material->cantidadenviada = $material->cantidadenviada - $material->cantidad;
                        $material->update();
                        $this->actualizarstockmaterialadicional($material, "SUMA");
                    }
                }
                $envio = Enviomaterial::find($ids[$i]);
                $envio->fecha = $fechas[$i];
                $envio->update();
            }
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function actualizarstockmaterialadicional($material, $sumaresta)
    {

        $producto = Product::find($material->producto_id);
        if ($producto->tipo == "kit") {
            $listaproductos = $this->productosxkitxmaterial($material->id);
            for ($i = 0; $i < count($listaproductos); $i++) {
                $cant = $material->cantidad * $listaproductos[$i]->cantidad;
                $this->actualizarstock($listaproductos[$i]->idproducto, $material->empresa_id, $cant, $sumaresta);
            }
        } else {
            $this->actualizarstock($producto->id, $material->empresa_id, $material->cantidad, $sumaresta);
        }
    }

    public function guardarenvioredes(Request $request)
    {
        try {
            $ids = $request->listaids;
            $fechas = $request->listafechas;
            for ($i = 0; $i < count($ids); $i++) {
                $envio = Enviored::find($ids[$i]);
                if (!$envio->fecha && $fechas[$i]) {
                    $red = Redcarro::find($envio->red_id);
                    if ($red) {
                        $red->cantidadenviada = $red->cantidadenviada + $red->cantidad;
                        $red->update();
                        $this->actualizarstockredes($red, "RESTA");
                    }
                } else if ($envio->fecha   && !$fechas[$i]) {
                    $red = Redcarro::find($envio->red_id);
                    if ($red) {
                        $red->cantidadenviada = $red->cantidadenviada - $red->cantidad;
                        $red->update();
                        $this->actualizarstockredes($red, "SUMA");
                    }
                }
                $envio = Enviored::find($ids[$i]);
                $envio->fecha = $fechas[$i];
                $envio->update();
            }
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function actualizarstockredes($red, $sumaresta)
    {

        $producto = Product::find($red->producto_id);
        if ($producto->tipo == "kit") {
            $listaproductos = $this->productosxkit($producto->id);
            for ($i = 0; $i < count($listaproductos); $i++) {
                $cant = $red->cantidad * $listaproductos[$i]->cantidad;
                $this->actualizarstock($listaproductos[$i]->idproducto, $red->empresa_id, $cant, $sumaresta);
            }
        } else {
            $this->actualizarstock($producto->id, $red->empresa_id, $red->cantidad, $sumaresta);
        }
    }

    public function productosxkitxmaterial($id)
    {
        $productosxkit = DB::table('products as p')
            ->join('detalle_kitmateriales as dkm', 'dkm.product_id', '=', 'p.id')
            ->where('dkm.detallematerialcarro_id', '=', $id)
            ->select('p.id as idproducto', 'p.nombre as producto', 'dkm.cantidad')
            ->get();
        return  $productosxkit;
    }

    public function materialadicionalxproduccion($id_produccion, $idcarro)
    {
        $id_carro_deseado = $idcarro;

        $material = DB::table('materialcarros as mc')
            ->leftJoin('enviomaterials as emc', function ($join) use ($id_carro_deseado) {
                $join->on('mc.id', '=', 'emc.material_id')
                    ->where('emc.carro_id', '=', $id_carro_deseado);
            })
            ->where('mc.produccioncarro_id', '=', $id_produccion)
            ->where('mc.tipo', '=', 'A')
            ->whereNull('emc.material_id')
            ->select('mc.id', 'mc.cantidad', 'mc.cantidadenviada')
            ->get();

        for ($i = 0; $i < count($material); $i++) {
            $envio = new Enviomaterial;
            $envio->fecha = "";
            $envio->carro_id = $idcarro;
            $envio->material_id = $material[$i]->id;
            $envio->save();
        }

        $envios = DB::table("enviomaterials as em")
            ->join('materialcarros as mc', 'em.material_id', '=', 'mc.id')
            ->join('products as p', 'mc.producto_id', '=', 'p.id')
            ->where('em.carro_id', '=', $idcarro)
            ->select('em.id as idenvio', 'em.fecha', 'mc.cantidad', 'p.tipo', 'p.nombre', 'p.id as idproducto', 'mc.id as idmaterial', 'em.carro_id')
            ->get();

        return $envios;
    }

    public function cantEnviadoMA($id_produccion)
    {

        $cantMA = DB::table("materialcarros as mc")
            ->where("mc.produccioncarro_id", '=', $id_produccion)
            ->where('mc.tipo', '=', 'A')
            ->select('mc.id as idmaterial', 'mc.cantidadenviada')
            ->get();
        return $cantMA;
    }

    public function cantEnviadoRed($id_produccion)
    {
        $cantRed = DB::table("redcarros as rc")
            ->where("rc.produccioncarro_id", '=', $id_produccion)
            ->select('rc.id as idmaterial', 'rc.cantidadenviada')
            ->get();
        return $cantRed;
    }

    public function fechaMA($id_carro, $fecha)
    {
        $nuevafecha = $fecha;
        if ($fecha == "-1") {
            $nuevafecha = "";
        }
        $carro = Carro::find($id_carro);
        $carro->materialadicionalenv = $nuevafecha;
        $carro->update();
        return "1";
    }

    public function fechaRed($id_carro, $fecha)
    {
        try {
            $nuevafecha = $fecha;
            if ($fecha == "-1") {
                $nuevafecha = "";
            }
            $carro = Carro::find($id_carro);
            $carro->redenviada = $nuevafecha;
            $carro->update();
            return "1";
        } catch (\Throwable $th) {
            return "0";
        }
    }

    public function todoenviado($id_produccion)
    {
        $material = DB::table("materialcarros as mc")
            ->join('produccioncarros as pc', 'pc.id', '=', 'mc.produccioncarro_id')
            ->where('mc.produccioncarro_id', '=', $id_produccion)
            ->select('mc.id as idmaterial', 'mc.cantidadenviada', 'mc.cantidad', 'mc.produccioncarro_id', 'pc.cantidad as cantidadcarros')
            ->get();

        $redes = DB::table("redcarros as rc")
            ->join('produccioncarros as pc', 'pc.id', '=', 'rc.produccioncarro_id')
            ->where('rc.produccioncarro_id', '=', $id_produccion)
            ->select('rc.id as idred', 'rc.cantidadenviada', 'rc.cantidad', 'rc.produccioncarro_id', 'pc.cantidad as cantidadcarros')
            ->get();

        //aca recorreer todo para ver si todo esta enviado
        $cont = 0;
        $cantMaterial = count($material);
        for ($i = 0; $i < count($material); $i++) {
            if (($material[$i]->cantidadcarros * $material[$i]->cantidad) <= $material[$i]->cantidadenviada) {
                $cont++;
            }
        }

        $contred = 0;
        $cantRedes = count($redes);
        for ($i = 0; $i < count($redes); $i++) {
            if (($redes[$i]->cantidadcarros * $redes[$i]->cantidad) <= $redes[$i]->cantidadenviada) {
                $contred++;
            }
        }

        $prod = Produccioncarro::find($material[0]->produccioncarro_id);
        if ($cont == $cantMaterial && $contred == $cantRedes) {
            $prod->todoenviado = "SI";
            $prod->update();
            return "1";
        } else {
            $prod->todoenviado = "NO";
            $prod->update();
            return "0";
        }
    }

    public function redesxproduccion($id_produccion, $idcarro)
    {
        $id_carro_deseado = $idcarro;
        $redes = DB::table('redcarros as rc')
            ->leftJoin('envioreds as er', function ($join) use ($id_carro_deseado) {
                $join->on('rc.id', '=', 'er.red_id')
                    ->where('er.carro_id', '=', $id_carro_deseado);
            })
            ->where('rc.produccioncarro_id', '=', $id_produccion)
            //->where('rc.tipo', '=', 'A')
            ->whereNull('er.red_id')
            ->select('rc.id', 'rc.cantidad', 'rc.cantidadenviada')
            ->get();

        for ($i = 0; $i < count($redes); $i++) {
            $envio = new Enviored;
            $envio->fecha = "";
            $envio->carro_id = $idcarro;
            $envio->red_id = $redes[$i]->id;
            $envio->save();
        }

        $envios = DB::table("envioreds as er")
            ->join('redcarros as rc', 'er.red_id', '=', 'rc.id')
            ->join('products as p', 'rc.producto_id', '=', 'p.id')
            ->where('er.carro_id', '=', $idcarro)
            ->select(
                'er.id as idenvio',
                'er.fecha',
                'rc.cantidad',
                'p.tipo',
                'p.nombre',
                'p.id as idproducto',
                'rc.id as idred'
            )
            ->get();

        return $envios;
    }

    public function crearfactura(Request $request)
    {
        $idcarros = $request->idcarros;
        $idproduccion = $request->idproduccion;
        $numerocarros = count($idcarros);
        $produccion = Produccioncarro::find($idproduccion);

        $materiales = DB::table('materialcarros as mc')
            ->where('mc.produccioncarro_id', '=', $idproduccion)
            ->select('mc.empresa_id')
            ->get();
        $redes = DB::table('redcarros as rc')
            ->where('rc.produccioncarro_id', '=', $idproduccion)
            ->select('rc.empresa_id')
            ->get();
        $empresas = $materiales->concat($redes);
        $empresasdistintas = $empresas->unique()->values()->all();
        $materialElectrico = DB::table('materialcarros as mc')
            ->where('mc.produccioncarro_id', '=', $idproduccion)
            ->where('mc.tipo', '=', 'E')
            ->select('mc.tipo', 'mc.id', 'mc.producto_id', 'mc.empresa_id', 'mc.cantidad')
            ->get();

        $materialAdicional = DB::table('materialcarros as mc')
            ->where('mc.produccioncarro_id', '=', $idproduccion)
            ->where('mc.tipo', '=', 'A')
            ->select('mc.tipo', 'mc.id', 'mc.producto_id', 'mc.empresa_id', 'mc.cantidad')
            ->get();
        $redesAdicional = DB::table('redcarros as rc')
            ->where('rc.produccioncarro_id', '=', $idproduccion)
            ->where('rc.tipo', '=', 'A')
            ->select('rc.tipo', 'rc.id', 'rc.producto_id', 'rc.empresa_id', 'rc.cantidad')
            ->get();
        $redesymateriales = $materialAdicional->concat($redesAdicional);
        $arnes = DB::table('products as p')
            ->where('p.modelo', '=', $produccion->modelo_id)
            ->where('p.carroceria', '=', $produccion->carroceria_id)
            ->select('p.id', 'p.moneda', 'p.NoIGV')
            ->get();

        $nombreBuscado = 'metalval';
        $metalval = DB::table('clientes')
            ->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($nombreBuscado) . '%'])
            ->get();

        $idcliente = $metalval[0]->id;
        $tasacambio = DB::table('datos as d')
            ->where('d.id', '=', '1')->get();
        $miventaFactura = "";

        for ($x = 0; $x < count($empresasdistintas); $x++) {
            if ($empresasdistintas[$x]->empresa_id == $materialElectrico[0]->empresa_id) {
                $preciofactura = 0;
                $precio = 0;
                //precio para el arnes
                $precioespecial = DB::table('listaprecios as lp')
                    ->where('lp.cliente_id', '=', $idcliente)
                    ->where('lp.product_id', '=', $arnes[0]->id)
                    ->select('lp.cliente_id', 'lp.product_id', 'lp.moneda', 'lp.preciounitariomo')
                    ->get();
                
                if (count($precioespecial) >0 ) {
                    if ($precioespecial[0]->moneda == "dolares") {
                        $precio = $precioespecial[0]->preciounitariomo;
                    } else {
                        $precio = round($precioespecial[0]->preciounitariomo / $tasacambio[0]->valor, 4);
                    }
                } else {
                    if ($arnes[0]->moneda == "dolares") {
                        $precio = $arnes[0]->NoIGV;
                    } else {
                        $precio = round($arnes[0]->NoIGV / $tasacambio[0]->valor, 4);
                    }
                }
                $nuevaventa = new Venta;
                $nuevaventa->moneda = "dolares";
                $nuevaventa->formapago = "contado";
                $nuevaventa->costoventa = $precio;
                $nuevaventa->fecha = date("Y-m-d");
                $nuevaventa->pagada = "NO";
                $nuevaventa->tasacambio = $tasacambio[0]->valor;
                $nuevaventa->cliente_id = $idcliente;
                $nuevaventa->company_id = $materialElectrico[0]->empresa_id;
                $nuevaventa->produccion_id = $idproduccion;
                $nuevaventa->save();
                $miventaFactura = $nuevaventa;
                //agregamos a la venta el arnes
                $nuevodetalleventa = new Detalleventa;
                $nuevodetalleventa->venta_id = $nuevaventa->id;
                $nuevodetalleventa->product_id = $arnes[0]->id;
                $nuevodetalleventa->cantidad = $numerocarros;
                $nuevodetalleventa->preciounitario = $precio;
                $nuevodetalleventa->servicio = 0;
                $nuevodetalleventa->preciounitariomo = $precio;
                $nuevodetalleventa->preciofinal = $precio * $numerocarros;
                $nuevodetalleventa->save();

                $preciofactura += ($precio * $numerocarros);

                for ($j = 0; $j < count($redesymateriales); $j++) {
                    if ($redesymateriales[$j]->empresa_id == $materialElectrico[0]->empresa_id) {
                        $productof = Product::find($redesymateriales[$j]->producto_id);
                        $preciof = 0;
                        $precioespecial = DB::table('listaprecios as lp')
                            ->where('lp.cliente_id', '=', $idcliente)
                            ->where('lp.product_id', '=', $redesymateriales[$j]->producto_id)
                            ->select('lp.cliente_id', 'lp.product_id', 'lp.moneda', 'lp.preciounitariomo')
                            ->get();
                        if (count($precioespecial) >0) {
                            if ($precioespecial[0]->moneda == "dolares") {
                                $preciof = $precioespecial[0]->preciounitariomo;
                            } else {
                                $preciof = round($precioespecial[0]->preciounitariomo / $tasacambio[0]->valor, 4);
                            }
                        } else {
                            if ($productof->moneda == "dolares") {
                                $preciof = $productof->NoIGV;
                            } else {
                                $preciof = round($productof->NoIGV / $tasacambio[0]->valor, 4);
                            }
                        }
 
                        //agregamos a la centa lso materiales y redes adicionales
                        $nuevodetalleventa = new Detalleventa;
                        $nuevodetalleventa->venta_id = $nuevaventa->id;
                        $nuevodetalleventa->product_id = $redesymateriales[$j]->producto_id;
                        $nuevodetalleventa->cantidad = $numerocarros * $redesymateriales[$j]->cantidad;
                        $nuevodetalleventa->preciounitario = $preciof;
                        $nuevodetalleventa->servicio = 0;
                        $nuevodetalleventa->preciounitariomo = $preciof;
                        $nuevodetalleventa->preciofinal = $preciof * $numerocarros * $redesymateriales[$j]->cantidad;
                        $nuevodetalleventa->save();
                        $preciofactura += ($preciof * $numerocarros * $redesymateriales[$j]->cantidad);
                        if ($productof->tipo == "kit") {
                            $detallesmaterial = 0;
                            $detallesmaterial = DB::table('detalle_kitmateriales as dkm')
                                ->where('dkm.detallematerialcarro_id', '=', $redesymateriales[$j]->id)
                                ->select('dkm.cantidad', 'dkm.product_id as idproducto')
                                ->get();
                            if (count($detallesmaterial) == 0) {
                                $detallesmaterial = DB::table('products as p')
                                    ->join('kits as k', 'k.product_id', '=', 'p.id')
                                    ->where('p.id', '=', $productof->id)
                                    ->select('k.cantidad', 'k.kitproduct_id as idproducto')
                                    ->get();
                            }
                            for ($z = 0; $z < count($detallesmaterial); $z++) {
                                $detallekit = new DetalleKitventa;
                                $detallekit->detalleventa_id = $nuevodetalleventa->id;
                                $detallekit->kitproduct_id = $detallesmaterial[$z]->idproducto;
                                $detallekit->cantidad = $detallesmaterial[$z]->cantidad;
                                $detallekit->save();
                            }
                        }
                    }
                }
                $nuevaventa->costoventa = round($preciofactura, 2);
                $nuevaventa->update();
            } else {
                $preciofacturaadicional = 0;
                $nuevaventaA = new Venta;
                $nuevaventaA->moneda = "dolares";
                $nuevaventaA->formapago = "contado";
                $nuevaventaA->costoventa = 0;
                $nuevaventaA->fecha = date("Y-m-d");
                $nuevaventaA->pagada = "NO";
                $nuevaventaA->tasacambio = $tasacambio[0]->valor;
                $nuevaventaA->cliente_id = $idcliente;
                $nuevaventaA->company_id = $empresasdistintas[$x]->empresa_id;
                $nuevaventaA->produccion_id = $idproduccion;
                $nuevaventaA->save();
                $miventaFactura = $nuevaventaA;
                for ($w = 0; $w < count($redesymateriales); $w++) {
                    if ($empresasdistintas[$x]->empresa_id == $redesymateriales[$w]->empresa_id) {
                        $productofA = Product::find($redesymateriales[$w]->producto_id);
                        $mipreciop = 0;
                        $precioespecial = DB::table('listaprecios as lp')
                            ->where('lp.cliente_id', '=', $idcliente)
                            ->where('lp.product_id', '=', $redesymateriales[$w]->producto_id)
                            ->select('lp.cliente_id', 'lp.product_id', 'lp.moneda', 'lp.preciounitariomo')
                            ->get();
                            if (count($precioespecial)>0) {
                                if ($precioespecial[0]->moneda == "dolares") {
                                    $mipreciop = $precioespecial[0]->preciounitariomo;
                                } else {
                                    $mipreciop = round($precioespecial[0]->preciounitariomo / $tasacambio[0]->valor, 4);
                                }
                            } else {
                                if ($productofA->moneda == "dolares") {
                                    $mipreciop = $productofA->NoIGV;
                                } else {
                                    $mipreciop = round($productofA->NoIGV / $tasacambio[0]->valor, 4);
                                }
                            }
                        
                        $nuevodetalleventa = new Detalleventa;
                        $nuevodetalleventa->venta_id = $nuevaventaA->id;
                        $nuevodetalleventa->product_id = $redesymateriales[$w]->producto_id;
                        $nuevodetalleventa->cantidad = $numerocarros * $redesymateriales[$w]->cantidad;
                        $nuevodetalleventa->preciounitario = $mipreciop;
                        $nuevodetalleventa->servicio = 0;
                        $nuevodetalleventa->preciounitariomo = $mipreciop;
                        $nuevodetalleventa->preciofinal = $mipreciop * $numerocarros * $redesymateriales[$w]->cantidad;
                        $nuevodetalleventa->save();
                        $preciofacturaadicional += $mipreciop * $numerocarros * $redesymateriales[$w]->cantidad;
                        if ($productofA->tipo == "kit") {
                            $detallesmaterial = 0;
                            $detallesmaterial = DB::table('detalle_kitmateriales as dkm')
                                ->where('dkm.detallematerialcarro_id', '=', $redesymateriales[$w]->id)
                                ->select('dkm.cantidad', 'dkm.product_id as idproducto')
                                ->get();
                            if (count($detallesmaterial) == 0) {
                                $detallesmaterial = DB::table('products as p')
                                    ->join('kits as k', 'k.product_id', '=', 'p.id')
                                    ->where('p.id', '=', $productofA->id)
                                    ->select('k.cantidad', 'k.kitproduct_id as idproducto')
                                    ->get();
                            }
                            for ($z = 0; $z < count($detallesmaterial); $z++) {
                                $detallekit = new DetalleKitventa;
                                $detallekit->detalleventa_id = $nuevodetalleventa->id;
                                $detallekit->kitproduct_id = $detallesmaterial[$z]->idproducto;
                                $detallekit->cantidad = $detallesmaterial[$z]->cantidad;
                                $detallekit->save();
                            }
                        }
                        $nuevaventaA->costoventa = $preciofacturaadicional;
                        $nuevaventaA->update();
                    }
                }
            }
            for ($c = 0; $c < count($idcarros); $c++) {

                $micarro = Carro::find($idcarros[$c]);
                if ($x == 0) {
                    $micarro->electrobus_id = $miventaFactura->id;
                } else if ($x == 1) {
                    $micarro->delmy_id = $miventaFactura->id;
                } else if ($x == 2) {
                    $empresa = Company::find($miventaFactura->company_id);
                    $micarro->otraempresa_id = $miventaFactura->id;
                    $micarro->empresaO = $empresa->nombre;
                }
                $micarro->update();
            }
        }
        return "1";
    }

    public function traercarros($idproduccion)
    {
        $carros = DB::table('carros as c')
            ->where('c.produccioncarro_id', '=', $idproduccion)
            ->select('c.id as idcarro', 'c.electrobus_id')
            ->get();
        $contadorfacturado = 0;
        for ($i = 0; $i < count($carros); $i++) {
            if ($carros[$i]->electrobus_id != null && $carros[$i]->electrobus_id != '') {
                $contadorfacturado++;
            }
        }
        if ($contadorfacturado == count($carros)) {
            $produccion = Produccioncarro::find($idproduccion);
            $produccion->facturado = "SI";
            $produccion->update();
        }
        return $carros;
    }
}
