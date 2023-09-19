@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @php  $detalles = count($detallescotizacion) @endphp
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
                    <h4>EDITAR LA COTIZACIÓN NRO: &nbsp; {{ $cotizacion->numero }}
                        <a href="{{ url('admin/cotizacion') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/cotizacion/' . $cotizacion->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <input type="hidden" name="numero" id="numero" value="{{ $cotizacion->numero }}" />
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " readonly required
                                    value="{{ $cotizacion->fecha }}" />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label id="labelfechav" class="form-label  is-required">FECHA DE VALIDÉZ</label>
                                <input type="date" name="fechav" id="fechav" class="form-control "
                                    value="{{ $cotizacion->fechav }}" />
                                @error('fechav')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @if ($cotizacion->moneda == 'soles')
                                        <option value="soles" data-moneda="soles" selected>Soles</option>
                                    @elseif($cotizacion->moneda == 'dolares')
                                        <option value="dolares" data-moneda="dolares" selected>Dolares Americanos</option>
                                    @endif
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.0001" class="form-control "
                                    min="1" readonly value="{{ $cotizacion->tasacambio }}" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @if ($cotizacion->formapago == 'credito')
                                        <option value="credito" data-formapago="credito" selected>Credito</option>
                                    @elseif($cotizacion->formapago == 'contado')
                                        <option value="contado" data-formapago="contado" selected>Contado</option>
                                    @endif
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">INCLUIR IGV</label>
                                <select name="igv" id="igv" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @if ($cotizacion->costoventaconigv == null)
                                        <option value="SI" data-formapago="SI">SI</option>
                                        <option value="NO" data-formapago="NO" selected>NO</option>
                                    @elseif($cotizacion->costoventaconigv != null)
                                        <option value="SI" data-formapago="SI" selected>SI</option>
                                        <option value="NO" data-formapago="NO">NO</option>
                                    @endif
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2 " name="company_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id == $cotizacion->company_id ? 'selected' : '' }}>
                                            {{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">CLIENTE</label>
                                <select class="form-select select2 " name="cliente_id" id="cliente_id" required>
                                    <option value="" select disabled>Seleccione una opción</option>

                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA COTIZACIÓN SIN
                                        IGV</label>
                                    @if ($cotizacion->moneda == 'dolares')
                                        <span class="input-group-text" id="spancostoventasinigv">$</span>
                                    @elseif($cotizacion->moneda == 'soles')
                                        <span class="input-group-text" id="spancostoventasinigv">S/.</span>
                                    @endif
                                    <input type="number" name="costoventasinigv" id="costoventasinigv" min="0.1"
                                        step="0.01" class="form-control  required" required readonly
                                        value="{{ $cotizacion->costoventasinigv }}" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group">PRECIO DE LA COTIZACIÓN CON IGV</label>
                                    @if ($cotizacion->moneda == 'dolares')
                                        <span class="input-group-text" id="spancostoventaconigv">$</span>
                                    @elseif($cotizacion->moneda == 'soles')
                                        <span class="input-group-text" id="spancostoventaconigv">S/.</span>
                                    @endif
                                    <input type="number" name="costoventaconigv" id="costoventaconigv" min="0.1"
                                        step="0.01" class="form-control  required" required readonly
                                        value="{{ $cotizacion->costoventaconigv }}" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group">PERSONA QUE SOLICITÓ LA COTIZACIÓN</label>

                                    <input type="text" name="persona" id="persona" class="form-control   "
                                        value="{{ $cotizacion->persona }}" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" id="divdiascredito">
                                <label id="labeldiascredito" class="form-label is-required">DIAS DE CREDITO PARA LA
                                    COMPRA</label>
                                <input type="number" name="diascredito" id="diascredito" step="1"
                                    class="form-control " min="1" value="{{ $cotizacion->diascredito }}" />
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion"
                                    class="form-control mayusculas" value="{{ $cotizacion->observacion }}" />
                            </div>
                            {{-- ---------------------------------------------------------------------- --}}
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <nav style="border-radius: 5px; ">
                                        <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-detalles-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-detalles" type="button" role="tab"
                                                aria-controls="nav-detalles" aria-selected="false">Detalles</button>
                                            <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-condiciones" type="button" role="tab"
                                                aria-controls="nav-condiciones" aria-selected="false">Condiciones</button>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                            aria-labelledby="nav-detalles-tab" tabindex="0">
                                            <br>
                                            <h4>Agregar Detalle de la Cotización</h4>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label" name="labelproducto"
                                                        id="labelproducto">PRODUCTO</label>
                                                    <select class="form-select select2 " name="product" id="product">
                                                        <option selected disabled value="">Seleccione una opción
                                                        </option>

                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label" name="labelcantidad"
                                                        id="labelcantidad">CANTIDAD</label>
                                                    <input type="number" name="cantidad" id="cantidad" min="1"
                                                        step="1" class="form-control " />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitarioref">PRECIO UNITARIO
                                                            (REFERENCIAL):</label>
                                                        <span class="input-group-text" id="spanpreciounitarioref"></span>
                                                        <input type="number" name="preciounitario" min="0"
                                                            step="0.01" id="preciounitario" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitario">PRECIO UNITARIO</label>
                                                        <span class="input-group-text" id="spanpreciounitario"></span>
                                                        <input type="number" name="preciounitariomo" min="0"
                                                            step="0.01" id="preciounitariomo" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelservicio"
                                                            name="labelservicio">SERVICIO ADICIONAL:</label>
                                                        <span class="input-group-text" id="spanservicio"></span>
                                                        <input type="number" name="servicio" min="0"
                                                            step="0.01" id="servicio"class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelpreciototal">PRECIO
                                                            TOTAL POR PRODUCTO</label>
                                                        <span class="input-group-text" id="spanpreciototal"></span>
                                                        <input type="number" name="preciofinal" min="0"
                                                            step="0.01" id="preciofinal" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label "
                                                        id="labelobservacionproducto">OBSERVACION(Nro Serie):</label>
                                                    <input type="text" name="observacionproducto"
                                                        id="observacionproducto"
                                                        class="form-control mayusculas gui-input" />
                                                </div>
                                                @php $ind=0 ; @endphp
                                                @php $indice=count($detallescotizacion) ; @endphp
                                                <button type="button" class="btn btn-info" id="addDetalleBatch"
                                                    onclick="agregarFila('{{ $indice }}')"><i
                                                        class="fa fa-plus"></i> Agregar Producto a la Venta</button>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-row-bordered gy-5 gs-5"
                                                        id="detallesVenta">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style="width: 300px;">PRODUCTO</th>
                                                                <th>OBSERVACION</th>
                                                                <th>CANTIDAD</th>
                                                                <th>PRECIO UNITARIO (REFERENCIAL)</th>
                                                                <th>PRECIO UNITARIO</th>
                                                                <th>SERVICIO ADICIONAL</th>
                                                                <th>PRECIO FINAL DEL PRODUCTO</th>
                                                                <th>ELIMINAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $datobd="db" ;  @endphp
                                                            @foreach ($detallescotizacion as $detalle)
                                                                @php $ind++;    @endphp
                                                                <tr id="fila{{ $ind }}">
                                                                    <td> <b> {{ $detalle->producto }} </b>
                                                                        @if ($detalle->tipo == 'kit')
                                                                            : <br>
                                                                            @foreach ($detalleskit as $kit)
                                                                                @if ($detalle->iddetallecotizacion == $kit->iddetallecotizacion)
                                                                                    -{{ $kit->cantidad }}
                                                                                    {{ $kit->producto }} <br>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td> {{ $detalle->observacionproducto }}</td>
                                                                    <td style="text-align: center;">
                                                                        {{ $detalle->cantidad }}</td>
                                                                    <td style="text-align: center;">
                                                                        @if ($detalle->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($detalle->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->preciounitario }}
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        @if ($cotizacion->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($cotizacion->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->preciounitariomo }}
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        @if ($cotizacion->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($cotizacion->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->servicio }}
                                                                    </td>
                                                                    <td style="text-align: center;"><input type="hidden"
                                                                            id="preciof{{ $ind }}"
                                                                            value="{{ $detalle->preciofinal }}" />
                                                                        @if ($cotizacion->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($cotizacion->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->preciofinal }}
                                                                    </td>
                                                                    <td style="text-align: center;">
                                                                        <button type="button" class="btn btn-danger"
                                                                            onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $detalle->iddetallecotizacion }}', '{{ $detalle->idproducto }}'  )"
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
                                        <div class="tab-pane fade show " id="nav-condiciones" role="tabpanel"
                                            aria-labelledby="nav-condiciones-tab" tabindex="0">
                                            <br>
                                            <h4>Agregar Condiciones a la Cotización</h4>
                                            <div class="row">
                                                <div class="col-md-10 mb-3">
                                                    <label class="form-label " id="labelcondicion">CONDICION:</label>
                                                    <input type="text" name="condicion" id="condicion"
                                                        class="form-control mayusculas gui-input" />
                                                </div>
                                                @php $indc=0 ; @endphp
                                                @php $indicec=count($condiciones) ; @endphp
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label " style="color: white">.</label>
                                                    <button type="button" class="btn btn-info form-control"
                                                        id="addCondicion"
                                                        onclick="agregarCondicion('{{ $indicec }}')">
                                                        Agregar</button>
                                                </div>
                                            </div>
                                            <div class="table-response">
                                                <table class="table table-striped table-row-bordered gy-5 gs-5"
                                                    id="condiciones">
                                                    <thead class="fw-bold text-primary">
                                                        <tr>
                                                            <th>CONDICION</th>
                                                            <th>ELIMINAR</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $datobd="db" ;  @endphp
                                                        @foreach ($condiciones as $item)
                                                            @php $indc++;    @endphp
                                                            <tr id="filacondicion{{ $indc }}">
                                                                <td> {{ $item->condicion }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger"
                                                                        onclick="eliminarCondicion( '{{ $indc }}' ,'{{ $datobd }}', '{{ $item->idcondicion }}'  )"
                                                                        data-id="0">ELIMINAR</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
        var preciounit = 0;
        var nameproduct = 0;
        var preciototalI = 0;
        var estadoguardar = 0;
        var monedafactura = "";
        var monedaproducto = "";
        var monedaantigua = 0;
        var simbolomonedaproducto = "";
        var simbolomonedafactura = "";
        var indicex = 0;
        var conigv = "";
        var idcompany = "";
        var tipoproducto = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var idcliente = 0;
        var detallesagregados = [];
        var micantidad2 = null;
        var miprecio2 = null;
        var micantidad3 = null;
        var miprecio3 = null;
        var miprecio = 0;
        var precioespecial = -1;
        var preciomo = 0;
        var monedaprecioespecial = "-1";
        var numeroproductoskit = 0;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        idcompany = @json($cotizacion->company_id);
        idcliente = @json($cotizacion->cliente_id);
        var misdetalles = @json($detallescotizacion);
        var formapago = @json($cotizacion->formapago);
        estadoguardar = @json(count($detallescotizacion));

        if (formapago == "contado") {
            document.getElementById('divdiascredito').style.display = 'none';
        } else {
            document.getElementById('divdiascredito').style.display = 'inline';
        }

        //alert(estadoguardar);
        var funcion1 = "inicio";
        botonguardar(funcion1);
        var costoventa = $('[id="costoventasinigv"]').val();
        ventatotal = costoventa;
        var hoy = new Date();
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');

        document.getElementById("cantidad").onchange = function() {
            preciofinal();
        };
        document.getElementById("servicio").onchange = function() {
            preciofinal();
        };
        document.getElementById("preciounitariomo").onchange = function() {
            preciofinal();
        };

        $("#igv").change(function() {
            var igv = $(this).val();
            conigv = igv;
            var costoCot = $('[name="costoventasinigv"]').val();
            if (conigv == "SI") {
                document.getElementById('costoventaconigv').value = (costoCot * 1.18).toFixed(2);
            } else {
                document.getElementById('costoventaconigv').value = "";
            }
        });

        $("#product").change(function() {
            $("#product option:selected").each(function() {
                precioespecial = -1;
                var miproduct = $(this).val();
                if (miproduct) {
                    $price = $(this).data("price");
                    $named = $(this).data("name");
                    $moneda = $(this).data("moneda");
                    $stock = $(this).data("stock");
                    $tipo = $(this).data("tipo");

                    $cantidad2 = $(this).data("cantidad2");
                    $precio2 = $(this).data("precio2");
                    $cantidad3 = $(this).data("cantidad3");
                    $precio3 = $(this).data("precio3");

                    micantidad2 = $cantidad2;
                    micantidad3 = $cantidad3;

                    tipoproducto = $tipo;
                    stockmaximo = $stock;
                    monedaproducto = $moneda;
                    idproducto = miproduct;

                    preciomo = $price
                    var urlpe = "{{ url('admin/venta/precioespecial') }}";
                    $.ajax({
                        type: "GET",
                        url: urlpe + '/' + idcliente + '/' + idproducto,
                        async: true,
                        success: function(data) {
                            if (data != 'x') {
                                precioespecial = data.preciounitariomo;
                                monedaprecioespecial = data.moneda;
                                actualizarprecioespecial(mitasacambio1, data.moneda,
                                    data.preciounitariomo);
                            }
                        }
                    });
                    if (precioespecial != -1) {
                        preciomo = precioespecial;
                    } else {
                        preciomo = $price;
                        monedaprecioespecial = monedaproducto;
                    }

                    monedafactura = $('[name="moneda"]').val();
                    if (monedafactura == "dolares") {
                        simbolomonedafactura = "$";
                    } else if (monedafactura == "soles") {
                        simbolomonedafactura = "S/.";
                    }

                    //alert(stocke);
                    if ($tipo == "kit") {
                        numeroproductoskit = 0;
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $('#detalleskit tbody tr').slice().remove();
                        $.get(urlventa + '/' + miproduct, function(data) {
                            var filasconcantidad = "";
                            var filasconcero = "";
                            for (var i = 0; i < data.length; i++) {
                                var fondo = "white";
                                if (data[i].cantidad == 0) {
                                    fondo = "#ff000095";
                                }
                                numeroproductoskit++;
                                filaDetalle =
                                    '<tr style="border-top: 1px solid silver;  background-color: ' +
                                    fondo + ';" id="filatoast' + i +
                                    '" ><td><input type="number" id="cantidadproductokit' +
                                    i + '" onchange="verstockkit(' + i + ');" min="0" value="' +
                                    data[i]
                                    .cantidad +
                                    '" style="width:70px;" /></td><td><input type="hidden" id="idproductokit' +
                                    i + '" value="' + data[i].id +
                                    '"/><input type="hidden" id="nombreproductokit' +
                                    i + '" value="' + data[i].producto + '"/>' + data[i]
                                    .producto +
                                    '</td></tr>';
                                if (data[i].cantidad != 0) {
                                    filasconcantidad += filaDetalle;
                                } else {
                                    filasconcero += filaDetalle;
                                }
                            }
                            $("#detalleskit>tbody").append(filasconcantidad);
                            $("#detalleskit>tbody").append(filasconcero);
                        });
                        $('.toast').toast('show');
                    }
                    var mitasacambio1 = $('[name="tasacambio"]').val();
                    //var mimoneda1 = $('[name="moneda"]').val();
                    if ($tipo == "estandar") {
                        $('.toast').toast('hide');
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                    } else if ($tipo == "kit") {
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO TIPO KIT";
                    }
                    document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" + $stock + ")";

                    var cant = document.getElementById('cantidad');
                    cant.setAttribute("max", $stock);
                    cant.setAttribute("min", 1);
                    if (monedaproducto == "soles") {
                        simbolomonedaproducto = "S/.";
                    } else {
                        simbolomonedaproducto = "$";
                    }
                    if ($price != null) {
                        preciounit = ($price).toFixed(2);
                        if (monedaprecioespecial == monedafactura) {
                            miprecio = $price;
                            miprecio2 = $precio2;
                            miprecio3 = $precio3;
                            preciomo = preciomo;
                        } else if (monedaprecioespecial == "dolares" && monedafactura == "soles") {
                            miprecio = ($price * mitasacambio1).toFixed(2);
                            miprecio2 = ($precio2 * mitasacambio1).toFixed(2);
                            miprecio3 = ($precio3 * mitasacambio1).toFixed(2);
                            preciomo = (preciomo * mitasacambio1).toFixed(2);
                        } else if (monedaprecioespecial == "soles" && monedafactura == "dolares") {
                            miprecio = ($price / mitasacambio1).toFixed(2);
                            miprecio2 = ($precio2 / mitasacambio1).toFixed(2);
                            miprecio3 = ($precio3 / mitasacambio1).toFixed(2);
                            preciomo = (preciomo / mitasacambio1).toFixed(2);
                        }
                        document.getElementById('preciounitario').value = ($price).toFixed(2);
                        document.getElementById('preciounitariomo').value = preciomo;
                        document.getElementById('preciofinal').value = preciomo;
                        document.getElementById('labelpreciounitarioref').innerHTML =
                            "PRECIO UNITARIO(REFERENCIAL): " + monedaproducto;
                        var mipreciounitariot = "PRECIO UNITARIO: " + monedafactura;
                        if (precioespecial != -1) {
                            mipreciounitariot += "(precio especial)";
                        }
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounitariot;
                        document.getElementById('labelservicio').innerHTML = "SERVICIO ADICIONAL: " +
                            monedafactura;
                        document.getElementById('labelpreciototal').innerHTML =
                            "PRECIO TOTAL POR PRODUCTO: " + monedafactura;
                        document.getElementById('spanpreciounitarioref').innerHTML = simbolomonedaproducto;
                        document.getElementById('spanpreciounitario').innerHTML = simbolomonedafactura;
                        document.getElementById('spanservicio').innerHTML = simbolomonedafactura;
                        document.getElementById('spanpreciototal').innerHTML = simbolomonedafactura;

                        document.getElementById('cantidad').value = 1;
                        document.getElementById('servicio').value = 0;
                        nameproduct = $named;
                    } else if ($price == null) {
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('servicio').value = 0;
                        document.getElementById('preciofinal').value = 0;
                        document.getElementById('preciounitario').value = 0;
                        document.getElementById('preciounitariomo').value = 0;
                    }
                    mipreciounit = "PRECIO UNITARIO: " + monedafactura;
                }
            });
        });

        function actualizarprecioespecial(tasacambio, monedaespecial, miprecioespecial) {
            if (monedaespecial == monedafactura) {
                preciomo = miprecioespecial;
            } else if (monedaespecial == "dolares" && monedafactura == "soles") {
                preciomo = parseFloat((miprecioespecial * tasacambio).toFixed(4));
            } else if (monedaespecial == "soles" && monedafactura == "dolares") {
                preciomo = parseFloat((miprecioespecial / tasacambio).toFixed(4));
            }
            var mipreciounitariox = "PRECIO UNITARIO: " + monedafactura;
            if (precioespecial != -1) {
                mipreciounitariox += "(precio especial)";
            }
            document.getElementById('labelpreciounitario').innerHTML = mipreciounitariox;
            document.getElementById('preciounitariomo').value = preciomo;
            document.getElementById('preciofinal').value = preciomo;
        }
        document.getElementById("cantidad").onchange = function() {
            var xcantidad = document.getElementById("cantidad").value;
            if (precioespecial != -1) {
                var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                document.getElementById("preciounitariomo").value = preciomo;
                document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
            } else if (micantidad2 != null) {
                var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;
                if (precioespecial != -1 && precioespecial < miprecio) {
                    mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                }
                document.getElementById("preciounitariomo").value = preciomo;
                document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;

                if (xcantidad >= micantidad2) {
                    if (parseFloat(miprecio2, 10) < parseFloat(preciomo, 10)) {
                        document.getElementById("preciounitariomo").value = miprecio2;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit + '(x' + micantidad2 +
                            ')';
                    }
                    if (micantidad3 != null) {
                        if (xcantidad >= micantidad3) {
                            if (parseFloat(miprecio3, 10) < parseFloat(preciomo, 10)) {
                                document.getElementById("preciounitariomo").value = miprecio3;
                                document.getElementById('labelpreciounitario').innerHTML = mipreciounit + '(x' +
                                    micantidad3 + ')';
                            }
                        }
                    }
                } else {
                    if (parseFloat(miprecio, 10) < parseFloat(preciomo, 10)) {
                        document.getElementById("preciounitariomo").value = miprecio;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit;
                    } else {

                        var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;
                        if (precioespecial != -1 && parseFloat(preciomo, 10) < parseFloat(miprecio, 10)) {
                            mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                        }
                        document.getElementById("preciounitariomo").value = preciomo;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
                    }
                }
            }
            preciofinal();
        };

        $(document).ready(function() {
            $('.select2').select2({});
            $('.toast').toast();
            var igv1 = $('[name="igv"]').val();
            conigv = igv1;
            var url5 = "{{ url('admin/venta/comboempresacliente') }}";
            $.get(url5 + '/' + idcompany, function(data) {
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    if (idcliente == data[i].id) {
                        producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                            .nombre +
                            '" selected>' + data[i].nombre + '</option>';
                    } else {
                        producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                            .nombre +
                            '" >' + data[i].nombre + '</option>';
                    }

                }
                $("#cliente_id").html(producto_select);
            });
            var url22 = "{{ url('admin/venta/productosxempresa') }}";
            $.get(url22 + '/' + idcompany, function(data) {
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].stockempresa == null) {
                        alert(data[i].stockempresa);
                    }
                    var desabilitado = "";
                    var contx = 0;
                    for (var x = 0; x < misdetalles.length; x++) {
                        if (misdetalles[x].idproducto == data[i].id) {
                            contx++;
                        }
                    }
                    if (contx > 0) {
                        desabilitado = "disabled";
                    }
                    producto_select += '<option ' + desabilitado + ' id="productoxempresa' + data[i].id +
                        '" value="' + data[i].id +
                        '" data-name="' + data[i].nombre + '" data-tipo="' + data[i].tipo +
                        '" data-stock="' + data[i].stockempresa + '" data-moneda="' + data[i].moneda +
                        '" data-cantidad2="' + data[i].cantidad2 + '" data-precio2="' + data[i].precio2 +
                        '" data-cantidad3="' + data[i].cantidad3 + '" data-precio3="' + data[i].precio3 +
                        '" data-price="' + data[i].NoIGV + '">' + data[i].nombre + ' - ' + data[i].codigo +
                        ' </option>';
                }
                $("#product").html(producto_select);
            });
        });

        var indicecondicion = 0;
        var pvc = 0;

        function agregarCondicion(indicecc) {

            if (pvc == 0) {
                indicecondicion = indicecc;
                pvc++;
                indicecondicion++;
            } else {
                indicecondicion++;
            }
            //datos del detalleSensor
            var condicion = $('[name="condicion"]').val();
            if (!condicion) {
                alert("ingrese una condicion:");
                $("#condicion").focus();
                return;
            }
            var LCondiciones = [];
            LCondiciones.push(condicion);

            filaDetalle = '<tr id="filacondicion' + indicecondicion +
                '"><td><input  type="hidden" name="Lcondicion[]" value="' + LCondiciones[0] + '"required>' + LCondiciones[
                    0] + '</td><td><button type="button" class="btn btn-danger" onclick="quitarCondicion(' +
                indicecondicion + ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#condiciones>tbody").append(filaDetalle);
            indicecondicion++;
            document.getElementById('condicion').value = "";
        }

        var indice = 0;
        var pv = 0;

        function verstockkit(itoast) {
            var cantidadtoast = document.getElementById("cantidadproductokit" + itoast).value;
            var fila = document.getElementById("filatoast" + itoast);
            if (cantidadtoast == 0) {
                fila.style.backgroundColor = "#ff000095";
            } else {
                fila.style.backgroundColor = "white";
            }
            var cantidadproducto = [];
            var idproductokit = [];
            var nombreproducto = [];
            for (var i = 0; i < numeroproductoskit; i++) {
                var cant = document.getElementById("cantidadproductokit" + i).value;
                var nombre = document.getElementById("nombreproductokit" + i).value;
                var id = document.getElementById("idproductokit" + i).value;
                if (cant > 0) {
                    cantidadproducto.push(cant);
                    idproductokit.push(id);
                    nombreproducto.push(nombre);
                }
            }
            var urlverstock = "{{ url('admin/venta/verstock') }}";
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: urlverstock,
                async: true,
                data: {
                    cantidad: cantidadproducto,
                    idproducto: idproductokit,
                    idempresa: idcompany
                },
                success: function(data) {
                    stockmaximo = data;
                    document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" +
                        stockmaximo + ")";
                    var micantidadmax = document.getElementById('cantidad');
                    micantidadmax.max = stockmaximo;
                }
            });
        };

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
            var preciounitario = $('[name="preciounitario"]').val();
            var servicio = $('[name="servicio"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
            var observacionproducto = $('[name="observacionproducto"]').val();

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!preciounitariomo) {
                alert("Ingrese un precio");
                return;
            }
            if (!servicio) {
                alert("Ingrese un servicio");
                return;
            }

            var LVenta = [];
            var tam = LVenta.length;
            var datodb = "local";
            var listaproductoskit = "";
            var milista = '<br>';
            var puntos = '';
            LVenta.push(product, nameproduct, cantidad, preciounitario, servicio, preciofinal, preciounitariomo,
                observacionproducto);
            if (tipoproducto == "kit") {
                puntos = ': ';
                for (var i = 0; i < numeroproductoskit; i++) {
                    var cant = document.getElementById("cantidadproductokit" + i).value;
                    var nombre = document.getElementById("nombreproductokit" + i).value;
                    var id = document.getElementById("idproductokit" + i).value;
                    var coma = '<br>';
                    if (i + 1 == numeroproductoskit) {
                        coma = '';
                    }
                    if (cant > 0) {
                        milista = milista + '-' + cant + ' ' + nombre + coma;
                        listaproductoskit += '<input  type="hidden" name="Lidkit[]" value="' +
                            idproducto + '" /><input  type="hidden" name="Lcantidadproductokit[]" value="' +
                            cant + '" /><input  type="hidden" name="Lidproductokit[]" value="' +
                            id + '" />';
                    }
                }
                milista += listaproductoskit;
                agregarFilasTabla(LVenta, puntos, milista);
            } else {
                agregarFilasTabla(LVenta, puntos, milista);
            }
        }

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] + '</b>' +
                puntos + milista +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' + indice +
                '" value="' + LVenta[7] + '"required>' + LVenta[7] +
                '</td><td style="text-align: center;"><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +
                '" value="' + LVenta[2] + '"required>' + LVenta[2] +
                '</td><td style="text-align: center;"><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' +
                indice + '" value="' + LVenta[3] + '"required>' + simbolomonedaproducto + LVenta[3] +
                '</td><td style="text-align: center;"><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' +
                indice + '" value="' + LVenta[6] + '"required>' + simbolomonedafactura + LVenta[6] +
                '</td><td style="text-align: center;"><input  type="hidden" name="Lservicio[]" id="servicio' + indice +
                '" value="' + LVenta[4] + '"required>' + simbolomonedafactura + LVenta[4] +
                '</td><td style="text-align: center;"><input id="preciof' + indice +
                '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] + '"required>' +
                simbolomonedafactura + LVenta[5] +
                '</td><td style="text-align: center;"> <button type="button" class="btn btn-danger" onclick="eliminarFila(' +
                indice + ',' + 0 + ',' + 0 + ',' + LVenta[0] + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesVenta>tbody").append(filaDetalle);

            indice++;
            ventatotal = (parseFloat(ventatotal) + parseFloat(LVenta[5])).toFixed(2);
            limpiarinputs();
            document.getElementById('costoventasinigv').value = (ventatotal * 1).toFixed(2);
            if (conigv == "SI") {
                document.getElementById('costoventaconigv').value = (ventatotal * 1.18).toFixed(2);
            } else {
                document.getElementById('costoventaconigv').value = "";
            }
            document.getElementById('productoxempresa' + LVenta[0]).disabled = true;
            detallesagregados.push(LVenta[0]);
            var funcion = "agregar";
            botonguardar(funcion);

            $('.toast').toast('hide');
        }

        function preciofinal() {
            var cantidad = $('[name="cantidad"]').val();
            var preciounit = $('[name="preciounitariomo"]').val();
            var servicio = $('[name="servicio"]').val();
            if (cantidad >= 1 && preciounit >= 0 && servicio >= 0) {
                preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)) + parseFloat(parseFloat(
                    cantidad) * parseFloat(servicio)));
                document.getElementById('preciofinal').value = parseFloat(preciototalI.toFixed(2));
            }
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: ";
            document.getElementById('labelpreciounitarioref').innerHTML = "PRECIO UNITARIO(REFERENCIAL): ";
            document.getElementById('labelservicio').innerHTML = "SERVICIO ADICIONAL:";
            document.getElementById('labelpreciototal').innerHTML = "PRECIO TOTAL POR PRODUCTO:";
            document.getElementById('spanpreciounitarioref').innerHTML = "";
            document.getElementById('spanpreciounitario').innerHTML = "";
            document.getElementById('spanservicio').innerHTML = "";
            document.getElementById('spanpreciototal').innerHTML = "";
            document.getElementById('cantidad').value = "";
            document.getElementById('servicio').value = "";
            document.getElementById('preciofinal').value = "";
            document.getElementById('preciounitario').value = "";
            document.getElementById('preciounitariomo').value = "";
            document.getElementById('observacionproducto').value = "";
            monedaproducto = "";
            simbolomonedaproducto = "";
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
                        var urle = "{{ url('admin/deletedetallecotizacion') }}";
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
            var resta = 0;
            resta = $('[id="preciof' + indicador + '"]').val();
            ventatotal = (ventatotal - resta).toFixed(2);
            $('#fila' + indicador).remove();
            //indice--;
            document.getElementById('costoventasinigv').value = ventatotal;
            document.getElementById('costoventaconigv').value = (ventatotal * 1.18).toFixed(2);
            var funcion = "eliminar";
            botonguardar(funcion);
        }

        function quitarCondicion(ind) {
            $('#filacondicion' + ind).remove();
            //indicecondicion--;
            return false;
        }

        function eliminarCondicion(ind, lugardato, idcondicion) {
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
                        var urle2 = "{{ url('admin/deletecondicion') }}";
                        $.get(urle2 + '/' + idcondicion, function(data) {
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarCondicion(ind);
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

        function eliminarTabla(ind) {
            $('#fila' + ind).remove();
            indice--;
            // damos el valor
            document.getElementById('costoventasinigv').value = 0;
            document.getElementById('costoventaconigv').value = "";
            //alert(resta);
            var funcion = "eliminar";
            botonguardar(funcion);
            ventatotal = 0;
            preciounit = 0;
            nameproduct = 0;
            preciototalI = 0;
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
