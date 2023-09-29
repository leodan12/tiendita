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
    <div>
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
                                <h4 id="mititulo">INVENTARIO DE LIBROS:
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
                                    <th>AÑO</th>
                                    <th>FORMATO</th>
                                    <th>TIPO PAPEL</th>
                                    <th>TIPO PASTA</th>
                                    <th>EDICION</th>
                                    <th>ESPECIALIZACION</th>
                                    <th>ORIGINAL?</th>
                                    <th>PRECIO(soles)</th>
                                    <th>STOCK TIENDA 1</th>
                                    <th>STOCK TIENDA 2</th>
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
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Producto</h1>
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
                                            <div class="col-sm-3   mb-3">
                                                <label for="veranio" class="col-form-label">AÑO:</label>
                                                <input type="number" class="form-control" id="veranio" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="verprecio" class="col-form-label">PRECIO:</label>
                                                <input type="number" class="form-control" id="verprecio" step="0.0001"
                                                    readonly>
                                            </div>
                                            <div class="col-sm-6   mb-3">
                                                <label for="verautor" class="col-form-label">AUTOR:</label>
                                                <input type="text" class="form-control" id="verautor" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="veredicion" class="col-form-label">EDICION:</label>
                                                <input type="text" class="form-control" id="veredicion" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="vertipopapel" class="col-form-label">TIPO PAPEL:</label>
                                                <input type="text" class="form-control" id="vertipopapel" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="vertipopasta" class="col-form-label">TIPO PASTA:</label>
                                                <input type="text" class="form-control" id="vertipopasta" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="verformato" class="col-form-label">FORMATO:</label>
                                                <input type="text" class="form-control" id="verformato" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <label for="verespecializacion"
                                                    class="col-form-label">ESPECIALIZACIOn:</label>
                                                <input type="text" class="form-control" id="verespecializacion" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="veroriginal" class="col-form-label">ORIGINAL?:</label>
                                                <input type="text" class="form-control" id="veroriginal" readonly>
                                            </div>
                                            <hr>
                                            <div class="col-sm-3   mb-3" id="colstock1">
                                                <label for="verstock1" class="col-form-label">STOCK 1:</label>
                                                <input type="number" class="form-control" id="verstock1">
                                            </div>
                                            <div class="col-sm-3 mb-3" id="colstock2">
                                                <label for="verstock2" class="col-form-label">STOCK 2:</label>
                                                <input type="number" class="form-control" id="verstock2">
                                            </div>
                                            <div class="col-sm-3 mb-3" id="colstockmin">
                                                <label for="verstockmin" class="col-form-label">STOCK MIN:</label>
                                                <input type="number" class="form-control" id="verstockmin"
                                                    min="0">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" id="btnactualizar"
                                        onclick="actualizarstock();">Actualizar Inventario</button>

                                    <button type="button" class="btn btn-secondary" onclick="volver()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- mis modales para ver los sin stock --}}
                <div class="modal fade" id="modalSinStock" aria-hidden="true" aria-labelledby="modalSinStockLabel"
                    tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="modalSinStockLabel1">
                                    TIENES: &nbsp;
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped table-bordered table-striped " style="width: 100%"
                                    id="mitabla1" name="mitabla1">
                                    <thead class="fw-bold text-primary">
                                        <tr style="text-align: center;">
                                            <th>ID</th>
                                            <th>TITULO</th>
                                            <th>AUTOR</th>
                                            <th>AÑO</th>
                                            <th>FORMATO</th>
                                            <th>TIPO PAPEL</th>
                                            <th>TIPO PASTA</th>
                                            <th>EDICION</th>
                                            <th>ESPECIALIZACION</th>
                                            <th>ORIGINAL?</th>
                                            <th>PRECIO(soles)</th>
                                            <th>STOCK TIENDA 1</th>
                                            <th>STOCK TIENDA 2</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <Tbody>
                                        <tr></tr>
                                    </Tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
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
        var numerosinstock = 0;
        $(document).ready(function() {
            if (mostrar == "SI") {
                $('#modalSinStock').modal('show');
            }
            var tabla = "#mitabla";
            var ruta = "{{ route('inventariolibro.index') }}"; //darle un nombre a la ruta index
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
                    data: 'anio',
                    name: 'anio'
                },
                {
                    data: 'formato',
                    name: 'f.formato'
                },
                {
                    data: 'tipopapel',
                    name: 'tp.tipopapel'
                },
                {
                    data: 'tipopasta',
                    name: 'tt.tipopasta'
                },
                {
                    data: 'edicion',
                    name: 'e.edicion'
                },
                {
                    data: 'especializacion',
                    name: 'es.especializacion'
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
                    data: 'stock1',
                    name: 'stock1'
                },
                {
                    data: 'stock2',
                    name: 'stock2'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    searchable: false,
                    orderable: false,
                },
            ];
            var btns = 'lfrtip';
            var idproducto = 0;
            iniciarTablaIndex(tabla, ruta, columnas, btns);

            var rutasinstock = "{{ url('admin/libros/numerosinstock') }}";
            $.get(rutasinstock, function(data) {
                numerosinstock = data;
                mostrarmensajetitulo(numerosinstock);
            });
        });

        //modal para ver el producto
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            idproducto = id;
            const accion = button.getAttribute('data-accion');
            var urlregistro = "{{ url('admin/libros/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                console.log(data);
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Inventario del Libro ${id}`;
                console.log(accion);
                document.getElementById("vertitulo").value = data[0].titulo;
                document.getElementById("verautor").value = data[0].autor;
                document.getElementById("veranio").value = data[0].anio;
                document.getElementById("vertipopapel").value = data[0].tipopapel;
                document.getElementById("verprecio").value = data[0].precio;
                document.getElementById("vertipopasta").value = data[0].tipopasta;
                document.getElementById("veredicion").value = data[0].edicion;
                document.getElementById("verespecializacion").value = data[0].especializacion;
                document.getElementById("veroriginal").value = data[0].original;
                var stock1 = document.getElementById("verstock1");
                var stock2 = document.getElementById("verstock2");
                var stockmin = document.getElementById("verstockmin");
                var btnactualizar = document.getElementById("btnactualizar");
                if (accion == "ver") {
                    console.log("accion de ver");
                    stock1.setAttribute("readonly", true);
                    stock2.setAttribute("readonly", true);
                    stockmin.setAttribute("readonly", true);
                    btnactualizar.style.display = "none";
                } else if (accion == "editar") {
                    console.log("accion de editar");
                    stock1.removeAttribute("readonly");
                    stock2.removeAttribute("readonly");
                    stockmin.removeAttribute("readonly");
                    btnactualizar.style.display = "inline";
                }
                stock1.value = data[0].stock1;
                stock2.value = data[0].stock2;
                stockmin.value = data[0].stockmin;
            });
        });

        var inicializartablasinstock = 0;
        const modalsinstock = document.getElementById('modalSinStock');
        modalsinstock.addEventListener('show.bs.modal', event => {
            var nrosinstock = 0;
            var nrostockminimo = 0;
            var urldatos = "{{ url('admin/libros/showsinstock') }}";

            $.get(urldatos, function(data) {
                var btns = 'lfrtip';
                var tabla = '#mitabla1';
                if (inicializartablasinstock > 0) {
                    $("#mitabla1").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                }
                $('#mitabla1 tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    var colorfondo = '<tr id="fila' + i + '">';
                    if (data[i].stock1 + data[i].stock2 <= 0) {
                        colorfondo = '<tr style="background-color:  #f89f9f" id="fila' + i + '">';
                        nrosinstock++;
                    } else {
                        nrostockminimo++;
                    }
                    const modalTitle = modalsinstock.querySelector('.modal-title');
                    modalTitle.textContent =
                        `Tienes ${nrosinstock} Libros sin Stock y ${nrostockminimo} con Stock Minimo`;

                    filaDetalle = colorfondo +
                        '<td>' + data[i].id +
                        '</td><td>' + data[i].titulo +
                        '</td><td>' + data[i].autor +
                        '</td><td>' + data[i].anio +
                        '</td><td>' + data[i].formato +
                        '</td><td>' + data[i].tipopapel +
                        '</td><td>' + data[i].tipopasta +
                        '</td><td>' + data[i].edicion +
                        '</td><td>' + data[i].especializacion +
                        '</td><td>' + data[i].original +
                        '</td><td>' + data[i].precio +
                        '</td><td>' + data[i].stock1 +
                        '</td><td>' + data[i].stock2 +
                        '</td> <td> <button type="button" class="btn btn-success" data-id="' + data[i].id +
                        '" data-accion="editar" data-bs-toggle="modal" data-bs-target="#mimodal">Editar</button>  ' +
                        '<button type="button" class="btn btn-secondary" data-id="' + data[i].id +
                        '" data-accion="ver" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>' +
                        '</td> ' +
                        '</tr>';
                    $("#mitabla1>tbody").append(filaDetalle);
                }
                inicializartabladatos(btns, tabla, "");
                inicializartablasinstock++;
            });
        });

        function actualizarstock() {
            var urlstock = "{{ url('admin/libros/updatestock') }}";
            var stock1 = document.getElementById("verstock1").value;
            var stock2 = document.getElementById("verstock2").value;
            var stockmin = document.getElementById("verstockmin").value;
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: urlstock,
                async: true,
                data: {
                    stock1: stock1,
                    stock2: stock2,
                    stockmin: stockmin,
                    idproducto: idproducto,
                },
                success: function(data1) {
                    if (data1 == "1") {
                        mensaje("Stock Actualizado", "success");
                        recargartabla();
                        volver();
                        $("#mimodal").modal("hide");
                    }
                }
            });
        }

        function mostrarmensajetitulo(numsinstock) {
            var registro = "INVENTARIO DE LIBROS: ";
            var tienes = "";
            var stock = " libros en stock minimo";
            var boton =
                '<button id="btnsinstock" class="btn btn-info btn-sm " data-bs-toggle="modal"  data-bs-target="#modalSinStock"> Ver </button> ';
            if (numsinstock > 0) {
                tienes = "Tienes ";
                document.getElementById('mititulo').innerHTML = registro + tienes + numsinstock + stock + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }
        }

        function volver() {
            $('#mimodal').modal('hide');
            if (numerosinstock > 0 && mostrar == "SI") {
                $('#modalSinStock').modal('show');
            }
        }
    </script>
@endpush
