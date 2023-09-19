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
                    <h4>AÑADIR COTIZACIÓN
                        <a href="{{ url('admin/cotizacion') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/cotizacion') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label id="labelfechav" class="form-label  is-required">FECHA DE VALIDÉZ</label>
                                <input type="date" name="fechav" id="fechav" class="form-control " />
                                @error('fechav')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="dolares" data-moneda="dolares">Dolares Americanos</option>
                                    <option value="soles" data-moneda="soles">Soles</option>
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.0001" class="form-control "
                                    min="1" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select " required>
                                    <option value="" disabled>Seleccion una opción</option>
                                    <option value="credito" data-formapago="credito">Credito</option>
                                    <option value="contado" data-formapago="contado" selected>Contado</option>
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">INCLUIR IGV</label>
                                <select name="igv" id="igv" class="form-select " required>
                                    <option value="" disabled>Seleccion una opción</option>
                                    <option value="SI" data-formapago="SI" selected>SI</option>
                                    <option value="NO" data-formapago="NO">NO</option>
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2  " name="company_id" id="company_id" required disabled>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">CLIENTE</label>
                                <select class="form-select select2  " name="cliente_id" id="cliente_id" required disabled>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA COTIZACIÓN SIN
                                        IGV</label>
                                    <span class="input-group-text" id="spancostoventasinigv"></span>
                                    <input type="number" name="costoventasinigv" id="costoventasinigv" min="0.1"
                                        step="0.01" class="form-control  required" required readonly />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group">PRECIO DE LA COTIZACIÓN CON IGV</label>
                                    <span class="input-group-text" id="spancostoventaconigv"></span>
                                    <input type="number" name="costoventaconigv" id="costoventaconigv" min="0.1"
                                        step="0.01" class="form-control  required" required readonly />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group">PERSONA QUE SOLICITÓ LA COTIZACIÓN</label>
                                    <input type="text" name="persona" id="persona" class="form-control  required"
                                        placeholder="Ejemplo: Sr. Jose Sanchez" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3" id="divdiascredito">
                                <label class="form-label is-required">DIAS DE CREDITO PARA LA COMPRA</label>
                                <input type="number" name="diascredito" id="diascredito" step="1"
                                    class="form-control " min="1" value="15" />
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion"
                                    class="form-control mayusculas" />
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <nav class="" style="border-radius: 5px; ">
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
                                                    <select class="form-select select2 " name="product" id="product"
                                                        disabled>
                                                        <option value="" selected disabled>Seleccione una opción
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
                                                            step="0.0001" id="preciounitario" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitario">PRECIO UNITARIO</label>
                                                        <span class="input-group-text" id="spanpreciounitario"></span>
                                                        <input type="number" name="preciounitariomo" min="0"
                                                            step="0.0001" id="preciounitariomo" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelservicio"
                                                            name="labelservicio">SERVICIO ADICIONAL:</label>
                                                        <span class="input-group-text" id="spanservicio"></span>
                                                        <input type="number" name="servicio" min="0"
                                                            step="0.0001" id="servicio"class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelpreciototal">PRECIO
                                                            TOTAL POR PRODUCTO</label>
                                                        <span class="input-group-text" id="spanpreciototal"></span>
                                                        <input type="number" name="preciofinal" min="0"
                                                            step="0.0001" id="preciofinal" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label "
                                                        id="labelobservacionproducto">OBSERVACION:</label>
                                                    <input type="text" name="observacionproducto"
                                                        id="observacionproducto"
                                                        class="form-control mayusculas gui-input" />
                                                </div>
                                                <button type="button" class="btn btn-info" id="addDetalleBatch"> Agregar
                                                    Producto a la Cotización</button>
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
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  " id="nav-condiciones" role="tabpanel"
                                            aria-labelledby="nav-condiciones-tab" tabindex="0">
                                            <br>
                                            <h4>Agregar Condiciones a la Cotización</h4>
                                            <div class="row">
                                                <div class="col-md-10 mb-3">
                                                    <label class="form-label " id="labelcondicion">CONDICION:</label>
                                                    <input type="text" name="condicion" id="condicion"
                                                        class="form-control  mayusculas gui-input" />
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label " style="color: white">.</label>
                                                    <button type="button" class="btn btn-info form-control"
                                                        id="addCondicion"> Agregar</button>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped table-row-bordered gy-5 gs-5"
                                                    id="condiciones">
                                                    <thead class="fw-bold text-primary">
                                                        <tr>
                                                            <th>CONDICION</th>
                                                            <th>ELIMINAR</th>
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
        var indicehp = 0;
        var conigv = "SI";
        var indicecondicion = 0;
        var tipoproducto = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var condformapago = 0;
        var idcondformapago = -1;
        var micantidad2 = null;
        var miprecio2 = null;
        var micantidad3 = null;
        var miprecio3 = null;
        var preciomo = 0;
        var miprecio = 0;
        var mipreciounit = "";
        var misproductos;
        var idcliente = 0;
        var precioespecial = -1;
        var monedaprecioespecial = "-1";
        var idempresa = "-1";
        var hoy = new Date();
        var numeroproductoskit = 0;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');


        $(document).ready(function() {
            $('.toast').toast();
            traertasacambio();
            document.getElementById("fecha").value = fechaActual;
            var validez = hoy;
            validez.setDate(validez.getDate() + 15);
            var fechavalidez = validez.getFullYear() + '-' + (String(validez.getMonth() + 1).padStart(2, '0')) +
                '-' + String(validez.getDate()).padStart(2, '0');
            document.getElementById("fechav").value = fechavalidez;
            $('.select2').select2({});
            $("#btnguardar").prop("disabled", true);

            //agregamos una condicion 1 de precio por defecto
            var igv = $('[name="igv"]').val();
            var cond = "";
            if (igv == "SI") {
                cond = "El precio inclúye IGV";
            }
            var LCondiciones = [];
            LCondiciones.push(cond);
            agregarCondicion(LCondiciones);

            //agregamos una condicion 2 de precio por defecto
            var fechav1 = $('[name="fechav"]').val();
            var fecha1 = $('[name="fecha"]').val();
            var fechav = new Date(fechav1);
            var fecha = new Date(fecha1);
            var resta = fechav.getTime() - fecha.getTime();
            var dias = resta / 1000 / 60 / 60 / 24;

            //var fecha2 = fechav.setDate()

            var cond = "";
            cond = "La cotizacion es válida por " + dias + " días";
            var LCondiciones = [];
            LCondiciones.push(cond);
            agregarCondicion(LCondiciones);

            //agregamos la condicion numero 3 del tipo de pago
            var tipo = $('[name="formapago"]').val();
            var cond = "";
            if (tipo == "contado") {
                cond = "El pago se realizará al contado";
            }
            var LCondiciones = [];
            LCondiciones.push(cond);
            agregarCondicion(LCondiciones);
        });
        document.getElementById('divdiascredito').style.display = 'none';

        document.getElementById("cantidad").onchange = function() {
            preciofinal();
        };
        document.getElementById("servicio").onchange = function() {
            preciofinal();
        };
        document.getElementById("preciounitariomo").onchange = function() {
            preciofinal();
        };

        $("#fechav").change(function() {
            var fechav1 = $(this).val();
            var fecha1 = $('[name="fecha"]').val();
            var fechav = new Date(fechav1);
            var fecha = new Date(fecha1);
            var resta = fechav.getTime() - fecha.getTime();
            var dias = resta / 1000 / 60 / 60 / 24;
            var cond = "";
            cond = "La cotización es válida por " + dias + " días";
            document.getElementById('inputcondicion1').value = cond;

        });
        $("#fecha").change(function() {
            var fecha1 = $(this).val();
            var fechav1 = $('[name="fechav"]').val();
            var fechav = new Date(fechav1);
            var fecha = new Date(fecha1);
            var resta = fechav.getTime() - fecha.getTime();
            var dias = resta / 1000 / 60 / 60 / 24;
            var cond = "";
            cond = "La cotización es válida por " + dias + " días";
            document.getElementById('inputcondicion1').value = cond;

        });
        $("#igv").change(function() {
            var igv = $(this).val();
            var condicion1 = "";
            if (igv == "SI") {
                condicion1 = "El precio inclúye IGV";
            } else {
                condicion1 = "El precio NO inclúye IGV";
            }
            document.getElementById('inputcondicion0').value = condicion1;

        });
        $("#formapago").change(function() {
            var forma = $(this).val();
            var condicion1 = "";
            if (forma == "credito") {
                var dias = document.getElementById('diascredito').value;
                document.getElementById('divdiascredito').style.display = 'inline';
                condicion1 = "El pago se realizará a crédito con un plazo de " + dias + " días";
            } else {
                document.getElementById('divdiascredito').style.display = 'none';
                condicion1 = "El pago se realizará al contado";
            }
            document.getElementById('inputcondicion2').value = condicion1;

        });
        $("#diascredito").change(function() {
            var dias = document.getElementById('diascredito').value;
            document.getElementById('divdiascredito').style.display = 'inline';
            condicion1 = "El pago se realizará a crédito con un plazo de " + dias + " días";
            document.getElementById('inputcondicion2').value = condicion1;
        });
        $("#igv").change(function() {
            var igv = $(this).val();
            conigv = igv;
            if (conigv == "SI") {
                document.getElementById('costoventaconigv').value = (ventatotal * 1.18).toFixed(2);
            } else {
                document.getElementById('costoventaconigv').value = "";
            }
        });
        $("#company_id").change(function() {
            var company = $(this).val();
            $("#product option:selected").each(function() {
                idempresa = company;
            });
            $('#product').select2("destroy");
            var url3 = "{{ url('admin/venta/productosxempresa') }}";
            $.get(url3 + '/' + company, function(
                data1) {
                misproductos = data1;
                var producto_select =
                    '<option value="" disabled selected>Seleccione una opción</option>';
                for (var i = 0; i < data1.length; i++) {
                    producto_select += '<option id="miproducto' + data1[i].id + '" value="' + data1[i]
                        .id + '" data-tipo="' + data1[i].tipo + '" data-name="' + data1[i].nombre +
                        '" data-stock="' + data1[i].stockempresa + '" data-moneda="' + data1[i].moneda +
                        '" data-cantidad2="' + data1[i].cantidad2 + '" data-precio2="' + data1[i]
                        .precio2 +
                        '" data-cantidad3="' + data1[i].cantidad3 + '" data-precio3="' + data1[i]
                        .precio3 +
                        '" data-price="' + data1[i].NoIGV + '">' + data1[i].nombre + ' - ' + data1[i]
                        .codigo + '</option>';
                }
                $("#product").html(producto_select);
                habilitaroptionsproductos();
            });
            var url4 = "{{ url('admin/venta/comboempresacliente') }}";
            $.get(url4 + '/' + company,
                function(data) {
                    var producto_select =
                        '<option value="" disabled selected>Seleccione una opción</option>'
                    for (var i = 0; i < data.length; i++) {
                        producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                            .nombre +
                            '" data-id="' + data[i].id + '">' + data[i].nombre + '</option>';
                    }
                    $("#cliente_id").html(producto_select);
                    $('#cliente_id').removeAttr('disabled');
                });
            if (indice > 0) {
                var indice2 = indice;
                for (var i = 0; i < indice2; i++) {
                    eliminarTabla(i);
                }
            }
            limpiarinputs();
            $('#product').select2({});
        });
        $("#moneda").change(function() {
            $('#company_id').removeAttr('disabled');
            $("#moneda option:selected").each(function() {
                $mimoneda = $(this).data("moneda");
                if ($mimoneda == "dolares") {
                    simbolomonedafactura = "$";
                } else if ($mimoneda == "soles") {
                    simbolomonedafactura = "S/.";
                }
                document.getElementById('spancostoventasinigv').innerHTML = simbolomonedafactura;
                document.getElementById('spancostoventaconigv').innerHTML = simbolomonedafactura;

                if (monedaantigua = 0) {
                    monedafactura = $mimoneda;
                    monedaantigua = 1;
                } else {
                    monedaantigua = monedafactura;
                    monedafactura = $mimoneda;
                    var indice3 = indice;
                    for (var i = 0; i < indice3; i++) {
                        eliminarTabla(i);
                    }
                    if (indicehp > 0) {
                        habilitaroptionsproductos();
                    }
                    indicehp++;

                }
            });
            limpiarinputs();
        });
        $("#product").change(function() {
            $("#product option:selected").each(function() {
                precioespecial = -1;
                var miproduct = $(this).val();
                if (miproduct) {
                    preciototalI = 0;
                    $price = $(this).data("price");
                    $named = $(this).data("name");
                    $moneda = $(this).data("moneda");
                    $stock = $(this).data("stock");
                    $tipo = $(this).data("tipo");
                    $cantidad2 = $(this).data("cantidad2");
                    $precio2 = $(this).data("precio2");
                    $cantidad3 = $(this).data("cantidad3");
                    $precio3 = $(this).data("precio3");

                    monedaproducto = $moneda;
                    idproducto = miproduct;
                    tipoproducto = $tipo;
                    stockmaximo = $stock;

                    micantidad2 = $cantidad2;
                    micantidad3 = $cantidad3;
                    //alert(stocke);
                    preciomo = $price
                    var mitasacambio1 = $('[name="tasacambio"]').val();

                    var urlpe = "{{ url('admin/venta/precioespecial') }}";
                    $.ajax({
                        type: "GET",
                        url: urlpe + '/' + idcliente + '/' + idproducto,
                        async: true,
                        success: function(data) {
                            if (data != 'x') {
                                precioespecial = data.preciounitariomo;
                                monedaprecioespecial = data.moneda;
                                actualizarprecioespecial(mitasacambio1, data.moneda, data
                                    .preciounitariomo);
                            }
                        }
                    });
                    if (precioespecial != -1) {
                        preciomo = precioespecial;
                    } else {
                        preciomo = $price;
                        monedaprecioespecial = monedaproducto;
                    }
                    //mostramos la notificacion
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
                                    '<tr style="border-top: 1px solid silver; background-color: ' +
                                    fondo + ';" id="filatoast' + i +
                                    '" ><td><input type="number" id="cantidadproductokit' +
                                    i + '" onchange="verstockkit(' + i + ');" min="0" value="' +
                                    data[i].cantidad +
                                    '" style="width:70px;" /></td><td><input type="hidden" id="idproductokit' +
                                    i + '" value="' + data[i].id +
                                    '"/><input type="hidden" id="nombreproductokit' +
                                    i + '" value="' + data[i].producto + '"/>' +
                                    data[i].producto + '</td></tr>';
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
                        preciounit = ($price).toFixed(4);
                        if (monedaprecioespecial == monedafactura) {
                            miprecio = $price;
                            miprecio2 = $precio2;
                            miprecio3 = $precio3;
                            preciomo = preciomo;
                        } else if (monedaprecioespecial == "dolares" && monedafactura == "soles") {
                            miprecio = parseFloat(($price * mitasacambio1).toFixed(4));
                            miprecio2 = parseFloat(($precio2 * mitasacambio1).toFixed(4));
                            miprecio3 = parseFloat(($precio3 * mitasacambio1).toFixed(4));
                            preciomo = parseFloat((preciomo * mitasacambio1).toFixed(4));
                        } else if (monedaprecioespecial == "soles" && monedafactura == "dolares") {
                            miprecio = parseFloat(($price / mitasacambio1).toFixed(4));
                            miprecio2 = parseFloat(($precio2 / mitasacambio1).toFixed(4));
                            miprecio3 = parseFloat(($precio3 / mitasacambio1).toFixed(4));
                            preciomo = parseFloat((preciomo / mitasacambio1).toFixed(4));
                        }
                        document.getElementById('preciounitario').value = parseFloat(($price).toFixed(
                            4));
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
                        document.getElementById('spanpreciounitarioref').innerHTML =
                            simbolomonedaproducto;
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
                    //alert(nameprod);
                    mipreciounit = "PRECIO UNITARIO: " + monedafactura;
                }
            });
        });
        $("#cliente_id").change(function() {
            $('#product').removeAttr('disabled');
            $("#cliente_id option:selected").each(function() {
                var micliente = $(this).val();
                idcliente = micliente;
                limpiarinputs();
                var indice3 = indice;
                for (var i = 0; i < indice3; i++) {
                    eliminarTabla(i);
                }
                if (indicehp > 0) {
                    habilitaroptionsproductos();
                }
                indicehp++;
            });
        });
        $('#addCondicion').click(function() {

            //datos del detalleSensor
            var condicion = $('[name="condicion"]').val();
            if (!condicion) {
                alert("ingrese una condicion:");
                $("#condicion").focus();
                return;
            }
            var LCondiciones = [];
            LCondiciones.push(condicion);
            agregarCondicion(LCondiciones);
        });
        document.getElementById("cantidad").onchange = function() {
            var xcantidad = document.getElementById("cantidad").value;
            if (precioespecial != -1) {
                var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;
                if (precioespecial != -1) {
                    mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                }

                document.getElementById("preciounitariomo").value = preciomo;
                document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
            } else if (micantidad2 != null) {
                var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;

                document.getElementById("preciounitariomo").value = preciomo;
                document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;

                if (xcantidad >= micantidad2) {
                    if (parseFloat(miprecio2, 10) < parseFloat(preciomo, 10)) {
                        document.getElementById("preciounitariomo").value = miprecio2;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit + '(x' +
                            micantidad2 +
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
                        if (precioespecial != -1) {
                            mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                        }
                        document.getElementById("preciounitariomo").value = preciomo;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
                    }
                }
            }
            preciofinal();
        };

        function actualizarprecioespecial(tasacambio, monedaespecial, miprecioespecial) {
            if (monedaespecial == monedafactura) {
                preciomo = miprecioespecial;
            } else if (monedaespecial == "dolares" && monedafactura == "soles") {
                preciomo = parseFloat((miprecioespecial * tasacambio).toFixed(4));
            } else if (monedaespecial == "soles" && monedafactura == "dolares") {
                preciomo = parseFloat((miprecioespecial / tasacambio).toFixed(4));
            }
            var mipreciounitariot = "PRECIO UNITARIO: " + monedafactura;
            if (precioespecial != -1) {
                mipreciounitariot += "(precio especial)";
            }
            document.getElementById('labelpreciounitario').innerHTML = mipreciounitariot;
            document.getElementById('preciounitariomo').value = preciomo;
            document.getElementById('preciofinal').value = preciomo;
        }

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
                    idempresa: idempresa
                },
                success: function(data) {
                    stockmaximo = data;
                    document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" + stockmaximo +
                        ")";
                    var micantidadmax = document.getElementById('cantidad');
                    micantidadmax.max = stockmaximo;
                }
            });
        };

        function agregarCondicion(LCondiciones) {
            filaDetalle = '<tr id="filacondicion' + indicecondicion +
                '"><td><input  id="inputcondicion' + indicecondicion +
                '"  type="text" class="form-control " name="Lcondicion[]" value="' + LCondiciones[0] +
                '"required  >' +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarCondicion(' +
                indicecondicion + ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#condiciones>tbody").append(filaDetalle);
            indicecondicion++;
            document.getElementById('condicion').value = "";
        }

        $('#addDetalleBatch').click(function() {

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
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (parseInt(cantidad) > parseInt(stockmaximo)) {
                alert("La cantidad máxima permitida es: " + stockmaximo);
                document.getElementById('cantidad').value = stockmaximo;
                return;
            }
            if (parseInt(cantidad) < 1) {
                alert("La cantidad mínima permitida es: 1");
                document.getElementById('cantidad').value = 1;
                return;
            }
            if (!preciounitariomo) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!servicio) {
                alert("Ingrese un servicio");
                return;
            }
            //if (!observacionproducto) {alert("ingrese una observacion:");   $("#observacionproducto").focus(); return;   }
            var milista = '<br>';
            var puntos = '';
            var listaproductoskit = "";
            var LVenta = [];
            // var tam = LVenta.length;
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

        });

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] +
                '</b>' +
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
                '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] +
                '"required>' + simbolomonedafactura + LVenta[5] +
                '</td><td style="text-align: center;"><button type="button" class="btn  btn-danger" onclick="eliminarFila(' +
                indice + ',' + LVenta[0] + ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#detallesVenta>tbody").append(filaDetalle);

            indice++;
            //alert(indice); 
            ventatotal = parseFloat(ventatotal) + parseFloat(LVenta[5]);
            limpiarinputs();
            document.getElementById('costoventasinigv').value = (ventatotal * 1).toFixed(2);
            if (conigv == "SI") {
                document.getElementById('costoventaconigv').value = (ventatotal * 1.18).toFixed(2);
            } else {
                document.getElementById('costoventaconigv').value = "";
            }
            document.getElementById('miproducto' + LVenta[0]).disabled = true;
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
                document.getElementById('preciofinal').value = parseFloat(preciototalI.toFixed(4));
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

        function eliminarFila(ind, idproducto) {
            var resta = 0;
            //document.getElementById('preciot' + ind).value();
            resta = $('[id="preciof' + ind + '"]').val();
            //alert(resta);
            ventatotal = (ventatotal - resta).toFixed(4);

            $('#fila' + ind).remove();
            indice--;
            // damos el valor
            document.getElementById('costoventasinigv').value = parseFloat((ventatotal * 1).toFixed(2));
            if (conigv == "SI") {
                document.getElementById('costoventaconigv').value = parseFloat((ventatotal * 1.18).toFixed(2));
            } else {
                document.getElementById('costoventaconigv').value = "";
            }
            document.getElementById('miproducto' + idproducto).disabled = false;
            var funcion = "eliminar";
            botonguardar(funcion);

            return false;
        }

        function eliminarCondicion(ind) {
            $('#filacondicion' + ind).remove();
            //indicecondicion--;
            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            //indice--;
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

        function habilitaroptionsproductos() {
            for (var i = 0; i < misproductos.length; i++) {
                document.getElementById('miproducto' + misproductos[i].id).disabled = false;
            }
        }
    </script>
@endpush
