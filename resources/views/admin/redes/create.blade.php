@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>AÑADIR REDES
                        <a href="{{ url('admin/redes') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/redes') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" class="form-control mayusculas" required
                                    value="{{ old('nombre') }}" />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">CARROCERIA</label>
                                <select class="form-select select2 " name="carroceria_id" id="carroceria_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($carrocerias as $carroceria)
                                        <option value="{{ $carroceria->id }}"> {{ $carroceria->tipocarroceria }}</option>
                                    @endforeach
                                </select>
                                @error('carroceria_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">MODELO</label>
                                <select class="form-select select2 " name="modelo_id" id="modelo_id" required disabled>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                     
                                </select>
                                @error('modelo_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <hr>
                            <h4>Agregar Detalle de Redes</h4>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="labelproducto">PRODUCTO</label>
                                <select class="form-select select2 " name="product" id="product">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($productos as $product)
                                        <option id="miproducto{{ $product->id }}" value="{{ $product->id }}"
                                            data-name="{{ $product->nombre }}" data-unidad="{{ $product->unidad }}"
                                            data-tipo="{{ $product->tipo }}">
                                            {{ $product->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                    class="form-control " />
                            </div> 
                            <div class="col-md-3 mb-3">
                                <label class="form-label" id="labelcantidad">UNIDAD</label>
                                <input type="text" name="unidad" id="unidad" 
                                    class="form-control mayusculas" />
                            </div> 
                            <div class="col-12 mb-3">
                                <button type="button" style="width: 100%;" class="btn btn-info" id="addDetalleBatch"><i
                                        class="fa fa-plus"></i>
                                    Agregar Producto </button>
                                <br><br>
                            </div>

                            <br><br>
                            <div class="table-responsive">
                                <table class="table-row-bordered gy-5 gs-5" id="detallesKit" style="width: 100%">
                                    <thead class="fw-bold text-primary">
                                        <tr style="text-align: center;">
                                            <th style="width:10%;">CANTIDAD</th>
                                            <th style="border-left: solid 1px silver; width:20%;">UNIDAD</th>
                                            <th style="border-left: solid 1px silver;">PRODUCTO</th>
                                            <th style="border-left: solid 1px silver; width:15%;">ELIMINAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>

                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar"
                                    class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>
                    </form>
                    <div class="toast-container position-fixed bottom-0 start-0 p-2" style="z-index: 1000">
                        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false"
                            style="width: 100%; box-shadow: 0 2px 5px 2px rgb(0, 89, 255); ">
                            <div class="  card-header">
                                <i class="mdi mdi-information menu-icon"></i>
                                <strong class="mr-auto"> &nbsp; Productos que incluye el kit:</strong>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <table id="detalleskit">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>CANTIDAD</th>
                                            <th>PRODUCTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        var indice = 0;
        var nameproduct = 0;
        var estadoguardar = 0;
        var indicex = 0;
        var modelos = "";
        var misproductos = @json($productos);
        modelos = @json($modelos);

        $(document).ready(function() {
            $('.select2').select2();
            $("#btnguardar").prop("disabled", true);

        });
        $("#carroceria_id").change(function() {
            $("#carroceria_id option:selected").each(function() {
                var carroceria_id = $(this).val();
                if (carroceria_id) {
                    var urlvent = "{{ url('admin/redes/modeloxcarroceria') }}";
                    $.get(urlvent + '/' + carroceria_id, function(data) {
                        var modelo_select =
                            '<option value="" disabled selected>Seleccione una opción</option>'
                        for (var x = 0; x < modelos.length; x++) {
                            var disabled = "";
                            for (var i = 0; i < data.length; i++) {
                                if (modelos[x].id == data[i].id) {
                                    disabled = " disabled ";
                                }
                            }
                            modelo_select += '<option value="' + modelos[x].id + '"' +
                                disabled + '  >' + modelos[x].modelo + '</option>';
                        }
                        $("#modelo_id").html(modelo_select);
                        $('#modelo_id').removeAttr('disabled');
                    });
                }
            });
        });

        $("#product").change(function() {
            $("#product option:selected").each(function() {

                var miproduct = $(this).val();
                if (miproduct) {
                    $named = $(this).data("name");
                    $tipo = $(this).data("tipo");
                    $unidad = $(this).data("unidad");
                    document.getElementById('cantidad').value = 1;
                    document.getElementById('unidad').value = $unidad;

                    idproducto = miproduct;
                    tipoproducto = $tipo;
                    unidad = $unidad;
                    //mostramos la notificacion
                    if ($tipo == "kit") {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.get(urlventa + '/' + miproduct, function(data) {
                            $('#detalleskit tbody tr').slice().remove();
                            for (var i = 0; i < data.length; i++) {
                                filaDetalle =
                                    '<tr style="border-top: 1px solid silver;" id="fila' +
                                    i + '"><td> ' + data[i].cantidad +
                                    '</td> <td> ' + data[i].producto + '</td></tr>';
                                $("#detalleskit>tbody").append(filaDetalle);
                            }
                        });
                        $('.toast').toast('show');
                    }
                    if ($tipo == "estandar") {
                        $('.toast').toast('hide');
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                    } else if ($tipo == "kit") {
                        document.getElementById('labelproducto').innerHTML =
                            "PRODUCTO TIPO KIT";
                    }
                    if ($named != null) {
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('unidad').value = unidad;
                        nameproduct = $named;
                    } else if ($named == null) {
                        document.getElementById('cantidad').value = "";
                        document.getElementById('unidad').value = "";
                    }
                }
            });
        });

        $('#addDetalleBatch').click(function() {

            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var unidad = $('[name="unidad"]').val();

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!unidad) {
                alert("Ingrese una unidad");
                return;
            }

            var milista = '<br>';
            var puntos = '';
            var LDetalle = [];
            var tam = LDetalle.length;
            LDetalle.push(product, nameproduct, cantidad, unidad);

            if (tipoproducto == "kit") {
                puntos = ': ';
                var urlventa = "{{ url('admin/venta/productosxkit') }}";
                $.get(urlventa + '/' + idproducto, function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var coma = '<br>';
                        if (i + 1 == data.length) {
                            coma = '';
                        }
                        milista = milista + '-' + data[i].cantidad + ' ' + data[i].producto +
                            coma;
                    }
                    agregarFilasTabla(LDetalle, puntos, milista, product);
                });
            } else {
                agregarFilasTabla(LDetalle, puntos, milista, product);
            }
        });

        function agregarFilasTabla(LDetalle, puntos, milista, product) {
            var fondo = "#ffffff";
            if (indice % 2 == 0) {
                fondo = "#E4EDE6";
            }
            filaDetalle = '<tr id="fila' + indice + '" style="background-color: ' + fondo +
                ';"><td style="text-align: center;"><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +
                '" value="' + LDetalle[2] + '"required>' + LDetalle[2] +
                '</td><td style="text-align: center;"><input  type="hidden" name="Lunidad[]" id="unidad' + indice +
                '" value="' + LDetalle[3] + '"required>' + LDetalle[3] +
                '</td> <td><input  type="hidden" name="Lproduct[]" value="' + LDetalle[0] + '"required><b>' + LDetalle[1] +
                '</b>' + puntos + milista +
                '</td><td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(' +
                indice + ',' + product + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesKit>tbody").append(filaDetalle);

            indice++;
            limpiarinputs();

            document.getElementById('miproducto' + product).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }

        function eliminarFila(ind, product) {

            $('#fila' + ind).remove();
            //indice--;
            // damos el valor
            document.getElementById('miproducto' + product).disabled = false;
            var funcion = "eliminar";
            botonguardar(funcion);
            return false;
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('cantidad').value = null;
            document.getElementById('unidad').value = "";
            $('.toast').toast('hide');
        }

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar < 1) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar >= 1) {
                $("#btnguardar").prop("disabled", false);
            }
        }
    </script>
@endpush
