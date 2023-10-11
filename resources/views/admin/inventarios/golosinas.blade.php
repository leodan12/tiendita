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
                                <h4 id="mititulo">INVENTARIO DE GOLASINAS:
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
                                    <th>STOCK {{ $tiendas[0]->nombre }}</th>
                                    <th>STOCK {{ $tiendas[1]->nombre }}</th>
                                    <th>STOCK {{ $tiendas[2]->nombre }}</th>
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
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Golosinas</h1>
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
                                            <hr> 
                                            <div class="col-sm-3   mb-3" id="colstock1">
                                                <label for="verstock1" class="col-form-label">STOCK {{ $tiendas[0]->nombre }}:</label>
                                                <input type="number" class="form-control" id="verstock1">
                                            </div>
                                            <div class="col-sm-3 mb-3" id="colstock2">
                                                <label for="verstock2" class="col-form-label">STOCK {{ $tiendas[1]->nombre }}:</label>
                                                <input type="number" class="form-control" id="verstock2">
                                            </div>
                                            <div class="col-sm-3 mb-3" id="colstock3">
                                                <label for="verstock3" class="col-form-label">STOCK {{ $tiendas[2]->nombre }}:</label>
                                                <input type="number" class="form-control" id="verstock3">
                                            </div>
                                            <div class="col-sm-3 mb-3" id="colstockmin">
                                                <label for="verstockmin" class="col-form-label">STOCK MIN:</label>
                                                <input type="number" class="form-control" id="verstockmin" min="0">
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
                                            <th>NOMBRE</th>
                                            <th>PESO</th> 
                                            <th>PRECIO(soles)</th>
                                            <th>STOCK {{ $tiendas[0]->nombre }}</th>
                                            <th>STOCK {{ $tiendas[1]->nombre }}</th>
                                            <th>STOCK {{ $tiendas[2]->nombre }}</th>
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
            var ruta = "{{ route('inventariogolosinas.index') }}"; //darle un nombre a la ruta index
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
                }, {
                    data: 'stock1',
                    name: 'stock1'
                },
                {
                    data: 'stock2',
                    name: 'stock2'
                },
                {
                    data: 'stock3',
                    name: 'stock3'
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
            
            var rutasinstock = "{{ url('admin/golosinas/numerosinstock') }}";
            $.get(rutasinstock, function(data) {
                numerosinstock = data;
                mostrarmensajetitulo(numerosinstock);
            });
        });

        //modal para ver el producto
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            idproducto = id;
            const accion = button.getAttribute('data-accion');
            var urlregistro = "{{ url('admin/golosinas/show') }}";
            $.get(urlregistro + '/' + id, function(data) { 
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Inventario del uniforme ${id}`; 
                document.getElementById("vernombre").value = data[0].nombre; 
                document.getElementById("verprecio").value = data[0].precio;
                document.getElementById("verpeso").value = data[0].peso;
                var stock1 = document.getElementById("verstock1");
                var stock2 = document.getElementById("verstock2");
                var stock3 = document.getElementById("verstock3");
                var stockmin = document.getElementById("verstockmin");
                var btnactualizar = document.getElementById("btnactualizar");
                if (accion == "ver") {
                    console.log("accion de ver");
                    stock1.setAttribute("readonly", true);
                    stock2.setAttribute("readonly", true);
                    stock3.setAttribute("readonly", true);
                    stockmin.setAttribute("readonly", true);
                    btnactualizar.style.display = "none";
                } else if (accion == "editar") {
                    console.log("accion de editar");
                    stock1.removeAttribute("readonly");
                    stock2.removeAttribute("readonly");
                    stock3.removeAttribute("readonly");
                    stockmin.removeAttribute("readonly");
                    btnactualizar.style.display = "inline";
                }
                stock1.value = data[0].stock1;
                stock2.value = data[0].stock2;
                stock3.value = data[0].stock3;
                stockmin.value = data[0].stockmin;
            });
        });

        function actualizarstock() {
            var urlstock = "{{ url('admin/golosinas/updatestock') }}";
            var stock1 = document.getElementById("verstock1").value;
            var stock2 = document.getElementById("verstock2").value;
            var stock3 = document.getElementById("verstock3").value;
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
                    stock3: stock3,
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

        //mostrar el modal de la lista de los sin stock---------------------------

        var inicializartablasinstock = 0;
        const modalsinstock = document.getElementById('modalSinStock');
        modalsinstock.addEventListener('show.bs.modal', event => {
            var nrosinstock = 0;
            var nrostockminimo = 0;
            var urldatos = "{{ url('admin/golosinas/showsinstock') }}";

            $.get(urldatos, function(data) { 
                var btns = 'lfrtip';
                var tabla = '#mitabla1';
                if (inicializartablasinstock > 0) {
                    $("#mitabla1").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                }
                $('#mitabla1 tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    var colorfondo = '<tr id="fila' + i + '">';
                    if (data[i].stock1 + data[i].stock2 + data[i].stock3 <= 0) {
                        colorfondo = '<tr style="background-color:  #f89f9f" id="fila' + i + '">';
                        nrosinstock++;
                    } else {
                        nrostockminimo++;
                    }
                    const modalTitle = modalsinstock.querySelector('.modal-title');
                    modalTitle.textContent =
                        `Tienes ${nrosinstock} Golosinas sin stock y ${nrostockminimo} con stock minimo`;

                    filaDetalle = colorfondo +
                        '<td>' + data[i].id +
                        '</td><td>' + data[i].nombre +
                        '</td><td>' + data[i].peso + 
                        '</td><td>' + data[i].precio +
                        '</td><td>' + data[i].stock1 +
                        '</td><td>' + data[i].stock2 +
                        '</td><td>' + data[i].stock3 +
                        '</td> <td> <button type="button" class="btn btn-success" data-id="' + data[i].id +
                        '" data-accion="editar" data-bs-toggle="modal" data-bs-target="#mimodal">Editar</button>  ' +
                        '<button type="button" class="btn btn-secondary" data-id="'+data[i].id+
                        '" data-accion="ver" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>' +
                        '</td> ' +
                        '</tr>';
                    $("#mitabla1>tbody").append(filaDetalle);
                }
                inicializartabladatos(btns, tabla, "");
                inicializartablasinstock++;
            });
        });

        function mostrarmensajetitulo(numsinstock) {
            var registro = "INVENTARIO DE GOLOSINAS: ";
            var tienes = "";
            var stock = " golosinas en stock minimo";
            var boton =
                '<button id="btnsinstock" class="btn btn-info btn-sm " data-bs-toggle="modal"  data-bs-target="#modalSinStock"> Ver </button> ';
            if (numsinstock > 0) {
                tienes = "Tienes ";
                document.getElementById('mititulo').innerHTML = registro + tienes + numsinstock + stock + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }
        }

        function volver(){
            $('#mimodal').modal('hide');
            if (numerosinstock > 0 && mostrar == "SI") {
                $('#modalSinStock').modal('show');
            }
        }
    </script>
@endpush
