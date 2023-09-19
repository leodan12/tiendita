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

                                <button class="nav-link active" id="nav-detalles-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-detalles" type="button" role="tab"
                                    aria-controls="nav-detalles" aria-selected="false">MODELO DE CARROS</button>

                                {{-- <a class="nav-link active" id="nav-detalles-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-detalles" type="button" role="tab"
                                    aria-controls="nav-detalles" aria-selected="false">
                                    MODELO DE CARROS
                                </a> --}}
                                {{-- <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-condiciones" type="button" role="tab"
                                    aria-controls="nav-condiciones" aria-selected="false">CARROCERIAS</button> --}}

                                <a class="nav-link " id="nav-condiciones-tab"  href="{{ url('admin/carroceria') }}">CARROCERIAS </a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                aria-labelledby="nav-detalles-tab" tabindex="0">
                                <br>
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col">
                                                <h4 id="mititulo">MODELOS DE CARROS:
                                                </h4>
                                            </div>
                                            <div class="col">
                                                <h4>
                                                    @can('crear-modelo-carro')
                                                        <button type="button" class="btn btn-primary float-end"
                                                            data-bs-toggle="modal" data-bs-target="#addModelo">
                                                            Agregar Modelo
                                                        </button> 
                                                    @endcan
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="modal fade" id="addModelo" tabindex="-1"
                                            aria-labelledby="addModeloLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="addModeloLabel">Agregar Nuevo
                                                            Modelo</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label is-required">NRO MODELO</label>
                                                                <input type="text" name="modelo" id="modelo"
                                                                    class="form-control mayusculas" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" id="btnguardarmodelo"
                                                            class="btn btn-success">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="editModelo" tabindex="-1"
                                            aria-labelledby="editModeloLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="editModeloLabel">Editar Modelo</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label is-required">NRO MODELO</label>
                                                                <input type="text" name="editmodelo" id="editmodelo"
                                                                    class="form-control mayusculas" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-warning"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="button" id="btnactualizarmodelo"
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
                                                        <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Modelos
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
                                                                        <th>NOMBRE</th>
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
                            <div class="tab-pane fade  " id="nav-condiciones" role="tabpanel"
                                aria-labelledby="nav-condiciones-tab" tabindex="0">
                                <br>

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
            var idmodelo = 0;
            $(document).ready(function() {

                var tabla = "#mitabla";
                var ruta = "{{ route('modelocarros.index') }}"; //darle un nombre a la ruta index
                var columnas = [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'modelo',
                        name: 'modelo'
                    },
                    {
                        data: 'accion',
                        name: 'accion',
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
                var urlregistro = "{{ url('admin/modelocarro') }}";
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
                var urlinventario = "{{ url('admin/modelocarro/showmodelocarrorestore') }}";
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
                            '</td><td>' + data[i].modelo +
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
                var urlregistro = "{{ url('admin/modelocarro/restaurar') }}";
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
                var registro = "MODELOS DE CARROS: ";
                var boton =
                    ' @can('recuperar-modelo-carro') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
                if (numeliminados > 0) {
                    document.getElementById('mititulo').innerHTML = registro + boton;
                } else {
                    document.getElementById('mititulo').innerHTML = registro;
                }
            }
            //para guardar un nuevo modelo
            var botonAdd = document.getElementById("btnguardarmodelo");
            // Agrega un evento de clic al botón
            botonAdd.addEventListener("click", function() {
                var modelo = document.getElementById("modelo").value;
                if (modelo) {
                    botonAdd.disabled = true;
                    var urladdmodelo = "{{ url('admin/modelocarro/addmodelo') }}";
                    $.ajax({
                        type: "GET",
                        url: urladdmodelo + '/' + modelo,
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
                            $('#addModelo').modal('hide');
                            document.getElementById("modelo").value = "";
                            document.getElementById("btnguardarmodelo").disabled = false;
                        }
                    });
                }
            });

            //para actualizar un modelo
            $(document).on('click', '.btneditar', function(event) {
                idmodelo = 0;
                const idmodelox = event.target.dataset.idmodelo;
                const modelo = event.target.dataset.modelo;
                document.getElementById("editmodelo").value = modelo;
                idmodelo = idmodelox;
            });

            var botonUpdate = document.getElementById("btnactualizarmodelo");
            // Agrega un evento de clic al botón
            botonUpdate.addEventListener("click", function() {
                var modeloE = document.getElementById("editmodelo").value;
                if (modeloE) {
                    botonUpdate.disabled = true;
                    var urlupdatemodelo = "{{ url('admin/modelocarro/updatemodelo') }}";
                    $.ajax({
                        type: "GET",
                        url: urlupdatemodelo + '/' + idmodelo + '/' + modeloE,
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
                            $('#editModelo').modal('hide');
                            document.getElementById("editmodelo").value = "";
                            document.getElementById("btnactualizarmodelo").disabled = false;
                        }
                    });
                }
            });
        </script>
    @endpush
