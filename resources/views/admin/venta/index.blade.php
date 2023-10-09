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
                        <table class="table table-striped table-striped table-bordered table-striped " id="mitabla"
                            name="mitabla">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>TIENDA</th>
                                    <th>FECHA</th>
                                    <th>PRECIO VENTA(soles) </th>
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
                                        <div class="col-md-3   mb-3">
                                            <label for="verFecha" class="col-form-label">FECHA:</label>
                                            <input type="text" class="form-control " id="verFecha" readonly>
                                        </div>
                                        <div class=" col-md-3   mb-3">
                                            <label for="verTienda" class="col-form-label">TIENDA:</label>
                                            <input type="text" class="form-control " id="verTienda" readonly>
                                        </div>
                                        <div class=" col-md-3   mb-3">
                                            <label for="verCliente" class="col-form-label">CLIENTE:</label>
                                            <input type="text" class="form-control " id="verCliente" readonly>
                                        </div>
                                        <div class=" col-md-3   mb-3">
                                            <div class="input-group">
                                                <label for="verPrecioventa" class="col-form-label input-group">PRECIO
                                                    VENTA:</label>
                                                <span class="input-group-text" id="spancostoventa">S/.</span>
                                                <input type="text" class="form-control " id="verPrecioventa" readonly
                                                    style="background-color: #fb0c3249">
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                <br>
                                <div class="row">
                                    <h4>DETALLES DE LA VENTA</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesventa">
                                            <thead class="fw-bold text-primary">
                                                <tr style="text-align: center;">
                                                    <th>Tipo</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>precio Unitario</th>
                                                    <th>precio total</th>
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
                                <button type="button" class="btn btn-success" id="generarfactura"> Generar Pdf de la
                                    Factura </button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        //para el modal ver venta
        var idventa = "";
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
                    data: 'tienda',
                    name: 't.nombre'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },
                {
                    data: 'costoventa',
                    name: 'costoventa'
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
                document.getElementById("verTienda").value = midata[0].tienda;
                document.getElementById("verPrecioventa").value = midata[0].costoventa;


                var tabla = document.getElementById(detallesventa);
                for (var ite = 0; ite < midata.length; ite++) {
                    var monedaproducto = midata[ite].monedaproducto;

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
            });
        })

        $('#generarfactura').click(function() {
            generarfactura(idventa);
        });

        function generarfactura($id) {
            if ($id != -1) {
                window.open('/admin/venta/generarfacturapdf/' + $id);
            }
        }
    </script>
@endpush
