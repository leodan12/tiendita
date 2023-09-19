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
                                <h4 id="mititulo">CATEGORIAS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-categoria')
                                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                                            data-bs-target="#addCategoria">
                                            Agregar Categoria
                                        </button>
                                    @endcan
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="addCategoria" tabindex="-1" aria-labelledby="addCategoriaLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="addCategoriaLabel">Agregar Nueva
                                            Categoria</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label is-required">CATEGORIA</label>
                                                <input type="text" name="categoria" id="categoria"
                                                    class="form-control mayusculas" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" id="btnguardarcategoria"
                                            class="btn btn-success">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="editCategoria" tabindex="-1" aria-labelledby="editCategoriaLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="editCategoriaLabel">Editar Categoria</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label is-required">CATEGORIA</label>
                                                <input type="text" name="editnombrecategoria" id="editnombrecategoria"
                                                    class="form-control mayusculas" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning"
                                            data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" id="btnactualizarcategoria"
                                            class="btn btn-success">Actualizar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mitabla" style="width: 100%;"
                                name="mitabla">
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

                        <div class="modal fade " id="modalkits" tabindex="-1" aria-labelledby="modalkits"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Categorias Eliminadas</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="table-responsive">
                                            <table class="table table-row-bordered gy-5 gs-5" style="width: 100%"
                                                id="mitabla1" name="mitabla1">
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
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        var numeroeliminados = 0;
        var idcategoria = 0;
        $(document).ready(function() {

            var tabla = "#mitabla";
            var ruta = "{{ route('categorias.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
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
            var urlregistro = "{{ url('admin/category') }}";
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
            var urlinventario = "{{ url('admin/category/showcategoryrestore') }}";
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
                        '</td><td>' + data[i].nombre +
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
            var urlregistro = "{{ url('admin/category/restaurar') }}";
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
            var registro = "CATEGORIAS: ";
            var boton =
                ' @can('recuperar-categoria') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
            if (numeliminados > 0) {
                document.getElementById('mititulo').innerHTML = registro + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }
        }

        //para guardar un nuevo categoria
        var botonAdd = document.getElementById("btnguardarcategoria");
        // Agrega un evento de clic al botón
        botonAdd.addEventListener("click", function() {
            var categoria = document.getElementById("categoria").value;
            if (categoria) {
                botonAdd.disabled = true;
                var urladdcategoria = "{{ url('admin/category/addcategoria') }}";
                $.ajax({
                    type: "GET",
                    url: urladdcategoria + '/' + categoria,
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
                        $('#addCategoria').modal('hide');
                        document.getElementById("categoria").value = "";
                        document.getElementById("btnguardarcategoria").disabled = false;
                    }
                });
            }
        });

        //para actualizar un categoria
        $(document).on('click', '.btneditar', function(event) {
            idcategoria = 0;
            const idcategoriax = event.target.dataset.idcategoria;
            const categoria = event.target.dataset.categoria;
            document.getElementById("editnombrecategoria").value = categoria;
            idcategoria = idcategoriax;
        });

        var botonUpdate = document.getElementById("btnactualizarcategoria");
        // Agrega un evento de clic al botón
        botonUpdate.addEventListener("click", function() {
            var categoriaE = document.getElementById("editnombrecategoria").value;
            if (categoriaE) {
                botonUpdate.disabled = true;
                var urlupdatecategoria = "{{ url('admin/category/updatecategoria') }}";
                $.ajax({
                    type: "GET",
                    url: urlupdatecategoria + '/' + idcategoria + '/' + categoriaE,
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
                        $('#editCategoria').modal('hide');
                        document.getElementById("editnombrecategoria").value = "";
                        document.getElementById("btnactualizarcategoria").disabled = false;
                    }
                });
            }
        });
    </script>
@endpush
