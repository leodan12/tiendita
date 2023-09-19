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
                        <div class="row">
                            <div class="col">
                                <h4 id="mititulo">REDES:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-red')
                                        <a href="{{ url('admin/redes/create') }}" class="btn btn-primary float-end">Añadir Red</a>
                                    @endcan
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mitabla" 
                                name="mitabla">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE</th>
                                        <th>CARROCERIA</th>
                                        <th>MODELO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">

                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Red</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-4  mb-3">
                                                    <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                    <input type="text" class="form-control" id="vernombre" readonly>
                                                </div>
                                                <div class="col-sm-4  mb-3">
                                                    <label for="vercarroceria" class="col-form-label">CARROCERIA:</label>
                                                    <input type="text" class="form-control" id="vercarroceria" readonly>
                                                </div>
                                                <div class="col-sm-4  mb-3">
                                                    <label for="vermodelo" class="col-form-label">MODELO:</label>
                                                    <input type="text" class="form-control" id="vermodelo" readonly>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table-striped table-row-bordered gy-5 gs-5" id="kits"
                                                style="width: 100%">
                                                <thead class="fw-bold text-primary">
                                                    <tr style="text-align: center;">
                                                        <th>Cantidad</th>
                                                        <th>Unidad</th>
                                                        <th>Producto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
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
        var numeroeliminados = 0;
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('red.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
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
            var urlregistro = "{{ url('admin/redes') }}";
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

         
        //para el modal de ver kits
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {
            $('#kits tbody tr').slice().remove();
            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            var urlregistro = "{{ url('admin/redes/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Red ${id}`
                document.getElementById("vernombre").value = data[0].nombrered;
                document.getElementById("vercarroceria").value = data[0].tipocarroceria;
                document.getElementById("vermodelo").value = data[0].modelo;
                //$('#kits tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    var fondo = "#ffffff";
                    if (i % 2 == 0) {
                        fondo = "#E4EDE6";
                    }
                    if (data[i].tipo == 'kit') {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + data[i].idproducto,
                            async: false,
                            data: {
                                id: id
                            },
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                var coma = '<br>';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;
                                }
                                filaDetalle = '<tr id="fila' + i + '" style="background-color:' +
                                    fondo +
                                    ';"><td style="text-align: center;">' + data[i].cantidad +
                                    '</td><td style="text-align: center;"> ' + data[i].unidad +
                                    '</td><td> <b>' + data[i].nombre + '</b>' + puntos +
                                    milista + coma +
                                    '</td></tr>';
                                $("#kits>tbody").append(filaDetalle);
                                milista = '<br>';
                            }
                        });
                    } else if (data[i].tipo == 'estandar') {
                        filaDetalle = '<tr id="fila' + i + '" style="background-color:' + fondo +
                            ';"><td style="text-align: center;">' + data[i].cantidad +
                            '</td><td style="text-align: center;"> ' + data[i].unidad +
                            '</td><td> ' + data[i].nombre +
                            '</td></tr>';

                        $("#kits>tbody").append(filaDetalle);
                    }


                }
            });

        })
    </script>
@endpush
