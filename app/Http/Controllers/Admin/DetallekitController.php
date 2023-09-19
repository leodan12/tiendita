<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Kit;
use App\Models\Category;
use App\Models\Inventario;
use App\Models\Carroceria;
use App\Models\Modelocarro;
use App\Models\Detallecotizacion;
use Illuminate\Http\Request;
use App\Http\Requests\KitFormRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class DetallekitController extends Controller
{   //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-kit|editar-kit|crear-kit|eliminar-kit', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-kit', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-kit', ['only' => ['edit', 'update', 'destroydetallekit']]);
        $this->middleware('permission:eliminar-kit', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-kit', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        $datoseliminados = DB::table('products as c')
            ->where('c.status', '=', 1)
            ->where('c.tipo', '=', 'kit')
            ->select('c.id')
            ->count();
        if ($request->ajax()) {
            $kits = DB::table('products as p')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->select(
                    'p.id',
                    'c.nombre as categoria',
                    'p.nombre',
                    'p.codigo',
                    'p.unidad',
                    'p.moneda',
                    'p.NoIGV',
                    'p.SiIGV',
                    'p.preciocompra',
                )->where('p.status', '=', 0)
                ->where('p.tipo', '!=', 'estandar');
            return DataTables::of($kits)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($kits) {
                    return view('admin.kit.botones', compact('kits'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }

        return view('admin.kit.index', compact('datoseliminados'));
    }
    //vista crear registro
    public function create()
    {
        $categories = Category::all()->where('status', '=', 0);
        $products = Product::all()
            ->where('status', '=', 0)
            ->where('tipo', '=', 'estandar');
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);
        return view('admin.kit.create', compact('categories', 'products', 'carrocerias', 'modelos'));
    }
    //funcion para guardar un registro
    public function store(KitFormRequest $request)
    {
        $validatedData = $request->validated();
        $producto = new Product;
        $producto->category_id = $validatedData['category_id'];
        $producto->nombre = $validatedData['nombre'];
        $producto->codigo = $validatedData['codigo'];
        $producto->unidad = "UNIDAD";
        if (!$request->carroceria_id && !$request->modelo_id) {
            $producto->tipo = "kit";
        } else if ($request->carroceria_id && $request->modelo_id) {
            $producto->tipo = "arnes";
        }
        $producto->unico = 0;
        $producto->maximo = $validatedData['NoIGV'];
        $producto->minimo = $validatedData['NoIGV'];
        $producto->moneda = $validatedData['moneda'];
        $producto->NoIGV = $validatedData['NoIGV'];
        $producto->SiIGV = $validatedData['SiIGV'];
        $producto->preciocompra = $validatedData['preciocompra'];
        $producto->tasacambio = $request->tasacambio;
        $producto->carroceria = $request->carroceria_id;
        $producto->modelo = $request->modelo_id;
        $producto->status =  '0';
        $producto->preciofob = $request->preciofob;
        if ($request->cantidad2 != null && $request->precio2 != null) {
            $producto->cantidad2 = $request->cantidad2;
            $producto->precio2 = $request->precio2;
            if ($request->cantidad3 != null && $request->precio3 != null) {
                $producto->cantidad3 = $request->cantidad3;
                $producto->precio3 = $request->precio3;
            }
        }
        if ($producto->save()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detallekit = new Kit;
                    $Detallekit->product_id = $producto->id;
                    $Detallekit->kitproduct_id = $product[$i];
                    $Detallekit->cantidad = $cantidad[$i];
                    $Detallekit->preciounitario = $preciounitario[$i];
                    $Detallekit->preciounitariomo = $preciounitariomo[$i];
                    $Detallekit->preciofinal = $preciofinal[$i];
                    $Detallekit->save();
                }
            }
            $this->crearhistorial('crear', $producto->id, $producto->nombre, null, 'kits');
            return redirect('admin/kits')->with('message', 'Kit de Productos Agregado Satisfactoriamente');
        } else {
            return redirect('admin/kits')->with('message', 'No se pudo agregar el kit');
        }
    }
    //vista editar
    public function edit(int $kit_id)
    {
        $lascategorias = Category::all()->where('status', '=', 0);
        $product = Product::findOrFail($kit_id);
        $micategoria = Category::all()
            ->where('id', '=', $product->category_id)
            ->where('status', '=', 1);
        if ($micategoria) {
            $categories = $lascategorias->concat($micategoria);
        }
        $products = Product::all()
            ->where('status', '=', 0)
            ->where('tipo', '=', 'estandar');
        $kitdetalles = DB::table('products as p')
            ->join('kits as k', 'k.kitproduct_id', '=', 'p.id')
            ->select(
                'p.moneda',
                'k.id',
                'k.product_id',
                'k.kitproduct_id',
                'k.cantidad',
                'k.preciounitario',
                'k.preciounitariomo',
                'k.preciofinal',
                'p.nombre as producto'
            )
            ->where('k.product_id', '=', $kit_id)->get();

        $productoskit = $this->traerdetallesredmaterial($product->carroceria, $product->modelo);
        $materialesyredes = $this->traermaterialyredes($product->carroceria, $product->modelo);
        $carrocerias = Carroceria::all()->where('status', '=', 0);
        $modelos = Modelocarro::all()->where('status', '=', 0);

        return view('admin.kit.edit', compact(
            'categories',
            'product',
            'kitdetalles',
            'products',
            'materialesyredes',
            'carrocerias',
            'modelos',
            'productoskit'
        ));
    }
    //funcion para actualizar un registro
    public function update(Request $request, int $kit_id)
    {
        $producto = Product::findOrFail($kit_id);
        $producto->category_id = $request->category_id;
        $producto->nombre = $request->nombre;
        $producto->codigo = $request->codigo;
        //$producto->maximo = $request->NoIGV;
        //$producto->minimo = $request->NoIGV;
        //$producto->moneda = $request->moneda;// no
        $producto->preciocompra = $request->preciocompra;
        //$producto->tasacambio = $request->tasacambio; //no
        $producto->NoIGV = $request->NoIGV;
        $producto->SiIGV = $request->SiIGV;
        $producto->status =  '0';
        $producto->preciofob = $request->preciofob;
        if ($request->cantidad2 != null && $request->precio2 != null) {
            $producto->cantidad2 = $request->cantidad2;
            $producto->precio2 = $request->precio2;
            if ($request->cantidad3 != null && $request->precio3 != null) {
                $producto->cantidad3 = $request->cantidad3;
                $producto->precio3 = $request->precio3;
            }
        } else {
            $producto->cantidad2 = null;
            $producto->precio2 =  null;
        }
        if ($request->cantidad3 == null || $request->precio3 == null) {
            $producto->cantidad3 = null;
            $producto->precio3 =  null;
        }
        if ($producto->update()) {
            $product = $request->Lproduct;
            $cantidad = $request->Lcantidad;
            $preciounitario = $request->Lpreciounitario;
            $preciofinal = $request->Lpreciofinal;
            $preciounitariomo = $request->Lpreciounitariomo;
            if ($product !== null) {
                for ($i = 0; $i < count($product); $i++) {
                    $Detallekit = new Kit;
                    $Detallekit->product_id = $producto->id;
                    $Detallekit->kitproduct_id = $product[$i];
                    $Detallekit->cantidad = $cantidad[$i];
                    $Detallekit->preciounitario = $preciounitario[$i];
                    $Detallekit->preciounitariomo = $preciounitariomo[$i];
                    $Detallekit->preciofinal = $preciofinal[$i];
                    $Detallekit->save();
                }
            }
            $this->crearhistorial('editar', $producto->id, $producto->nombre, null, 'kits');
            return redirect('admin/kits')->with('message', 'Kit de Productos Actualizado Satisfactoriamente');
        } else {
            return redirect('admin/kits')->with('message', 'No se pudo actualizar el kit');
        }
    }
    //funcion para mstra el modal ve registro
    public function show($id)
    {
        $product = "";
        $prod = Product::find($id);
        if ($prod->tipo == "kit") {
            $product = DB::table('products as p')
                ->join('categories as ct', 'p.category_id', '=', 'ct.id')
                ->select(
                    'p.maximo',
                    'p.minimo',
                    'ct.nombre as nombrecategoria',
                    'p.nombre',
                    'p.codigo',
                    'p.unidad',
                    'p.preciofob',
                    'p.moneda',
                    'p.NoIGV',
                    'p.SiIGV',
                    'p.tasacambio',
                    'p.tipo',
                    'p.carroceria',
                    'p.modelo',
                )
                ->where('p.id', '=', $id)->get();
        } else {
            $product = DB::table('products as p')
                ->join('categories as ct', 'p.category_id', '=', 'ct.id')
                ->join('carrocerias as cc', 'p.carroceria', '=', 'cc.id')
                ->join('modelocarros as m', 'p.modelo', '=', 'm.id')
                ->select(
                    'p.maximo',
                    'p.minimo',
                    'ct.nombre as nombrecategoria',
                    'p.nombre',
                    'p.codigo',
                    'p.unidad',
                    'p.preciofob',
                    'p.moneda',
                    'p.NoIGV',
                    'p.SiIGV',
                    'p.tasacambio',
                    'p.tipo',
                    'cc.tipocarroceria',
                    'm.modelo',
                    'm.id as idmodelo',
                    'cc.id as idcarroceria'
                )
                ->where('p.id', '=', $id)->get();
        }
        return  $product;
    }

    public function showdetallekit($id)
    {
        $datos = "";
        $producto = Product::find($id);
        if ($producto->tipo == "kit") {
            $datos = DB::table('products as p')
                ->join('kits as k', 'k.product_id', '=', 'p.id')
                ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
                ->select(
                    'k.kitproduct_id as idkitproduct',
                    'pk.nombre as kitproductname',
                    'pk.maximo as kitproductmaximo',
                    'pk.minimo as kitproductminimo',
                    'pk.codigo as kitproductcodigo',
                    'pk.unidad as kitproductunidad',
                    'pk.moneda as kitproductmoneda',
                    'pk.NoIGV as kitproductnoigv',
                    'pk.SiIGV as kitproductsiigv',
                    'k.cantidad as kitcantidad',
                    'k.preciounitario as kitpreciounitario',
                    'k.preciounitariomo as kitpreciounitariomo',
                    'p.tasacambio',
                    'k.preciofinal as kitpreciofinal'
                )
                ->where('p.id', '=', $id)->get();
        } else {
            $datos = $this->traermaterialyredes($producto->carroceria, $producto->modelo);
        }
        return  $datos;
    }
    //funcion para eliminar o solo ocultar un registro
    public function destroy(int $kit_id)
    {
        $product = Product::find($kit_id);
        if ($product) {

            try {
                $product->delete();
                $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'kits');
                return "1";
            } catch (\Throwable $th) {
                $product->status = 1;
                $product->update();
                $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'kits');
                return "1";
            }
        } else {
            return "2";
        }
    }
    //funcion para eliminar un detalle del registro
    public function destroydetallekit($id)
    {
        $detallekit = Kit::find($id);
        $productoh = DB::table('products as p')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->where('k.id', '=', $id)
            ->select('p.id', 'p.nombre')->first();
        if ($detallekit) {
            $kit = DB::table('kits as dc')
                ->join('products as p', 'dc.product_id', '=', 'p.id')
                ->select('p.id', 'dc.preciofinal', 'p.NoIGV')
                ->where('dc.id', '=', $id)->first();
            if ($detallekit->delete()) {
                $costof = $kit->NoIGV;
                $detalle = $kit->preciofinal;
                $editprod = Product::findOrFail($kit->id);
                $editprod->NoIGV = $costof - $detalle;
                $editprod->SiIGV = round(($costof - $detalle) * 1.18, 2);
                $editprod->update();
                $this->crearhistorial('editar', $productoh->id, $productoh->nombre, null, 'kits');
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }
    //funcion para mostrar los registros eliminados que se pueden restaurar
    public function showrestore()
    {
        $productos   = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.status', '=', 1)
            ->where('p.tipo', '=', 'kit')
            ->select(
                'p.id',
                'c.nombre as categoria',
                'p.nombre',
                'p.codigo',
                'p.unidad',
                'p.moneda',
                'p.NoIGV',
                'p.SiIGV',
            )->get();
        return $productos->values()->all();
    }
    //funcion para restaurar un registro eliminado
    public function restaurar($idregistro)
    {
        $registro = Product::find($idregistro);
        if ($registro) {
            $registro->status = 0;
            if ($registro->update()) {
                $this->crearhistorial('restaurar', $registro->id, $registro->nombre, null, 'kits');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }

    public function traermaterialyredes($carroceria_id, $modelo_id)
    {
        //$materialyredesestandar= collect();
        $materialelectrico =  DB::table('materialelectricos as me')
            ->join('detallecarrocerias as dc', 'dc.materialelectrico_id', '=', 'me.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->select('dc.cantidad', 'dc.unidad', 'p.nombre', 'p.tipo', 'p.id as idproducto')
            ->where('me.carroceria_id', '=', $carroceria_id)
            ->where('me.modelo_id', '=', $modelo_id)
            ->get();

        $redesestandar = DB::table('reds as r')
            ->join('detallereds as dr', 'dr.red_id', '=', 'r.id')
            ->join('products as p', 'dr.producto_id', '=', 'p.id')
            ->select('dr.cantidad', 'dr.unidad', 'p.nombre', 'p.tipo', 'p.id as idproducto')
            ->where('r.carroceria_id', '=', $carroceria_id)
            ->where('r.modelo_id', '=', $modelo_id)
            ->get();


        $materialyredesestandar = $materialelectrico->concat($redesestandar);

        return $materialyredesestandar;
    }

    public function traerdetallesredmaterial($carroceria_id, $modelo_id)
    {
        
        $materialelectrico =  DB::table('materialelectricos as me')
            ->join('detallecarrocerias as dc', 'dc.materialelectrico_id', '=', 'me.id')
            ->join('products as p', 'dc.producto_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select('k.cantidad', 'pk.nombre', 'p.id as idproducto')
            ->where('me.carroceria_id', '=', $carroceria_id)
            ->where('me.modelo_id', '=', $modelo_id)
            ->get();

        $redesestandar = DB::table('reds as r')
            ->join('detallereds as dr', 'dr.red_id', '=', 'r.id')
            ->join('products as p', 'dr.producto_id', '=', 'p.id')
            ->join('kits as k', 'k.product_id', '=', 'p.id')
            ->join('products as pk', 'k.kitproduct_id', '=', 'pk.id')
            ->select('k.cantidad', 'pk.nombre', 'p.id as idproducto')
            ->where('r.carroceria_id', '=', $carroceria_id)
            ->where('r.modelo_id', '=', $modelo_id)
            ->get();


        $materialyredesestandar = $materialelectrico->concat($redesestandar);

        return $materialyredesestandar;
    }
}
