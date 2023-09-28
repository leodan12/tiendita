<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    { 
        $usuario = Auth::user()->name;
        return redirect('admin/dashboard'); 
    }
    public function home()
    { 
        $usuario = Auth::user()->name;
        return redirect('admin/dashboard')->with('message','Bienvenido usuario '.$usuario); 
    }
    public function inicio()
    {  
        $uniformessinstock = $this->uniformesinstock();
        $librossinstock = $this->librossinstock();
        $ventasxcobrar = $this->numeroingresos('credito', 'NO', '2010-01-01');

        return view('admin.dashboard', compact('uniformessinstock', 'librossinstock', 'ventasxcobrar'));
    }

    public function uniformesinstock()
    {
        $numerosinstock = DB::table('uniformes as u')
        ->whereRaw('u.stock1 + u.stock2 < u.stockmin')
        ->count();

        return $numerosinstock;
    }

    public function librossinstock()
    {
        $numerosinstock = DB::table('libros as l')
        ->whereRaw('l.stock1 + l.stock2 < l.stockmin')
        ->count();

        return   $numerosinstock;
    }
    public function numeroingresos($formapago, $pagado, $inicio)
    {
        $ventas = "";
        $ventas = DB::table('ingresos as i')
            ->where('i.formapago', '=', $formapago)
            ->where('i.fecha', '>', $inicio)
            ->where('i.pagada', '=', $pagado)
            ->count();

        return   $ventas;
    }
}
