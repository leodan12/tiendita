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
                                <h4 id="mititulo">MIS TIENDAS:&nbsp;&nbsp;
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    {{-- @can('crear-empresa')
                                        <a href="{{ url('admin/tienda/create') }}" class="btn btn-primary float-end">Añadir
                                            Tienda</a>
                                    @endcan --}}
                                </h4>
                            </div>
                        </div>
                        <h4>
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped " style="width: 100%;" id="mitabla"
                                name="mitabla">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE</th>
                                        <th>UBICACION</th>
                                        <th>PERSONA A CARGO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <Tbody id="tbody-mantenimientos">

                                </Tbody>
                            </table>
                        </div>

                    </div>


                    <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">VER TIENDA</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="row">
                                                    <div class="col-md-6  mb-3">
                                                        <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                        <input type="text" class="form-control" id="vernombre" readonly>
                                                    </div>
                                                    <div class="col-md-6  mb-3" id="divtelefono">
                                                        <label for="verencargado" class="col-form-label">PERSONA A
                                                            CARGO:</label>
                                                        <input type="text" class="form-control" id="verencargado"
                                                            readonly>
                                                    </div>
                                                    <div class="col-md-12 mb-3" id="divdireccion">
                                                        <label for="verubicacion" class="col-form-label">UBICACION:</label>
                                                        <input type="text" class="form-control" id="verubicacion"
                                                            readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

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
            var ruta = "{{ route('tiendas.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },

                {
                    data: 'ubicacion',
                    name: 'ubicacion'
                },
                {
                    data: 'encargado',
                    name: 'encargado'
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
            var urlregistro = "{{ url('admin/tienda') }}";
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
                                mensaje("Registro Eliminado", "success");
                            } else if (data1 == "0") {
                                mensaje("Registro No Eliminado", "error"); 
                            } else if (data1 == "2") {
                                mensaje("Registro No Encontrado", "error"); 
                            }
                        }
                    });
                }
            });
        });
        //modal para ver la empresa
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            var urlregistro = "{{ url('admin/tienda/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`

                document.getElementById("vernombre").value = data.nombre;
                document.getElementById("verubicacion").value = data.ubicacion;
                document.getElementById("verencargado").value = data.encargado; 
            });

        });
    </script>
@endpush
