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
            @if (session('message'))
                <div class="alert alert-danger">{{ session('message') }}</div>
            @endif

            <div class="card">
                <form action="{{ url('admin/venta') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>AÑADIR VENTA &nbsp;&nbsp;&nbsp;
                            <a href="{{ url('admin/venta') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIENDA</label>
                                <select class="form-select select2  " name="tienda_id" id="tienda_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($tiendas as $tienda)
                                        <option value="{{ $tienda->id }}">{{ $tienda->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">CLIENTE</label>
                                <select class="form-select select2  " name="cliente_id" id="cliente_id">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO VENTA </label>
                                    <span class="input-group-text" id="spancostoventa">S/.</span>
                                    <input type="number" name="costoventa" id="costoventa" min="0.1" step="0.01"
                                        class="form-control sinborde  required" required
                                        style="background-color: #fb0c3249" />
                                </div>
                            </div>
                            <br>
                            <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                            <div class="row">
                                <h4>Agregar Detalle de la Venta</h4>
                                <div class="col-md-3 mb-1">
                                    <label class="form-label" id="labeltipoproduct" name="labeltipoproduct">TIPO</label>
                                    <select class="form-select select2 " name="tipoproduct" id="tipoproduct" disabled>
                                        <option value="" selected disabled>Seleccione una opción
                                        </option>
                                        <option value="SNACKS">SNACKS </option>
                                        <option value="GOLOSINAS">GOLOSINAS </option>
                                        <option value="INSTRUMENTOS">INSTRUMENTOS </option>
                                        <option value="UTILES">UTILES </option>
                                        <option value="LIBROS">LIBROS </option>
                                        <option value="UNIFORMES">UNIFORMES </option>
                                    </select>
                                </div>
                                <div class="col-md-9 mb-1">
                                    <label class="form-label" id="labelproducto" name="labelproducto">PRODUCTO</label>
                                    <select class="form-select select2 " name="product" id="product" disabled>
                                        <option value="" selected disabled>Seleccione una opción
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <label class="form-label" name="labelcantidad" id="labelcantidad">CANTIDAD</label>
                                    <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                        class="form-control " />
                                </div>
                                <div class="col-md-4 mb-1">
                                    <div class="input-group">
                                        <label class="form-label input-group" id="labelpreciounitarioref">PRECIO
                                            UNITARIO:</label>
                                        <span class="input-group-text" id="spanpreciounitarioref">S/.</span>
                                        <input type="number" name="preciounitariomo" min="0" step="0.0001"
                                            id="preciounitariomo" class="form-control " />
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="input-group">
                                        <label class="form-label input-group" id="labelpreciototal">PRECIO
                                            TOTAL POR
                                            PRODUCTO</label>
                                        <span class="input-group-text" id="spanpreciototal">S/.</span>
                                        <input type="number" name="preciofinal" min="0" step="0.0001"
                                            id="preciofinal" class="form-control " />
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info" id="addDetalleBatch"><i
                                        class="fa fa-plus"></i>
                                    Agregar Producto a la Venta</button>
                                <div class="table-responsive">
                                    <table class="table table-striped table-striped table-row-bordered gy-5 gs-5"
                                        id="detallesVenta">
                                        <thead class="fw-bold text-primary">
                                            <tr style="text-align: center;">
                                                <th style="width: 5%;">TIPO</th>
                                                <th style="width: 73%;">PRODUCTO</th>
                                                <th style="width: 4%;">CANTIDAD</th>
                                                <th style="width: 7%;">PRECIO UNITARIO</th>
                                                <th style="width: 7%;">TOTAL PRODUCTO</th>
                                                <th style="width: 4%;">ELIMINAR</th>
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
                                    class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script type="text/javascript">
        var indice = 0;
        var idtienda = 0;
        var tipoproducto = "";
        var stock1 = 0;
        var stock2 = 0;
        var precio = 0;
        var nombre = "";
        var idproducto = 0;
        var ventatotal = 0;
        var estadoguardar = 0;
        $(document).ready(function() {

            $('.select2').select2({});
            $("#btnguardar").prop("disabled", true);
            //Para poner automaticamente la fecha actual
            var hoy = new Date();
            var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');
            document.getElementById("fecha").value = fechaActual;

        });
        document.getElementById("cantidad").onchange = function() {
            preciofinal();
        };

        $("#tienda_id").change(function() {
            var tienda = $(this).val();
            idtienda = tienda;
            $('#tipoproduct').removeAttr('disabled');
        });

        $("#tipoproduct").change(function() {
            var tipo = $(this).val();
            tipoproducto = tipo;
            var miurl = "{{ url('admin/venta/productosxtipo') }}";
            $.get(miurl + '/' + tipoproducto, function(data) {
                console.log(data);
                var producto_select =
                    '<option value="" disabled selected>Seleccione una opcion</option>';

                for (var i = 0; i < data.length; i++) {
                    var valortexto = "";
                    if (tipoproducto == "UTILES") {
                        valortexto = data[i].nombre + " - " + data[i].marcautil + " - " + data[i].colorutil;
                    } else if (tipoproducto == "UNIFORMES") {
                        valortexto = data[i].nombre + " - " + data[i].genero + " - " + data[i].talla +
                            " - " + data[i].tela + " - " + data[i].color;
                    } else if (tipoproducto == "LIBROS") {
                        valortexto = data[i].titulo + " - " + data[i].autor + " - " + data[i].anio +
                            " - " + data[i].original + " - " + data[i].formato + " - " + data[i].tipopapel +
                            " - " + data[i].tipopasta + " - " + data[i].edicion + " - " + data[i]
                            .especializacion;
                    } else if (tipoproducto == "INSTRUMENTOS") {
                        valortexto = data[i].nombre + " - " + data[i].marca + " - " + data[i].modelo +
                            " - " + data[i].garantia;
                    } else if (tipoproducto == "GOLOSINAS") {
                        valortexto = data[i].nombre + " - " + data[i].peso;
                    } else if (tipoproducto == "SNACKS") {
                        valortexto = data[i].nombre + " - " + data[i].tamanio + " - " + data[i].marcasnack +
                            " - " + data[i].saborsnack;
                    }

                    producto_select += '<option id="productoxtipo' + data[i].id +
                        '" value="' + data[i].id + '" data-nombre="' + valortexto + '" data-stock1="' +
                        data[i].stock1 + '" data-stock2="' + data[i].stock2 + '" data-precio="' +
                        data[i].precio + '" >' + valortexto + '</option>';
                }
                $("#product").html(producto_select);
                $('#product').removeAttr('disabled');
                $('.select2').select2({});
                limpiarinputs();
            });
        });

        function preciofinal() {
            var cantidad = $('[name="cantidad"]').val();
            var preciounit = $('[name="preciounitariomo"]').val();

            if (cantidad >= 1 && preciounit >= 0) {
                preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)));
                document.getElementById('preciofinal').value = parseFloat(preciototalI.toFixed(4));
            }
        }
        $("#product").change(function() {
            $("#product option:selected").each(function() {
                var miproduct = $(this).val();
                if (miproduct) {
                    var preciop = $(this).data("precio");
                    var nombrep = $(this).data("nombre");
                    var stock1p = $(this).data("stock1");
                    var stock2p = $(this).data("stock2");
                    precio = preciop;
                    nombre = nombrep;
                    stock1 = stock1p;
                    stock2 = stock2p;
                    if (idtienda == 1) {
                        document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" + stock1p +
                            ")";
                    } else {
                        document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" + stock2p +
                            ")";
                    }
                    document.getElementById('cantidad').value = 1;
                    document.getElementById('preciounitariomo').value = preciop;
                    document.getElementById('preciofinal').value = preciop;
                }
            });
        });
        $('#addDetalleBatch').click(function() {

            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitariomo"]').val();
            var tipo = $('[name="tipoproduct"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            //alertas para los detallesBatch
            if (!tipo) {
                alert("Seleccione un Tipo");
                return;
            }
            if (!product) {
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!preciounitario) {
                alert("Ingrese una precio unitario");
                return;
            }
            if (!preciofinal) {
                alert("Ingrese una precio total del producto");
                return;
            }

            var LVenta = [];
            var tam = LVenta.length;
            LVenta.push(product, nombre, cantidad, preciounitario, tipoproducto, preciofinal);
            agregarFilasTabla(LVenta);
        });

        function agregarFilasTabla(LVenta) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Ltipo[]" value="' + LVenta[4] + '"required>' + LVenta[4] +
                '</td><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required> ' + LVenta[1] +
                '</td><td style="text-align:center;"><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +
                '" value="' + LVenta[2] +
                '"required>' + LVenta[2] +
                '</td><td style="text-align:center;"><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitario' +
                indice + '" value="' +
                LVenta[3] + '"required> S/.' + LVenta[3] +
                '</td><td style="text-align:center;"><input id="preciof' + indice +
                '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] +
                '"required> S/.' + LVenta[5] +
                '</td><td style="text-align:center;"><button type="button" class="btn btn-xs btn-danger" onclick="eliminarFila(' +
                indice + ',' + LVenta[0] +
                ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#detallesVenta>tbody").append(filaDetalle);
            $('.toast').toast('hide');
            indice++;
            ventatotal = parseFloat(ventatotal) + parseFloat(LVenta[5]);
            limpiarinputs();
            document.getElementById('costoventa').value = parseFloat((ventatotal * 1).toFixed(2));
            document.getElementById('productoxtipo' + LVenta[0]).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }


        function eliminarFila(ind, idproducto) {
            var resta = 0;
            resta = $('[id="preciof' + ind + '"]').val();
            ventatotal = (ventatotal - resta).toFixed(4);
            $('#fila' + ind).remove();
            //indice--;
            // damos el valor
            document.getElementById('costoventa').value = (ventatotal * 1).toFixed(2);

            var funcion = "eliminar";
            botonguardar(funcion);
            document.getElementById('productoxtipo' + idproducto).disabled = false;
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

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('cantidad').value = "";
            document.getElementById('preciofinal').value = "";
            document.getElementById('preciounitariomo').value = "";
        }
    </script>
@endpush
