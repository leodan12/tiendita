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
                                <h4 id="mititulo">UNIFORMES:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-producto')
                                        <a href="{{ url('admin/uniformes/create') }}" class="btn btn-primary float-end">Añadir
                                            Producto</a>
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
                                    <th>TALLA</th>
                                    <th>GENERO</th> 
                                    <th>TELA</th>
                                    <th>COLOR</th> 
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
                                            <div class="col-sm-9 mb-3">
                                                <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                <input type="text" class="form-control" id="vernombre" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="verprecio" class="col-form-label">PRECIO:</label>
                                                <input type="number" class="form-control" id="verprecio" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="vergenero" class="col-form-label">GENERO:</label>
                                                <input type="text" class="form-control" id="vergenero" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="vertalla" class="col-form-label">TALLA:</label>
                                                <input type="text" class="form-control" id="vertalla" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="vertela" class="col-form-label">TIPO DE TELA:</label>
                                                <input type="text" class="form-control" id="vertela" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="vercolor" class="col-form-label">COLOR:</label>
                                                <input type="text" class="form-control" id="vercolor" readonly>
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
            var ruta = "{{ route('uniforme.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                }, 
                {
                    data: 'nombre',
                    name: 'nombre'
                },

                {
                    data: 'talla',
                    name: 't.talla'
                },
                {
                    data: 'genero',
                    name: 'genero'
                },
                {
                    data: 'tela',
                    name: 'tt.tela'
                },
                {
                    data: 'color',
                    name: 'c.color'
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
            var urlregistro = "{{ url('admin/uniformes') }}";
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
            var urlregistro = "{{ url('admin/uniformes/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                console.log(data);
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Uniforme ${id}`;
 
                document.getElementById("vernombre").value = data[0].nombre;
                document.getElementById("vergenero").value = data[0].genero;
                document.getElementById("vertalla").value = data[0].talla;
                document.getElementById("vertela").value = data[0].tela;
                document.getElementById("verprecio").value = data[0].precio;
                document.getElementById("vercolor").value = data[0].color; 

            });

        })
        
 
    </script>
@endpush
