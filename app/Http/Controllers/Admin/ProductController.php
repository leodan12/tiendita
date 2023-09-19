<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inventario; 
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductFormRequest;
use App\Models\Detalleinventario;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Traits\HistorialTrait;

class ProductController extends Controller
{ //para asignar los permisos a las funciones
    function __construct()
    {
        $this->middleware('permission:ver-producto|editar-producto|crear-producto|eliminar-producto', ['only' => ['index', 'show']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
        $this->middleware('permission:recuperar-producto', ['only' => ['showrestore', 'restaurar']]);
    }
    use HistorialTrait;
    //vista index datos para (datatables-yajra)
    public function index(Request $request)
    {
        $datoseliminados = DB::table('products as p')
            ->where('p.status', '=', 1)
            ->where('p.tipo', '=', 'estandar')
            ->select('p.id')
            ->count();
        if ($request->ajax()) {
            $productos = DB::table('products as p')
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
                ->where('p.tipo', '=', 'estandar');
            return DataTables::of($productos)
                ->addColumn('acciones', 'Acciones')
                ->editColumn('acciones', function ($productos) {
                    return view('admin.products.botones', compact('productos'));
                })
                ->rawColumns(['acciones'])
                ->make(true);
        }
        return view('admin.products.index', compact('datoseliminados'));
    }
    //vista crear
    public function create()
    {
        $categories = Category::all()->where('status', '=', 0);
        return view('admin.products.create', compact('categories'));
    }
    //funcion para guardar un registro
    public function store(ProductFormRequest $request)
    {   //validamos y asignamos los datos a un registro nuevo
        $validatedData = $request->validated();
        $product = new Product;
        $product->category_id = $validatedData['category_id'];
        $product->nombre = $validatedData['nombre'];
        $product->codigo = $validatedData['codigo'];
        $product->unidad = $validatedData['unidad'];
        $product->tipo = "estandar";
        $product->unico = 0;
        $product->maximo = $validatedData['NoIGV'];
        $product->minimo = $validatedData['NoIGV'];
        $product->moneda = $validatedData['moneda'];
        $product->NoIGV = $validatedData['NoIGV'];
        $product->SiIGV = $validatedData['SiIGV'];
        $product->preciocompra = $validatedData['preciocompra'];
        $product->status =  '0';
        $product->preciofob =  $request->preciofob;
        if ($request->cantidad2 != null && $request->precio2 != null) {
            $product->cantidad2 = $request->cantidad2;
            $product->precio2 = $request->precio2;
            if ($request->cantidad3 != null && $request->precio3 != null) {
                $product->cantidad3 = $request->cantidad3;
                $product->precio3 = $request->precio3;
            }
        }
        //guardamos el registro y creamos un nuevo inventario
        $product->save();
        $inventario = new Inventario;
        $inventario->product_id = $product->id;
        $inventario->stockminimo = 5;
        $inventario->stocktotal = 0;
        $inventario->status = 0;
        $inventario->save();
        $empresas = Company::all(); 
        for($i=0;$i<count($empresas);$i++){
            $detalle = new Detalleinventario;
            $detalle->inventario_id = $inventario->id;
            $detalle->company_id = $empresas[$i]->id;
            $detalle->stockempresa = 0;
            $detalle->save();
        }
        $this->crearhistorial('crear', $inventario->id, $product->nombre, null, 'inventarios');
        $this->crearhistorial('crear', $product->id, $product->nombre, null, 'productos');
        return redirect('admin/products')->with('message', 'Producto Agregado Satisfactoriamente');
    }
    //vista editar
    public function edit(int $product_id)
    {
        $lascategorias = Category::all()->where('status', '=', 0);
        $product = Product::findOrFail($product_id);
        $micategoria = Category::all()
            ->where('id', '=', $product->category_id)
            ->where('status', '=', 1);
        if ($micategoria) {
            $categories = $lascategorias->concat($micategoria);
        }
        return view('admin.products.edit', compact('categories', 'product'));
    }
    //funcion para actualizar un registro
    public function update(ProductFormRequest $request, int $product_id)
    {   //validamos los datos y asignamos los nuevos datos al registro
        $validatedData = $request->validated();
        $categoria = Product::findOrFail($product_id);
        $product = Category::findOrFail($categoria->category_id)
            ->products()->where('id', $product_id)->first();
        if ($product) {
            $product->category_id = $validatedData['category_id'];
            $product->nombre = $validatedData['nombre'];
            $product->codigo = $request->codigo;
            $product->unidad = $validatedData['unidad'];
            $product->tipo = "estandar";
            $product->unico = 0;
            $product->maximo = $validatedData['maximo'];
            $product->minimo = $validatedData['minimo'];
            $product->moneda = $validatedData['moneda'];
            $product->NoIGV = $validatedData['NoIGV'];
            $product->SiIGV = $validatedData['SiIGV'];
            $product->preciocompra = $validatedData['preciocompra'];
            $product->status =  '0';
            $product->preciofob =  $request->preciofob;
            if ($request->cantidad2 != null && $request->precio2 != null) {
                $product->cantidad2 = $request->cantidad2;
                $product->precio2 = $request->precio2;
                if ($request->cantidad3 != null && $request->precio3 != null) {
                    $product->cantidad3 = $request->cantidad3;
                    $product->precio3 = $request->precio3;
                }
            } else {
                $product->cantidad2 = null;
                $product->precio2 =  null;
            }
            if ($request->cantidad3 == null || $request->precio3 == null) {
                $product->cantidad3 = null;
                $product->precio3 =  null;
            }
            $product->update();
            $this->crearhistorial('editar', $product->id, $product->nombre, null, 'productos');
            return redirect('/admin/products')->with('message', 'Producto Actualizado Satisfactoriamente');
        } else {
            return redirect('admin/products')->with('message', 'No se encontro el ID del Producto');
        }
    }
    //funcion para eliminar un registro
    public function destroy(int $product_id)
    {
        $product = Product::find($product_id);
        if ($product) {
            try {
                $product->delete();
                $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'productos');
                return "1";
            } catch (\Throwable $th) {
                $product->status = 1;
                $product->update();
                $this->crearhistorial('eliminar', $product->id, $product->nombre, null, 'productos');
                return "1";
            }
        } else {
            return "2";
        }
    }
    //funcion para mostrar los datos del producto en el modal
    public function show($id)
    {
        $product = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->select(
                'p.maximo',
                'p.minimo',
                'c.nombre as nombrecategoria',
                'p.tipo',
                'p.nombre',
                'p.codigo',
                'p.unidad',
                'p.preciofob',
                'p.moneda',
                'p.NoIGV',
                'p.SiIGV',
                'p.cantidad2',
                'p.precio2',
                'p.cantidad3',
                'p.precio3'
            )
            ->where('p.id', '=', $id)->first();
        return  $product;
    }
    //funcion para mostrar los registros eliminados que se pueden restaurar
    public function showrestore()
    {
        $productos   = DB::table('products as p')
            ->join('categories as c', 'p.category_id', '=', 'c.id')
            ->where('p.status', '=', 1)
            ->where('p.tipo', '=', 'estandar')
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
        $producto = Product::find($idregistro);
        if ($producto) {
            $producto->status = 0;
            if ($producto->update()) {
                $this->crearhistorial('restaurar', $producto->id, $producto->nombre, null, 'productos');
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }
}
