@extends('layouts.admin')
@push('css')
    <style type="text/css">
        .ingresos {
            border: 1px solid #79EB68;
            background-color: #C4EAA4
        }

        .ventas {
            border: 1px solid #53CAD4;
            background-color: #90D4D4
        }

        .cotizaciones {
            border: 1px solid #F59075;
            background-color: #F4BEA8
        }

        .productos {
            border: 1px solid #D6BD2C;
            background-color: #D7D080
        }

        .borde {
            border-radius: 10px;
            color: black;
        }

        .centro {
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                    <div class="me-md-3 me-xl-5">
                        @if (session('message'))
                            <h2 class="alert alert-success">{{ session('message') }}</h2>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="card ingresos borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h6>COMPRAS DEL MES: </h6>
                                    </div>
                                    <div class="col-3 centro">
                                        <h6 id="verIngresomes"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Uniformes:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="ingresouniformes"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Libros:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="ingresolibros"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Instrumentos:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="ingresoinstrumentos"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Utiles:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="ingresoutiles"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Golosinas:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="ingresogolosinas"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Snacks:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="ingresosnacks"> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card ventas borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h6>VENTAS DEL MES: </h6>
                                    </div>
                                    <div class="col-3 centro">
                                        <h6 id="verVentames"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Uniformes:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="salidauniformes"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Libros:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="salidalibros"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Instrumentos:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="salidainstrumentos"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Utiles:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="salidautiles"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Golosinas:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="salidagolosinas"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Snacks:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="salidasnacks"> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card cotizaciones borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-10" style="text-align: left">
                                        <h6>PRODUCTOS SIN STOCK: </h6>
                                    </div>
                                    <div class="col-2 centro">
                                        <h6 id="verCotizacionmes"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Uniformes:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="sinstockuniformes">{{ $uniformessinstock }} </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Libros:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="sinstocklibros">{{ $librossinstock }} </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Instrumentos:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="sinstockinstrumentos">{{ $instrumentossinstock }} </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Utiles:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="sinstockutiles">{{ $utilessinstock }} </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Golosinas:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="sinstockgolosinas">{{ $golosinassinstock }} </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Snacks:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="sinstocksnacks">{{ $snackssinstock }} </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card productos borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-10" style="text-align: left">
                                        <h6>TOTAL PRODUCTOS:
                                        </h6>
                                    </div>
                                    <div class="col-2 centro">
                                        <h6 id="verProducto"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Uniformes:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="stockuniformes"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Libros:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="stocklibros"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Instrumentos:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="stockinstrumentos"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Utiles:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="stockutiles"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Golosinas:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="stockgolosinas"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Snacks:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="stocksnacks"> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                @if ($uniformessinstock > 0)
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">

                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                UNIFORMES SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $uniformessinstock }} Uniformes Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventariouniformes2') }}"
                                style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER UNIFORMES</i>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($librossinstock > 0)
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">
                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                LIBROS SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $librossinstock }} Libros Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventariolibros2') }}"
                                style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER LIBROS</i>
                            </a>
                        </div>
                    </div>
                @endif

                @if ($instrumentossinstock > 0)
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">

                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                INSTRUMENTOS SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $instrumentossinstock }} instrumentos Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventarioinstrumentos2') }}"
                                style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER INSTRUMENTOS</i>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($utilessinstock > 0)
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">
                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                UTILES SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $utilessinstock }} Utiles Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventarioutiles2') }}"
                                style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER UTILES</i>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($golosinassinstock > 0)
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">
                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                GOLOSINAS SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $golosinassinstock }} Golosinas Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventariogolosinas2') }}"
                                style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER GOLOSINAS</i>
                            </a>
                        </div>
                    </div>
                @endif
                @if ($snackssinstock > 0)
                    <div class="col-md-6">
                        <div class="alert alert-danger" role="alert">
                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                SNACKS SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $snackssinstock }} Snacks Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventariosnacks2') }}"
                                style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER SNACKS</i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var urlbalance = "{{ url('admin/reporte/balancemensualinicio') }}";
            $.get(urlbalance, function(data) {
                console.log(data);
                document.getElementById('ingresolibros').innerHTML = data['compras']['LIBROS']?? "";
                document.getElementById('ingresoinstrumentos').innerHTML = data['compras']['INSTRUMENTOS']?? "";
                document.getElementById('ingresouniformes').innerHTML = data['compras']['UNIFORMES']?? "";
                document.getElementById('ingresoutiles').innerHTML = data['compras']['UTILES']?? "";
                document.getElementById('ingresogolosinas').innerHTML = data['compras']['GOLOSINAS']?? "";
                document.getElementById('ingresosnacks').innerHTML = data['compras']['SNACKS']?? "";

                document.getElementById('salidalibros').innerHTML = data['ventas']['LIBROS']?? "";
                document.getElementById('salidainstrumentos').innerHTML = data['ventas']['INSTRUMENTOS']?? "";
                document.getElementById('salidauniformes').innerHTML = data['ventas']['UNIFORMES']?? "";
                document.getElementById('salidautiles').innerHTML = data['ventas']['UTILES']?? "";
                document.getElementById('salidagolosinas').innerHTML = data['ventas']['GOLOSINAS']?? "";
                document.getElementById('salidasnacks').innerHTML = data['ventas']['SNACKS']?? "";

                document.getElementById('stocklibros').innerHTML = data['stocks']['LIBROS']?? "";
                document.getElementById('stockinstrumentos').innerHTML = data['stocks']['INSTRUMENTOS']?? "";
                document.getElementById('stockuniformes').innerHTML = data['stocks']['UNIFORMES']?? "";
                document.getElementById('stockutiles').innerHTML = data['stocks']['UTILES']?? "";
                document.getElementById('stockgolosinas').innerHTML = data['stocks']['GOLOSINAS']?? "";
                document.getElementById('stocksnacks').innerHTML = data['stocks']['SNACKS']?? "";
            });
        });
    </script>
@endpush
