@extends('layouts.admin')
@push('css')
    <script>
        var mostrar = "NO";

        function mostrarstocks() {
            mostrar = "SI";
        }
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if (session('verstock'))
                <script>
                    mostrarstocks();
                </script>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 id="mititulo">REGISTRO DE VENTAS:
                            </h4>
                        </div>
                        <div class="col">
                            <h4>
                                @can('crear-venta')
                                    <a href="{{ url('admin/venta/create') }}" class="btn btn-primary float-end">Añadir
                                        venta</a>
                                @endcan
                            </h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <h5 id="sinnumerofactura"></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-striped table-bordered table-striped " id="mitabla" name="mitabla">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>FACTURA</th>
                                    <th>FECHA</th>
                                    <th>CLIENTE</th>
                                    <th>EMPRESA</th>
                                    <th>MONEDA</th>
                                    <th>FORMA PAGO</th>
                                    <th>COSTO VENTA </th>
                                    <th>PAGADA </th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-mantenimientos">
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- modal paera ver la venta --}}
                <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="mimodalLabel">Ver Venta</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="col-md-4   mb-3">
                                            <label for="verFecha" class="col-form-label">FECHA:</label>
                                            <input type="text" class="form-control " id="verFecha" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verFactura" class="col-form-label">NUMERO FACTURA:</label>
                                            <input type="text" class="form-control" id="verFactura" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verFormapago" class="col-form-label">FORMA PAGO:</label>
                                            <input type="text" class="form-control" id="verFormapago" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3 " id="divfechav">
                                            <label for="verFechav" class="col-form-label">FECHA VENCIMIENTO:</label>
                                            <input type="text" class="form-control" id="verFechav" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                            <input type="text" class="form-control " id="verMoneda" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3" id="divtasacambio">
                                            <label for="verTipocambio" class="col-form-label">TIPO DE CAMBIO:</label>
                                            <input type="text" class="form-control " id="verTipocambio" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verEmpresa" class="col-form-label">EMPRESA:</label>
                                            <input type="text" class="form-control " id="verEmpresa" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verCliente" class="col-form-label">CLIENTE:</label>
                                            <input type="text" class="form-control " id="verCliente" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <div class="input-group">
                                                <label for="verPrecioventa" class="col-form-label input-group">PRECIO
                                                    VENTA:</label>
                                                <span class="input-group-text" id="spancostoventa"></span>
                                                <input type="text" class="form-control " id="verPrecioventa" readonly>
                                            </div>
                                        </div>
                                        <div class=" col-md-4   mb-3" id="divobservacion">
                                            <label for="verObservacion" class="col-form-label">OBSERVACION:</label>
                                            <input type="text" class="form-control " id="verObservacion" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verPagada" class="col-form-label">FACTURA PAGADA:</label>
                                            <input type="text" class="form-control " id="verPagada" readonly>
                                        </div>

                                    </div>
                                </form>

                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                        <nav class="" style="border-radius: 5px; ">
                                            <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">

                                                <button class="nav-link active" id="nav-detalles-tab"
                                                    data-bs-toggle="tab" data-bs-target="#nav-detalles" type="button"
                                                    role="tab" aria-controls="nav-detalles"
                                                    aria-selected="false">DETALLES</button>
                                                <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-condiciones" type="button" role="tab"
                                                    aria-controls="nav-condiciones" aria-selected="false"> DATOS DE
                                                    PAGO</button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                                aria-labelledby="nav-detalles-tab" tabindex="0">
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesventa">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th>Producto</th>
                                                                <th>Unidad</th>
                                                                <th>Observacion</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio Unitario (referencial)</th>
                                                                <th>precio Unitario</th>
                                                                <th>Servicio Adicional</th>
                                                                <th>Costo Productos</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade  " id="nav-condiciones" role="tabpanel"
                                                aria-labelledby="nav-condiciones-tab" tabindex="0">
                                                <br><br>
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="input-group form-label">PRECIO DE LA VENTA
                                                                CON IGV</label>
                                                            <span class="input-group-text"
                                                                id="0spanprecioventaconigv"></span>
                                                            <input type="number" name="0precioventaconigv"
                                                                id="0precioventaconigv"
                                                                class="input-group form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">NRO OC</label>
                                                        <input type="text" name="0nrooc" id="0nrooc"
                                                            class="form-control mayusculas" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label input-group">GUIA DE REMISION</label>
                                                        <input type="text" name="0guiaremision" id="0guiaremision"
                                                            class="form-control mayusculas" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label input-group">FECHA DE PAGO</label>
                                                        <input type="date" name="0fechapago" id="0fechapago"
                                                            class="form-control" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group" id="0labelacuenta">A
                                                                CUENTA 1</label>
                                                            <span class="input-group-text" id="0spanacuenta1"></span>
                                                            <input type="number" name="0acuenta1" min="0"
                                                                step="0.01" id="0acuenta1" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group" id="0labelacuenta2">A
                                                                CUENTA 2</label>
                                                            <span class="input-group-text" id="0spanacuenta2"></span>
                                                            <input type="number" name="0acuenta2" min="0"
                                                                step="0.01" id="0acuenta2" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group" id="labelacuenta3">A
                                                                CUENTA 3</label>
                                                            <span class="input-group-text" id="0spanacuenta3"></span>
                                                            <input type="number" name="0acuenta3" min="0"
                                                                step="0.01" id="0acuenta3" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group"
                                                                id="labelsaldo">SALDO</label>
                                                            <span class="input-group-text" id="0spansaldo"></span>
                                                            <input type="number" name="0saldo" min="0"
                                                                step="0.01" id="0saldo" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label input-group">CONSTANCIA
                                                            RETENCION</label>
                                                        <input type="text" name="0constanciaretencion"
                                                            id="0constanciaretencion" class="form-control mayusculas" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group"
                                                                id="labelretencion">DETRACCION / RETENCION (soles)</label>
                                                            <span class="input-group-text" id="0spanretencion"></span>
                                                            <input type="number" name="0retencion" min="0"
                                                                step="0.01" id="0retencion" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group"
                                                                id="labelmontopagado">MONTO PAGADO</label>
                                                            <span class="input-group-text" id="0spanmontopagado"></span>
                                                            <input type="number" name="0montopagado" min="0"
                                                                step="0.01" id="0montopagado" class="form-control " />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="generarfactura"> Generar Pdf de la
                                    Factura </button>
                                @can('ver-venta')
                                    <button type="button" class="btn btn-warning" id="pagarfactura">Guardar Datos de
                                        Pago</button>
                                @endcan
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- mis modales para ver los creditos vencidos --}}
                <div class="modal fade" id="modalCreditos1" aria-hidden="true" aria-labelledby="modalCreditos1Label"
                    tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalCreditos1Label1"> </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped table-bordered table-striped " id="mitabla1" name="mitabla1">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>ID</th>
                                            <th>FECHA</th>
                                            <th>FECHA VENC</th>
                                            <th>CLIENTE</th>
                                            <th>EMPRESA</th>
                                            <th>MONEDA</th>
                                            <th>FORMA PAGO</th>
                                            <th>COSTO VENTA </th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <Tbody id="tbody-mantenimientos">
                                        <tr></tr>
                                    </Tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- modal para ver los datosde los creditos x vencer --}}
                <div class="modal fade" id="modalVer2" aria-hidden="true" aria-labelledby="modalCreditos2Label2"
                    tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalCreditos2Label2">Ver Venta a Credito</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="col-md-4   mb-3">
                                            <label for="verFecha1" class="col-form-label">FECHA:</label>
                                            <input type="text" class="form-control " id="verFecha1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verFactura1" class="col-form-label">NUMERO FACTURA:</label>
                                            <input type="text" class="form-control" id="verFactura1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verFormapago1" class="col-form-label">FORMA PAGO:</label>
                                            <input type="text" class="form-control" id="verFormapago1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3 " id="divfechav1">
                                            <label for="verFechav1" class="col-form-label">FECHA VENCIMIENTO:</label>
                                            <input type="text" class="form-control" id="verFechav1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verMoneda1" class="col-form-label">MONEDA:</label>
                                            <input type="text" class="form-control " id="verMoneda1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3" id="divtasacambio">
                                            <label for="verTipocambio1" class="col-form-label">TIPO DE CAMBIO:</label>
                                            <input type="text" class="form-control " id="verTipocambio1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verEmpresa1" class="col-form-label">EMPRESA:</label>
                                            <input type="text" class="form-control " id="verEmpresa1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verCliente1" class="col-form-label">CLIENTE:</label>
                                            <input type="text" class="form-control " id="verCliente1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <div class="input-group">
                                                <label for="verPrecioventa1" class="col-form-label input-group">PRECIO
                                                    VENTA:</label>
                                                <span class="input-group-text" id="spancostoventa1"></span>
                                                <input type="text" class="form-control " id="verPrecioventa1"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class=" col-md-4   mb-3" id="divobservacion1">
                                            <label for="verObservacion1" class="col-form-label">OBSERVACION:</label>
                                            <input type="text" class="form-control " id="verObservacion1" readonly>
                                        </div>
                                        <div class=" col-md-4   mb-3">
                                            <label for="verPagada1" class="col-form-label">FACTURA PAGADA:</label>
                                            <input type="text" class="form-control " id="verPagada1" readonly>
                                        </div>
                                    </div>
                                </form>

                                <div class="row justify-content-center">
                                    <div class="col-lg-12">
                                        <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                        <nav class="" style="border-radius: 5px; ">
                                            <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                                                <button class="nav-link active" id="nav-detalles1-tab"
                                                    data-bs-toggle="tab" data-bs-target="#nav-detalles1" type="button"
                                                    role="tab" aria-controls="nav-detalles1"
                                                    aria-selected="false">DETALLES</button>
                                                <button class="nav-link " id="nav-condiciones1-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-condiciones1" type="button" role="tab"
                                                    aria-controls="nav-condiciones1" aria-selected="false"> DATOS DE
                                                    PAGO</button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-detalles1" role="tabpanel"
                                                aria-labelledby="nav-detalles1-tab" tabindex="0">
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesventa1">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th>Producto</th>
                                                                <th>Observacion</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio Unitario (referencial)</th>
                                                                <th>precio Unitario</th>
                                                                <th>Servicio Adicional</th>
                                                                <th>Costo Productos</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade  " id="nav-condiciones1" role="tabpanel"
                                                aria-labelledby="nav-condiciones1-tab" tabindex="0">
                                                <br><br>
                                                <div class="row">
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="input-group form-label">PRECIO DE LA VENTA
                                                                CON IGV</label>
                                                            <span class="input-group-text"
                                                                id="1spanprecioventaconigv"></span>
                                                            <input type="number" name="1precioventaconigv"
                                                                id="1precioventaconigv"
                                                                class="input-group form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">NRO OC</label>
                                                        <input type="text" name="1nrooc" id="1nrooc"
                                                            class="form-control mayusculas" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label input-group">GUIA DE REMISION</label>
                                                        <input type="text" name="1guiaremision" id="1guiaremision"
                                                            class="form-control  mayusculas" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label input-group">FECHA DE PAGO</label>
                                                        <input type="date" name="1fechapago" id="1fechapago"
                                                            class="form-control" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group" id="1labelacuenta">A
                                                                CUENTA 1</label>
                                                            <span class="input-group-text" id="1spanacuenta1"></span>
                                                            <input type="number" name="1acuenta1" min="0"
                                                                step="0.0001" id="1acuenta1" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group" id="1labelacuenta2">A
                                                                CUENTA 2</label>
                                                            <span class="input-group-text" id="1spanacuenta2"></span>
                                                            <input type="number" name="1acuenta2" min="0"
                                                                step="0.0001" id="1acuenta2" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group" id="labelacuenta3">A
                                                                CUENTA 3</label>
                                                            <span class="input-group-text" id="1spanacuenta3"></span>
                                                            <input type="number" name="1acuenta3" min="0"
                                                                step="0.0001" id="1acuenta3" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group"
                                                                id="labelsaldo">SALDO</label>
                                                            <span class="input-group-text" id="1spansaldo"></span>
                                                            <input type="number" name="1saldo" min="0"
                                                                step="0.0001" id="1saldo" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label input-group">CONSTANCIA
                                                            RETENCION</label>
                                                        <input type="text" name="1constanciaretencion"
                                                            id="1constanciaretencion" class="form-control mayusculas" />
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group"
                                                                id="labelretencion">DETRACCION / RETENCION (soles)</label>
                                                            <span class="input-group-text" id="1spanretencion"></span>
                                                            <input type="number" name="1retencion" min="0"
                                                                step="0.0001" id="1retencion" class="form-control " />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <div class="input-group">
                                                            <label class="form-label input-group"
                                                                id="labelmontopagado">MONTO
                                                                PAGADO</label>
                                                            <span class="input-group-text" id="1spanmontopagado"></span>
                                                            <input type="number" name="1montopagado" min="0"
                                                                step="0.0001" id="1montopagado" class="form-control " />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="generarfactura1"> Generar Pdf de
                                    la Factura </button>
                                @can('editar-venta')
                                    <button type="button" class="btn btn-warning" id="pagarfactura1">Guardar Datos de
                                        Pago</button>
                                @endcan
                                <button class="btn btn-primary" data-bs-target="#modalCreditos1"
                                    data-bs-toggle="modal">Volver</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- fin del modal --}}

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        //para el modal ver venta
        var idventa = "";
        var formapago = "";
        var hoy = new Date();
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');
        var inicializartabla = 0;
        var numerocreditos = 0;
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        $(document).ready(function() {
            if (mostrar == "SI") {
                $('#modalCreditos1').modal('show');
            }
            var tabla = "#mitabla";
            var ruta = "{{ route('venta.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'factura',
                    name: 'factura'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },

                {
                    data: 'cliente',
                    name: 'c.nombre'
                },
                {
                    data: 'empresa',
                    name: 'e.nombre'
                },
                {
                    data: 'moneda',
                    name: 'moneda'
                },
                {
                    data: 'formapago',
                    name: 'formapago'
                },
                {
                    data: 'costoventa',
                    name: 'costoventa'
                },
                {
                    data: 'pagada',
                    name: 'pagada'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    searchable: false,
                    orderable: false,
                },
            ];
            var btns = 'lfrtip';

            iniciarTablaIndex(tabla, ruta, columnas, btns);
            var nroeliminados = "{{ url('admin/venta/sinnumero') }}";
            $.get(nroeliminados, function(data) {
                mostrarmensajesinfactura(data);
            });
            var sinstock = "{{ url('admin/venta/creditosxvencer') }}";
            $.get(sinstock, function(data) {
                numerocreditos = data;
                console.log(data);
                mostrarmensaje(data);
            });
        });
        //para borrar un registro de la tabla
        $(document).on('click', '.btnborrar', function(event) {
            const idregistro = event.target.dataset.idregistro;
            var urlregistro = "{{ url('admin/venta') }}";
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
                    $.ajax({
                        type: "GET",
                        url: urlregistro + '/' + idregistro + '/delete',
                        success: function(data1) {
                            if (data1 == "1") {
                                $('#modalCreditos1').modal('hide');
                                recargartabla();
                                $(event.target).closest('tr').remove();

                                Swal.fire({
                                    icon: "success",
                                    text: "Registro Eliminado",
                                });
                            } else if (data1 == "0") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro No Eliminado",
                                });
                            } else if (data1 == "2") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro No Encontrado",
                                });
                            }
                        }
                    });
                }
            });
        });


        //modal para ver una venta
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id'); 
            $('#detallesventa tbody tr').slice().remove();
            var urlventa = "{{ url('admin/venta/show') }}";
            $.get(urlventa + '/' + id, function(data) {
                var midata = data;
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`;
                idventa = id;
                formapago = midata[0].formapago;
                document.getElementById("verFecha").value = midata[0].fecha;
                document.getElementById("verFactura").value = midata[0].factura;
                document.getElementById("verMoneda").value = midata[0].moneda;
                document.getElementById("verFormapago").value = midata[0].formapago;
                document.getElementById("verEmpresa").value = midata[0].company;
                document.getElementById("verCliente").value = midata[0].cliente
                document.getElementById("verPagada").value = midata[0].pagada;
                document.getElementById("verPrecioventa").value = midata[0].costoventa;

                document.getElementById("0nrooc").value = midata[0].nrooc;
                document.getElementById("0guiaremision").value = midata[0].guiaremision;
                document.getElementById("0fechapago").value = midata[0].fechapago;
                document.getElementById("0constanciaretencion").value = midata[0].constanciaretencion;
                document.getElementById("0acuenta1").value = midata[0].acuenta1;
                document.getElementById("0acuenta2").value = midata[0].acuenta2;
                document.getElementById("0acuenta3").value = midata[0].acuenta3;
                document.getElementById("0saldo").value = midata[0].saldo;
                document.getElementById("0retencion").value = midata[0].retencion;
                document.getElementById("0montopagado").value = midata[0].montopagado;
                document.getElementById("0precioventaconigv").value = parseFloat((parseFloat((midata[0].costoventa *
                    0.18).toFixed(4)) + parseFloat((midata[0].costoventa).toFixed(4))).toFixed(4));

                if (midata[0].moneda == "dolares") {
                    document.getElementById('spancostoventa').innerHTML = "$";
                    document.getElementById('0spanacuenta1').innerHTML = "$";
                    document.getElementById('0spanacuenta2').innerHTML = "$";
                    document.getElementById('0spanacuenta3').innerHTML = "$";
                    document.getElementById('0spansaldo').innerHTML = "$";
                    document.getElementById('0spanretencion').innerHTML = "S/.";
                    document.getElementById('0spanmontopagado').innerHTML = "$";
                    document.getElementById('0spanprecioventaconigv').innerHTML = "$";
                } else if (midata[0].moneda == "soles") {
                    document.getElementById('spancostoventa').innerHTML = "S/.";
                    document.getElementById('0spanacuenta1').innerHTML = "S/.";
                    document.getElementById('0spanacuenta2').innerHTML = "S/.";
                    document.getElementById('0spanacuenta3').innerHTML = "S/.";
                    document.getElementById('0spansaldo').innerHTML = "S/.";
                    document.getElementById('0spanretencion').innerHTML = "S/.";
                    document.getElementById('0spanmontopagado').innerHTML = "S/.";
                    document.getElementById('0spanprecioventaconigv').innerHTML = "S/.";
                }
                if (midata[0].fechav == null) {
                    document.getElementById('divfechav').style.display = 'none';
                } else {
                    document.getElementById('divfechav').style.display = 'inline';
                    document.getElementById("verFechav").value = midata[0].fechav;
                }
                document.getElementById("verTipocambio").value = midata[0].tasacambio;
                var deshabilitar = false;
                if (midata[0].pagada == "NO") {
                    deshabilitar = false
                    var btnventa = document.getElementById('pagarfactura')
                    if (btnventa) {
                        btnventa.style.display = 'inline';
                    }
                } else if (midata[0].pagada == "SI") {
                    deshabilitar = true
                    var btnventa = document.getElementById('pagarfactura')
                    if (btnventa) {
                        btnventa.style.display = 'none';
                    }
                }
                document.getElementById("0nrooc").readOnly = deshabilitar;
                document.getElementById("0guiaremision").readOnly = deshabilitar;
                document.getElementById("0fechapago").readOnly = deshabilitar;
                document.getElementById("0constanciaretencion").readOnly = deshabilitar;
                document.getElementById("0acuenta1").readOnly = deshabilitar;
                document.getElementById("0acuenta2").readOnly = deshabilitar;
                document.getElementById("0acuenta3").readOnly = deshabilitar;
                document.getElementById("0saldo").readOnly = deshabilitar;
                document.getElementById("0retencion").readOnly = deshabilitar;
                document.getElementById("0montopagado").readOnly = deshabilitar;
                document.getElementById("0precioventaconigv").readOnly = deshabilitar;
                if (midata[0].observacion == null) {
                    document.getElementById('divobservacion').style.display = 'none';
                } else {
                    document.getElementById('divobservacion').style.display = 'inline';
                    document.getElementById("verObservacion").value = midata[0].observacion;
                }

                var monedafactura = midata[0].moneda;
                var simbolomonedaproducto = "";
                var simbolomonedafactura = "";

                //comprobar la iteracion del midata porque se pasa un numero y genera error
                if (monedafactura == "dolares") {
                    simbolomonedafactura = "$";
                } else if (monedafactura == "soles") {
                    simbolomonedafactura = "S/.";
                }

                var tabla = document.getElementById(detallesventa);
                for (var ite = 0; ite < midata.length; ite++) {
                    var monedaproducto = midata[ite].monedaproducto;
                    if (monedaproducto == "dolares") {
                        simbolomonedaproducto = "$";
                    } else if (monedaproducto == "soles") {
                        simbolomonedaproducto = "S/.";
                    }
                    var observacion = "";
                    if (midata[ite].observacionproducto) {
                        observacion = midata[ite].observacionproducto;
                    }
                    if (midata[ite].tipo == 'kit') {
                        var urlventa = "{{ url('admin/venta/productosxdetallexkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + midata[ite].iddetalleventa,
                            async: false,
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;
                                }
                                filaDetalle = '<tr id="fila' + ite +
                                    '"><td> <b>' + midata[ite].producto + '</b>' + puntos +
                                    milista + coma + '</td><td style="text-align: center;"> ' +
                                    midata[ite].unidad + '</td><td> ' + observacion +
                                    '</td><td style="text-align: center;"> ' +
                                    midata[ite].cantidad +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedaproducto + midata[ite].preciounitario +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedafactura + midata[ite].preciounitariomo +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedafactura + midata[ite].servicio +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedafactura + midata[ite].preciofinal +
                                    '</td></tr>';
                                $("#detallesventa>tbody").append(filaDetalle);
                                milista = '<br>';
                            }
                        });
                    } else //if (midata[ite].tipo == 'estandar') 
                    {
                        filaDetalle = '<tr id="fila' + ite +
                            '"><td> <b>' + midata[ite].producto + '</b>' +
                            '</td><td style="text-align: center;"> ' + midata[ite].unidad +
                            '</td><td> ' + observacion +
                            '</td><td style="text-align: center;"> ' + midata[ite].cantidad +
                            '</td><td style="text-align: center;"> ' + simbolomonedaproducto + midata[ite]
                            .preciounitario +
                            '</td><td style="text-align: center;"> ' + simbolomonedafactura + midata[ite]
                            .preciounitariomo +
                            '</td><td style="text-align: center;"> ' + simbolomonedafactura + midata[ite]
                            .servicio +
                            '</td><td style="text-align: center;"> ' + simbolomonedafactura + midata[ite]
                            .preciofinal +
                            '</td></tr>';
                        $("#detallesventa>tbody").append(filaDetalle);
                    } 
                    //termina el for    
                }
            });
        })
        //mostrar el modal de las ventas por vencer------------------------------------------------------------
        const mimodalVercreditosxvencer = document.getElementById('modalCreditos1')
        mimodalVercreditosxvencer.addEventListener('show.bs.modal', event => {
            var nroxvencer = 0;
            var nrovencidos = 0;
            var hoy = new Date();
            var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');

            const button = event.relatedTarget;
            //  const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/venta/showcreditos') }}";
            $.get(urlventa, function(data) {
                if (inicializartabla > 0) {
                    $("#mitabla1").dataTable().fnDestroy(); //eliminar el objeto datatables de la tabla  
                }
                const modalTitle = mimodalVercreditosxvencer.querySelector('.modal-title');
                modalTitle.textContent = `Ventas a credito por vencer `;
                var simbolomonedafact = "";
                $('#mitabla1 tbody tr').slice().remove();
                var miurl = "{{ url('/admin/venta/') }}";
                for (var i = 0; i < data.length; i++) {
                    var monedafactura = data[i].moneda;
                    if (monedafactura == "dolares") {
                        simbolomonedafact = "$";
                    } else if (monedafactura == "soles") {
                        simbolomonedafact = "S/.";
                    }
                    var colorfondo = '<tr id="fila' + i + '">';
                    if (data[i].fechav < fechaActual) {
                        colorfondo = '<tr style="background-color:  #f89f9f" id="fila' + i + '">';
                        nrovencidos++;
                    } else {
                        nroxvencer++;
                    }
                    filaDetalle = colorfondo +
                        '<td><input  type="hidden"   value="' + data[i].id + '"required>' + data[i].id +
                        '</td><td><input  type="hidden"  value="' + data[i].fecha + '"required>' + data[i]
                        .fecha +
                        '</td><td><input  type="hidden"  value="' + data[i].fechav + '"required>' + data[i]
                        .fechav +
                        '</td><td><input  type="hidden"  value="' + data[i].nombrecliente + '"required>' +
                        data[i].nombrecliente +
                        '</td><td><input  type="hidden"  value="' + data[i].nombreempresa + '"required>' +
                        data[i].nombreempresa +
                        '</td><td><input  type="hidden"  value="' + data[i].moneda + '"required>' + data[i]
                        .moneda +
                        '</td><td><input  type="hidden"  value="' + data[i].formapago + '"required>' + data[
                            i].formapago +
                        '</td><td><input  type="hidden"  value="' + data[i].costoventa + '"required>' +
                        simbolomonedafact + data[i].costoventa +
                        '</td><td>@can('editar-venta')<a  href="' + miurl + '/' + data[i]
                        .id +
                        '/edit" class="btn btn-success">Editar</a> @endcan' +
                        '<button type="button" class="btn btn-secondary" data-id="' + data[i].id +
                        '" data-bs-target="#modalVer2" data-bs-toggle="modal">Ver</button>' +
                        ' @can('eliminar-venta')<button type="button" class="btn btn-danger btnborrar" data-idregistro="' +
                        data[i].id +
                        '">Eliminar</button>@endcan ' +
                        '</td></tr>';
                    $("#mitabla1>tbody").append(filaDetalle);
                }
                inicializartabla1(inicializartabla);
                inicializartabla++;
                mostrarmensajemodal(nroxvencer, nrovencidos);
            });

        });

        //mostrar el modal de los datos de los creditos
        const mimodalcreditos = document.getElementById('modalVer2')
        mimodalcreditos.addEventListener('show.bs.modal', event => {
            //destroytabla();
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/venta/show') }}";
            $.get(urlventa + '/' + id, function(midata) {
                const modalTitle = mimodalcreditos.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`;
                idventa = id;
                formapago = midata[0].formapago;
                document.getElementById("verFecha1").value = midata[0].fecha;
                document.getElementById("verFactura1").value = midata[0].factura;
                document.getElementById("verMoneda1").value = midata[0].moneda;
                document.getElementById("verFormapago1").value = midata[0].formapago;
                document.getElementById("verEmpresa1").value = midata[0].company;
                document.getElementById("verCliente1").value = midata[0].cliente
                document.getElementById("verPagada1").value = midata[0].pagada;
                document.getElementById("verPrecioventa1").value = midata[0].costoventa;

                document.getElementById("1nrooc").value = midata[0].nrooc;
                document.getElementById("1guiaremision").value = midata[0].guiaremision;
                document.getElementById("1fechapago").value = midata[0].fechapago;
                document.getElementById("1constanciaretencion").value = midata[0].constanciaretencion;
                document.getElementById("1acuenta1").value = midata[0].acuenta1;
                document.getElementById("1acuenta2").value = midata[0].acuenta2;
                document.getElementById("1acuenta3").value = midata[0].acuenta3;
                document.getElementById("1saldo").value = midata[0].saldo;
                document.getElementById("1retencion").value = midata[0].retencion;
                document.getElementById("1montopagado").value = midata[0].montopagado;
                document.getElementById("1precioventaconigv").value = parseFloat((midata[0].costoventa *
                    0.18).toFixed(4)) + parseFloat((midata[0].costoventa).toFixed(4));

                if (midata[0].moneda == "dolares") {
                    document.getElementById('spancostoventa1').innerHTML = "$";
                    document.getElementById('1spanacuenta1').innerHTML = "$";
                    document.getElementById('1spanacuenta2').innerHTML = "$";
                    document.getElementById('1spanacuenta3').innerHTML = "$";
                    document.getElementById('1spansaldo').innerHTML = "$";
                    document.getElementById('1spanretencion').innerHTML = "S/.";
                    document.getElementById('1spanmontopagado').innerHTML = "$";
                    document.getElementById('1spanprecioventaconigv').innerHTML = "$";
                } else if (midata[0].moneda == "soles") {
                    document.getElementById('spancostoventa1').innerHTML = "S/.";
                    document.getElementById('1spanacuenta1').innerHTML = "S/.";
                    document.getElementById('1spanacuenta2').innerHTML = "S/.";
                    document.getElementById('1spanacuenta3').innerHTML = "S/.";
                    document.getElementById('1spansaldo').innerHTML = "S/.";
                    document.getElementById('1spanretencion').innerHTML = "S/.";
                    document.getElementById('1spanmontopagado').innerHTML = "S/.";
                    document.getElementById('1spanprecioventaconigv').innerHTML = "S/.";
                }

                if (midata[0].fechav == null) {
                    document.getElementById('divfechav1').style.display = 'none';
                } else {
                    document.getElementById('divfechav1').style.display = 'inline';
                    document.getElementById("verFechav1").value = midata[0].fechav;
                }
                document.getElementById("verTipocambio1").value = midata[0].tasacambio;
                var deshabilitar = false;
                if (midata[0].pagada == "NO") {
                    deshabilitar = false;
                    var pagarfactura = document.getElementById('pagarfactura1');
                    if (pagarfactura) {
                        pagarfactura.style.display = 'inline';
                    }
                } else if (midata[0].pagada == "SI") {
                    deshabilitar = true;
                    var pagarfactura = document.getElementById('pagarfactura1');
                    if (pagarfactura) {
                        pagarfactura.style.display = 'none';
                    }
                }
                document.getElementById("1nrooc").readOnly = deshabilitar;
                document.getElementById("1guiaremision").readOnly = deshabilitar;
                document.getElementById("1fechapago").readOnly = deshabilitar;
                document.getElementById("1constanciaretencion").readOnly = deshabilitar;
                document.getElementById("1acuenta1").readOnly = deshabilitar;
                document.getElementById("1acuenta2").readOnly = deshabilitar;
                document.getElementById("1acuenta3").readOnly = deshabilitar;
                document.getElementById("1saldo").readOnly = deshabilitar;
                document.getElementById("1retencion").readOnly = deshabilitar;
                document.getElementById("1montopagado").readOnly = deshabilitar;
                document.getElementById("1precioventaconigv").readOnly = deshabilitar;

                if (midata[0].observacion == null) {
                    document.getElementById('divobservacion1').style.display = 'none';
                } else {
                    document.getElementById('divobservacion1').style.display = 'inline';
                    document.getElementById("verObservacion1").value = midata[0].observacion;
                }

                var monedafactura = midata[0].moneda;
                var simbolomonedaproducto = "";
                var simbolomonedafactura = "";

                if (monedafactura == "dolares") {
                    simbolomonedafactura = "$";
                } else if (monedafactura == "soles") {
                    simbolomonedafactura = "S/.";
                }

                var tabla = document.getElementById(detallesventa);
                $('#detallesventa1 tbody tr').slice().remove();
                for (var ite = 0; ite < midata.length; ite++) {
                    var monedaproducto = midata[ite].monedaproducto;
                    if (monedaproducto == "dolares") {
                        simbolomonedaproducto = "$";
                    } else if (monedaproducto == "soles") {
                        simbolomonedaproducto = "S/.";
                    }
                    var observacion = "";
                    if (midata[ite].observacionproducto) {
                        observacion = midata[ite].observacionproducto;
                    }
                    if (midata[ite].tipo == 'kit') {
                        var urlventa = "{{ url('admin/venta/productosxdetallexkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + midata[ite].iddetalleventa,
                            async: false,
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;
                                }
                                filaDetalle = '<tr id="fila' + ite +
                                    '"><td> <b>' + midata[ite]
                                    .producto + '</b>' + puntos +
                                    milista + coma +
                                    '</td><td> ' + observacion +
                                    '</td><td style="text-align: center;"> ' + midata[ite]
                                    .cantidad +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedaproducto + midata[ite]
                                    .preciounitario +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedafactura + midata[ite]
                                    .preciounitariomo +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedafactura + midata[ite].servicio +
                                    '</td><td style="text-align: center;"> ' +
                                    simbolomonedafactura + midata[ite].preciofinal +
                                    '</td></tr>';
                                $("#detallesventa1>tbody").append(filaDetalle);
                                milista = '<br>';
                            }
                        });
                    } else //if (midata[ite].tipo == 'estandar') 
                    {
                        filaDetalle = '<tr id="fila' + ite +
                            '"><td> <b>' + midata[ite].producto + '</b>' +
                            '</td><td> ' + observacion +
                            '</td><td style="text-align: center;"> ' + midata[ite].cantidad +
                            '</td><td style="text-align: center;"> ' + simbolomonedaproducto + midata[ite]
                            .preciounitario +
                            '</td><td style="text-align: center;"> ' + simbolomonedafactura + midata[ite]
                            .preciounitariomo +
                            '</td><td style="text-align: center;"> ' + simbolomonedafactura + midata[ite]
                            .servicio +
                            '</td><td style="text-align: center;"> ' + simbolomonedafactura + midata[ite]
                            .preciofinal +
                            '</td></tr>';
                        $("#detallesventa1>tbody").append(filaDetalle);
                    }
                }

            });

        });
        // fin de los modales 

        $('#pagarfactura').click(function() {
            var pagada = document.getElementById("verPagada").value;
            var nrooc = document.getElementById("0nrooc").value;
            var guiaremision = document.getElementById("0guiaremision").value;
            var fechapago = document.getElementById("0fechapago").value;
            var constanciaretencion = document.getElementById("0constanciaretencion").value;
            var acuenta1 = document.getElementById("0acuenta1").value;
            var acuenta2 = document.getElementById("0acuenta2").value;
            var acuenta3 = document.getElementById("0acuenta3").value;
            var saldo = document.getElementById("0saldo").value;
            var retencion = document.getElementById("0retencion").value;
            var montopagado = document.getElementById("0montopagado").value;
            pagarfactura(pagada, nrooc, guiaremision, fechapago, constanciaretencion, acuenta1,
                acuenta2, acuenta3, saldo, retencion, montopagado);
        });
        $('#pagarfactura1').click(function() {
            var pagada = document.getElementById("verPagada1").value;
            var nrooc = document.getElementById("1nrooc").value;
            var guiaremision = document.getElementById("1guiaremision").value;
            var fechapago = document.getElementById("1fechapago").value;
            var constanciaretencion = document.getElementById("1constanciaretencion").value;
            var acuenta1 = document.getElementById("1acuenta1").value;
            var acuenta2 = document.getElementById("1acuenta2").value;
            var acuenta3 = document.getElementById("1acuenta3").value;
            var saldo = document.getElementById("1saldo").value;
            var retencion = document.getElementById("1retencion").value;
            var montopagado = document.getElementById("1montopagado").value;
            pagarfactura(pagada, nrooc, guiaremision, fechapago, constanciaretencion, acuenta1,
                acuenta2, acuenta3, saldo, retencion, montopagado);
        });

        $('#generarfactura').click(function() {
            generarfactura(idventa);
        });
        $('#generarfactura1').click(function() {
            generarfactura(idventa);
        });

        function generarfactura($id) {
            if ($id != -1) {
                window.open('/admin/venta/generarfacturapdf/' + $id);
            }
        }

        function pagarfactura(pagada, nrooc, guiaremision, fechapago, constanciaretencion, acuenta1,
            acuenta2, acuenta3, saldo, retencion, montopagado) {
            var urlventa = "{{ url('/admin/venta/guardardatospago') }}";
            Swal.fire({
                title: '¿Esta seguro que desea guardar los datos de pago?',
                //text: "No lo podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Guardar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        async: true,
                        data: {
                            pagada: pagada,
                            nrooc: nrooc,
                            guiaremision: guiaremision,
                            fechapago: fechapago,
                            constanciaretencion: constanciaretencion,
                            acuenta1: acuenta1,
                            acuenta2: acuenta2,
                            acuenta3: acuenta3,
                            saldo: saldo,
                            retencion: retencion,
                            montopagado: montopagado,
                            idventa: idventa,
                        },
                        url: urlventa,
                        success: function(data) {
                            $('#mimodal').modal('hide');
                            $('#modalVer2').modal('hide');
                            if (data[0] == 1) {
                                recargartabla();
                                if (pagada == "SI" && formapago == "credito") {
                                    numerocreditos--;
                                    mostrarmensaje(numerocreditos);
                                }
                                Swal.fire({
                                    text: "Datos de pago guardados",
                                    icon: "success"
                                });
                            } else if (data[0] == 0) {
                                Swal.fire({
                                    text: "No se puede guardar datos",
                                    icon: "error"
                                });
                            } else if (data[0] == 2) {
                                Swal.fire({
                                    text: "Venta no encontrada",
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        }

        function mostrarmensaje(numCred) {
            var registro = "REGISTRO DE VENTAS: ";
            var tienes = "Tienes ";
            var pago = " Creditos por Cobrar ";
            var boton =
                '<button class="btn btn-info" data-bs-target="#modalCreditos1" data-bs-toggle="modal">  Ver</button>';
            if (numCred > 0) {
                document.getElementById('mititulo').innerHTML = registro + tienes + numCred + pago + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }

        }

        function mostrarmensajemodal(nroxvencer, nrovencidos) {
            var xvencer = " Creditos por Vencer";
            var vencidos = " Creditos Vencidos";
            var y = " y ";
            var tienes = "TIENES: ";
            if (nroxvencer > 0 && nrovencidos > 0) {
                document.getElementById('modalCreditos1Label1').innerHTML = tienes + nroxvencer + xvencer + y +
                    nrovencidos + vencidos;
            } else if (nroxvencer > 0 && nrovencidos == 0) {
                document.getElementById('modalCreditos1Label1').innerHTML = tienes + nroxvencer + xvencer;
            } else if (nroxvencer == 0 && nrovencidos > 0) {
                document.getElementById('modalCreditos1Label1').innerHTML = tienes + nrovencidos + vencidos;
            }

        }

        function mostrarmensajesinfactura(nrofacturas) {
            var tienes = "Tienes ";
            var pago = " Compras sin numero de factura.";

            if (nrofacturas > 0) {
                document.getElementById('sinnumerofactura').innerHTML = tienes + nrofacturas + pago;
            } else {
                document.getElementById('sinnumerofactura').innerHTML = "";
            }
        }

        //para los pagos
        document.getElementById("0acuenta1").onchange = function() {
            pagocredito();
        };
        document.getElementById("0acuenta2").onchange = function() {
            pagocredito();
        };
        document.getElementById("0acuenta3").onchange = function() {
            pagocredito();
        };
        document.getElementById("0saldo").onchange = function() {
            var saldo = document.getElementById('0saldo').value;
            if (parseFloat(saldo) < 0.2) {
                document.getElementById('verPagada').value = "SI";
            } else {
                document.getElementById('verPagada').value = "NO";
            }
        };

        function pagocredito() {
            var acuenta1 = $('[name="0acuenta1"]').val();
            var acuenta2 = $('[name="0acuenta2"]').val();
            var acuenta3 = $('[name="0acuenta3"]').val();
            var precioventaconigv = $('[name="0precioventaconigv"]').val();
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

            document.getElementById('0saldo').value = parseFloat(saldo.toFixed(4));
            document.getElementById('0montopagado').value = parseFloat(montopagado.toFixed(4));
            if (parseFloat(saldo) < 0.2) {
                document.getElementById('verPagada').value = "SI";
            } else {
                document.getElementById('verPagada').value = "NO";
            }
        }

        //para los pagos
        document.getElementById("1acuenta1").onchange = function() {
            pagocredito1();
        };
        document.getElementById("1acuenta2").onchange = function() {
            pagocredito1();
        };
        document.getElementById("1acuenta3").onchange = function() {
            pagocredito1();
        };
        document.getElementById("1saldo").onchange = function() {
            var saldo = document.getElementById('1saldo').value;
            if (parseFloat(saldo) < 0.2) {
                document.getElementById('verPagada1').value = "SI";
            } else {
                document.getElementById('verPagada1').value = "NO";
            }
        };

        function pagocredito1() {
            var acuenta1 = $('[name="1acuenta1"]').val();
            var acuenta2 = $('[name="1acuenta2"]').val();
            var acuenta3 = $('[name="1acuenta3"]').val();
            var precioventaconigv = $('[name="1precioventaconigv"]').val();
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

            document.getElementById('1saldo').value = parseFloat(saldo.toFixed(4));
            document.getElementById('1montopagado').value = parseFloat(montopagado.toFixed(4));
            if (parseFloat(saldo) < 0.2) {
                document.getElementById('verPagada1').value = "SI";
            } else {
                document.getElementById('verPagada1').value = "NO";
            }
        }
    </script>
@endpush
