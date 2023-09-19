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
                    <h4>EDITAR STOCKS DE LOS PRODUCTOS
                        <a href="{{ url('admin/inventario') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/inventario/' . $inventario->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label is-required">PRODUCTO</label>
                                <select class="form-select select2 " name="product_id" required>
                                    <option value="" disabled>Seleccione una opción</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $product->id == $inventario->product_id ? 'selected' : '' }}>
                                            {{ $product->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">STOCK MINIMO</label>
                                <input type="number" name="stockminimo" value="{{ $inventario->stockminimo }}"
                                    class="form-control " required />
                                @error('stockminimo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">STOCK TOTAL</label>
                                <input type="number" name="stocktotal" id="stocktotal"
                                    value="{{ $inventario->stocktotal }}" readonly class="form-control" />

                            </div>
                            <hr>
                            <h5>Agregar Detalle de Inventario</h5>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">EMPRESA</label>
                                <select class="form-select select2 " name="empresa" id="empresa">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        @php $conte=0; @endphp
                                        @foreach ($detalleinventario as $detalle)
                                            @if ($detalle->idcompany == $company->id)
                                                @php $conte++; @endphp
                                            @endif
                                        @endforeach
                                        @if ($conte == 0)
                                            <option id="micompany{{ $company->id }}" value="{{ $company->id }}"
                                                data-name="{{ $company->nombre }}">
                                                {{ $company->nombre }}</option>
                                        @else
                                            <option disabled id="micompany{{ $company->id }}" value="{{ $company->id }}"
                                                data-name="{{ $company->nombre }}">
                                                {{ $company->nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">STOCK POR EMPRESA</label>
                                <input type="number" name="stockempresa" id="stockempresa" class="form-control " />
                                @error('stockempresa')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            @php $ind=0 ; @endphp
                            @php $indice=count($detalleinventario) ; @endphp
                            <button type="button" class="btn btn-info" id="addDetalleBatch"
                                onclick="agregarFila('{{ $indice }}')"><i class="fa fa-plus"></i> Agregar Stock por
                                Empresa</button>
                            <div class="table-responsive">
                                <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesCompra">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>EMPRESA</th>
                                            <th>STOCK POR EMPRESA</th>
                                            {{-- <th>ELIMINAR</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detalleinventario as $detalle)
                                            @php $ind++; @endphp
                                            <tr id="fila{{ $ind }}">
                                                <td> <input type="hidden" value="{{ $detalle->iddetalleinventario }}"
                                                        name="Lids[]" />
                                                    <input type="hidden" name="Lempresa[]"
                                                        value="{{ $detalle->idcompany }}" /> {{ $detalle->nombre }}
                                                </td>
                                                <td><input type="number" class="form-control" name="Lstockempresa[]"
                                                        id="stockempresa{{ $ind }}" onchange="fstocktotal();"
                                                        value="{{ $detalle->stockempresa }}" /> </td>
                                                 

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Actualizar</button>
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
        var pv = 0;
        var numeroempresas = 0;
        var tabla = document.getElementById(detallesCompra);

        function fstocktotal() {
            stocktotal = 0;
            const stockempresas = document.querySelectorAll(`[name="Lstockempresa[]"]`);
            stockempresas.forEach(
                element => stocktotal += parseInt(element.value)
            );
            document.getElementById("stocktotal").value = stocktotal;
        }

        function agregarFila(indice1) {

            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }

            //datos del detalleSensor
            var empresa = $('[name="empresa"]').val();
            var stockempresa = $('[name="stockempresa"]').val();


            //alertas para los detallesBatch
            if (!empresa) {
                alert("Seleccione una empresa");
                return;
            }
            if (!stockempresa) {
                alert("ingrese un stockempresa");
                return;
            }
            var LDInventario = [];
            var tam = LDInventario.length;
            LDInventario.push(empresa, stockempresa, nameempresa);

            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lempresa[]" value="' + LDInventario[0] + '" required>' +
                LDInventario[2] +
                '</td><td> <input type="hidden" name="Lids[]" value="-1" />  <input  type="number" class="form-control" name="Lstockempresa[]" id="stockempresa' +
                indice + '" value="' + LDInventario[1] +
                '" required onchange="fstocktotal();" /> </td></tr>';

            $("#detallesCompra>tbody").append(filaDetalle);
            $('#empresa').val(null).trigger('change');
            indice++;
            var mistocktotal = $('[name="stocktotal"]').val();
            stocktotal = parseInt(stockempresa) + parseInt(mistocktotal);
            document.getElementById('stocktotal').value = stocktotal;
            document.getElementById('stockempresa').value = null;
            document.getElementById('micompany' + empresa).disabled = true;
        };
        $("#empresa").change(function() {

            $("#empresa option:selected").each(function() {
                $named = $(this).data("name");
                nameempresa = $named;
                document.getElementById('stockempresa').value = 1;
                //alert(nameempresa);
            });
        });

        // function eliminarFila(idBD, ind, stockemp, idempresa) {
        //     var miurl = "{{ url('admin/deletedetalleinventario') }}";
        //     $.get(miurl + '/' + idBD, function(data) {
        //         $('#fila' + ind).remove();
        //         var stocktotal2 = 0;

        //         //document.getElementById('preciot' + ind).value();
        //         stocktotal2 = $('[id="stocktotal"]').val();
        //         //alert(resta);
        //         stocktotal2 = stocktotal2 - stockemp;
        //         document.getElementById('stocktotal').value = stocktotal2;
        //         stocktotal = stocktotal2;
        //         document.getElementById('micompany' + idempresa).disabled = false;
        //     });
        // }

        // function quitarFila(ind, empresa) {

        //     var resta = 0;

        //     //document.getElementById('preciot' + ind).value();
        //     resta = $('[id="stockempresa' + ind + '"]').val();
        //     //alert(resta);
        //     stocktotal = stocktotal - resta;

        //     $('#fila' + ind).remove();
        //     indice--;
        //     // damos el valor
        //     document.getElementById('stocktotal').value = stocktotal;
        //     //alert(resta);
        //     document.getElementById('micompany' + empresa).disabled = false;
        //     return false;

        // }

        $(document).ready(function() {
            $('.select2').select2({});
        });
    </script>
@endpush
