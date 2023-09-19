@extends('layouts.admin')

@section('content')
<div class="row" style="text-align: center;">
    <div class="col">
        <h3>
            PRECIOS ESPECIALES DE VENTA PARA CLIENTES
        </h3>
    </div>
</div><br>
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">CLIENTE</label>
                    <select class="form-select select2 " name="cliente_id" id="cliente_id" required>
                        <option value="-1" selected>TODOS</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" data-micliente="{{ $cliente->nombre }}">
                                {{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">PRODUCTO</label>
                    <select class="form-select select2 " name="producto" id="producto">
                        <option value="-1" selected>TODOS</option>
                        @foreach ($productos as $item)
                            <option value="{{ $item->id }}" data-miproducto="{{ $item->nombre }}">{{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped" id="mitablaprod" name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>
                                <th>CLIENTE</th>
                                <th>PRODUCTO</th> 
                                <th>PRECIO</th> 
                                <th>MONEDA</th>

                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                            <tr></tr>
                        </Tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script type="text/javascript">
        var contini = 0;
        var mitituloexcel = "";
        $(document).ready(function() {
            $('.select2').select2({});
           
            mititulo();
            traerdatos();

        });

        
        function traerdatos() {
            var cliente = document.getElementById("cliente_id").value;
            var producto = document.getElementById("producto").value;

            var urldatosproductos = "{{ url('admin/reporte/listapreciosespeciales') }}";
            $.get(urldatosproductos + '/'  + cliente + '/' + producto, function(data) {
                llenartabla(data);
            });
        }

        function llenartabla(datos) {
            var btns = 'lBfrtip';
            var tabla = '#mitablaprod';
            if (contini > 0) {
                $("#mitablaprod").dataTable().fnDestroy(); //eliminar las filas de la tabla  
            }
            $('#mitablaprod tbody tr').slice().remove();
            for (var i = 0; i < datos.length; i++) {
                filaDetalle =
                    '<tr><td> ' + datos[i].cliente + '</td>' +
                    '<td> ' + datos[i].producto + '</td>' + 
                    '<td> ' + datos[i].preciounitariomo + '</td>' + 
                    '<td> ' + datos[i].moneda + '</td>' +
                    '</tr>';
                $("#mitablaprod>tbody").append(filaDetalle);
            }
            inicializartabladatos2(btns, tabla, mitituloexcel);
            contini++;
        }
        $("#cliente_id").change(function() {
            var cliente = $(this).val();
            mititulo();
            traerdatos();
        });

        $("#producto").change(function() {
            var tipografico = $(this).val();
            mititulo();
            traerdatos();
        });

        function mititulo() {
            var idcliente_id = document.getElementById("cliente_id").value;
            var idproducto = document.getElementById("producto").value;
            var cliente_id = document.getElementById("cliente_id");
            var producto = document.getElementById("producto");
            if (idcliente_id != "-1") {
                if (idproducto != "-1") {
                    var namecliente_id = cliente_id[cliente_id.selectedIndex].getAttribute('data-micliente');
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Precios especiales_' + namecliente_id + '_' + nameproducto ; 
                } else {
                    var namecliente_id = cliente_id[cliente_id.selectedIndex].getAttribute('data-micliente');
                    mitituloexcel = 'Precios especiales_' + namecliente_id ; 
                }
            } else {
                if (idproducto != "-1") {
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Precios especiales_' + nameproducto ;
                } else {
                    mitituloexcel = 'Precios especiales';
                }
            }
        }
    </script>
@endpush
