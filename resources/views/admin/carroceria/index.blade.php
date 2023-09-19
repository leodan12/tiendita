@extends('layouts.admin')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <nav class="" style="border-radius: 5px; ">
                            <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                                <a class="nav-link" id="nav-detalles-tab"  href="{{ url('admin/modelocarro') }}">
                                    MODELO DE CARROS
                                </a>
                                <button class="nav-link active " id="nav-condiciones-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-condiciones" type="button" role="tab"
                                    aria-controls="nav-condiciones" aria-selected="false">CARROCERIAS</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade" id="nav-detalles" role="tabpanel" aria-labelledby="nav-detalles-tab"
                                tabindex="0">
                                <br>

                            </div>
                            <div class="tab-pane fade show active" id="nav-condiciones" role="tabpanel"
                                aria-labelledby="nav-condiciones-tab" tabindex="0">
                                <br>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col">
                                                <h4 id="mititulo">CARROCERIAS:
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4>
                                                    @can('crear-carroceria')
                                                        <button type="button" class="btn btn-primary float-end"
                                                            data-bs-toggle="modal" data-bs-target="#addCarroceria">
                                                            Agregar Carroceria
                                                        </button>
                                                    @endcan
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="modal fade" id="addCarroceria" tabindex="-1"
                                            aria-labelledby="addCarroceriaLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="addCarroceriaLabel">Agregar Nueva
                                                            Carroceria</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label is-required">TIPO CARROCERIA</label>
                                                                <input type="text" name="tipocarroceria" id="tipocarroceria"
                                                                    class="form-control mayusculas" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" id="btnguardarcarroceria"
                                                            class="btn btn-success">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="editCarroceria" tabindex="-1"
                                            aria-labelledby="editCarroceriaLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editCarroceriaLabel">Editar Carroceria</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label is-required">TIPO CARROCERIA</label>
                                                                <input type="text" name="edittipocarroceria" id="edittipocarroceria"
                                                                    class="form-control mayusculas" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" id="btnactualizarcarroceria"
                                                            class="btn btn-success">Actualizar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" id="mitabla"
                                                style="width: 100%;" name="mitabla">
                                                <thead class="fw-bold text-primary">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NOMBRE</th>
                                                        <th>ACCIONES</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-mantenimientos">
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="modal fade " id="modalkits" tabindex="-1"
                                            aria-labelledby="modalkits" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Carrocerias
                                                            Eliminadas
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-row-bordered gy-5 gs-5"
                                                                style="width: 100%" id="mitabla1" name="mitabla1">
                                                                <thead class="fw-bold text-primary">
                                                                    <tr>
                                                                        <th>ID</th>
                                                                        <th>CARROCERIA</th>
                                                                        <th>ACCION</th>
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
                </div>
            </div>
        </div>
    @endsection
    @push('script')
        <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
        <script> 
            var numeroeliminados = 0;
            var idcarroceria = 0;
            $(document).ready(function() {

                var tabla = "#mitabla";
                var ruta = "{{ route('carrocerias.index') }}"; //darle un nombre a la ruta index
                var columnas = [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tipocarroceria',
                        name: 'tipocarroceria'
                    },
                    {
                        data: 'acciones',
                        name: 'acciones',
                        searchable: false,
                        orderable: false,
                    },
                ];
                numeroeliminados = @json($datoseliminados);
                mostrarmensaje(numeroeliminados);
                var btns = 'lfrtip';
                iniciarTablaIndex(tabla, ruta, columnas, btns);

            });
            //para borrar un registro de la tabla
            $(document).on('click', '.btnborrar', function(event) {
                const idregistro = event.target.dataset.idregistro;
                var urlregistro = "{{ url('admin/carroceria') }}";
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
                                    numeroeliminados++;
                                    mostrarmensaje(numeroeliminados);
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

            var inicializartabla = 0;
            const modalkits = document.getElementById('modalkits');
            modalkits.addEventListener('show.bs.modal', event => {
                var urlinventario = "{{ url('admin/carroceria/showcarroceriarestore') }}";
                $.get(urlinventario, function(data) {
                    var btns = 'lfrtip';
                    var tabla = '#mitabla1';
                    if (inicializartabla > 0) {
                        $("#mitabla1").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                    }
                    $('#mitabla1 tbody tr').slice().remove();
                    for (var i = 0; i < data.length; i++) {
                        filaDetalle = '<tr id="fila' + i +
                            '"><td>' + data[i].id +
                            '</td><td>' + data[i].tipocarroceria +
                            '</td><td><button type="button" class="btn btn-info"  ' +
                            ' onclick="RestaurarRegistro(' + data[i].id + ')" >Restaurar</button></td>  ' +
                            '</tr>';
                        $("#mitabla1>tbody").append(filaDetalle);
                    }
                    inicializartabladatos(btns, tabla, "");
                    inicializartabla++;
                });
            });

            function RestaurarRegistro(idregistro) {
                var urlregistro = "{{ url('admin/carroceria/restaurar') }}";
                Swal.fire({
                    title: '¿Desea Restaurar El Registro?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí,Restaurar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: urlregistro + '/' + idregistro,
                            success: function(data1) {
                                if (data1 == "1") {
                                    recargartabla();
                                    $('#modalkits').modal('hide');
                                    numeroeliminados--;
                                    mostrarmensaje(numeroeliminados);
                                    Swal.fire({
                                        icon: "success",
                                        text: "Registro Restaurado",
                                    });
                                } else if (data1 == "0") {
                                    Swal.fire({
                                        icon: "error",
                                        text: "Registro NO Restaurado",
                                    });
                                } else if (data1 == "2") {
                                    Swal.fire({
                                        icon: "error",
                                        text: "Registro NO Encontrado",
                                    });
                                }
                            }
                        });
                    }
                });
            }

            function mostrarmensaje(numeliminados) {
                var registro = "CARROCERIAS: ";
                var boton =
                    ' @can('recuperar-modelo-carro') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
                if (numeliminados > 0) {
                    document.getElementById('mititulo').innerHTML = registro + boton;
                } else {
                    document.getElementById('mititulo').innerHTML = registro;
                }
            }
            //para guardar un nuevo carroceria
            var botonAdd = document.getElementById("btnguardarcarroceria");
            // Agrega un evento de clic al botón
            botonAdd.addEventListener("click", function() {
                var carroceria = document.getElementById("tipocarroceria").value;
                if (carroceria) {
                    botonAdd.disabled = true;
                    var urladdcarroceria = "{{ url('admin/carroceria/addcarroceria') }}";
                    $.ajax({
                        type: "GET",
                        url: urladdcarroceria + '/' + carroceria,
                        async: true,
                        success: function(data) {
                            if (data == '1') {
                                Swal.fire({
                                    icon: "success",
                                    text: "Registro Agregado",
                                });
                                recargartabla();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro No Agregado",
                                });
                            }
                            $('#addCarroceria').modal('hide');
                            document.getElementById("tipocarroceria").value = "";
                            document.getElementById("btnguardarcarroceria").disabled = false;
                        }
                    });
                }
            });

            //para actualizar un carroceria
            $(document).on('click', '.btneditar', function(event) {
                idcarroceria = 0;
                const idcarroceriax = event.target.dataset.idcarroceria;
                const carroceria = event.target.dataset.carroceria;
                document.getElementById("edittipocarroceria").value = carroceria;
                idcarroceria = idcarroceriax;
            });

            var botonUpdate = document.getElementById("btnactualizarcarroceria");
            // Agrega un evento de clic al botón
            botonUpdate.addEventListener("click", function() {
                var carroceriaE = document.getElementById("edittipocarroceria").value;
                if (carroceriaE) {
                    botonUpdate.disabled = true;
                    var urlupdatecarroceria = "{{ url('admin/carroceria/updatecarroceria') }}";
                    $.ajax({
                        type: "GET",
                        url: urlupdatecarroceria + '/' + idcarroceria + '/' + carroceriaE,
                        async: true,
                        success: function(data) {
                            if (data == '1') {
                                Swal.fire({
                                    icon: "success",
                                    text: "Registro Actualizado",
                                });
                                recargartabla();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro No Actualizado",
                                });
                            }
                            $('#editCarroceria').modal('hide');
                            document.getElementById("edittipocarroceria").value = "";
                            document.getElementById("btnactualizarcarroceria").disabled = false;
                        }
                    });
                }
            });
        </script>
    @endpush
