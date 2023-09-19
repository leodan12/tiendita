@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>AÑADIR ORDEN DE COMPRA
                        <a href="{{ url('admin/ordencompra') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/ordencompra') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group">PERSONA A LA QUE SE ENVIA LA ORDEN</label>
                                    <input type="text" name="persona" id="persona" class="form-control  required"
                                        placeholder="Ejemplo: Sr. Jose Sanchez" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2  " name="company_id" id="company_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label is-required">PROVEEDOR</label>
                                <select class="form-select select2  " name="cliente_id" id="cliente_id" disabled required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    {{-- @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"> {{ $cliente->nombre }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">MONEDA</label>
                                    <select class="form-select " name="moneda" id="moneda" required>
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="dolares" data-moneda="dolares">Dolares Americanos</option>
                                        <option value="soles" data-moneda="soles">Soles</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">FORMA PAGO</label>
                                    <select class="form-select " name="formapago" id="formapago" required>
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="credito">Credito</option>
                                        <option value="contado">Contado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" id="divdiascredito" style="display: none;">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">DIAS DE CREDITO</label>
                                    <input class="form-control " type="number" name="diascredito" id="diascredito" />
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control mayusculas" />
                            </div>
                            <br>
                            <hr>
                            <h4>Agregar Detalle de la Orden</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" name="labelproducto" id="labelproducto">PRODUCTO</label>
                                    <select class="form-select select2 " name="product" id="product" disabled>
                                        <option value="" selected disabled>Seleccione una opción </option>
                                        @foreach ($products as $product)
                                            <option id="miproducto{{ $product->id }}" value="{{ $product->id }}"
                                                data-name="{{ $product->nombre }}" data-tipo="{{ $product->tipo }}"
                                                data-unidad="{{ $product->unidad }}" data-moneda="{{ $product->moneda }}"
                                                data-preciocompra="{{ $product->preciocompra }}">
                                                {{ $product->nombre }} - {{ $product->codigo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label" name="labelcantidad" id="labelcantidad">CANTIDAD</label>
                                    <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                        class="form-control " />
                                </div> 
                                <div class="col-md-4 mb-3">
                                    <div class="input-group">
                                        <label class="form-label input-group">PRECIO COMPRA:</label>
                                        <span class="input-group-text" id="spanpreciocompra"></span>
                                        <input type="number" name="preciocompra" min="1" step="0.0001"
                                            id="preciocompra"class="form-control " readonly/>
                                    </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label " id="labelobservacionproducto">OBSERVACION:</label>
                                    <input type="text" name="observacionproducto" id="observacionproducto"
                                        class="form-control mayusculas gui-input" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" name="labelcantidad" id="labelcantidad">UNIDAD</label>
                                    <input type="text" name="unidad" id="unidad" class="form-control mayusculas" />
                                </div>
                                <button type="button" class="btn btn-info" id="addDetalleBatch"> Agregar
                                    Producto a la Orden</button>
                                <div class="table-responsive">
                                    <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesVenta">
                                        <thead class="fw-bold text-primary">
                                            <tr>
                                                <th>CANTIDAD</th>
                                                <th>UNIDAD</th>
                                                <th>PRECIO COMPRA</th>
                                                <th>PRODUCTO</th>
                                                <th>OBSERVACION</th>
                                                <th>ELIMINAR</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end" disabled>Guardar</button>
                            </div>
                        </div>
                    </form>
                    <div class="toast-container position-fixed bottom-0 start-0 p-2" style="z-index: 1000">
                        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true"
                            data-bs-autohide="false" style="width: 100%; box-shadow: 0 2px 5px 2px rgb(0, 89, 255); ">
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
        var indicehp = 0;
        var indicecondicion = 0;
        var tipoproducto = "";
        var unidad = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var misproductos;
        var idcliente = 0;
        var hoy = new Date();
        var monedaorden = "";
        var simbolomoneda = "";
        var tasacambio = 0;
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');

        $(document).ready(function() {
            $('.toast').toast();
            document.getElementById("fecha").value = fechaActual;
            var validez = hoy;
            validez.setDate(validez.getDate() + 15);
            var fechavalidez = validez.getFullYear() + '-' + (String(validez.getMonth() + 1).padStart(2, '0')) +
                '-' + String(validez.getDate()).padStart(2, '0');
            $('.select2').select2({});
            $("#btnguardar").prop("disabled", true);

            var urlvertasacambio = "{{ url('admin/dato/vertasacambio') }}";
            $.ajax({
                type: "GET",
                url: urlvertasacambio,
                success: function(data) {
                    tasacambio = data['valor'];
                }
            });
        });

        $("#company_id").change(function() {
            //$('#product').removeAttr('disabled');
            $("#company_id option:selected").each(function() {
                var company = $(this).val();
                if (company) {
                    clientesxempresa(company);
                    $('#company_id').removeAttr('disabled');
                }
            });
        });

        $("#moneda").change(function() {
            $("#moneda option:selected").each(function() {
                var moneda = $(this).val();
                if (moneda) {
                    monedaorden = moneda;
                    if (moneda == "dolares") {
                        simbolomoneda = "$";
                    } else {
                        simbolomoneda = "S/.";
                    } 
                    document.getElementById('spanpreciocompra').innerHTML = simbolomoneda;
                    $('#product').removeAttr('disabled');
                }
            });
        })

        function clientesxempresa(idempresa) {
            var urlclientexempresa = "{{ url('admin/venta/comboempresacliente') }}";
            $.get(urlclientexempresa + '/' + idempresa, function(data) {
                var producto_select =
                    '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                        .nombre + '" >' + data[i].nombre + '</option>';
                }
                $("#cliente_id").html(producto_select);
                $('#cliente_id').removeAttr('disabled');
            });
        }

        $("#formapago").change(function() {
            $("#formapago option:selected").each(function() {
                var formapago = $(this).val();
                if (formapago == "credito") {
                    document.getElementById('diascredito').value = 15;
                    document.getElementById('divdiascredito').style.display = "inline";
                } else {
                    document.getElementById('divdiascredito').style.display = "none";
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
                    $preciocompra = $(this).data("preciocompra");
                    $moneda = $(this).data("moneda");

                    var precioproducto;
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

                    if (monedaorden == $moneda) {
                        precioproducto = $preciocompra;
                    } else if (monedaorden == "soles" && $moneda == "dolares") {
                        precioproducto = parseFloat(($preciocompra * tasacambio).toFixed(4));
                    } else if (monedaorden == "dolares" && $moneda == "soles") {
                        precioproducto = parseFloat(($preciocompra / tasacambio).toFixed(4));
                    }


                    if ($named != null) {
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('unidad').value = unidad;
                        if ($preciocompra) {
                            document.getElementById('preciocompra').value = precioproducto;
                        } else {
                            document.getElementById('preciocompra').value = 0;
                        }

                        nameproduct = $named;
                    } else if ($named == null) {
                        document.getElementById('cantidad').value = "";
                        document.getElementById('unidad').value = "";
                    }

                }
            });
        });

        $('#addDetalleBatch').click(function() {

            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var unidad = $('[name="unidad"]').val();
            var observacionproducto = $('[name="observacionproducto"]').val();
            var preciocompra = $('[name="preciocompra"]').val();

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!unidad) {
                alert("Ingrese una cantidad");
                return;
            }

            var milista = '<br>';
            var puntos = '';
            var LVenta = [];
            // var tam = LVenta.length;
            LVenta.push(product, nameproduct, cantidad, observacionproducto, unidad,preciocompra);

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
                    agregarFilasTabla(LVenta, puntos, milista);
                });
            } else {
                agregarFilasTabla(LVenta, puntos, milista);
            }
        });

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[
                    2] + '"required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lunidadproducto[]" id="unidadproducto' + indice +
                '" value="' + LVenta[4] + '"required>' + LVenta[4] +
                '</td><td><input  type="hidden" name="Lpreciocompra[]" id="preciocompra' + indice +
                '" value="' + LVenta[5] + '"required>' +simbolomoneda+' '+ LVenta[5] +
                '</td><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' +
                LVenta[1] + '</b>' + puntos + milista +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' +
                indice +
                '" value="' + LVenta[3] + '"required>' + LVenta[3] +
                '</td><td><button type="button" class="btn  btn-danger" onclick="eliminarFila(' + indice + ',' +
                LVenta[0] +
                ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#detallesVenta>tbody").append(filaDetalle);

            indice++;

            limpiarinputs();
            document.getElementById('miproducto' + LVenta[0]).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
            $('.toast').toast('hide');
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('cantidad').value = "";
            document.getElementById('unidad').value = "";
            document.getElementById('observacionproducto').value = "";
            document.getElementById('preciocompra').value = "";
        }

        function eliminarFila(ind, idproducto) {

            $('#fila' + ind).remove();
            indice--;
            document.getElementById('miproducto' + idproducto).disabled = false;
            var funcion = "eliminar";
            botonguardar(funcion);
            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            indice--;
            var funcion = "eliminar";
            botonguardar(funcion);
            nameproduct = 0;
            return false;
        }

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar == 0) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar > 0) {
                $("#btnguardar").prop("disabled", false);
            }
        }

        function habilitaroptionsproductos() {
            for (var i = 0; i < misproductos.length; i++) {
                document.getElementById('miproducto' + misproductos[i].id).disabled = false;
            }
        }
    </script>
@endpush
