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

    <script src="{{ asset('admin/jsusados/chartjs.min.js') }}"></script>
    <script src="{{ asset('admin/jsusados/chartjs-datalabels.min.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label is-required">TIENDA</label>
                    <select class="form-select " name="tienda_id" id="tienda_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->id }}">{{ $tienda->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label is-required">PRODUCTO</label>
                    <select class="form-select " name="producto_id" id="producto_id" required>
                        <option value="-1" selected>TODOS</option>
                        <option value="UNIFORMES">UNIFORMES</option>
                        <option value="LIBROS">LIBROS</option>
                        <option value="INSTRUMENTOS">INSTRUMENTOS</option>
                        <option value="UTILES">UTILES</option>
                        <option value="GOLOSINAS">GOLOSINAS</option>
                        <option value="SNACKS">SNACKS</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label is-required">FECHA INICIO</label>
                    <input type="date" class="form-control " id="fechainicio" name="fechainicio" />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label is-required">FECHA FIN</label>
                    <input type="date" class="form-control " id="fechafin" name="fechafin" />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="card ingresos borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h6> COMPRAS&nbsp; TOTALES: &nbsp;</h6>
                                    </div>
                                    <div class="col centro">
                                        <h6 id="verIngresomes"> S/.{{ number_format((float) $ingresomes, 2, '.', '') }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-text">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Uniformes:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresouniformes">
                                            S/.{{ number_format((float) $ingresouniformes, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Libros:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresolibros">S/.{{ number_format((float) $ingresolibros, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Instrumentos:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresoinstrumentos">
                                            S/.{{ number_format((float) $ingresoinstrumentos, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Utiles:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresoutiles">
                                            S/.{{ number_format((float) $ingresoutiles, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Golosinas:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresogolosinas">
                                            S/.{{ number_format((float) $ingresogolosinas, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Snacks:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresosnacks">
                                            S/.{{ number_format((float) $ingresosnacks, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div> 
                <div class="col-sm-5">
                    <div class="card ventas borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h6> VENTAS&nbsp; TOTALES: &nbsp;</h6>
                                    </div>
                                    <div class="col centro">
                                        <h6 id="verVentames"> S/.{{ number_format((float) $ventames, 2, '.', '') }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-text">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Uniformes:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentauniformes">
                                            S/.{{ number_format((float) $ventauniformes, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Libros:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentalibros">S/.{{ number_format((float) $ventalibros, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Instrumentos:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentainstrumentos">
                                            S/.{{ number_format((float) $ventainstrumentos, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Utiles:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentautiles">S/.{{ number_format((float) $ventautiles, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Golosinas:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentagolosinas">
                                            S/.{{ number_format((float) $ventagolosinas, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Snacks:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentasnacks">S/.{{ number_format((float) $ventasnacks, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label is-required">Reporte Grafico Sobre:</label>
                    <select class="form-select " name="reporte" id="reporte">
                        <option value="ccv" selected>Compras y Ventas:</option>
                        <option value="pmv">Productos Mas Vendidos:</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label is-required">Tipo de grafico</label>
                    <select class="form-select " name="tipo_grafico" id="tipo_grafico">
                        <option value="line" selected>Lineas</option>
                        <option value="bar">Barras</option>
                        <option value="polarArea">Area Polar</option>
                        <option value="radar">Radar</option>
                        <option value="doughnut">Pastel</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3" id="divnumero" name="divnumero">
                    <label class="form-label is-required">Numero de Resultados:</label>
                    <input type="number" class="form-control " id="numero" name="numero" value="20"
                        min="1" step="1" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <canvas id="myChart" name="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        document.getElementById('divnumero').style.display = 'none';
        var tipo = 'line';
        var reporte = 'ccv';
        var empresa = '-1';
        var tabla = '-1';
        var midatasetV = @json($datosventas);
        var midatasetC = @json($datoscompras);
        var midatasetP = [];
        var midatasetCL = [];
        var traer = 20;
        var fechainicio;
        var fechafin;
        titulov = "VENTAS";
        tituloc = "COMPRAS";

        titulop = "PRODUCTOS MAS VENDIDOS";


        var labelsF = @json($fechas);

        const graph = document.querySelector("#myChart");
        Chart.register(ChartDataLabels);

        $(document).ready(function() {
            var hoy = new Date();
            var fechaFin = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');
            document.getElementById("fechafin").value = fechaFin;
            var inicio = hoy;
            var diadelmes = hoy.getDate();
            inicio.setDate(inicio.getDate() - (diadelmes - 1));
            var fechaInicio = inicio.getFullYear() + '-' + (String(inicio.getMonth() + 1).padStart(2, '0')) +
                '-' + String(inicio.getDate()).padStart(2, '0');
            document.getElementById("fechainicio").value = fechaInicio;
            fechainicio = fechaInicio;
            fechafin = fechaFin;

            llamarydibujargraficoccv(labelsF, tipo, titulov, tituloc, midatasetV, midatasetC, graph);


        });

        function midataset(mitipo, mititulo, midataset, micolor) {
            var colorBorde = micolor;
            var colorFondo = micolor;
            if (mitipo == 'doughnut' || mitipo == 'polarArea') {
                colorBorde = '#ffffff';
            }
            if (mitipo == 'polarArea') {
                colorFondo = micolor;
            }

            const dataset = {
                type: mitipo,
                label: mititulo,
                data: midataset,
                borderColor: colorBorde, //para que salga los bordes blancos el grafico no tiene que tener bordes
                backgroundColor: colorFondo,
                fill: false,
                tension: 0.1
            };
            return dataset;
        }

        function midata(milabelsF, midatasetx) {
            const data = {
                labels: milabelsF,
                datasets: midatasetx
            };
            return data;
        }

        function miconfig(midata) {
            const config = {
                // type: 'bar',
                data: midata,
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            display: true,
                            color: 'black',
                            font: {
                                size: 12,
                            },
                            anchor: 'end', // Cambia a 'center' si quieres que esté en el centro del punto
                            align: 'right', // Posición relativa al punto ('top', 'center', 'bottom')
                            offset: 0, // Espacio entre la etiqueta y el punto

                            formatter: (value) => {
                                return value.y;
                            }
                        },
                        legend: {
                            position: 'top',
                        },
                    },
                    backgroundColor: 'white',
                }
            };
            return config;
        }

        function migrafico(migraph, miconfig) {
            if (window.grafica) {
                window.grafica.clear();
                window.grafica.destroy();
            }
            window.grafica = new Chart(migraph, miconfig);
        }
        //para las compras,ventas y cotizaciones
        function llamarydibujargraficoccv(D_labelsF, D_tipo, D_titulov, D_tituloc, D_midatasetV,
            D_midatasetC, D_graph) {
            const datasetV = midataset(D_tipo, D_titulov, D_midatasetV, '#53CAD4');
            const datasetC = midataset(D_tipo, D_tituloc, D_midatasetC, '#79EB68');

            const datasetx = [datasetV, datasetC];
            const data = midata(D_labelsF, datasetx);
            const config = miconfig(data);

            migrafico(D_graph, config);

        }

        function llamarydibujargraficoproductos(D_labelsF, D_tipo, D_titulop, D_midatasetP, D_graph) {
            var color = colores();
            const datasetP = midataset(D_tipo, D_titulop, D_midatasetP, color);
            const datasetx = [datasetP];
            const data = midata(D_labelsF, datasetx);
            const config = miconfig(data);

            migrafico(D_graph, config);
        }

        $("#tienda_id").change(function() {
            var company = $(this).val();
            empresa = company;
            obtenerbalance();
            obtenerreporte();
        });

        $("#producto_id").change(function() {
            var producto = $(this).val();
            tabla = producto;
            obtenerreporte();
        });

        function obtenerbalance() {
            var urlbalance = "{{ url('admin/reporte/obtenerbalance') }}";
            $.get(urlbalance + '/' + empresa + '/' + fechainicio + '/' + fechafin, function(data) {
                document.getElementById('verIngresomes').innerHTML = "S/." +
                    (parseFloat(data.ingresomes).toFixed(2));
                document.getElementById('verIngresouniformes').innerHTML = "S/." +
                    (parseFloat(data.ingresouniformes).toFixed(2));
                document.getElementById('verIngresolibros').innerHTML = "S/." +
                    (parseFloat(data.ingresolibros).toFixed(2));
                document.getElementById('verIngresoinstrumentos').innerHTML = "S/." +
                    (parseFloat(data.ingresoinstrumentos).toFixed(2));
                document.getElementById('verIngresoutiles').innerHTML = "S/." +
                    (parseFloat(data.ingresoutiles).toFixed(2));
                document.getElementById('verIngresogolosinas').innerHTML = "S/." +
                    (parseFloat(data.ingresogolosinas).toFixed(2));
                document.getElementById('verIngresosnacks').innerHTML = "S/." +
                    (parseFloat(data.ingresosnacks).toFixed(2));

                document.getElementById('verVentames').innerHTML = "S/." +
                    (parseFloat(data.ventames).toFixed(2));
                document.getElementById('verVentauniformes').innerHTML = "S/." +
                    (parseFloat(data.ventauniformes).toFixed(2));
                document.getElementById('verVentalibros').innerHTML = "S/." +
                    (parseFloat(data.ventalibros).toFixed(2));
                document.getElementById('verVentainstrumentos').innerHTML = "S/." +
                    (parseFloat(data.ventainstrumentos).toFixed(2));
                document.getElementById('verVentautiles').innerHTML = "S/." +
                    (parseFloat(data.ventautiles).toFixed(2));
                document.getElementById('verVentagolosinas').innerHTML = "S/." +
                    (parseFloat(data.ventagolosinas).toFixed(2));
                document.getElementById('verVentasnacks').innerHTML = "S/." +
                    (parseFloat(data.ventasnacks).toFixed(2));

            });
        }

        $("#tipo_grafico").change(function() {
            var tipografico = $(this).val();
            tipo = tipografico;
            if (reporte == 'ccv') {
                llamarydibujargraficoccv(labelsF, tipo, titulov, tituloc, midatasetV,
                    midatasetC, graph);
            } else if (reporte == 'pmv') {
                llamarydibujargraficoproductos(labelsF, tipo, titulop, midatasetP, graph);
            } else if (reporte == 'cmc') {
                llamarydibujargraficoproductos(labelsF, tipo, titulocl, midatasetCL, graph);
            }
        });
        $("#reporte").change(function() {
            var Vreporte = $(this).val();
            reporte = Vreporte;
            if (Vreporte == "pmv") {
                document.getElementById('divnumero').style.display = 'inline';
            } else {
                document.getElementById('divnumero').style.display = 'none';
            }
            obtenerreporte();

        });
         
        $("#numero").change(function() {
            var cant = $(this).val();
            if (cant > 0) {
                traer = cant;
                obtenerreporte();
            }

        });
        $("#fechainicio").change(function() {
            var Finicio = $(this).val();
            fechainicio = Finicio;
            obtenerbalance();
            obtenerreporte();
        });
        $("#fechafin").change(function() {
            var Ffin = $(this).val();
            fechafin = Ffin;
            obtenerbalance();
            obtenerreporte();
        });
        $("#reporte").change(function() {
            var Vreporte = $(this).val();
            reporte = Vreporte;
            if (Vreporte == "pmv") {
                document.getElementById('divnumero').style.display = 'inline';
            } else {
                document.getElementById('divnumero').style.display = 'none';
            }
            obtenerreporte();

        });

        function obtenerreporte() {
            //para las compras,, cotizaciones y ventas
            if (reporte == 'ccv') {
                var urldatosgrafico = "{{ url('admin/reporte/obtenerdatosgrafico') }}";
                $.get(urldatosgrafico + '/' + empresa + '/' + fechainicio + '/' + fechafin + '/' + tabla, function(data) {
                    labelsF = data['fechas'];
                    midatasetV = data['datosventas'];
                    midatasetC = data['datoscompras'];
                    midatasetT = data['datoscotizacions'];

                    llamarydibujargraficoccv(labelsF, tipo, titulov, tituloc, midatasetV,
                        midatasetC, graph);
                });
            } else if (reporte == 'pmv') {
                var urldatosgrafico = "{{ url('admin/reporte/obtenerproductosmasv') }}";
                $.get(urldatosgrafico + '/' + empresa + '/' + traer + '/' + fechainicio + '/' + fechafin+'/'+tabla,
                    function(data) {
                        labelsF = data['productos'];
                        midatasetP = data['cantidades'];

                        llamarydibujargraficoproductos(labelsF, tipo, titulop, midatasetP, graph);
                    });
            }
        }

        function colores() {
            miscolores = [
                '#53CAD4', '#79EB68', '#F59075', '#ADA9FC', '#6AD9C9', '#B9F081', '#D9B76A', '#FC877C',
                '#f5938b', '#e3b8b2', '#f9df94', '#808f12', '#f4324a', '#17a7a8', '#ff6e4a', '#e33258',
                '#a4f7d4', '#fbc5d8', '#d9764d', '#d1dc5a', '#89fcb3', '#0b8770', '#b1ceaf'
            ];
            return miscolores;
        }
    </script>
@endpush
