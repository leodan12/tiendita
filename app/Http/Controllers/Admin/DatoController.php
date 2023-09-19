<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dato;
use Illuminate\Support\Facades\DB;

class DatoController extends Controller
{

    public function vertasacambio()
    {
        $tasa = DB::table('datos as d')
            ->where('d.nombre', '=', 'tasacambio')
            ->select('d.nombre', 'd.valor', 'd.fecha', 'd.id')
            ->first();
        //return $tasa;
        $dato = collect();
        $dia = substr($tasa->fecha,-2,2);
        $mes = substr($tasa->fecha,5,2);
        $anio = substr($tasa->fecha,0,4);
        $mifecha = $dia.'/'.$mes.'/'.$anio;
        $dato->put('tasacambio', $tasa->nombre);
        $dato->put('valor', $tasa->valor);
        $dato->put('fecha', $mifecha);
        $dato->put('fechatasa', $tasa->fecha);
        $dato->put('id', $tasa->id);
        return $dato;
    }

    public function actualizartasacambio($tasacambio, $fecha, $id)
    {
        $tasa = Dato::find($id);
        if ($tasa) {
            $tasa->valor = $tasacambio;
            $tasa->fecha = $fecha;
            if ($tasa->update()) {
                return "1";
            } else {
                return "0";
            }
        } else {
            return "2";
        }
    }

    // public function traerlistacambio(){
    //     try {
    //         //url de la sunat donde brinda el tipo de cambio del dia
    //         $urlsunat = "https://e-consulta.sunat.gob.pe/cl-at-ittipcam/tcS01Alias/listarTipoCambio";
    //         //agregamos opciones para la peticion

    //         $params = [];
    //         $params["anio"]=2023;
    //         $params["mes"]=07;
    //         //$params["token"]="token";

    //         $paramsJSON = json_encode($params);

    //         $opciones = array(
    //             "http" => array(
    //                 "header" => "Content-type: application/x-www-form-urlencoded\r\n",
    //                 "method" => "POST",
    //                 "dataType" => 'json',
    //                 "data"  => $paramsJSON,
    //             ),
    //         );
    //         //formamos la peticion
    //         $contexto = stream_context_create($opciones);
    //         //realizamos la peticion a la url con el contexto
    //         $resultado = file_get_contents($urlsunat, false, $contexto);
 
    //         //separamos los precios de compra y precios de venta
    //         //$valores = explode("|", $resultado);
 
    //         // $fecha = $valores[0];
    //         // $compra = $valores[1];
    //         // $venta = $valores[2];
 
    //         return  $resultado; 
    //     } catch (\Throwable $th) {
    //         return "-1";
    //     }
    // }

    public function vertraertasasunat()
    {
        try {
            //url de la sunat donde brinda el tipo de cambio del dia
            $urlsunat = "https://www.sunat.gob.pe/a/txt/tipoCambio.txt";
            //agregamos opciones para la peticion
            $opciones = array(
                "http" => array(
                    "header" => "Content-type: application/x-www-form-urlencoded\r\n",
                    "method" => "GET",
                ),
            );
            //formamos la peticion
            $contexto = stream_context_create($opciones);
            //realizamos la peticion a la url con el contexto
            $resultado = file_get_contents($urlsunat, false, $contexto);
 
            //separamos los precios de compra y precios de venta
            $valores = explode("|", $resultado);
 
            $fecha = $valores[0];
            $compra = $valores[1];
            $venta = $valores[2];
 
            return  $valores; 
        } catch (\Throwable $th) {
            return "-1";
        }
    }
}
