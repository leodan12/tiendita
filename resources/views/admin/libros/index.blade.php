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
                                <h4 id="mititulo">LIBROS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-libro')
                                        <a href="{{ url('admin/libros/create') }}" class="btn btn-primary float-end">Añadir
                                            Libro</a>
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
                                    <th>TITULO</th>
                                    <th>AUTOR</th>
                                    <th>ESPECIALIZACION</th>
                                    <th>EDICION</th>
                                    <th>AÑO</th>
                                    <th>FORMATO</th>
                                    <th>TIPO PAPEL</th>
                                    <th>TIPO PASTA</th>
                                    <th>ORIGINAL O COPIA</th>
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
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Libro</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-6 mb-3">
                                                <label for="vertitulo" class="col-form-label">TITULO:</label>
                                                <input type="text" class="form-control" id="vertitulo" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="verprecio" class="col-form-label">PRECIO:</label>
                                                <input type="number" class="form-control" id="verprecio" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="veranio" class="col-form-label">AÑO:</label>
                                                <input type="text" class="form-control" id="veranio" readonly>
                                            </div>
                                            <div class="col-sm-6 mb-3">
                                                <label for="verautor" class="col-form-label">AUTOR:</label>
                                                <input type="text" class="form-control" id="verautor" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="verespecializacion"
                                                    class="col-form-label">ESPECIALIZACION:</label>
                                                <input type="text" class="form-control" id="verespecializacion" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="veredicion" class="col-form-label">EDICION:</label>
                                                <input type="text" class="form-control" id="veredicion" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="verformato" class="col-form-label">FORMATO:</label>
                                                <input type="text" class="form-control" id="verformato" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="vertipopapel" class="col-form-label">TIPO DE PAPEL:</label>
                                                <input type="text" class="form-control" id="vertipopapel" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="vertipopasta" class="col-form-label">TIPO DE PASTA:</label>
                                                <input type="text" class="form-control" id="vertipopasta" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="veroriginal" class="col-form-label">ORIGINAL O COPIA:</label>
                                                <input type="text" class="form-control" id="veroriginal" readonly>
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
            var ruta = "{{ route('libro.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'titulo',
                    name: 'titulo'
                },

                {
                    data: 'autor',
                    name: 'autor'
                },
                {
                    data: 'especializacion',
                    name: 'e.especializacion'
                },
                {
                    data: 'edicion',
                    name: 'edicion'
                },
                {
                    data: 'anio',
                    name: 'anio'
                },
                {
                    data: 'formato',
                    name: 'formato'
                },
                {
                    data: 'tipopapel',
                    name: 'tipopapel'
                },
                {
                    data: 'tipopasta',
                    name: 'tipopasta'
                },
                {
                    data: 'original',
                    name: 'original'
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
            var urlregistro = "{{ url('admin/libros') }}";
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

        //modal para ver el producto
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlregistro = "{{ url('admin/libros/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                console.log(data);
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Libro ${id}`;

                document.getElementById("vertitulo").value = data[0].titulo;
                document.getElementById("verautor").value = data[0].autor;
                document.getElementById("verespecializacion").value = data[0].especializacion;
                document.getElementById("veredicion").value = data[0].edicion;
                document.getElementById("veranio").value = data[0].anio;
                document.getElementById("verformato").value = data[0].formato;
                document.getElementById("vertipopapel").value = data[0].tipopapel;
                document.getElementById("vertipopasta").value = data[0].tipopasta;
                document.getElementById("veroriginal").value = data[0].original;
                document.getElementById("verprecio").value = data[0].precio;

            });

        })
    </script>
@endpush
