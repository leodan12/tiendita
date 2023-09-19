<?php

namespace App\Traits;

use App\Models\Inventario;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Detalleinventario;

trait ActualizarStockTrait
{
    public function actualizarstock($idproducto, $idempresa, $cantidad, $sumaoresta)
    {
        $detalle = DB::table('detalleinventarios as di')
            ->join('inventarios as i', 'di.inventario_id', '=', 'i.id')
            ->where('i.product_id', '=', $idproducto)
            ->where('di.company_id', '=', $idempresa)
            ->select('di.id')
            ->first();
        //actualizamos el inventario restandole la cantidad vendida 
        if (!$detalle) {
            $detalle = $this->creardetalleinventario($idempresa, $idproducto);
        }
        $detalleinventario = Detalleinventario::find($detalle->id);
        if ($detalleinventario) {
            $mistock = $detalleinventario->stockempresa;
            if ($sumaoresta == "RESTA") {
                $mistock = (($detalleinventario->stockempresa) - $cantidad);
            } else {
                $mistock = (($detalleinventario->stockempresa) + $cantidad);
            }
            $detalleinventario->stockempresa = $mistock;
            if ($detalleinventario->update()) {
                $inventario = Inventario::find($detalleinventario->inventario_id);
                $mistockt = $inventario->stocktotal;
                if ($sumaoresta == "RESTA") {
                    $mistockt = $inventario->stocktotal - $cantidad;
                } else {
                    $mistockt = $inventario->stocktotal + $cantidad;
                }
                $inventario->stocktotal = $mistockt;
                $inventario->update();
            }
        }
    }
}
