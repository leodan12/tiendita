@extends('layouts.admin')
@push('css')
    <script src="{{ asset('admin/jsusados/chartjs.min.js') }}"></script>
    <script src="{{ asset('admin/jsusados/chartjs-datalabels.min.js') }}"></script>

    <style>
        .xlarge {
            width: 100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="row" style="text-align: center;">
        <div class="col">
            <h3>
                REGISTROS DE COMPRAS Y VENTAS
            </h3>
        </div>
    </div><br>
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">EMPRESA</label>
                    <select class="form-select select2 " name="company_id" id="company_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" data-miempresa="{{ $company->nombre }}">
                                {{ $company->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">PRODUCTO</label>
                    <select class="form-select select2 " name="producto" id="producto">
                        <option value="-1" selected>TODOS</option>
                        @foreach ($productos as $item)
                            <option value="{{ $item->id }}" data-miproducto="{{ $item->nombre }}">{{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">FECHA INICIO</label>
                    <input type="date" class="form-control " id="fechainicio" name="fechainicio" />
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">FECHA FIN</label>
                    <input type="date" class="form-control " id="fechafin" name="fechafin" />
                </div>

                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn-warning" onclick="traertodos();">Traer todos los registros
                    </button>
                </div>
                <div class="col-md-6 mb-3">
                    <button type="button" id="btnvergraficoventas" onclick="vergraficos()" class="btn btn-info"
                        data-bs-toggle="modal" data-bs-target="#staticBackdrop" disabled>
                        Ver Graficos
                    </button>
                </div>

                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Grafico de Datos</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <nav class="" style="border-radius: 5px;">
                                            <div class="nav nav-pills nav-justified  " id="nav-tab" role="tablist">

                                                <div class="row xlarge">
                                                    <div class="col-md-6 mb-3 " style="text-align: right;">
                                                        <input id="checkventas" type="radio" value="ventas"
                                                            name="radiografico" checked>
                                                        <label class="" for="exampleRadios1">
                                                            GRAFICO DE VENTAS
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6 mb-3 " style="text-align: left;">
                                                        <input id="checkcompras" type="radio" value="compras"
                                                            name="radiografico">
                                                        <label class="" for="exampleRadios2">
                                                            GRAFICO DE COMPRAS
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-ventas" role="tabpanel"
                                                aria-labelledby="nav-detalles-tab" tabindex="0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <canvas id="ChartVentas" name="ChartVentas"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade  " id="nav-compras" role="tabpanel"
                                                aria-labelledby="nav-condiciones-tab" tabindex="0">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <canvas id="ChartCompras" name="ChartCompras"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped" id="mitablaprod" name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>
                                <th>FECHA <br> </th>
                                <th>COMPRA O VENTA </th>
                                <th>FACTURA </th>
                                <th>EMPRESA </th>
                                <th>CLIENTE/PROVEEDOR</th>
                                <th>PRODUCTO</th>
                                <th>TIPO</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO UNITARIO</th>
                                <th>PRECIO FINAL</th>
                                <th>MONEDA</th>

                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                            <tr></tr>
                        </Tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script type="text/javascript">
        var contini = 0;
        var mitituloexcel = "";
        var nombreproducto = ""

        $(document).ready(function() {
            $('.select2').select2({});
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
            mititulo();
            traerdatos();

        });

        function traertodos() {
            Swal.fire({
                title: "Obteniendo Datos...",
                imageUrl: '{{ asset('admin/images/loading2.gif') }}',
                imageWidth: "150px",
                imageHeight: "150px",
                showConfirmButton: false,
                allowOutsideClick: false, 
            });
            setTimeout(traertodosdatos, 100);
        }

        function traertodosdatos() {
            var empresa1 = document.getElementById("company_id").value;
            var producto1 = document.getElementById("producto").value;
            var fechainicio1 = "01-01-2000";
            var fechafin1 = "31-12-2099";
            var urldatosproductos = "{{ url('admin/reporte/datosproductos') }}";
            $.ajax({
                type: "GET",
                url: urldatosproductos + '/' + fechainicio1 + '/' + fechafin1 + '/' + empresa1 + '/' + producto1,
                async: true,
                success: function(data) {
                    llenartabla(data);
                    Swal.close({});
                }
            });
        }

        function traerdatos() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var empresa = document.getElementById("company_id").value;
            var producto = document.getElementById("producto").value;

            var urldatosproductos = "{{ url('admin/reporte/datosproductos') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + empresa + '/' + producto, function(
                data) {
                llenartabla(data);
            });
        }

        function llenartabla(datos) {

            var btns = 'lBfrtip';
            var tabla = '#mitablaprod';
            if (contini > 0) {
                $("#mitablaprod").dataTable().fnDestroy(); //eliminar las filas de la tabla  
            }
            $('#mitablaprod tbody tr').slice().remove();
            for (var i = 0; i < datos.length; i++) {
                var factura = "";
                if (datos[i].factura) {
                    factura = datos[i].factura;
                }
                filaDetalle =
                    '<tr><td> ' + datos[i].fecha + '</td>' +
                    '<td> ' + datos[i].compraventa + '</td>' +
                    '<td> ' + factura + '</td>' +
                    '<td> ' + datos[i].empresa + '</td>' +
                    '<td> ' + datos[i].cliente + '</td>' +
                    '<td> ' + datos[i].producto + '</td>' +
                    '<td> ' + datos[i].tipo + '</td>' +
                    '<td> ' + datos[i].cantidad + '</td>' +
                    '<td> ' + datos[i].preciounitariomo + '</td>' +
                    '<td> ' + datos[i].preciofinal + '</td>' +
                    '<td> ' + datos[i].moneda + '</td>' +
                    '</tr>';
                $("#mitablaprod>tbody").append(filaDetalle);
            }
            inicializartabladatos(btns, tabla, mitituloexcel);
            contini++;
        }

        $("#company_id").change(function() {
            var company = $(this).val();
            mititulo();
            traerdatos();
        });

        $("#producto").change(function() {
            $("#producto option:selected").each(function() {
                var product = $(this).val();
                if (product) {
                    var nombre = $(this).data("miproducto");
                    nombreproducto = nombre;

                    mititulo();
                    traerdatos();

                    var producto = document.getElementById("producto").value;
                    if (producto == "-1") {
                        document.getElementById('btnvergraficoventas').disabled = true;
                    } else {
                        document.getElementById('btnvergraficoventas').disabled = false;
                    }
                }

            });
        });
        $("#fechainicio").change(function() {
            var Vreporte = $(this).val();
            mititulo();
            traerdatos();
        });
        $("#fechafin").change(function() {
            var cant = $(this).val();
            mititulo();
            traerdatos();
        });

        function mititulo() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var idcompany_id = document.getElementById("company_id").value;
            var idproducto = document.getElementById("producto").value;
            var company_id = document.getElementById("company_id");
            var producto = document.getElementById("producto");
            if (idcompany_id != "-1") {
                if (idproducto != "-1") {
                    var namecompany_id = company_id[company_id.selectedIndex].getAttribute('data-miempresa');
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Ventas y compras_' + namecompany_id + '_' + nameproducto + '_' + fechainicio +
                        '_' +
                        fechafin;
                } else {
                    var namecompany_id = company_id[company_id.selectedIndex].getAttribute('data-miempresa');
                    mitituloexcel = 'Ventas y compras_' + namecompany_id + '_' + fechainicio + '_' + fechafin;
                }
            } else {
                if (idproducto != "-1") {
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Ventas y compras_' + nameproducto + '_' + fechainicio + '_' + fechafin;
                } else {
                    mitituloexcel = 'Ventas y compras_' + fechainicio + '_' + fechafin;
                }
            }
        }
        //-----------------------FUNCIONES PARA EL GRAFICO DE LAS VENTAS Y COMPRAS ---------------------------

        const graphVentas = document.querySelector("#ChartVentas");

        const graphCompras = document.querySelector("#ChartCompras");
        const graficos = [];

        Chart.register(ChartDataLabels);
        //para cambiar de grafico entre compras y ventas
        $("#checkventas").change(function() {
            verificarCheckbox();
        });
        $("#checkcompras").change(function() {
            verificarCheckbox();
        });

        function verificarCheckbox() {
            var ventas = document.getElementById('checkventas');
            var compras = document.getElementById('checkcompras');
            if (ventas.checked) {
                mostrarTab('nav-ventas');
            }
            if (compras.checked) {
                mostrarTab('nav-compras');
            }
        }

        function mostrarTab(tabId) {
            var tabs = document.querySelectorAll('.tab-pane');
            tabs.forEach(function(tab) {
                if (tab.id === tabId) {
                    tab.classList.add('active', 'show');
                } else {
                    tab.classList.remove('active', 'show');
                }
            });
        }


        function vergraficos() {
            deletemisgraficos(graficos);
            vergraficoventas();
            vergraficocompras();
        }

        function deletemisgraficos(graficos) {
            graficos.forEach(grafico => {
                grafico.clear();
                grafico.destroy();
            });
            graficos.length = 0;
        }

        function showmigrafico(migraph, miconfig) {
            const migrafico = new Chart(migraph, miconfig);
            graficos.push(migrafico);
        }

        function vergraficoventas() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var empresa = document.getElementById("company_id").value;
            var producto = document.getElementById("producto").value;

            var urldatosproductos = "{{ url('admin/reporte/datosgraficoventas') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + empresa + '/' + producto, function(
                data) {
                llamarydibujargrafico(graphVentas, data, ' VENTAS ');
            });

        }

        function vergraficocompras() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var empresa = document.getElementById("company_id").value;
            var producto = document.getElementById("producto").value;

            var urldatosproductos = "{{ url('admin/reporte/datosgraficocompras') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + empresa + '/' + producto, function(
                data) {
                llamarydibujargrafico(graphCompras, data, ' COMPRAS ');
            });

        }

        //para las compras,ventas y cotizaciones
        function llamarydibujargrafico(D_graph, datosventas, compraventa) {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var D_labelsF = datosventas[0];
            const dataset = midataset('line', 'todas', datosventas[2][0], '#ff0000', 'rgb(54, 162, 235)', 1);
            var datasetx = [dataset];

            for (var i = 1; i < datosventas[2].length; i++) {
                var color = colores(i);
                var datasetn = midataset('bar', datosventas[1][i - 1], datosventas[2][i], color, color, 2);
                datasetx[i] = datasetn;
            }
            const data = midata(D_labelsF, datasetx);
            var titulo = 'CANTIDAD DE' + compraventa + 'DE: ' + nombreproducto + ' (' + fechainicio + ' / ' + fechafin +
                ')';
            const config = miconfiguracion(data, titulo);

            showmigrafico(D_graph, config);
        }

        function midataset(mitipo, mititulo, midataset, micolor, colorback, orden) {
            const dataset = {
                type: mitipo,
                label: mititulo,
                data: midataset,
                borderColor: colorback,
                backgroundColor: micolor,
                fill: false,
                tension: 0.1,
                order: orden,
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

        function miconfiguracion(midata, titulo) {
            const config = {
                data: midata,
                options: {
                    plugins: {
                        title: {
                            display: true,
                            text: titulo,
                        },
                        datalabels: {
                            display: function(context) {
                                return context.dataset.type == 'line'
                            },
                            color: 'black',
                            font: {
                                size: 12,
                            },
                            anchor: 'end', // Cambia a 'center' si quieres que esté en el centro del punto
                            align: 'right', // Posición relativa al punto ('top', 'center', 'bottom')
                            offset: 2, // Espacio entre la etiqueta y el punto

                            formatter: (value) => {
                                return value.y;
                            }
                        }
                    },
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    },
                    backgroundColor: 'white',
                }
            };
            return config;
        }

        function colores(nro) {
            var miscolores = [
                '#53CAD4', '#79EB68', '#F59075', '#ADA9FC', '#6AD9C9', '#B9F081', '#D9B76A', '#FC877C', '#f5938b',
                '#e3b8b2', '#f9df94', '#808f12', '#f4324a', '#17a7a8', '#ff6e4a', '#e33258',
                '#a4f7d4', '#fbc5d8', '#d9764d', '#d1dc5a', '#89fcb3', '#0b8770', '#b1ceaf'
            ];
            return miscolores[nro];
        }
    </script>
@endpush
