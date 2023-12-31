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
        $instrumentossinstock = $this->instrumentossinstock();
        $utilessinstock = $this->utilessinstock();
        $golosinassinstock = $this->golosinassinstock(); 
        $snackssinstock = $this->snackssinstock(); 
        return view(
            'admin.dashboard',
            compact(
                'uniformessinstock',
                'librossinstock',
                'instrumentossinstock',
                'utilessinstock',
                'golosinassinstock',
                'snackssinstock'
            )
        );
    }

    public function uniformesinstock()
    {
        $numerosinstock = DB::table('uniformes as ut')
            ->whereRaw('ut.stock1 < ut.stockmin OR ut.stock2 < ut.stockmin OR ut.stock3 < ut.stockmin')
            ->count();

        return $numerosinstock;
    }
    public function librossinstock()
    {
        $numerosinstock = DB::table('libros as ut')
            ->whereRaw('ut.stock1 < ut.stockmin OR ut.stock2 < ut.stockmin OR ut.stock3 < ut.stockmin')
            ->count();

        return   $numerosinstock;
    }
    public function instrumentossinstock()
    {
        $numerosinstock = DB::table('instrumentos as ut')
            ->whereRaw('ut.stock1 < ut.stockmin OR ut.stock2 < ut.stockmin OR ut.stock3 < ut.stockmin')
            ->count();

        return   $numerosinstock;
    }
    public function utilessinstock()
    {
        $numerosinstock = DB::table('utiles as ut')
            ->whereRaw('ut.stock1 < ut.stockmin OR ut.stock2 < ut.stockmin OR ut.stock3 < ut.stockmin')
            ->count();

        return   $numerosinstock;
    }
    public function golosinassinstock()
    {
        $numerosinstock = DB::table('golosinas as ut')
            ->whereRaw('ut.stock1 < ut.stockmin OR ut.stock2 < ut.stockmin OR ut.stock3 < ut.stockmin')
            ->count();

        return   $numerosinstock;
    }
    public function snackssinstock()
    {
        $numerosinstock = DB::table('snacks as ut')
            ->whereRaw('ut.stock1 < ut.stockmin OR ut.stock2 < ut.stockmin OR ut.stock3 < ut.stockmin')
            ->count();

        return   $numerosinstock;
    }
}
