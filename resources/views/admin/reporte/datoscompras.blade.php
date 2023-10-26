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
                REGISTROS DE COMPRAS 
            </h3>
        </div>
    </div><br>
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label is-required">TIENDA</label>
                    <select class="form-select select2 " name="tienda_id" id="tienda_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->id }}" data-mitienda="{{ $tienda->nombre }}">
                                {{ $tienda->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label is-required">PRODUCTO</label>
                    <select class="form-select select2 " name="producto_id" id="producto_id">
                        <option value="-1" selected>TODOS</option>
                        <option value="UNIFORMES" data-miproducto="UNIFORMES">UNIFORMES</option>
                        <option value="LIBROS" data-miproducto="LIBROS">LIBROS</option>
                        <option value="INSTRUMENTOS" data-miproducto="INSTRUMENTOS">INSTRUMENTOS</option>
                        <option value="UTILES" data-miproducto="UTILES">UTILES</option>
                        <option value="GOLOSINAS" data-miproducto="GOLOSINAS">GOLOSINAS</option>
                        <option value="SNACKS" data-miproducto="SNACKS">SNACKS</option>
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

                <div class="col-md-6 mb-3">
                    <button type="button" class="btn btn-warning" onclick="traertodos();">Traer todos los registros
                    </button>
                </div>
                 

            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped" id="mitablaprod" name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>
                                <th>FECHA <br> </th>  
                                <th>TIENDA </th>
                                <th>PRODUCTO</th> 
                                <th>CANTIDAD</th>
                                <th>PRECIO UNITARIO S/</th>
                                <th>PRECIO FINAL S/</th>  
                                <th>PROVEEDOR</th>
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
            var empresa1 = document.getElementById("tienda_id").value;
            var producto1 = document.getElementById("producto_id").value;
            var fechainicio1 = "01-01-2000";
            var fechafin1 = "31-12-2099";
            var urldatosproductos = "{{ url('admin/reporte/datoscomprasproductos') }}";
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
            var tienda = document.getElementById("tienda_id").value;
            var producto = document.getElementById("producto_id").value;

            var urldatosproductos = "{{ url('admin/reporte/datoscomprasproductos') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + tienda + '/' + producto, function(
                data) {
                    console.log(data);
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
                    '<tr><td> ' + datos[i]['fecha'] + '</td>' +
                    '<td> ' + datos[i]['tienda'] + '</td>' + 
                    '<td> ' + datos[i]['producto'] + '</td>' + 
                    '<td> ' + datos[i]['cantidad'] + '</td>' +
                    '<td> ' + datos[i]['preciounitariomo'] + '</td>' +
                    '<td> ' + datos[i]['preciofinal'] + '</td>' +  
                    '<td> ' + datos[i]['cliente'] + '</td>' +
                    '</tr>';
                $("#mitablaprod>tbody").append(filaDetalle);
            }
            inicializartabladatos2(btns, tabla, mitituloexcel);
            contini++;
        }

        $("#tienda_id").change(function() {
            var company = $(this).val();
            mititulo();
            traerdatos();
        });
        

        $("#producto_id").change(function() {
            var company = $(this).val();
            mititulo();
            traerdatos();
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
            var idtienda_id = document.getElementById("tienda_id").value;
            var idproducto = document.getElementById("producto_id").value;
            var tienda_id = document.getElementById("tienda_id");
            var producto = document.getElementById("producto_id");
            if (idtienda_id != "-1") {
                if (idproducto != "-1") {
                    var nametienda_id = tienda_id[tienda_id.selectedIndex].getAttribute('data-mitienda');
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Compras_' + nametienda_id + '_' + nameproducto + ' al ' + fechainicio +
                        '_' +
                        fechafin;
                } else {
                    var nametienda_id = tienda_id[tienda_id.selectedIndex].getAttribute('data-mitienda');
                    mitituloexcel = 'Compras_' + nametienda_id + '_' + fechainicio + ' al ' + fechafin;
                }
            } else {
                if (idproducto != "-1") {
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Compras_' + nameproducto + '_' + fechainicio + ' al ' + fechafin;
                } else {
                    mitituloexcel = 'Compras_' + fechainicio + ' al ' + fechafin;
                }
            }
        }
      
  
    </script>
@endpush
