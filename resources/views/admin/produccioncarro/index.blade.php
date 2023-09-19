@extends('layouts.admin')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>PRODUCCION DE CARROS
                            <br>
                            @can('crear-produccion-carro')
                                <a href="{{ url('admin/produccioncarro/create') }}" class="btn btn-primary float-end">Añadir
                                    Produccion de carros</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mitabla" name="mitabla">
                                <thead>
                                    <tr class="fw-bold text-primary ">
                                        <th>ID</th>
                                        <th>CANTIDAD</th>
                                        <th>NOMBRE</th>
                                        <th>CARROCERIA</th>
                                        <th>MODELO</th>
                                        <th>TODO ENVIADO</th>
                                        <th>FACTURADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Produccion de Carros</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6   mb-3">
                                                <label for="verNombre" class="col-form-label">NOMBRE:</label>
                                                <input type="text" class="form-control " id="verNombre" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verCantidad" class="col-form-label">NUMERO DE CARROS:</label>
                                                <input type="text" class="form-control " id="verCantidad" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verDescuento" class="col-form-label">% DESCUENTO:</label>
                                                <input type="text" class="form-control " id="verDescuento" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verCarroceria" class="col-form-label">CARROCERIA:</label>
                                                <input type="text" class="form-control " id="verCarroceria" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verModelo" class="col-form-label">MODELO:</label>
                                                <input type="text" class="form-control " id="verModelo" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verTodoenviado" class="col-form-label">TODO ENVIADO:</label>
                                                <input type="text" class="form-control " id="verTodoenviado" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verFacturado" class="col-form-label">FACTURADO:</label>
                                                <input type="text" class="form-control " id="verFacturado" readonly>
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
                                                        aria-selected="false">Materiales</button>
                                                    <button class="nav-link" id="nav-redes-tab" data-bs-toggle="tab"
                                                        data-bs-target="#nav-redes" type="button" role="tab"
                                                        aria-controls="nav-redes" aria-selected="false">Redes</button>
                                                    <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                                        data-bs-target="#nav-condiciones" type="button" role="tab"
                                                        aria-controls="nav-condiciones"
                                                        aria-selected="false">Carros</button>
                                                </div>
                                            </nav>
                                            <div class="tab-content" id="nav-tabContent">
                                                <br>
                                                <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                                    aria-labelledby="nav-detalles-tab" tabindex="0">
                                                    <div class="row">
                                                        <div class="table-container">
                                                            <table style="width: 100%; border-collapse: collapse;">
                                                                <thead class="fw-bold text-primary">
                                                                    <tr style="text-align: center;">
                                                                        <th style="width: 9%;">ENVIADO</th>
                                                                        <th
                                                                            style="width: 8%; border-left:1px solid silver;">
                                                                            CANTIDAD</th>
                                                                        <th
                                                                            style="width: 44%; border-left:1px solid silver;">
                                                                            PRODUCTO</th>
                                                                        <th
                                                                            style="width: 20%; border-left:1px solid silver;">
                                                                            EMPRESA</th>
                                                                        <th
                                                                            style="width: 20%; border-left:1px solid silver;">
                                                                            OBSERVACION</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <div class="accordion" id="accordion1">
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header"
                                                                                        id="headingOne">
                                                                                        <button class="accordion-button"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#collapseOne">
                                                                                            Material Electrico
                                                                                        </button>
                                                                                    </h2>
                                                                                    <div id="collapseOne"
                                                                                        class="accordion-collapse collapse show">
                                                                                        <div class="accordion-body">
                                                                                            <div class="table-container">
                                                                                                <table
                                                                                                    class="table-row-bordered gy-5 gs-5"
                                                                                                    id="detallesmaterial"
                                                                                                    style="width: 100%;">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <div class="accordion" id="accordion2">
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header"
                                                                                        id="headingTwo">
                                                                                        <button class="accordion-button"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#collapseTwo">
                                                                                            Material Adicional
                                                                                        </button>
                                                                                    </h2>
                                                                                    <div id="collapseTwo"
                                                                                        class="accordion-collapse collapse show">
                                                                                        <div class="accordion-body">
                                                                                            <div class="table-container">
                                                                                                <table
                                                                                                    class="table-row-bordered gy-5 gs-5"
                                                                                                    id="detallesmaterialadicional"
                                                                                                    style="width: 100%;">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="nav-redes" role="tabpanel"
                                                    aria-labelledby="nav-redes-tab" tabindex="0">
                                                    <div class="row">
                                                        <div class="table-container">

                                                            <table style="width: 100%;">
                                                                <thead class="fw-bold text-primary">
                                                                    <tr style="text-align: center;">
                                                                        <th style="width: 9%;">ENVIADO</th>
                                                                        <th
                                                                            style="width: 8%; border-left:1px solid silver;">
                                                                            CANTIDAD</th>
                                                                        <th
                                                                            style="width: 44%; border-left:1px solid silver;">
                                                                            PRODUCTO</th>
                                                                        <th
                                                                            style="width: 20%; border-left:1px solid silver;">
                                                                            EMPRESA</th>
                                                                        <th
                                                                            style="width: 20%; border-left:1px solid silver;">
                                                                            OBSERVACION</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <div class="accordion" id="accordionRed1">
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header"
                                                                                        id="headingRedOne">
                                                                                        <button class="accordion-button"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#collapseRedOne">
                                                                                            Redes Estandar
                                                                                        </button>
                                                                                    </h2>
                                                                                    <div id="collapseRedOne"
                                                                                        class="accordion-collapse collapse show">
                                                                                        <div class="accordion-body">
                                                                                            <div class="table-container">
                                                                                                <table
                                                                                                    class="table-row-bordered gy-5 gs-5"
                                                                                                    id="detallesredes"
                                                                                                    style="width: 100%;">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="5">
                                                                            <div class="accordion" id="accordionRed2">
                                                                                <div class="accordion-item">
                                                                                    <h2 class="accordion-header"
                                                                                        id="headingRedTwo">
                                                                                        <button class="accordion-button"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#collapseRedTwo">
                                                                                            Redes Adicionales
                                                                                        </button>
                                                                                    </h2>
                                                                                    <div id="collapseRedTwo"
                                                                                        class="accordion-collapse collapse show">
                                                                                        <div class="accordion-body">
                                                                                            <table
                                                                                                class="table-row-bordered gy-5 gs-5"
                                                                                                id="detallesredesadicional"
                                                                                                style="width: 100%;">
                                                                                                <tbody>
                                                                                                    <tr>
                                                                                                    </tr>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade  " id="nav-condiciones" role="tabpanel"
                                                    aria-labelledby="nav-condiciones-tab" tabindex="0">
                                                    <div class="row">
                                                        <div class="table-responsive">
                                                            <table class=" table-row-bordered gy-5 gs-5" id="tablacarros"
                                                                style="width: 2050px;">
                                                                <thead class="fw-bold text-primary">
                                                                    <tr style="text-align: center;">
                                                                        <th style="width: 110px;">NRO DE OP</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 110px;">
                                                                            NRO CHASIS</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 100px;">
                                                                            % DESCUENTO</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 200px;">
                                                                            ORDEN COMPRA</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 140px;">
                                                                            RED ENVIADA</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 140px;">
                                                                            MATERIAL ELECTRICO ENV</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 140px;">
                                                                            MATERIAL ADICIONAL ENV</th>
                                                                        <th style="border-left: solid 1px silver; width: 260px;"
                                                                            colspan="2"> FACTURA ELECTROBUS <br> <br>
                                                                            <h6 style="border-top: 1px solid silver;">
                                                                                FECHA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <span
                                                                                    style="border-left: 1px solid silver;"></span>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO
                                                                            </h6>
                                                                        </th>
                                                                        <th style="border-left: solid 1px silver; width: 260px;"
                                                                            colspan="2"> FACTURA DELMY <br> <br>
                                                                            <h6 style="border-top: 1px solid silver;">
                                                                                FECHA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                <span
                                                                                    style="border-left: 1px solid silver;"></span>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO
                                                                            </h6>
                                                                        </th>
                                                                        <th style="border-left: solid 1px silver; width: 390px;"
                                                                            colspan="3"> FACTURA ADICIONAL <br> <br>
                                                                            <h6 style="border-top: 1px solid silver;">
                                                                                FECHA&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                                                                                <span style="border-left: 1px solid silver;"></span>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp; NUMERO
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="border-left: 1px solid silver;"></span>
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EMPRESA
                                                                            </h6>
                                                                        </th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 100px;">
                                                                            BONO</th>
                                                                        <th
                                                                            style="border-left: solid 1px silver; width: 100px;">
                                                                            MES DE BONO</th>
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="materialAdicionalModal" tabindex="-1"
                        aria-labelledby="materialAdicionalModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="materialAdicionalModalLabel">
                                        Material Adicional Enviado para OP Nro: </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table style="width: 100%;" id="TenviarMA">
                                        <thead class="fw-bold text-primary" style="width: 100%; text-align:center;">
                                            <tr style="width: 100%;">
                                                <th style="width: 4%; ">
                                                </th>
                                                <th style="border-left: solid 1px silver; width:8%; ">Cant</th>
                                                <th style="border-left: solid 1px silver; width:68%; ">Material Adicional
                                                </th>
                                                <th style="border-left: solid 1px silver; width:20%;">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="redesModal" tabindex="-1" aria-labelledby="redesModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="redesModalLabel">
                                        Redes Enviadas para OP nro: </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table style="width: 100%;" id="TenviarRed">
                                        <thead class="fw-bold text-primary" style="width: 100%; text-align:center;">
                                            <tr style="width: 100%;">
                                                <th style="width: 4%; ">
                                                </th>
                                                <th style="border-left: solid 1px silver; width:8%; ">Cant</th>
                                                <th style="border-left: solid 1px silver; width:68%; ">Red
                                                </th>
                                                <th style="border-left: solid 1px silver; width:20%;">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    {{-- <button type="button" onclick="btnactualizarenvioRed();" class="btn btn-success"
                                        id="btnActualizarEnvioRed">Enviar</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        var idproduccion = 0;
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('produccioncarros.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'cantidad',
                    name: 'cantidad'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'carroceria',
                    name: 'c.tipocarroceria'
                },
                {
                    data: 'modelo',
                    name: 'mc.modelo'
                },
                {
                    data: 'todoenviado',
                    name: 'todoenviado'
                },
                {
                    data: 'facturado',
                    name: 'facturado'
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
        });

        //para borrar un registro de la tabla
        $(document).on('click', '.btnborrar', function(event) {
            const idregistro = event.target.dataset.idregistro;
            var urlregistro = "{{ url('admin/produccioncarro') }}";
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

        var svgme =
            '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"> <path  d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7H272.5L349.4 44.6z" />  </svg>';
        var svgma =
            '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"> <path d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" /></svg>';
        var svgred =
            '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"> <path d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48V96H384V80c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H432c-26.5 0-48-21.5-48-48V160H192v16c0 1.7-.1 3.4-.3 5L272 288h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H272c-26.5 0-48-21.5-48-48V336c0-1.7 .1-3.4 .3-5L144 224H48c-26.5 0-48-21.5-48-48V80z" /> </svg>';

        //para mostrar el modal de ver un registro

        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            $('#detallesmaterial tbody tr').slice().remove();
            $('#detallesmaterialadicional tbody tr').slice().remove();
            $('#tablacarros tbody tr').slice().remove();
            var urlventa = "{{ url('admin/produccioncarro/show') }}";
            $.get(urlventa + '/' + id, function(midata) {
                var titulo = document.getElementById("mimodalLabel"); 
                titulo.textContent = `Ver Produccion de Carros Nro: ` + midata[0].idproduccioncarro;
                idproduccion = id;
                document.getElementById("verNombre").value = midata[0].nombre;
                document.getElementById("verCantidad").value = midata[0].numerocarros;
                document.getElementById("verDescuento").value = midata[0].descuento;
                document.getElementById("verCarroceria").value = midata[0].carroceria;
                document.getElementById("verModelo").value = midata[0].modelo
                document.getElementById("verTodoenviado").value = midata[0].todoenviado;
                document.getElementById("verFacturado").value = midata[0].facturado;

            });

            var urlmat = "{{ url('admin/produccioncarro/showmateriales') }}";
            $.get(urlmat + '/' + id, function(datamat) {
                for (var ite = 0; ite < datamat.length; ite++) {
                    var fondo = "#ffffff";
                    if (ite % 2 == 0) {
                        fondo = "#E1E5F5";
                    }
                    var observacionmaterial = "";
                    if (datamat[ite].observacion) {
                        observacionmaterial = datamat[ite].observacion;
                    }
                    if (datamat[ite].tipo == 'kit') {
                        var urlventa = "{{ url('admin/produccioncarro/productosxkitxmaterial') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + datamat[ite].idmaterial,
                            async: false,
                            data: {
                                id: id
                            },
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' +
                                        data1[j].producto + coma;
                                }
                                filaDetalle = '<tr style="background-color: ' + fondo +
                                    ';" id="filam' + ite +
                                    '"><td style="text-align: center; width:8%;"> ' +
                                    datamat[ite].cantidadenviada + ' / ' +
                                    parseInt(datamat[ite].cantidadproducto) *
                                    parseInt(datamat[ite].numerocarros) +
                                    '</td><td style="text-align: center; width:8%;"> ' +
                                    datamat[ite].cantidadproducto +
                                    '</td><td style="width:45%;"> <b>' + datamat[ite].producto +
                                    '</b>' +
                                    puntos + milista + coma +
                                    '</td><td style="text-align: center;"> ' +
                                    datamat[ite].empresa +
                                    '</td> <td style="width:17%;"> <textarea rows="3" disabled style="font-size:11px; width:100%;"> ' +
                                    observacionmaterial + ' </textarea></td> </tr>';
                                if (datamat[ite].tipomaterial == "E") {
                                    $("#detallesmaterial>tbody").append(filaDetalle);
                                } else if (datamat[ite].tipomaterial == "A") {
                                    $("#detallesmaterialadicional>tbody").append(filaDetalle);
                                }
                                milista = '<br>';
                            }
                        });
                    } else
                    if (datamat[ite].tipo == 'estandar') {

                        filaDetalle = '<tr style="background-color: ' + fondo + ';" id="fila' + ite +
                            '"><td style="text-align: center; width:8%;"> ' + datamat[ite].cantidadenviada +
                            ' / ' + parseInt(datamat[ite].cantidadproducto) *
                            parseInt(datamat[ite].numerocarros) +
                            '</td><td style="text-align: center; width:8%;"> ' +
                            datamat[ite].cantidadproducto + '</td><td style="width:45%;"> <b>' +
                            datamat[ite].producto + '</b>' +
                            '</td><td style="text-align: center;"> ' +
                            datamat[ite].empresa + '</td><td style="width:17%;">' +
                            '<textarea rows="3" disabled style="font-size:11px; width:100%;"> ' +
                            observacionmaterial + ' </textarea>  </td> </tr>';
                        if (datamat[ite].tipomaterial == "E") {
                            $("#detallesmaterial>tbody").append(filaDetalle);
                        } else if (datamat[ite].tipomaterial == "A") {
                            $("#detallesmaterialadicional>tbody").append(filaDetalle);
                        }
                    }
                }
            });

            var urlredes = "{{ url('admin/produccioncarro/showredes') }}";
            $('#detallesredes tbody tr').slice().remove();
            $('#detallesredesadicional tbody tr').slice().remove();
            $.get(urlredes + '/' + id, function(datared) {
                for (var ite = 0; ite < datared.length; ite++) {
                    var fondo = "#ffffff";
                    if (ite % 2 == 0) {
                        fondo = "#E1E5F5";
                    }
                    var observacionred = "";
                    if (datared[ite].observacion) {
                        observacionred = datared[ite].observacion;
                    }
                    if (datared[ite].tipo == 'kit') {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + datared[ite].idproducto,
                            async: false,
                            data: {
                                id: id
                            },
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;
                                }
                                filaDetalle = '<tr style="background-color: ' + fondo +
                                    ';" id="filam' + ite +
                                    '"><td style="text-align: center; width:8%;"> ' +
                                    datared[ite].cantidadenviada + ' / ' +
                                    parseInt(datared[ite].cantidadproducto) *
                                    parseInt(datared[ite].numerocarros) +
                                    '</td><td style="text-align: center; width:8%;"> ' +
                                    datared[ite].cantidadproducto +
                                    '</td><td  width:45%;> <b>' + datared[ite].producto +
                                    '</b>' +
                                    puntos + milista + coma +
                                    '</td><td style="text-align: center;"> ' +
                                    datared[ite].empresa +
                                    '</td><td <td style="width:17%;"> <textarea rows="2" disabled style="font-size:11px; width:100%;"> ' +
                                    observacionred + ' </textarea></td> </tr>';
                                if (datared[ite].tipored == "E") {
                                    $("#detallesredes>tbody").append(filaDetalle);
                                } else if (datared[ite].tipored == "A") {
                                    $("#detallesredesadicional>tbody").append(filaDetalle);
                                }
                                milista = '<br>';
                            }
                        });
                    } else
                    if (datared[ite].tipo == 'estandar') {
                        filaDetalle = '<tr style="background-color: ' + fondo + ';" id="fila' + ite +
                            '"><td style="text-align: center; width:8%;"> ' + datared[ite].cantidadenviada +
                            ' / ' + parseInt(datared[ite].cantidadproducto) *
                            parseInt(datared[ite].numerocarros) +
                            '</td><td style="text-align: center; width:8%;"> ' +
                            datared[ite].cantidadproducto + '</td><td style="width:45%;"> <b>' +
                            datared[ite].producto + '</b>' + '</td><td style="text-align: center;"> ' +
                            datared[ite].empresa + '</td><td style="width:17%;">' +
                            '<textarea rows="2" disabled style="font-size:11px; width:100%;"> ' +
                            observacionred + ' </textarea>  </td> </tr>';
                        if (datared[ite].tipored == "E") {
                            $("#detallesredes>tbody").append(filaDetalle);
                        } else if (datared[ite].tipored == "A") {
                            $("#detallesredesadicional>tbody").append(filaDetalle);
                        }
                    }
                }
            });
            var urlccarro = "{{ url('admin/produccioncarro/showcarros') }}";
            $.get(urlccarro + '/' + id, function(data) {
                $('#tablacarros tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    var fondo = "#ffffff";
                    if (i % 2 == 0) {
                        fondo = "#E1E5F5";
                    }
                    var datosenvio = "";
                    if (data[i].datosenvio) {
                        datosenvio = data[i].datosenvio;
                    }
                    var ordencompra = "";
                    if (data[i].ordencompra) {
                        ordencompra = data[i].ordencompra;
                    }
                    var fechaE = "";
                    var fechaD = "";
                    var numeroE = "";
                    var numeroD = "";
                    var fechaO = "";
                    var numeroO = "";
                    var empresaO = "";
                    var mesbonificacion = "";
                    if (data[i].fechaE) {
                        fechaE = data[i].fechaE;
                    }
                    if (data[i].fechaD) {
                        fechaD = data[i].fechaD;
                    }
                    if (data[i].fechaO) {
                        fechaO = data[i].fechaO;
                    }
                    if (data[i].numeroE) {
                        numeroE = data[i].numeroE;
                    }
                    if (data[i].numeroD) {
                        numeroD = data[i].numeroD;
                    }
                    if (data[i].numeroO) {
                        numeroO = data[i].numeroO;
                    }
                    if (data[i].empresaO) {
                        empresaO = data[i].empresaO;
                    }
                    if (data[i].mesbonificacion) {
                        mesbonificacion = data[i].mesbonificacion;
                    }
                    var btnMatExenviar = "";
                    if (data[i].materialelectricoenv) {
                        btnMatExenviar = '<div class="infobox" > <button type="button" id="btnenviarme' +
                            data[i].idcarro + '" class="btn btn-success" ' + ' disabled >  Enviado' +
                            svgme + ' </button> <span class="infoboxtext"' +
                            '> Fecha Envio: <br>' + data[i].materialelectricoenv + ' </span> </div>';
                    } else {
                        btnMatExenviar = '<div class="infobox" > <button type="button" id="btnenviarme' +
                            data[i].idcarro + '" class="btn btn-warning"' +
                            ' disabled >  No Enviado' + svgme + ' </button> <span class="infoboxtext"' +
                            '> Material Electrico No Enviado para: <br>' + data[i].nroordenp +
                            ' </span> </div>';
                    }
                    var btnMatAxenviar = "";
                    var nroordenp = "'" + data[i].nroordenp + "'";
                    if (data[i].materialadicionalenv) {
                        btnMatAxenviar = '<div class="infobox" > <button type="button" id="btnenviarMA' +
                            data[i].idcarro + '" class="btn btn-success" ' +
                            'onclick="enviarmaterialadicional(' + data[i].idcarro + ',' +
                            nroordenp + ');" >  Enviado' +
                            svgma + ' </button> <span class="infoboxtext"' +
                            '> Fecha Envio: <br>' + data[i].materialadicionalenv + ' </span> </div>';
                    } else {
                        btnMatAxenviar = '<div class="infobox" > <button type="button" id="btnenviarMA' +
                            data[i].idcarro + '" class="btn btn-warning"' +
                            ' onclick="enviarmaterialadicional(' + data[i].idcarro + ',' +
                            nroordenp + ');">  No Enviado' +
                            svgma + ' </button> <span class="infoboxtext"' +
                            ' > Material Adicional No Enviado para: <br>' + data[i].nroordenp +
                            ' </span> </div>';
                    }

                    var btnRedxenviar = "";
                    if (data[i].redenviada) {
                        btnRedxenviar = '<div class="infobox" > <button type="button" id="btnenviarred' +
                            data[i].idcarro + '" class="btn btn-success" ' +
                            'onclick="mostarenvioredcarros(' + data[i].idcarro + ',' +
                            nroordenp + ');" >  Enviado' + svgred + ' </button> <span class="infoboxtext"' +
                            '> Fecha Envio: <br>' + data[i].redenviada + ' </span> </div>';
                    } else {
                        btnRedxenviar = '<div class="infobox" > <button type="button" id="btnenviarred' +
                            data[i].idcarro + '" class="btn btn-warning"' +
                            ' onclick="mostarenvioredcarros(' + data[i].idcarro + ',' + nroordenp +
                            ');">  No Enviado' + svgred + ' </button> <span class="infoboxtext"' +
                            ' > Redes No Enviadas para: <br>' + nroordenp + ' </span> </div>';
                    }

                    filaDetalle = '<tr style="background-color: ' + fondo + ';" id="filacarro' + i +
                        '"><td style="text-align: center; width: 110px;">' + data[i].nroordenp +
                        '</td><td style="text-align: center; width: 110px;">' + data[i].chasis +
                        '</td><td style="text-align: center; width: 100px;">' + data[i].porcentajedescuento +
                        '</td><td style="width: 200px;"><textarea rows="3" disabled style="font-size:11px; width:100%;">' +
                        ordencompra + '</textarea></td><td style="text-align: center; width: 140px;">' + btnRedxenviar +
                        '</td><td style="text-align: center; width: 140px;"> ' + btnMatExenviar +
                        '</td><td style="text-align: center; width: 140px;"> ' + btnMatAxenviar +
                        '</td><td style="text-align: center; width: 130px;" > ' + fechaE +
                        '</td><td style="text-align: center; width: 130px;"> ' + numeroE +
                        '</td><td style="text-align: center; width: 130px;"> ' + fechaD +
                        '</td><td style="text-align: center; width: 130px;"> ' + numeroD +
                        '</td><td style="text-align: center; width: 130px; "> ' + fechaO +
                        '</td><td style="text-align: center; width: 130px; "> ' + numeroO +
                        '</td><td style="text-align: center; width: 130px; "> ' + empresaO +
                        '</td><td style="text-align: center; width: 100px;"> ' + data[i].bonificacion +
                        '</td><td style="text-align: center; width: 100px;">' + mesbonificacion + 
                        '</td></tr>';
                    $("#tablacarros>tbody").append(filaDetalle);
                }
            });
        });

        function enviarmaterialadicional(idcarro, nroop) {
            var miModal = new bootstrap.Modal(document.getElementById('materialAdicionalModal'));
            document.getElementById('materialAdicionalModalLabel').innerHTML =
                "Material Adicional Enviado Para OP Nro: " + nroop;
            $('#TenviarMA tbody tr').slice().remove();
            var urlgetmaterialadicional = "{{ url('admin/produccioncarro/materialadicionalxproduccion') }}";
            $.ajax({
                type: "GET",
                url: urlgetmaterialadicional + '/' + idproduccion + '/' + idcarro,
                success: function(data1) {
                    materialadicional = data1;
                    cantidadMA = data1.length;
                    for (var i = 0; i < data1.length; i++) {
                        var fondo = "#ffffff";
                        if (i % 2 == 0) {
                            fondo = "#CFEFCA";
                        }
                        var marcado = "";
                        var fechaenvMA = "";
                        if (data1[i].fecha) {
                            marcado = "checked";
                            fechaenvMA = data1[i].fecha;
                        }
                        var lista = "<br>";
                        if (data1[i].tipo == "kit") {
                            var urlproductos = "{{ url('admin/produccioncarro/productosxkitxmaterial') }}";
                            $.ajax({
                                type: "GET",
                                url: urlproductos + '/' + data1[i].idmaterial,
                                async: false,
                                success: function(data) {
                                    for (var x = 0; x < data.length; x++) {
                                        lista = lista + '-' + data[x].cantidad + ' ' +
                                            data[x].producto + '<br>';
                                    }
                                }
                            });
                        }
                        filaDetalle = '<tr style="background-color: ' + fondo + ';" >' +
                            '<td> <input disabled type="checkbox" class="form-check-input" ' + marcado +
                            ' /> </td>' +
                            '<td  style="text-align: center;">' +
                            data1[i].cantidad + ' </td><td> <b>' +
                            data1[i].nombre + '</b>' + lista +
                            '</td><td> <input  style="width:100%; border:solid 1px silver;" type="text" value="' +
                            fechaenvMA + '" readonly /> </td> </tr>';
                        $("#TenviarMA>tbody").append(filaDetalle);
                    }
                }
            });
            miModal.show();
        }

        function mostarenvioredcarros(idcarro, nroop) {
            var miModalRed = new bootstrap.Modal(document.getElementById('redesModal'));

            document.getElementById('redesModalLabel').innerHTML =
                "Redes Enviadas Para OP Nro: " + nroop;
            $('#TenviarRed tbody tr').slice().remove();
            var urlredes = "{{ url('admin/produccioncarro/redesxproduccion') }}";
            $.ajax({
                type: "GET",
                url: urlredes + '/' + idproduccion + '/' + idcarro,
                success: function(data1) {

                    for (var i = 0; i < data1.length; i++) {
                        var fondo = "#ffffff";
                        if (i % 2 == 0) {
                            fondo = "#CFEFCA";
                        }
                        var marcado = "";
                        var fechaenvMA = "";
                        if (data1[i].fecha) {
                            marcado = "checked";
                            fechaenvMA = data1[i].fecha;
                        }
                        var lista = "<br>";
                        if (data1[i].tipo == "kit") {
                            var urlproductos = "{{ url('admin/venta/productosxkit') }}";
                            $.ajax({
                                type: "GET",
                                url: urlproductos + '/' + data1[i].idproducto,
                                async: false,
                                success: function(data) {
                                    for (var x = 0; x < data.length; x++) {
                                        lista = lista + '-' + data[x].cantidad + ' ' +
                                            data[x].producto + '<br>';
                                    }
                                }
                            });
                        }
                        filaDetalle = '<tr style="background-color: ' + fondo + ';" >' +
                            '<td> <input type="checkbox" class="form-check-input" ' + marcado +
                            ' disabled /> </td>' +
                            '<td  style="text-align: center;">' +
                            data1[i].cantidad + ' </td><td> <b>' +
                            data1[i].nombre + '</b>' + lista +
                            '</td><td> <input style="width:100%; border:solid 1px silver;" type="text" value="' +
                            fechaenvMA + '" readonly /> </td> </tr>';
                        $("#TenviarRed>tbody").append(filaDetalle);
                    }
                }
            });
            miModalRed.show();
        }
    </script>
@endpush
