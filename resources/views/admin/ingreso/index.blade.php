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
                            <h4 id="mititulo">REGISTRO DE INGRESOS:
                            </h4>
                        </div>
                        <div class="col">
                            <h4>
                                @can('crear-ingreso')
                                    <a href="{{ url('admin/ingreso/create') }}" class="btn btn-primary float-end">Añadir
                                        Ingreso</a>
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
                                    <th>PRECIO DEL INGRESO (soles) </th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-mantenimientos">
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- modal paera ver el ingreso --}}
                <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="mimodalLabel">Ver Ingreso</h1>
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
                                            <label for="verProveedor" class="col-form-label">PROVEEDOR:</label>
                                            <input type="text" class="form-control " id="verProveedor" readonly>
                                        </div>
                                        <div class=" col-md-3   mb-3">
                                            <div class="input-group">
                                                <label for="verPrecioingreso" class="col-form-label input-group">PRECIO
                                                    DE INGRESO:</label>
                                                <span class="input-group-text" id="spancostoingreso">S/.</span>
                                                <input type="text" class="form-control " id="verPrecioingreso" readonly
                                                    style="background-color: #fb0c3249">
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                <br>
                                <div class="row">
                                    <h4>DETALLES DEL INGRESO</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesingreso">
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
        //para el modal ver ingreso
        var idingreso = "";
        $(document).ready(function() {
            if (mostrar == "SI") {
                $('#modalCreditos1').modal('show');
            }
            var tabla = "#mitabla";
            var ruta = "{{ route('ingreso.index') }}"; //darle un nombre a la ruta index
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
            var urlregistro = "{{ url('admin/ingreso') }}";
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

        //modal para ver un ingreso
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            $('#detallesingreso tbody tr').slice().remove();
            var urlingreso = "{{ url('admin/ingreso/show') }}";
            $.get(urlingreso + '/' + id, function(data) {
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`;

                document.getElementById("verPrecioingreso").value = data[0]['costoventa'];
                document.getElementById("verFecha").value = data[0]['fecha'];
                document.getElementById("verTienda").value = data[1]['nombre'];
                if(data[3]){
                    document.getElementById("verProveedor").value = data[3]['nombre'];
                }
                 
                var tabla = document.getElementById('detallesingreso');
                for (var i = 0; i < data[2].length; i++) {
                    var nombre = "";
                    if (data[2][i]['tipo'] == "UTILES") {
                        nombre = data[2][i]['nombre'] + " - marca:" + data[2][i]['marcautil'] +
                            " - color:" + data[2][i]['colorutil'];
                    } else if (data[2][i]['tipo'] == "UNIFORMES") {
                        nombre = data[2][i]['nombre'] + " - " + data[2][i]['genero'] + " - talla:" + data[2]
                            [i]['talla'] +
                            " - tela:" + data[2][i]['tela'] + " - color:" + data[2][i]['color'];
                    } else if (data[2][i]['tipo'] == "LIBROS") {
                        nombre = data[2][i]['nombre'] + " - autor:" + data[2][i]['autor'] + " - año:" +
                            data[2][i]['anio'] +
                            " - formato:" + data[2][i]['formato'] + " - papel:" + data[2][i]['tipopapel'] +
                            " - pasta:" + data[2][i]['tipopasta'] + " - edicion:" + data[2][i]['edicion'] +
                            " - especialiacion:" + data[2][i]['especializacion'] +
                            " - " + data[2][i]['original'];
                    } else if (data[2][i]['tipo'] == "INSTRUMENTOS") {
                        nombre = data[2][i]['nombre'] + " - marca:" + data[2][i]['marca'] +
                            " - modelo:" + data[2][i]['modelo'];
                    } else if (data[2][i]['tipo'] == "GOLOSINAS") {
                        nombre = data[2][i]['nombre'] + " - peso:" + data[2][i]['peso'] + " gramos";
                    } else if (data[2][i]['tipo'] == "SNACKS") {
                        nombre = data[2][i]['nombre'] + " - tamaño:" + data[2][i]['tamanio'] +
                            " - marca:" + data[2][i]['marcasnack'] + " - sabor:" + data[2][i]['saborsnack'];
                    }

                    filaDetalle = '<tr ><td>' + data[2][i]['tipo'] + 
                        '</td><td >' + nombre +
                        '</td><td style="text-align: center;">' + data[2][i]['cantidad'] +
                        '</td><td style="text-align: center;">S/.' + data[2][i]['preciounitariomo'] +
                        '</td><td style="text-align: center;">S/.' + data[2][i]['preciofinal'] +
                        '</td></tr>';
                    $("#detallesingreso>tbody").append(filaDetalle);
                }
            });
        })

        $('#generarfactura').click(function() {
            generarfactura(idingreso);
        });

        function generarfactura($id) {
            if ($id != -1) {
                window.open('/admin/ingreso/generarfacturapdf/' + $id);
            }
        }
    </script>
@endpush
