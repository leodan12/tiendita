@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-md-12">
            @php  $detalles = count($detallesordencompra) @endphp
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
                    <h4>EDITAR LA ORDEN DE COMPRA NRO: &nbsp; {{ $ordencompra->numero }}
                        <a href="{{ url('admin/ordencompra') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/ordencompra/' . $ordencompra->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="numero" id="numero" value="{{ $ordencompra->numero }}" />
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " readonly required
                                    value="{{ $ordencompra->fecha }}" />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group">PERSONA A LA QUE SE ENVIA LA ORDEN </label>
                                    <input type="text" name="persona" id="persona" class="form-control   "
                                        value="{{ $ordencompra->persona }}" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2 " name="company_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id == $ordencompra->company_id ? 'selected' : '' }}>
                                            {{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label is-required">PROVEEDOR</label>
                                <select class="form-select select2 " name="cliente_id" id="cliente_id" required>
                                    <option value="" select disabled>Seleccione una opción</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                            {{ $cliente->id == $ordencompra->cliente_id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @if ($ordencompra->moneda == 'dolares')
                                        <option value="dolares" data-moneda="dolares" selected>Dolares Americanos</option>
                                        <option value="soles" data-moneda="soles">Soles</option>
                                    @elseif($ordencompra->moneda == 'soles')
                                        <option value="dolares" data-moneda="dolares">Dolares Americanos</option>
                                        <option value="soles" data-moneda="soles" selected>Soles</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">FORMA PAGO</label>
                                    <select class="form-select " name="formapago" id="formapago" required>
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        @if ($ordencompra->formapago == 'credito')
                                            <option value="credito" data-moneda="credito" selected>Credito </option>
                                            <option value="contado" data-moneda="contado">Contado</option>
                                        @elseif($ordencompra->formapago == 'contado')
                                            <option value="credito" data-moneda="credito">Credito </option>
                                            <option value="contado" data-moneda="contado" selected>Contado</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @if ($ordencompra->formapago == 'credito')
                                <div class="col-md-4 mb-3" id="divdiascredito" style="display: inline;">
                                    <div class="input-group">
                                        <label class="form-label input-group is-required">DIAS DE CREDITO</label>
                                        <input class="form-control " type="number" name="diascredito"
                                            value="{{ $ordencompra->diascredito }}" id="diascredito" />
                                    </div>
                                </div>
                            @elseif($ordencompra->formapago == 'contado')
                                <div class="col-md-4 mb-3" id="divdiascredito" style="display: none;">
                                    <div class="input-group">
                                        <label class="form-label input-group is-required">DIAS DE CREDITO</label>
                                        <input class="form-control " type="number" name="diascredito" id="diascredito" />
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12  mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control mayusculas"
                                    value="{{ $ordencompra->observacion }}" />
                            </div>
                            {{-- ----------------------                          ------------------------- --}}
                            <hr>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                    aria-labelledby="nav-detalles-tab" tabindex="0">
                                    <br>
                                    <h4>Agregar Detalle de la Cotización</h4>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label " id="labelproducto">PRODUCTO</label>
                                            <select class="form-select select2 " name="product" id="product">
                                                <option selected disabled value="">Seleccione una opción
                                                </option>
                                                @foreach ($products as $product)
                                                    @php $contp=0;    @endphp
                                                    @foreach ($detallesordencompra as $item)
                                                        @if ($product->id == $item->idproducto)
                                                            @php $contp++;    @endphp
                                                        @endif
                                                    @endforeach
                                                    @if ($contp == 0)
                                                        <option id="productoxempresa{{ $product->id }}"
                                                            value="{{ $product->id }}" data-tipo="{{ $product->tipo }}"
                                                            data-name="{{ $product->nombre }}"
                                                            data-unidad="{{ $product->unidad }}"
                                                            data-moneda="{{ $product->moneda }}"
                                                            data-preciocompra="{{ $product->preciocompra }}">
                                                            {{ $product->nombre }} - {{ $product->codigo }}</option>
                                                    @else
                                                        <option disabled id="productoxempresa{{ $product->id }}"
                                                            value="{{ $product->id }}" data-tipo="{{ $product->tipo }}"
                                                            data-name="{{ $product->nombre }}"
                                                            data-unidad="{{ $product->unidad }}"
                                                            data-moneda="{{ $product->moneda }}"
                                                            data-preciocompra="{{ $product->preciocompra }}">
                                                            {{ $product->nombre }} - {{ $product->codigo }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label class="form-label" name="labelcantidad"
                                                id="labelcantidad">CANTIDAD</label>
                                            <input type="number" name="cantidad" id="cantidad" min="1"
                                                step="1" class="form-control " />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="input-group">
                                                <label class="form-label input-group">PRECIO COMPRA:</label>
                                                <span class="input-group-text" id="spanpreciocompra">
                                                    @if ($ordencompra->moneda == 'soles')
                                                        S/.
                                                    @else
                                                        $
                                                    @endif
                                                </span>
                                                <input type="number" name="preciocompra" min="1" step="0.0001"
                                                    id="preciocompra"class="form-control " readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label " id="labelobservacionproducto">OBSERVACION:</label>
                                            <input type="text" name="observacionproducto" id="observacionproducto"
                                                class="form-control mayusculas gui-input" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label" name="labelcantidad"
                                                id="labelcantidad">UNIDAD</label>
                                            <input type="text" name="unidad" id="unidad" class="form-control mayusculas" />
                                        </div>
                                        @php $ind=0 ; @endphp
                                        @php $indice=count($detallesordencompra) ; @endphp
                                        <button type="button" class="btn btn-info" id="addDetalleBatch"
                                            onclick="agregarFila('{{ $indice }}')"><i class="fa fa-plus"></i>
                                            Agregar Producto a la Venta</button>
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
                                                    @php $datobd="db" ;  @endphp
                                                    @foreach ($detallesordencompra as $detalle)
                                                        @php $ind++;    @endphp
                                                        <tr id="fila{{ $ind }}">
                                                            <td> {{ $detalle->cantidad }}</td>
                                                            <td> {{ $detalle->unidad }}</td>
                                                            @if ($ordencompra->moneda == 'soles')
                                                                <td>S/. {{ $detalle->preciocompra }}</td>
                                                            @else
                                                                <td>$ {{ $detalle->preciocompra }}</td>
                                                            @endif
                                                            <td> <b> {{ $detalle->producto }} </b>
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
                                                            <td> {{ $detalle->observacionproducto }}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger"
                                                                    onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $detalle->iddetalleordencompra }}', '{{ $detalle->idproducto }}'  )"
                                                                    data-id="0"><i
                                                                        class="bi bi-trash-fill"></i>ELIMINAR</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12 mb-3">
                                    <button type="submit" id="btnguardar" name="btnguardar"
                                        class="btn btn-primary text-white float-end" disabled>Actualizar</button>
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
        var ventatotal = 0;
        var nameproduct = 0;
        var estadoguardar = 0;
        var indicex = 0;
        var idcompany = "";
        var tipoproducto = "";
        var idproducto = 0;
        var idcliente = 0;
        var detallesagregados = [];
        var unidadproducto = "";
        var simbolomoneda = "";
        idcompany = @json($ordencompra->company_id);
        idcliente = @json($ordencompra->cliente_id);
        var misdetalles = @json($detallesordencompra);
        estadoguardar = @json(count($detallesordencompra));

        var monedaorden = @json($ordencompra->moneda);

        if (monedaorden == "soles") {
            simbolomoneda = "S/.";
        } else {
            simbolomoneda = "$";
        }
        //alert(estadoguardar);
        var funcion1 = "inicio";
        botonguardar(funcion1);

        var hoy = new Date();
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');

        $("#product").change(function() {
            $("#product option:selected").each(function() {
                precioespecial = -1;
                var miproduct = $(this).val();
                if (miproduct) {
                    $named = $(this).data("name");
                    $tipo = $(this).data("tipo");
                    $unidad = $(this).data("unidad");
                    $preciocompra = $(this).data("preciocompra");
                    $moneda = $(this).data("moneda");
                    var precioproducto;

                    tipoproducto = $tipo;
                    idproducto = miproduct;
                    unidadproducto = $unidad;

                    if (monedaorden == $moneda) {
                        precioproducto = $preciocompra;
                    } else if (monedaorden == "soles" && $moneda == "dolares") {
                        precioproducto = parseFloat(($preciocompra * tasacambio).toFixed(4));
                    } else if (monedaorden == "dolares" && $moneda == "soles") {
                        precioproducto = parseFloat(($preciocompra / tasacambio).toFixed(4));
                    }

                    //producto tipo kit
                    if ($tipo == "kit") {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.get(urlventa + '/' + miproduct, function(data) {
                            $('#detalleskit tbody tr').slice().remove();
                            for (var i = 0; i < data.length; i++) {
                                filaDetalle = '<tr style="border-top: 1px solid silver;" id="fila' +
                                    i +
                                    '"><td> ' + data[i].cantidad +
                                    '</td><td> ' + data[i].producto +
                                    '</td></tr>';
                                $("#detalleskit>tbody").append(filaDetalle);
                            }
                        });
                        $('.toast').toast('show');
                    }

                    if ($tipo == "estandar") {
                        $('.toast').toast('hide');
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                    } else if ($tipo == "kit") {
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO TIPO KIT";
                    }

                    if ($named != null) {
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('unidad').value = unidadproducto;
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

        $("#formapago").change(function() {
            $("#formapago option:selected").each(function() {
                var formapago = $(this).val();
                if (formapago == "credito") {
                    document.getElementById('divdiascredito').style.display = "inline";
                } else {
                    document.getElementById('divdiascredito').style.display = "none";
                }

            });
        });

        $(document).ready(function() {
            $('.select2').select2({});
            $('.toast').toast();

            var urlvertasacambio = "{{ url('admin/dato/vertasacambio') }}";
            $.ajax({
                type: "GET",
                url: urlvertasacambio,
                success: function(data) {
                    tasacambio = data['valor'];
                }
            });

        });

        var pvc = 0;

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
            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var unidad = $('[name="unidad"]').val();
            var observacionproducto = $('[name="observacionproducto"]').val();
            var preciocompra = $('[name="preciocompra"]').val();

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

            var LVenta = [];
            var tam = LVenta.length;
            var datodb = "local";
            var milista = '<br>';
            var puntos = '';
            LVenta.push(product, nameproduct, cantidad, observacionproducto, unidad, preciocompra);

            if (tipoproducto == "kit") {
                puntos = ': ';
                var urlventa = "{{ url('admin/venta/productosxkit') }}";
                $.get(urlventa + '/' + idproducto, function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var coma = '<br>';
                        if (i + 1 == data.length) {
                            coma = '';
                        }
                        milista = milista + '-' + data[i].cantidad + ' ' + data[i].producto + coma;
                    }
                    agregarFilasTabla(LVenta, puntos, milista);
                });
            } else {
                agregarFilasTabla(LVenta, puntos, milista);
            }
        }

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[2] +
                '"required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lunidadproducto[]" id="unidadproducto' + indice + '" value="' +
                LVenta[4] + '"required>' + LVenta[4] +
                '</td><td><input  type="hidden" name="Lpreciocompra[]" id="preciocompra' + indice + '" value="' +
                LVenta[5] + '"required>' +simbolomoneda+ LVenta[5] +
                '</td><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] +
                '</b>' +
                puntos + milista +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' + indice +
                '" value="' + LVenta[3] + '"required>' + LVenta[3] +
                '</td><td> <button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' + 0 + ',' +
                0 + ',' + LVenta[0] + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesVenta>tbody").append(filaDetalle);

            indice++;

            limpiarinputs();
            document.getElementById('productoxempresa' + LVenta[0]).disabled = true;
            detallesagregados.push(LVenta[0]);
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

        function eliminarFila(ind, lugardato, iddetalle, idproducto) {
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
                        document.getElementById('product').disabled = true;
                        $('.product').select2("destroy");
                        var urle = "{{ url('admin/ordencompra/deletedetalleordencompra') }}";
                        $.get(urle + '/' + iddetalle, function(data) {
                            //alert(data[0]);
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarFila(ind);
                                document.getElementById('productoxempresa' + idproducto).disabled = false;
                                document.getElementById('product').disabled = false;
                                $('.select2').select2({});
                                //llenarselectproducto();
                            } else if (data[0] == 0) {
                                Swal.fire({
                                    text: "No se puede eliminar",
                                    icon: "error"
                                });
                            } else if (data[0] == 2) {
                                Swal.fire({
                                    text: "Registro no encontrado",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            } else {
                quitarFila(ind);
            }
            return false;
        }

        function quitarFila(indicador) {
            $('#fila' + indicador).remove();
            indice--;
            var funcion = "eliminar";
            botonguardar(funcion);
        }

        function eliminarTabla(ind) {
            $('#fila' + ind).remove();
            indice--;
            // damos el valor
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
    </script>
@endpush
