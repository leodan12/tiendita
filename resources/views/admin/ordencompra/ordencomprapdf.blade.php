<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orden de Compra</title>
    <style type="text/css">
        @font-face {
            font-family: 'Arial';
            src: url('/fonts/calibri/calibri.ttf');
        }

        @page {
            margin-top: 20mm;
            margin-bottom: 15mm;
        }

        .container {
            /* border: 1px solid red; */
            height: 98%;
            margin-left: 8mm;
            margin-right: 8mm;
            margin-bottom: 4mm;
        }

        .tdlogo {
            width: 320px;
            height: 120px;
        }

        .logo {
            width: 320px;
            height: 120px;
            /* border: 1px solid red; */
        }

        .logovacio {
            width: 320px;
            height: 120px;
        }

        .empresa {
            width: 310px;
            font-size: 14px;
            font-weight: bold;
            font-family: 'Arial';
            /* border: 1px solid red; */
            text-align: right;
        }

        .cliente {
            width: 690px;
            font-size: 14px;
            /* border: 1px solid red; */
            text-align: left;
            margin-bottom: 80px;
        }

        .cabecera {
            margin-bottom: 20px;
        }

        .linea {
            margin-bottom: 10px;
        }

        .linea2 {
            margin-bottom: 5px;
        }

        .lineacliente {
            margin-bottom: 10px;
        }

        .referencia {
            margin-bottom: 30px;
        }

        .fecha {
            margin-top: 10px;
        }

        .listacotizacion {
            width: 630px;
            font-size: 14px;
            text-align: right;
            border-collapse: collapse;
            border: none;
            margin-left: 5px;

        }

        .cantidad {
            width: 50px;
        }

        .detalleproducto {
            width: 360px;
        }

        .unidad {
            width: 75px;
        }



        .borde {
            border: 1px solid black;
        }

        .aleft {
            text-align: left;
        }

        .acenter {
            text-align: center;
        }

        .aright {
            text-align: right;
        }

        .margensuperior {
            margin-top: 30px;
        }

        .letraazul {
            color: #0e3988;
            font-size: 14px;
        }

        .letranegra {
            color: black;
            font-size: 14px;
        }

        .direccionfooter {
            width: 690px;
            margin-bottom: 0;
            font-size: 13px;
            text-align: center;
            /* border: 1px solid orangered; */
            bottom: 1mm;
            position: fixed;
        }

        .margenleft {
            padding-left: 10px;
        }

        .fondofinal {
            background-color: #FFFC99;
        }

        .headcondicion {
            text-align: left;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">

        <table class="cabecera">
            <tr>
                <td class="tdlogo">
                    @if ($empresa->logo != null)
                        <img class="logo" src="{{ public_path('logos/' . $empresa->logo) }}"> <br>
                    @else
                        <div class="logovacio"></div>
                    @endif
                    <div class="fecha"> Trujilo, &nbsp;{{ $fechaletra }}</div>
                </td>
                <td class="empresa">
                    <div class="linea"> DE:&nbsp; {{ $empresa->nombre }} <br></div>
                    <div class="linea"> RUC:&nbsp; {{ $empresa->ruc }} <br> </div>
                    <div class="linea"> ORDEN DE COMPRA N° {{ $ordencompra[0]->numero }} </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="cliente">
                    <div class="linea">
                        <b> Señores: </b> <br>
                    </div>
                    @if ($ordencompra[0]->persona != null)
                        <div class="linea">
                            {{ $cliente->nombre }}
                        </div>
                    @else
                        <div class="lineacliente">
                            {{ $cliente->nombre }}
                        </div>
                    @endif

                    @if ($ordencompra[0]->persona != null)
                        <div class="lineacliente">
                            Att. {{ $ordencompra[0]->persona }}
                        </div>
                    @endif

                    <div class="referencia">
                        Referencia: Orden de Compra
                    </div>
                </td>

            </tr>
            <tr>
                <td class="cliente">
                    <div class="linea">
                        <b>Estimado señor:</b>
                    </div>
                    <div class="lineacliente">Sirvase proformar lo siguiente:
                    </div>
                </td>
                <td>

                </td>
            </tr>
        </table>
        <table class="listacotizacion">
            <thead>
                <tr>
                    <th class="borde cantidad acenter">
                        <b>CANT.</b>
                    </th>
                    <th class="borde unidad acenter">
                        <b>UNIDAD&nbsp; </b>
                    </th>
                    <th class="borde detalleproducto acenter">
                        <b>PRODUCTO</b>
                    </th>


                </tr>
            </thead>
            <tbody>
                @foreach ($ordencompra as $item)
                    <tr>
                        <td class="borde acenter">&nbsp;
                            {{ $item->cantidad }}
                        </td>
                        <td class="borde acenter">&nbsp;
                            {{ $item->unidad }}
                        </td>
                        <td class="borde margenleft aleft">
                            @if ($item->tipo == 'kit')
                                @php $c=0;   @endphp
                                <b> {{ $item->producto }}:</b><br>

                                @foreach ($detallekit as $kit)
                                    @if ($item->idproducto == $kit->idkit)
                                        @if ($c == 1)
                                            ,
                                        @endif
                                        @if ($c == 0)
                                            @php $c=1;   @endphp
                                        @endif
                                        {{ $kit->cantidad }}&nbsp;{{ $kit->producto }}
                                    @endif
                                @endforeach
                                .
                            @else
                                {{ $item->producto }}
                            @endif
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
        <table class="condiciones margensuperior">
            <tr>
                <td class="headcondicion"><b> CONDICIONES: </b></td>
            </tr>
            <tr>
                <td>-Precio en {{ $ordencompra[0]->moneda }}</td>
            </tr>
            @if ($ordencompra[0]->formapago == 'credito')
                <tr>
                    <td>-El pago se realizará a crédito</td>
                </tr>
                <tr>
                    <td>-Plazo de pago de {{ $ordencompra[0]->diascredito }} días </td>
                </tr>
            @endif
            @if ($ordencompra[0]->formapago == 'contado')
                <tr>
                    <td>-El pago se realizará al contado</td>
                </tr>
            @endif
        </table>
        <div class="direccionfooter"><b>{{ $empresa->direccion }}</b></div>
    </div>
</body>

</html>
