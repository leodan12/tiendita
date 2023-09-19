@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>AÑADIR STOCKS DE LOS PRODUCTOS
                        <a href="{{ url('admin/inventario') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/inventario') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label is-required">PRODUCTO</label>
                                <select class="form-select select2 " name="product_id" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">STOCK MINIMO</label>
                                <input type="number" name="stockminimo" id="stockminimo" class="form-control " required />
                                @error('stockminimo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">STOCK TOTAL</label>
                                <input type="number" name="stocktotal" id="stocktotal" readonly class="form-control "
                                    required />

                            </div>
                            <hr>
                            <h5>Agregar Detalle de Inventario</h5>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">EMPRESA</label>
                                <select class="form-select select2 " name="empresa" id="empresa">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option id="micompany{{ $company->id }}" value="{{ $company->id }}"
                                            data-name="{{ $company->nombre }}">
                                            {{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">STOCK POR EMPRESA</label>
                                <input type="number" name="stockempresa" step="1" min="0" id="stockempresa"
                                    class="form-control " />
                                @error('stockempresa')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="button" class="btn btn-info" id="addDetalleBatch"><i class="fa fa-plus"></i>
                                Agregar Stock por Empresa</button>
                            <div class="table-responsive">
                                <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesCompra">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>EMPRESA</th>
                                            <th>STOCK POR EMPRESA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('script')
    <script type="text/javascript">
        var indice = 0;
        var nameempresa = 0;
        var stocktotal = 0;
        var estadoguardar = 0;
        var tabla = document.getElementById(detallesCompra);

        document.getElementById('stockminimo').value = 1;

        $('#addDetalleBatch').click(function() {

            //datos del detalleSensor
            var empresa = $('[name="empresa"]').val();
            var stockempresa = $('[name="stockempresa"]').val();


            //alertas para los detallesBatch

            if (!empresa) {
                alert("Seleccione una empresa");
                return;
            }
            if (!stockempresa) {
                alert("ingrese un stock para la empresa");
                return;
            }
            var LDInventario = [];
            var tam = LDInventario.length;
            LDInventario.push(empresa, stockempresa, nameempresa);

            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lempresa[]" value="' + LDInventario[0] + '"required>' +
                LDInventario[2] +
                '</td><td><input  type="number" class="form-control" name="Lstockempresa[]" id="stockempresa' +
                indice + '" value="' + LDInventario[1] +
                '" required onchange="fstocktotal();" > </td></tr>';

            $("#detallesCompra>tbody").append(filaDetalle);
            $('#empresa').val(null).trigger('change');
            document.getElementById('stockempresa').value = "";
            indice++;
            stocktotal = parseInt(stocktotal) + parseInt(stockempresa);
            document.getElementById('stocktotal').value = stocktotal;
            document.getElementById('micompany' + empresa).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        });
        $("#empresa").change(function() {

            $("#empresa option:selected").each(function() {
                $named = $(this).data("name");
                nameempresa = $named;
                document.getElementById('stockempresa').value = 1;
                //alert(nameempresa);
            });
        });

        function fstocktotal() {
            stocktotal=0;
            const stockempresas = document.querySelectorAll(`[name="Lstockempresa[]"]`);
            stockempresas.forEach(
                element => stocktotal+=parseInt(element.value)
                );
            document.getElementById("stocktotal").value = stocktotal;
        }
 


        $(document).ready(function() {
            $('.select2').select2();
            $("#btnguardar").prop("disabled", true);
        });

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar == 0) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar > 0) {
                $("#btnguardar").prop("disabled", false);
            }
        }
    </script>
@endpush
