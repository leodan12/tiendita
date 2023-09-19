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
                            <label>¿REGISTRAR TAMBIEN INGRESO?</label>
                            {{-- <input class="form-check-input" type="checkbox" name="ingreso" id="ingreso" /> --}}
                            <select name="ingreso" id="ingreso" style="border-radius: 5px">
                                <option value="NO" selected>NO</option>
                                <option value="SI">SI</option>
                            </select>
                            <a href="{{ url('admin/venta') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        {{-- <form action="{{ url('admin/venta') }}" method="POST" enctype="multipart/form-data">
                        @csrf --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="labelfactura" name="labelfactura">NUMERO DE FACTURA</label>
                                <input type="text" name="factura" id="factura" class="form-control mayusculas" />
                                <div class="invalid-feedback" name="validacionfactura" id="validacionfactura"
                                    style="color: red;">
                                    ¡Numero de Factura ya Registrado!
                                </div>
                                @error('factura')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="credito" data-formapago="credito">Credito</option>
                                    <option value="contado" data-formapago="contado">Contado</option>
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labelfechav" class="form-label">FECHA DE VENCIMIENTO</label>
                                <input type="date" name="fechav" id="fechav" class="form-control " readonly />
                                @error('fechav')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="dolares" data-moneda="dolares">Dolares Americanos</option>
                                    <option value="soles" data-moneda="soles">Soles</option>
                                </select>
                                @error('moneda')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.0001" class="form-control "
                                    min="1" />
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
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA VENTA </label>
                                    <span class="input-group-text" id="spancostoventa"></span>
                                    <input type="number" name="costoventa" id="costoventa" min="0.1" step="0.01"
                                        class="form-control  required" required readonly />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FACTURA PAGADA</label>
                                <select class="form-select " name="pagada" id="pagada" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion"
                                    class="form-control mayusculas" />
                                @error('observacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <nav class="" style="border-radius: 5px;">
                                        <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-detalles-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-detalles" type="button" role="tab"
                                                aria-controls="nav-detalles" aria-selected="false">DETALLES</button>
                                            <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-condiciones" type="button" role="tab"
                                                aria-controls="nav-condiciones" aria-selected="false">¿AGREGAR DATOS DE
                                                PAGO?</button>
                                        </div>
                                    </nav>
                                    <br>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                            aria-labelledby="nav-detalles-tab" tabindex="0">
                                            <br>
                                            <div class="row">
                                                <h4>Agregar Detalle de la Venta</h4>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label" id="labelproducto"
                                                        name="labelproducto">PRODUCTO</label>
                                                    <select class="form-select select2 " name="product" id="product"
                                                        disabled>
                                                        <option value="" selected disabled>Seleccione una opción
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label" id="labelunidad">UNIDAD</label>
                                                    <input type="text" name="unidadproducto" id="unidadproducto"
                                                        class="form-control mayusculas" />
                                                </div>
                                                <div class="col-md-3 mb-3">
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
                                                            TOTAL POR
                                                            PRODUCTO</label>
                                                        <span class="input-group-text" id="spanpreciototal"></span>
                                                        <input type="number" name="preciofinal" min="0"
                                                            step="0.0001" id="preciofinal" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label "
                                                        id="labelobservacionproducto">OBSERVACION(Nro Serie):</label>
                                                    <input type="text" name="observacionproducto"
                                                        id="observacionproducto"
                                                        class="form-control mayusculas gui-input" />
                                                    <input type="hidden" name="idcotizacion" value="-1" required>
                                                </div>
                                                <button type="button" class="btn btn-info" id="addDetalleBatch"><i
                                                        class="fa fa-plus"></i>
                                                    Agregar Producto a la Venta</button>
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-striped table-striped table-row-bordered gy-5 gs-5"
                                                        id="detallesVenta">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style="width: 250px;">PRODUCTO</th>
                                                                <th>UNIDAD</th>
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
                                            <br><br>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="input-group form-label">PRECIO DE LA VENTA CON
                                                            IGV</label>
                                                        <span class="input-group-text" id="spanprecioventaconigv"></span>
                                                        <input type="number" name="precioventaconigv" readonly
                                                            id="precioventaconigv" class="input-group form-control "
                                                            step="0.01" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">NRO OC</label>
                                                    <input type="text" name="nrooc" id="nrooc"
                                                        class="form-control mayusculas" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">GUIA DE REMISION</label>
                                                    <input type="text" name="guiaremision" id="guiaremision"
                                                        class="form-control mayusculas" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">CONSTANCIA RETENCION</label>
                                                    <input type="text" name="constanciaretencion"
                                                        id="constanciaretencion" class="form-control mayusculas" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta">A CUENTA
                                                            1</label>
                                                        <span class="input-group-text" id="spanacuenta1"></span>
                                                        <input type="number" name="acuenta1" min="0"
                                                            step="0.0001" id="acuenta1" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta2">A CUENTA
                                                            2</label>
                                                        <span class="input-group-text" id="spanacuenta2"></span>
                                                        <input type="number" name="acuenta2" min="0"
                                                            step="0.0001" id="acuenta2" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta3">A CUENTA
                                                            3</label>
                                                        <span class="input-group-text" id="spanacuenta3"></span>
                                                        <input type="number" name="acuenta3" min="0"
                                                            step="0.0001" id="acuenta3" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelsaldo">SALDO</label>
                                                        <span class="input-group-text" id="spansaldo"></span>
                                                        <input type="number" name="saldo" min="0"
                                                            step="0.0001" id="saldo" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelretencion">DETRACCION / RETENCION (soles)</label>
                                                        <span class="input-group-text" id="spanretencion">S/.</span>
                                                        <input type="number" name="retencion" min="0"
                                                            step="0.0001" id="retencion" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelmontopagado">MONTO
                                                            PAGADO</label>
                                                        <span class="input-group-text" id="spanmontopagado"></span>
                                                        <input type="number" name="montopagado" min="0"
                                                            step="0.0001" id="montopagado" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">FECHA DE PAGO</label>
                                                    <input type="date" name="fechapago" id="fechapago"
                                                        class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>
                        {{-- </form> --}}
                        <div class="toast-container position-fixed bottom-0 start-0 p-2" style="z-index: 1000">
                            <div class="toast " id="productoskit" role="alert" aria-live="assertive"
                                aria-atomic="true" data-bs-autohide="false"
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
                                            <tr style="text-align: center;">
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
                        <div class="toast-container position-fixed   start-0 p-2" style="z-index: 1000; top: 60px;">
                            <div class="toast " id="listaprecios" role="alert" aria-live="assertive"
                                aria-atomic="true" data-bs-autohide="false"
                                style="width: 100%; box-shadow: 0 2px 5px 2px rgb(0, 89, 255); ">
                                <div class="  card-header">
                                    <i class="mdi mdi-information menu-icon"></i>
                                    <strong class="mr-auto"> &nbsp; Precios de compra:</strong>
                                    <button type="button" class="btn-close float-end" data-bs-dismiss="toast"
                                        aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    <table id="precioscompra">
                                        <thead class="fw-bold text-primary">
                                            <tr style="text-align: center;">
                                                <th>FECHA</th>
                                                <th>PRECIO</th>
                                                <th>CANTIDAD</th>
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
                </form>
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
        var tipoproducto = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var idempresa = "-1";
        var facturadisponible = "";
        var urlclientexempresa = "{{ url('admin/venta/comboempresacliente') }}";
        var micantidad2 = null;
        var miprecio2 = null;
        var micantidad3 = null;
        var miprecio3 = null;
        var miprecio = 0;
        var indicehp = 0;
        var mipreciounit = "";
        var misproductos;
        var idcliente = 0;
        var precioespecial = -1;
        var preciomo = 0;
        var toastlistaprecios;
        var toastproductoskit;
        var monedaprecioespecial = "-1";
        var numeroproductoskit = 0;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.getElementById("validacionfactura").style.display = 'none';
        $(document).ready(function() {
            $('.toast').toast();
            traertasacambio();
            toastlistaprecios = document.getElementById('listaprecios');
            toastproductoskit = document.getElementById('productoskit');
            $('.select2').select2({});
            $("#btnguardar").prop("disabled", true);
            //Para poner automaticamente la fecha actual
            var hoy = new Date();
            var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');
            document.getElementById("fecha").value = fechaActual;
            var validez = hoy;
            validez.setDate(validez.getDate() + 15);
            var fechavalidez = validez.getFullYear() + '-' + (String(validez.getMonth() + 1).padStart(2, '0')) +
                '-' + String(validez.getDate()).padStart(2, '0');
            document.getElementById("fechav").value = fechavalidez;

            document.getElementById("acuenta1").onchange = function() {
                pagocredito();
            };
            document.getElementById("acuenta2").onchange = function() {
                pagocredito();
            };
            document.getElementById("acuenta3").onchange = function() {
                pagocredito();
            };
            document.getElementById("saldo").onchange = function() {
                saldox = document.getElementById("saldo").value;
                var selectElement = document.getElementById("pagada");
                if (saldox < 0.2) {
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value === "SI") {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                } else {
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value === "NO") {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            function pagocredito() {
                var acuenta1 = $('[name="acuenta1"]').val();
                var acuenta2 = $('[name="acuenta2"]').val();
                var acuenta3 = $('[name="acuenta3"]').val();
                var precioventaconigv = $('[name="precioventaconigv"]').val();
                var montopagado = 0;
                var saldo = 0;
                if (parseFloat(acuenta1)) {
                    montopagado += parseFloat(acuenta1);
                }
                if (parseFloat(acuenta2)) {
                    montopagado += parseFloat(acuenta2);
                }
                if (parseFloat(acuenta3)) {
                    montopagado += parseFloat(acuenta3);
                }
                if (parseFloat(precioventaconigv)) {
                    saldo = parseFloat(precioventaconigv) - parseFloat(montopagado);
                }
                document.getElementById('saldo').value = parseFloat(saldo.toFixed(4));
                document.getElementById('montopagado').value = parseFloat(montopagado.toFixed(4));
                var selectElement = document.getElementById("pagada");
                if (saldo < 0.2) {
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value === "SI") {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                } else {
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value === "NO") {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

        });
        factura.oninput = function() {
            var mifactura = document.getElementById("factura");
            var empresa = document.getElementById("company_id").value;
            verificarfactura(empresa, mifactura.value);
        };

        function verificarfactura(empresa, factura) {
            var xfactura = document.getElementById("factura");
            var validacion = document.getElementById("validacionfactura");
            if (empresa && factura) {
                var urlvent = "{{ url('admin/venta/facturadisponible') }}";
                $.get(urlvent + '/' + empresa + '/' + factura, function(data) {
                    enviar = document.getElementById('btnguardar');
                    facturadisponible = data;
                    if (data == "SI") {
                        xfactura.style.borderColor = "green";
                        enviar.disabled = false;
                        validacion.style.display = 'none';
                    } else {
                        xfactura.style.borderColor = "red";
                        enviar.disabled = true;
                        validacion.style.display = 'inline';
                    }
                });
            }
        }

        $("#ingreso").change(function() {

            $("#ingreso option:selected").each(function() {
                var ingreso = $(this).val();
                if (ingreso == "SI") {
                    urlclientexempresa = "{{ url('admin/venta/comboempresaclientevi') }}";
                    if (idempresa != "-1") {
                        clientesxempresa(idempresa);
                    }
                } else {
                    urlclientexempresa = "{{ url('admin/venta/comboempresacliente') }}";
                    if (idempresa != "-1") {
                        clientesxempresa(idempresa);
                    }
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
        document.getElementById("cantidad").onchange = function() {
            var xcantidad = document.getElementById("cantidad").value;
            if (precioespecial != -1) {
                var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";

                document.getElementById("preciounitariomo").value = preciomo;
                document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
            } else if (micantidad2 != null) {
                var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;

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
        document.getElementById("servicio").onchange = function() {
            preciofinal();
        };
        document.getElementById("preciounitariomo").onchange = function() {
            preciofinal();
        };

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
                    document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" + stockmaximo + ")";
                    var micantidadmax = document.getElementById('cantidad');
                    micantidadmax.max = stockmaximo;
                }
            });
        };

        $("#company_id").change(function() {
            var company = $(this).val();
            idempresa = company;
            var factura = document.getElementById("factura").value;
            verificarfactura(company, factura);
            $('#product').select2("destroy");
            var urlvent = "{{ url('admin/venta/productosxempresa') }}";
            $.get(urlvent + '/' + company, function(data) {
                misproductos = data;
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    producto_select += '<option  id="productoxempresa' + data[i].id + '" value="' + data[i]
                        .id + '" data-name="' + data[i].nombre + '" data-tipo="' + data[i].tipo +
                        '" data-stock="' + data[i].stockempresa + '" data-moneda="' + data[i].moneda +
                        '" data-cantidad2="' + data[i].cantidad2 + '" data-precio2="' + data[i].precio2 +
                        '" data-cantidad3="' + data[i].cantidad3 + '" data-precio3="' + data[i].precio3 +
                        '" data-unidadproducto="' + data[i].unidad +
                        '" data-price="' + data[i].NoIGV + '">' + data[i].nombre + ' - ' + data[i].codigo +
                        '</option>';
                }
                $("#product").html(producto_select);
                habilitaroptionsproductos();
            });

            clientesxempresa(company);
            if (indice > 0) {
                var indice2 = indicex;
                for (var i = 0; i < indice2; i++) {
                    eliminarTabla(i);
                }
            }
            limpiarinputs();
            $('#product').select2({});
        });

        function clientesxempresa(idempresa) {
            $.get(urlclientexempresa + '/' + idempresa, function(data) {
                var producto_select =
                    '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                        .nombre +
                        '" >' + data[i].nombre + '</option>';
                }
                $("#cliente_id").html(producto_select);
                $('#cliente_id').removeAttr('disabled');
            });
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

        $('#addDetalleBatch').click(function() {

            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitario"]').val();
            var servicio = $('[name="servicio"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
            var observacionproducto = $('[name="observacionproducto"]').val();
            var unidadproducto = $('[name="unidadproducto"]').val();
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
                alert("Ingrese un precio unitario");
                return;
            }
            if (!servicio) {
                alert("Ingrese un servicio");
                return;
            }

            var milista = '<br>';
            var puntos = '';
            var listaproductoskit = "";
            var LVenta = [];
            var tam = LVenta.length;
            LVenta.push(product, nameproduct, cantidad, preciounitario, servicio, preciofinal, preciounitariomo,
                observacionproducto, unidadproducto);
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

                    $unidad = $(this).data("unidadproducto");

                    monedaproducto = $moneda;
                    idproducto = miproduct;
                    tipoproducto = $tipo;
                    stockmaximo = $stock;

                    micantidad2 = $cantidad2;
                    micantidad3 = $cantidad3;

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

                    var urlpc = "{{ url('admin/venta/listaprecioscompra') }}";
                    $.ajax({
                        type: "GET",
                        url: urlpc + '/' + idproducto + '/' + idempresa,
                        async: true,
                        success: function(data) {
                            $('#precioscompra tbody tr').slice().remove();
                            for (var i = 0; i < data.length; i++) {
                                filaDetalle =
                                    '<tr style="border-top: 1px solid silver;" id="fila' + i +
                                    '"><td style="border-right: 1px solid silver;"> ' + data[i]
                                    .fecha +
                                    '</td><td style="border-right: 1px solid silver;"> ' + data[
                                        i].precio +
                                    '</td><td  > ' + data[i].cantidad +
                                    '</td></tr>';
                                $("#precioscompra>tbody").append(filaDetalle);
                            }
                            $(toastlistaprecios).toast('show');
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
                                    i + '" value="' + data[i].producto + '"/>' + data[i].producto +
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
                        $(toastproductoskit).toast('show');
                    }
                    //var mimoneda1 = $('[name="moneda"]').val();

                    if ($tipo == "estandar") {
                        $(toastproductoskit).toast('hide');
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
                        preciounit = parseFloat(($price).toFixed(4));
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
                        document.getElementById('preciounitario').value = parseFloat(($price).toFixed(4));
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
                        document.getElementById('unidadproducto').value = $unidad;
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
            var mipreciounitariot = "PRECIO UNITARIO: " + monedafactura;
            if (precioespecial != -1) {
                mipreciounitariot += "(precio especial)";
            }
            document.getElementById('labelpreciounitario').innerHTML = mipreciounitariot;
            document.getElementById('preciounitariomo').value = preciomo;
            document.getElementById('preciofinal').value = preciomo;
        }

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] + '</b>' +
                puntos + milista + '</td><td><input  type="hidden" name="Lunidadprod[]"  >' + LVenta[8] +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' + indice +
                '" value="' + LVenta[7] + '"required>' + LVenta[7] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[2] +
                '"required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice + '" value="' +
                LVenta[3] + '"required>' + simbolomonedaproducto + LVenta[3] +
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice + '" value="' +
                LVenta[6] + '"required>' + simbolomonedafactura + LVenta[6] +
                '</td><td><input  type="hidden" name="Lservicio[]" id="servicio' + indice + '" value="' + LVenta[4] +
                '"required>' + simbolomonedafactura + LVenta[4] +
                '</td><td ><input id="preciof' + indice + '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] +
                '"required>' + simbolomonedafactura + LVenta[5] +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' + LVenta[0] +
                ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#detallesVenta>tbody").append(filaDetalle);
            $('.toast').toast('hide');
            indice++;
            indicex++;
            ventatotal = parseFloat(ventatotal) + parseFloat(LVenta[5]);
            limpiarinputs();
            document.getElementById('costoventa').value = parseFloat((ventatotal * 1).toFixed(2));
            document.getElementById('precioventaconigv').value = parseFloat((ventatotal * 1.18).toFixed(2));
            document.getElementById('productoxempresa' + LVenta[0]).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }

        //para cambiar la forma de pago  y dehabilitar la fecha de vencimiento
        $("#formapago").change(function() {
            $("#formapago option:selected").each(function() {
                $mimoneda = $(this).data("formapago");
                var selectElement = document.getElementById("pagada");
                if ($mimoneda == "credito") {
                    $("#fechav").prop("readonly", false);
                    $("#fechav").prop("required", true);
                    var fechav = document.getElementById("labelfechav");
                    fechav.className += " is-required";
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value === "NO") {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                } else if ($mimoneda == "contado") {
                    $("#fechav").prop("readonly", true);
                    $("#fechav").prop("required", false);
                    var fechav = document.getElementById("labelfechav");
                    fechav.className = "form-label";
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value === "SI") {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                }
            });
        });

        //para cambiar la moneda de pago y deshabilitar la tasa de cambio
        $("#moneda").change(function() {
            $('#company_id').removeAttr('disabled');
            $("#moneda option:selected").each(function() {
                $mimoneda = $(this).data("moneda");
                if ($mimoneda == "dolares") {
                    simbolomonedafactura = "$";
                } else if ($mimoneda == "soles") {
                    simbolomonedafactura = "S/.";
                }
                document.getElementById('spancostoventa').innerHTML = simbolomonedafactura;
                document.getElementById('spanacuenta1').innerHTML = simbolomonedafactura;
                document.getElementById('spanacuenta2').innerHTML = simbolomonedafactura;
                document.getElementById('spanacuenta3').innerHTML = simbolomonedafactura;
                document.getElementById('spansaldo').innerHTML = simbolomonedafactura;
                document.getElementById('spanmontopagado').innerHTML = simbolomonedafactura;
                document.getElementById('spanprecioventaconigv').innerHTML = simbolomonedafactura;
                if (monedaantigua = 0) {
                    monedafactura = $mimoneda;
                    monedaantigua = 1;
                } else {
                    monedaantigua = monedafactura;
                    monedafactura = $mimoneda;
                    var indice3 = indicex;
                    for (var i = 0; i < indice3; i++) {
                        eliminarTabla(i);
                    }
                }
                if (indicehp > 0) {
                    habilitaroptionsproductos();
                }
                indicehp++;
            });
            limpiarinputs();
        });

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
            document.getElementById('productoxempresa' + idproducto).disabled = false;

            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            indice--;

            // damos el valor
            document.getElementById('costoventa').value = 0
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
            if (facturadisponible == "NO") {
                $("#btnguardar").prop("disabled", true);
            } else {
                $("#btnguardar").prop("disabled", false);
            }
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: ";
            document.getElementById('labelpreciounitarioref').innerHTML = "PRECIO UNITARIO (REFERENCIAL): ";
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
            document.getElementById('unidadproducto').value = "";
            monedaproducto = "";
            simbolomonedaproducto = "";
            $('.toast').toast('hide');
        }

        function habilitaroptionsproductos() {
            for (var i = 0; i < misproductos.length; i++) {
                document.getElementById('productoxempresa' + misproductos[i].id).disabled = false;
            }
        }
    </script>
@endpush
