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
        return redirect('admin/dashboard')->with('message', 'Bienvenido usuario ' . $usuario);
    }
    public function inicio()
    {
        $uniformessinstock = $this->uniformesinstock();
        $librossinstock = $this->librossinstock();
        $instrumentossinstock = $this->librossinstock();
        $utilessinstock = $this->librossinstock();
        $golosinassinstock = $this->librossinstock();
        dump($uniformessinstock);
        dump($librossinstock);
        dump($instrumentossinstock);
        dump($utilessinstock);
        dump($golosinassinstock);
        return view(
            'admin.dashboard',
            compact(
                'uniformessinstock',
                'librossinstock',
                'instrumentossinstock',
                'utilessinstock',
                'golosinassinstock'
            )
        );
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
    public function instrumentossinstock()
    {
        $numerosinstock = DB::table('instrumentos as l')
            ->whereRaw('l.stock1 + l.stock2 < l.stockmin')
            ->count();

        return   $numerosinstock;
    }
    public function utilessinstock()
    {
        $numerosinstock = DB::table('utiles as l')
            ->whereRaw('l.stock1 + l.stock2 < l.stockmin')
            ->count();

        return   $numerosinstock;
    }
    public function golosinassinstock()
    {
        $numerosinstock = DB::table('golosinas as l')
            ->whereRaw('l.stock1 + l.stock2 < l.stockmin')
            ->count();

        return   $numerosinstock;
    }
}
