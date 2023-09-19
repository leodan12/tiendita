@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>EDITAR RED
                        <a href="{{ url('admin/redes') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/redes/' . $red->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" class="form-control mayusculas" required
                                    value="{{ $red->nombre }}" />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">CARROCERIA</label>
                                <select class="form-select select2 " name="carroceria_id" id="carroceria_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($carrocerias as $carroceria)
                                        <option value="{{ $carroceria->id }}"
                                            {{ $carroceria->id == $red->carroceria_id ? 'selected' : 'disabled' }}>
                                            {{ $carroceria->tipocarroceria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">MODELO</label> modelo
                                <select class="form-select select2 " name="modelo_id" id="modelo_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->id }}"
                                            {{ $modelo->id == $red->modelo_id ? 'selected' : 'disabled' }}>
                                            {{ $modelo->modelo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>

                            <h4>Agregar Detalle a la Red</h4>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="labelproducto">PRODUCTO</label>
                                <select class="form-select select2 " name="product" id="product">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($productos as $product)
                                        @php $contp=0;    @endphp
                                        @foreach ($detalles as $item)
                                            @if ($product->id == $item->producto_id)
                                                @php $contp++;    @endphp
                                            @endif
                                        @endforeach
                                        @if ($contp == 0)
                                            <option id="miproducto{{ $product->id }}" value="{{ $product->id }}"
                                                data-name="{{ $product->nombre }}" data-unidad="{{ $product->unidad }}"
                                                data-tipo="{{ $product->tipo }}">
                                                {{ $product->nombre }}</option>
                                        @else
                                            <option disabled id="miproducto{{ $product->id }}"
                                                value="{{ $product->id }}" data-name="{{ $product->nombre }}"
                                                data-unidad="{{ $product->unidad }}" data-tipo="{{ $product->tipo }}">
                                                {{ $product->nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                    class="form-control " />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" id="labelunidad">UNIDAD</label>
                                <input type="text" name="unidad" id="unidad" class="form-control mayusculas" />
                            </div>
                            @php $ind=0 ; @endphp
                            @php $indice=count($detalles) ; @endphp
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-info" id="addDetalleBatch" style="width: 100%;"
                                    onclick="agregarFila('{{ $indice }}')"><i class="fa fa-plus"></i> Agregar
                                    Producto</button>
                                <br>
                            </div>

                            <div class="table-responsive">
                                <table class="table-row-bordered gy-5 gs-5" id="detallesKit" style="width: 100%;">
                                    <thead class="fw-bold text-primary">
                                        <tr style="text-align: center;">
                                            <th>CANTIDAD</th>
                                            <th style="border-left: solid 1px silver;">UNIDAD</th>
                                            <th style="border-left: solid 1px silver;">PRODUCTO</th>
                                            <th style="border-left: solid 1px silver; width: 10%;">ELIMINAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $datobd="db" ;  @endphp
                                        @foreach ($detalles as $detalle)
                                            @php
                                                $ind++;
                                                $fondo = '';
                                            @endphp
                                            @if ($ind % 2 == 0)
                                                @php  $fondo ="#E4EDE6"; @endphp
                                            @endif
                                            <tr id="fila{{ $ind }}"
                                                style="background-color:{{ $fondo }};">
                                                <td style="text-align: center;">{{ $detalle->cantidad }}</td>
                                                <td style="text-align: center;">{{ $detalle->unidad }}</td>
                                                <td> <b> {{ $detalle->nombre }} </b>
                                                    @if ($detalle->tipo == 'kit')
                                                        : <br>
                                                        @foreach ($detalleskit as $kit)
                                                            @if ($detalle->idproducto == $kit->product_id)
                                                                -{{ $kit->cantidad }}
                                                                {{ $kit->producto }} <br>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td style="text-align: center;">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $detalle->id }}', '{{ $detalle->idproducto }}'  )"
                                                        data-id="0">ELIMINAR</button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <hr>

                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Actualizar</button>
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
        estadoguardar = @json($indice);
        var indicex = 0;
        var misproductos = @json($productos);
        botonguardar("inicio");

        $(document).ready(function() {
            $('.select2').select2();
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

        var indice = 0;
        var pv = 0;

        function agregarFila(indice1) {

            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }

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
        }

        function agregarFilasTabla(LDetalle, puntos, milista, product) {
            var fondo = "#ffffff";
            if (indice % 2 == 0) {
                fondo = "#E4EDE6";
            }
            filaDetalle = '<tr id="fila' + indice + '" style="background-color:' + fondo +
                '";><td style="text-align: center;"><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +
                '" value="' + LDetalle[2] + '"required>' + LDetalle[2] +
                '</td><td style="text-align: center;"><input  type="hidden" name="Lunidad[]" id="unidad' + indice +
                '" value="' + LDetalle[3] + '"required>' + LDetalle[3] +
                '</td><td><input  type="hidden" name="Lproduct[]" value="' + LDetalle[0] + '"required><b>' + LDetalle[1] +
                '</b>' + puntos + milista +
                '</td><td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(' +
                indice + ',-1,-1,' + product + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesKit>tbody").append(filaDetalle);

            indice++;
            limpiarinputs();

            document.getElementById('miproducto' + product).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }


        function eliminarFila(ind, lugardato, iddetalle, producto) {
            if (lugardato == "db") {
                Swal.fire({
                    title: '¿Esta seguro de Eliminar?',
                    text: "No lo podra revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí,Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var miurl = "{{ url('admin/redes/deletedetallered') }}";
                        $.get(miurl + '/' + iddetalle, function(data) {
                            //alert(data[0]);
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarFila(ind);
                            } else if (data[0] == 0) {
                                alert("no se puede eliminar");
                            } else if (data[0] == 2) {
                                alert("registro no encontrado");
                            }
                        });
                    }
                });
            } else {
                quitarFila(ind);
            }
            document.getElementById('miproducto' + producto).disabled = false;
            return false;
        }

        function quitarFila(ind) {
            $('#fila' + ind).remove();
            indice--;
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
            if (parseInt(estadoguardar) < 1) {
                $("#btnguardar").prop("disabled", true); 
            } else if (parseInt(estadoguardar) >= 1) {
                $("#btnguardar").prop("disabled", false); 
            }
        }
    </script>
@endpush
