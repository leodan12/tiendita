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
                        <h4>ORDENES DE COMPRA
                            @can('crear-orden-compra')
                                <a href="{{ url('admin/ordencompra/create') }}" class="btn btn-primary float-end">Añadir
                                    Orden de Compra</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-striped" id="mitabla" name="mitabla">
                                <thead>
                                    <tr class="fw-bold text-primary ">
                                        <th>ID</th>
                                        <th>NUMERO</th>
                                        <th>FECHA</th>
                                        <th>EMPRESA</th>
                                        <th>PROVEEDOR</th>
                                        <th>OBSERVACION </th>
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
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Orden de Compra</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class=" col-md-3   mb-3" id="divobservacion">
                                                <label for="verNumero" class="col-form-label">NUMERO:</label>
                                                <input type="text" class="form-control " id="verNumero" readonly>
                                            </div>
                                            <div class="col-md-3   mb-3">
                                                <label for="verFecha" class="col-form-label">FECHA:</label>
                                                <input type="text" class="form-control " id="verFecha" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verPersona" class="col-form-label">PERSONA:</label>
                                                <input type="text" class="form-control " id="verPersona" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                                <input type="text" class="form-control " id="verMoneda" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verEmpresa" class="col-form-label">EMPRESA:</label>
                                                <input type="text" class="form-control " id="verEmpresa" readonly>
                                            </div>
                                            <div class=" col-md-8   mb-3">
                                                <label for="verCliente" class="col-form-label">PROVEEDOR:</label>
                                                <input type="text" class="form-control " id="verCliente" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verFormapago" class="col-form-label">FORMA PAGO:</label>
                                                <input type="text" class="form-control " id="verFormapago" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" id="divdiascredito">
                                                <label for="verDiascredito" class="col-form-label">DIAS DE CREDITO:</label>
                                                <input type="text" class="form-control " id="verDiascredito" readonly>
                                            </div>
                                            <div class=" col-md-6   mb-3" id="divobservacion">
                                                <label for="verObservacion" class="col-form-label">OBSERVACION:</label>
                                                <input type="text" class="form-control " id="verObservacion" readonly>
                                            </div>
                                        </div>
                                    </form>

                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <div class="tab-content" id="nav-tabContent">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-striped" id="detallesventa">
                                                <thead class="fw-bold text-primary">
                                                    <tr>
                                                        <th>Cantidad</th>
                                                        <th>Unidad</th>
                                                        <th>Precio compra</th>
                                                        <th>Producto</th>
                                                        <th>Observación</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" id="generarcotizacion"> Generar Pdf de
                                        la Cotización </button>
                                    <div id="btnvender" name="btnvender">
                                    </div>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
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
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('ordencompras.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'numero',
                    name: 'numero'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },
                {
                    data: 'empresa',
                    name: 'e.nombre'
                },
                {
                    data: 'cliente',
                    name: 'cl.nombre'
                },
                {
                    data: 'observacion',
                    name: 'observacion'
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
            var urlregistro = "{{ url('admin/ordencompra') }}";
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

        //para mostrar el modal de ver un registro
        var idventa = "";
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/ordencompra/show') }}";
            $.get(urlventa + '/' + id, function(midata) {
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Orden de Compra Nro: ` + midata[0].numero;
                idventa = id;
                document.getElementById("verNumero").value = midata[0].numero;
                document.getElementById("verPersona").value = midata[0].persona;
                document.getElementById("verFecha").value = midata[0].fecha;
                document.getElementById("verEmpresa").value = midata[0].company;
                document.getElementById("verCliente").value = midata[0].cliente
                document.getElementById("verObservacion").value = midata[0].observacion;
                document.getElementById("verMoneda").value = midata[0].moneda;
                document.getElementById("verFormapago").value = midata[0].formapago;

                var simbolomoneda = "";
                if (midata[0].moneda == "soles") {
                    simbolomoneda = "S/.";
                } else {
                    simbolomoneda = "$";
                }
                if (midata[0].formapago == "contado") {
                    document.getElementById('divdiascredito').style.display = "none";
                } else {
                    document.getElementById('divdiascredito').style.display = "inline";
                    document.getElementById("verDiascredito").value = midata[0].diascredito;
                }
                var tabla = document.getElementById(detallesventa);
                $('#detallesventa tbody tr').slice().remove();
                for (var ite = 0; ite < midata.length; ite++) {

                    var obsproducto = "";
                    if (midata[ite].observacionproducto != null) {
                        obsproducto = midata[ite].observacionproducto;
                    }
                    if (midata[ite].tipo == 'kit') {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + midata[ite].idproducto,
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
                                filaDetalle = '<tr id="fila' + ite +
                                    '"><td> ' + midata[ite].cantidad +
                                    '</td><td> ' + midata[ite].unidad +
                                    '</td><td> ' + simbolomoneda + midata[ite].preciocompra +
                                    '</td><td> <b>' + midata[ite].producto + '</b>' +
                                    puntos + milista + coma +
                                    '</td><td> ' + obsproducto +
                                    '</td> </tr>';
                                $("#detallesventa>tbody").append(filaDetalle);
                                milista = '<br>';
                            }
                        });
                    } else
                    if (midata[ite].tipo == 'estandar') {
                        filaDetalle = '<tr id="fila' + ite +
                            '"><td> ' + midata[ite].cantidad +
                            '</td><td> ' + midata[ite].unidad +
                            '</td><td> ' + simbolomoneda + midata[ite].preciocompra +
                            '</td><td> <b>' + midata[ite].producto + '</b>' +
                            '</td><td> ' + obsproducto +
                            '</td></tr>';
                        $("#detallesventa>tbody").append(filaDetalle);
                    }
                }
            });
        })

        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });
        $('#generarcotizacion').click(function() {
            generarfactura(idventa);
        });

        function generarfactura($id) {
            if ($id != -1) {
                window.open('/admin/ordencompra/generarordencomprapdf/' + $id);
            }
        }
    </script>
@endpush
