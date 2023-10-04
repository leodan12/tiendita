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
                                <h4 id="mititulo">GOLOSINAS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-producto')
                                        <a href="{{ url('admin/golosinas/create') }}" class="btn btn-primary float-end">Añadir
                                            Golosina</a>
                                    @endcan
                                </h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="mitabla" name="mitabla">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>ID</th> 
                                    <th>NOMBRE</th>
                                    <th>PESO</th> 
                                    <th>PRECIO(soles)</th> 
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <Tbody id="tbody-mantenimientos">

                            </Tbody>
                        </table>
                        <div>

                        </div>
                    </div>

                    <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Producto</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row"> 
                                            <div class="col-sm-6 mb-3">
                                                <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                <input type="text" class="form-control" id="vernombre" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="verprecio" class="col-form-label">PRECIO:</label>
                                                <input type="number" class="form-control" id="verprecio" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="verpeso" class="col-form-label">PESO:</label>
                                                <input type="text" class="form-control" id="verpeso" readonly>
                                            </div>
                                            
                                        </div>
                                    </form>
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
@endsection
@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        var numeroeliminados = 0;
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('golosinas.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                }, 
                {
                    data: 'nombre',
                    name: 'nombre'
                }, 
                {
                    data: 'peso',
                    name: 'peso'
                },
                {
                    data: 'precio',
                    name: 'precio'
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
            var urlregistro = "{{ url('admin/golosinas') }}";
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

        //modal para ver el producto
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlregistro = "{{ url('admin/golosinas/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                console.log(data);
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Uniforme ${id}`; 
                document.getElementById("vernombre").value = data[0].nombre;
                document.getElementById("verpeso").value = data[0].peso; 
                document.getElementById("verprecio").value = data[0].precio;  
            }); 
        }) 
    </script>
@endpush
