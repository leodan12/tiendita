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
                    <h4>AÑADIR PRODUCCION DE UN CARRO
                        <a href="{{ url('admin/produccioncarro') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/produccioncarro') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas"
                                    required />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">NUMERO DE CARROS</label>
                                <input type="number" name="cantidadcarros" id="cantidadcarros" class="form-control "
                                    step="1" min="1" value="1" required />
                                @error('cantidadcarros')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">DESCUENTO</label>
                                <input type="number" name="descuento" id="descuento" class="form-control " step="0.01"
                                    min="0" value="0" required />
                                @error('descuento')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">CARROCERIA</label>
                                <select class="form-select" name="carroceria_id" id="carroceria_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($carrocerias as $carroceria)
                                        <option value="{{ $carroceria->id }}">{{ $carroceria->tipocarroceria }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">MODELO</label>
                                <select class="form-select" name="modelo_id" id="modelo_id" required disabled>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->id }}">{{ $modelo->modelo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TODO ENVIADO</label>
                                <select class="form-select" name="todoenviado" id="todoenviado" required>
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="NO" selected>NO</option>
                                    <option value="SI">SI</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">FACTURADO</label>
                                <select class="form-select" name="facturado" id="facturado" required>
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="NO" selected>NO</option>
                                    <option value="SI">SI</option>
                                </select>
                            </div>
                            <br>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <nav class="" style="border-radius: 5px; ">
                                        <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">

                                            <button class="nav-link active" id="nav-detalles-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-detalles" type="button" role="tab"
                                                aria-controls="nav-detalles" aria-selected="false">Materiales</button>
                                            <button class="nav-link " id="nav-redes-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-redes" type="button" role="tab"
                                                aria-controls="nav-redes" aria-selected="false">Redes</button>
                                            <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-condiciones" type="button" role="tab"
                                                aria-controls="nav-condiciones" aria-selected="false">Carros</button>
                                        </div>
                                    </nav>
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                            aria-labelledby="nav-detalles-tab" tabindex="0">
                                            <br>
                                            <h4>Agregar Material </h4>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label id="labelproducto" class="form-label">PRODUCTO</label>
                                                    <select class="form-select select2 " name="product" id="product"
                                                        disabled>
                                                        <option value="" disabled selected>Seleccione una opción
                                                        </option>
                                                        @foreach ($productos as $product)
                                                            <option id="miproducto{{ $product->id }}"
                                                                name="mioptionproduct" value="{{ $product->id }}"
                                                                data-name="{{ $product->nombre }}"
                                                                data-unidad="{{ $product->unidad }}"
                                                                data-tipo="{{ $product->tipo }}">
                                                                {{ $product->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                                    <input type="number" name="cantidad" id="cantidad" min="1"
                                                        step="1" class="form-control " />
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelempresa">TIPO</label>
                                                        <select class="form-select" name="tipomaterial"
                                                            id="tipomaterial">
                                                            <option value="" disabled>Seleccione una opción</option>
                                                            <option value="E">Electrico</option>
                                                            <option value="A" selected>Adicional</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelempresa">EMPRESA</label>
                                                        <input type="hidden" name="enviado" id="enviado" readonly
                                                            min="0" step="1" class="form-control " />
                                                        <select class="form-select" name="empresa" id="empresa">
                                                            <option value="" disabled>Seleccione una opción</option>
                                                            @foreach ($empresas as $empresa)
                                                                <option value="{{ $empresa->id }}"
                                                                    data-nombreempresa="{{ $empresa->nombre }}">
                                                                    {{ $empresa->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <br>
                                                    <button type="button" class="btn btn-info" id="addDetalleBatch"
                                                        style="width: 100%;"> Agregar Material Adicional</button><br>
                                                </div>
                                                <div class="table-responsive">
                                                    <table style="width: 100%;">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style="width: 10%;">
                                                                    ENVIADO</th>
                                                                <th style="border-left: solid 1px silver; width: 7%;">
                                                                    CANTIDAD</th>
                                                                <th style="border-left: solid 1px silver; width: 42%;">
                                                                    PRODUCTO</th>
                                                                <th style="border-left: solid 1px silver; width: 13%;">
                                                                    EMPRESA</th>
                                                                <th style="border-left: solid 1px silver; width: 17%;">
                                                                    OBSERVACIONES</th>
                                                                <th style="border-left: solid 1px silver; width: 10%;">
                                                                    ELIMINAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="6">
                                                                    <div class="accordion" id="accordion1"
                                                                        style="width: 100%;">
                                                                        <div class="accordion-item" style="width: 100%;">
                                                                            <h2 class="accordion-header" id="headingOne">
                                                                                <button class="accordion-button"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseOne">
                                                                                    Material Electrico
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseOne" style="width: 100%;"
                                                                                class="accordion-collapse collapse show">
                                                                                <div class="accordion-body"
                                                                                    style="width: 100%;">
                                                                                    <table id="detallesmaterial"
                                                                                        style="width: 100%;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="6">
                                                                    <div class="accordion" id="accordion2"
                                                                        style="width: 100%;">
                                                                        <div class="accordion-item" style="width: 100%;">
                                                                            <h2 class="accordion-header" id="headingTwo">
                                                                                <button class="accordion-button"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseTwo">
                                                                                    Material Adicional
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseTwo"
                                                                                class="accordion-collapse collapse show"
                                                                                style="width: 100%;">
                                                                                <div class="accordion-body"
                                                                                    style="width: 100%;">
                                                                                    <table id="detallesmaterialadicional"
                                                                                        style="width: 100%;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade  " id="nav-redes" role="tabpanel"
                                            aria-labelledby="nav-redes-tab" tabindex="0">
                                            <br>
                                            <h4>Agregar Redes </h4>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label id="labelred" class="form-label">REDES</label> <br>
                                                    <select class="form-select select2 " name="red" id="red"
                                                        disabled style="width: 100%;">
                                                        <option value="" disabled selected>Seleccione una opción
                                                        </option>
                                                        @foreach ($redes as $red)
                                                            <option id="mired{{ $red->idproducto }}" name="mioptionred"
                                                                value="{{ $red->idproducto }}"
                                                                data-name="{{ $red->producto }}"
                                                                data-unidad="{{ $red->unidad }}"
                                                                data-tipo="{{ $red->tipo }}">
                                                                {{ $red->producto }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <label class="form-label" id="labelcantidadred">CANTIDAD</label>
                                                    <input type="number" name="cantidadred" id="cantidadred"
                                                        min="1" step="1" class="form-control " />
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelempresa">TIPO</label>
                                                        <select class="form-select" name="tipored" id="tipored">
                                                            <option value="" disabled>Seleccione una opción</option>
                                                            <option value="E">Estandar</option>
                                                            <option value="A" selected>Adicional</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelunidadred">EMPRESA</label>
                                                        <input type="hidden" name="enviadored" id="enviadored" readonly
                                                            min="0" step="1" class="form-control " />
                                                        <select class="form-select" name="empresared" id="empresared">
                                                            <option value="" disabled>Seleccione una opción</option>
                                                            @foreach ($empresas as $empresa)
                                                                <option value="{{ $empresa->id }}"
                                                                    data-nombreempresa="{{ $empresa->nombre }}">
                                                                    {{ $empresa->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <br>
                                                    <button type="button" class="btn btn-info" id="addDetalleRed"
                                                        style="width: 100%;"> Agregar Red Adicional</button><br>
                                                </div>
                                                <div class="table-responsive">
                                                    <table style="width: 100%;">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style="width: 10%;">
                                                                    ENVIADO</th>
                                                                <th style="border-left: solid 1px silver; width: 7%;">
                                                                    CANTIDAD</th>
                                                                <th style="border-left: solid 1px silver; width: 42%;">
                                                                    RED</th>
                                                                <th style="border-left: solid 1px silver; width: 13%;">
                                                                    EMPRESA</th>
                                                                <th style="border-left: solid 1px silver; width: 17%;">
                                                                    OBSERVACIONES</th>
                                                                <th style="border-left: solid 1px silver; width: 10%;">
                                                                    ELIMINAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="6">
                                                                    <div class="accordion" id="accordion1"
                                                                        style="width: 100%;">
                                                                        <div class="accordion-item" style="width: 100%;">
                                                                            <h2 class="accordion-header"
                                                                                id="headingOneRed">
                                                                                <button class="accordion-button"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseOneRed">
                                                                                    Redes Estandar
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseOneRed" style="width: 100%;"
                                                                                class="accordion-collapse collapse show">
                                                                                <div class="accordion-body"
                                                                                    style="width: 100%;">
                                                                                    <table id="detallesred"
                                                                                        style="width: 100%;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="6">
                                                                    <div class="accordion" id="accordion2"
                                                                        style="width: 100%;">
                                                                        <div class="accordion-item" style="width: 100%;">
                                                                            <h2 class="accordion-header"
                                                                                id="headingTwoRed">
                                                                                <button class="accordion-button"
                                                                                    type="button"
                                                                                    data-bs-toggle="collapse"
                                                                                    data-bs-target="#collapseTwoRed">
                                                                                    Redes Adicionales
                                                                                </button>
                                                                            </h2>
                                                                            <div id="collapseTwoRed"
                                                                                class="accordion-collapse collapse show"
                                                                                style="width: 100%;">
                                                                                <div class="accordion-body"
                                                                                    style="width: 100%;">
                                                                                    <table id="detallesredadicional"
                                                                                        style="width: 100%;">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade  " id="nav-condiciones" role="tabpanel"
                                            aria-labelledby="nav-condiciones-tab" tabindex="0">
                                            <br>
                                            <h4>Agregar Carros </h4>
                                            <div class="row">

                                                <div class="table-responsive">
                                                    <table class="mitable table-row-bordered gy-5 gs-5" id="tablacarros"
                                                        style="width: 100%; overflow-x: auto;"> {{-- 1620px --}}
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style="width: 110px;">NRO DE OP</th>
                                                                <th style="border-left: solid 1px silver; width: 110px;">
                                                                    NRO CHASIS</th>
                                                                <th style="border-left: solid 1px silver; width: 100px;">%
                                                                    DESCUENTO</th>
                                                                <th style="border-left: solid 1px silver; width: 100px;">
                                                                    BONO
                                                                </th>
                                                                <th style="border-left: solid 1px silver; width: 100px;">
                                                                    MES DE BONO</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3"> <br>
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end" disabled>Guardar</button>
                            </div>
                        </div>
                    </form>
                    <div class="toast-container position-fixed bottom-0 start-0 p-2" style="z-index: 1000">
                        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true"
                            data-bs-autohide="false" style="width: 100%; box-shadow: 0 2px 5px 2px rgb(0, 89, 255); ">
                            <div class="  card-header">
                                <i class="mdi mdi-information menu-icon"></i>
                                <strong class="mr-auto"> &nbsp; Productos que incluye el kit:</strong>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <table id="detalleskit">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>CANTIDAD</th>
                                            <th>PRODUCTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        var indice = 0;
        var nameproduct = 0;
        var estadoguardar = 0;
        var indicehp = 0;
        var indicecondicion = 0;
        var tipoproducto = "";
        var unidad = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var misproductos;
        var idcliente = 0;
        var carroceria = 0;
        var modelo = 0;
        var indicec = 0;
        var cantidadcarros = 1;
        var numeroproductos = @json(count($productos));
        var hoy = new Date();
        var idempresa = 0;
        var nombreempresa = "";
        var numeroproductoskit = 0;
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');

        $(document).ready(function() {
            $('.toast').toast();
            $('.select2').select2({});
            $("#btnguardar").prop("disabled", true);
            agregarcarro(1);
            document.querySelectorAll(".mayusculas").forEach(input => input.addEventListener("input", () =>
                input.value = input.value.toUpperCase()));
            // Obtén una referencia al elemento select
            var selectElement = document.getElementById("empresa");
            var textoBuscado = "ELECTROBUS";
            for (var i = 0; i < selectElement.options.length; i++) {
                var option = selectElement.options[i];
                if (option.text.toLowerCase().includes(textoBuscado.toLowerCase())) {
                    selectElement.selectedIndex = i;
                    break;
                }
            }
            var selectElementR = document.getElementById("empresared");
            var textoBuscadoR = "ELECTROBUS";
            for (var i = 0; i < selectElementR.options.length; i++) {
                var optionR = selectElementR.options[i];
                if (optionR.text.toLowerCase().includes(textoBuscadoR.toLowerCase())) {
                    selectElementR.selectedIndex = i;
                    break;
                }
            }
        });
        $("#tipomaterial").change(function() {
            var tipomaterial = $(this).val();
            if (tipomaterial == 'E') {
                var valorDeseado = "ELECTROBUS";
                var empresa = document.getElementById("empresa");
                for (var i = 0; i < empresa.options.length; i++) {
                    if (empresa.options[i].text.indexOf(valorDeseado) !== -1) {
                        empresa.options[i].selected = true;
                    } else {
                        empresa.options[i].disabled = true;
                    }
                }
            } else {
                var empresa = document.getElementById("empresa");
                for (var i = 0; i < empresa.options.length; i++) {
                    empresa.options[i].disabled = false;
                }
            }
        });
        $("#tipored").change(function() {
            var tipored = $(this).val();
            if (tipored == 'E') {
                var valorDeseado = "ELECTROBUS";
                var empresa = document.getElementById("empresared");
                for (var i = 0; i < empresa.options.length; i++) {
                    if (empresa.options[i].text.indexOf(valorDeseado) !== -1) {
                        empresa.options[i].selected = true;
                    } else {
                        empresa.options[i].disabled = true;
                    }
                }
            } else {
                var empresa = document.getElementById("empresared");
                for (var i = 0; i < empresa.options.length; i++) {
                    empresa.options[i].disabled = false;
                }
            }
        });

        $("#carroceria_id").change(function() {
            $("#carroceria_id option:selected").each(function() {
                var carroceria_id = $(this).val();
                carroceria = carroceria_id;
                $('#modelo_id').removeAttr('disabled');
            });
        });
        $("#modelo_id").change(function() {
            $("#modelo_id option:selected").each(function() {
                var modelo_id = $(this).val();
                modelo = modelo_id;
                $('#product').removeAttr('disabled');
                $('#red').removeAttr('disabled');
                if (modelo_id) {
                    habilitarProductos();
                    traermaterialcarroceria();
                    traerredes();
                }
            });
        });
        $("#cantidadcarros").change(function() {
            var cantidad = $(this).val();
            cantidadcarros = cantidad;
            indicec = 0;
            if (cantidadcarros <= 0) {
                document.getElementById('cantidadcarros').value = 1;
                cantidadcarros = 1;
            }
            $('#tablacarros tbody tr').slice().remove();
            var cantidadc = document.getElementById('cantidadcarros').value;
            for (var i = 1; i <= cantidadc; i++) {
                agregarcarro(i);
            }
        });
        $("#cantidad").change(function() {
            var cantidad = $(this).val();
        });

        function traermaterialcarroceria() {
            var selectE = document.getElementById("empresa");
            var idempresax = selectE.value;
            var empresanombre = selectE.options[selectE.selectedIndex].getAttribute("data-nombreempresa");
            var urlmaterial = "{{ url('admin/produccioncarro/materialcarroceria') }}";
            $.get(urlmaterial + '/' + carroceria + '/' + modelo, function(data) {
                $('#detallesmaterial tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    var milista = '<br>';
                    var puntos = '';
                    var LVenta = [];
                    tipoproducto = data[i].tipo;
                    idproducto = data[i].producto_id;
                    LVenta.push(data[i].producto_id, data[i].producto, data[i].cantidad, 0, idempresax,
                        empresanombre);
                    if (tipoproducto == "kit") {
                        puntos = ': ';
                        var urlkit = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlkit + '/' + idproducto,
                            async: false,
                            success: function(datak) {
                                for (var k = 0; k < datak.length; k++) {
                                    var coma = '<br>';
                                    if (k + 1 == datak.length) {
                                        coma = '';
                                    }
                                    milista = milista + '-' + datak[k].cantidad + ' ' + datak[k]
                                        .producto + coma;
                                }
                                agregarFilasTabla(LVenta, puntos, milista, data[i].producto_id,
                                    "detallesmaterial", "E", "m", "no");
                            }
                        });
                    } else {
                        agregarFilasTabla(LVenta, puntos, milista, data[i].producto_id, "detallesmaterial", "E",
                            "m", "no");
                    }
                }
            });
        }

        function traerredes() {
            var selectE = document.getElementById("empresared");
            var idempresax = selectE.value;
            var empresanombre = selectE.options[selectE.selectedIndex].getAttribute("data-nombreempresa");
            var urlmaterial = "{{ url('admin/produccioncarro/redes') }}";
            $.get(urlmaterial + '/' + carroceria + '/' + modelo, function(data) {
                $('#detallesred tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    var milista = '<br>';
                    var puntos = '';
                    var LVenta = [];
                    tipoproducto = data[i].tipo;
                    idproducto = data[i].producto_id;
                    LVenta.push(data[i].producto_id, data[i].producto, data[i].cantidad, 0, idempresax,
                        empresanombre);
                    if (tipoproducto == "kit") {
                        puntos = ': ';
                        var urlkit = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlkit + '/' + idproducto,
                            async: false,
                            success: function(datak) {
                                for (var k = 0; k < datak.length; k++) {
                                    var coma = '<br>';
                                    if (k + 1 == datak.length) {
                                        coma = '';
                                    }
                                    milista = milista + '-' + datak[k].cantidad + ' ' + datak[k]
                                        .producto + coma;
                                }
                                agregarFilasTabla(LVenta, puntos, milista, data[i].producto_id,
                                    "detallesred", "E", "redC", "no");
                            }
                        });
                    } else {
                        agregarFilasTabla(LVenta, puntos, milista, data[i].producto_id, "detallesred", "E", "redC",
                            "no");
                    }
                }
            });
        }

        $("#product").change(function() {
            $("#product option:selected").each(function() {
                var miproduct = $(this).val();
                if (miproduct) {
                    $named = $(this).data("name");
                    $tipo = $(this).data("tipo");

                    idproducto = miproduct;
                    tipoproducto = $tipo;
                    //mostramos la notificacion
                    numeroproductoskit = 0;
                    if ($tipo == "kit") {
                        document.getElementById('labelproducto').innerHTML =
                            "PRODUCTO TIPO KIT";
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $('#detalleskit tbody tr').slice().remove();
                        $.get(urlventa + '/' + miproduct, function(data) {
                            var filasconcantidad = "";
                            var filasconcero = "";
                            for (var i = 0; i < data.length; i++) {
                                var fondo = "white";
                                if (data[i].cantidad == 0) {
                                    fondo = "#ff000095";
                                }
                                numeroproductoskit++;
                                filaDetalle =
                                    '<tr style="border-top: 1px solid silver; background-color: ' +
                                    fondo + ';" id="filatoast' + i +
                                    '" ><td><input type="number" id="cantidadproductokit' +
                                    i + '"  min="0" value="' + data[i].cantidad +
                                    '" style="width:70px;" onchange="cantidadproductocero(' +
                                    i + ');" /></td><td><input type="hidden" id="idproductokit' +
                                    i + '" value="' + data[i].id +
                                    '"/><input type="hidden" id="nombreproductokit' +
                                    i + '" value="' + data[i].producto + '"/>' + data[i].producto +
                                    '</td></tr>';
                                if (data[i].cantidad != 0) {
                                    filasconcantidad += filaDetalle;
                                } else {
                                    filasconcero += filaDetalle;
                                }
                            }
                            $("#detalleskit>tbody").append(filasconcantidad);
                            $("#detalleskit>tbody").append(filasconcero);
                        });
                        $('.toast').toast('show');
                    } else {
                        $('.toast').toast('hide');
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                    }

                    if ($named != null) {
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('enviado').value = 0;
                        nameproduct = $named;
                    } else if ($named == null) {
                        document.getElementById('cantidad').value = 1;
                        document.getElementById('enviado').value = 0;
                    }

                }
            });
        });

        function cantidadproductocero(itoast) {
            var cantidadtoast = document.getElementById("cantidadproductokit" + itoast).value;
            var fila = document.getElementById("filatoast" + itoast);
            if (cantidadtoast == 0) {
                fila.style.backgroundColor = "#ff000095";
            } else {
                fila.style.backgroundColor = "white";
            }
        }

        $("#red").change(function() {
            $("#red option:selected").each(function() {
                var mired = $(this).val();
                if (mired) {
                    $named = $(this).data("name");
                    $tipo = $(this).data("tipo");
                    //$unidad = $(this).data("unidad");

                    idproducto = mired;
                    tipoproducto = $tipo;
                    //mostramos la notificacion
                    if ($tipo == "kit") {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.get(urlventa + '/' + mired, function(data) {
                            $('#detalleskit tbody tr').slice().remove();
                            for (var i = 0; i < data.length; i++) {
                                filaDetalle =
                                    '<tr style="border-top: 1px solid silver;" id="fila' +
                                    i + '"><td> ' + data[i].cantidad +
                                    '</td> <td> ' + data[i].producto + '</td></tr>';
                                $("#detalleskit>tbody").append(filaDetalle);
                            }
                        });
                        $('.toast').toast('show');
                    }

                    if ($tipo == "estandar") {
                        $('.toast').toast('hide');
                        document.getElementById('labelred').innerHTML = "PRODUCTO";
                    } else if ($tipo == "kit") {
                        document.getElementById('labelred').innerHTML =
                            "PRODUCTO TIPO KIT";
                    }

                    if ($named != null) {
                        document.getElementById('cantidadred').value = 1;
                        document.getElementById('enviadored').value = 0;
                        nameproduct = $named;
                    } else if ($named == null) {
                        document.getElementById('cantidadred').value = 1;
                        document.getElementById('enviadored').value = 0;
                    }

                }
            });
        });

        $('#addDetalleBatch').click(function() {

            //datos del detalle
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var enviado = $('[name="enviado"]').val();
            var tipomaterial = $('[name="tipomaterial"]').val();

            var selectE = document.getElementById("empresa");
            var idempresax = selectE.value;
            var empresanombre = selectE.options[selectE.selectedIndex].getAttribute(
                "data-nombreempresa");

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!enviado) {
                alert("Ingrese una cantidad enviada");
                return;
            }

            var milista = '<br>';
            var puntos = '';
            var LVenta = [];
            var listaproductoskit = "";
            var tam = LVenta.length;
            LVenta.push(product, nameproduct, cantidad, enviado, idempresax, empresanombre);

            var mitabla = "detallesmaterialadicional";
            if (tipomaterial == "E") {
                mitabla = "detallesmaterial";
            }
            if (tipoproducto == "kit") {
                puntos = ': ';
                for (var i = 0; i < numeroproductoskit; i++) {
                    var cant = document.getElementById("cantidadproductokit" + i).value;
                    var nombre = document.getElementById("nombreproductokit" + i).value;
                    var id = document.getElementById("idproductokit" + i).value;
                    var coma = '<br>';
                    if (i + 1 == numeroproductoskit) {
                        coma = '';
                    }
                    if (cant > 0) {
                        milista = milista + '-' + cant + ' ' + nombre + coma;
                        listaproductoskit += '<input  type="hidden" name="Lidkit[]" value="' +
                            idproducto + '" /><input  type="hidden" name="Lcantidadproductokit[]" value="' +
                            cant + '" /><input  type="hidden" name="Lidproductokit[]" value="' +
                            id + '" />';
                    }
                }
                milista += listaproductoskit;

                agregarFilasTabla(LVenta, puntos, milista, product, mitabla, tipomaterial, "m", "si");
            } else {
                agregarFilasTabla(LVenta, puntos, milista, product, mitabla, tipomaterial, "m", "si");
            }
        });

        $('#addDetalleRed').click(function() {

            //datos del detalle
            var product = $('[name="red"]').val();
            var cantidad = $('[name="cantidadred"]').val();
            var enviado = $('[name="enviadored"]').val();
            var tipored = $('[name="tipored"]').val();
            var selectE = document.getElementById("empresared");
            var idempresax = selectE.value;
            var empresanombre = selectE.options[selectE.selectedIndex].getAttribute("data-nombreempresa");
            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione una Red");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una Cantidad");
                return;
            }
            if (!enviado) {
                alert("Ingrese una Cantidad Enviada");
                return;
            }

            var milista = '<br>';
            var puntos = '';
            var LVenta = [];
            LVenta.push(product, nameproduct, cantidad, enviado, idempresax, empresanombre);
            var mitabla = "detallesredadicional";
            if (tipored == "E") {
                mitabla = "detallesred";
            }
            if (tipoproducto == "kit") {
                puntos = ': ';
                var urlventa = "{{ url('admin/venta/productosxkit') }}";
                $.get(urlventa + '/' + idproducto, function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var coma = '<br>';
                        if (i + 1 == data.length) {
                            coma = '';
                        }
                        milista = milista + '-' + data[i].cantidad + ' ' + data[i].producto +
                            coma;
                    }
                    agregarFilasTabla(LVenta, puntos, milista, product, mitabla, tipored,
                        "redC", "si");
                });
            } else {
                agregarFilasTabla(LVenta, puntos, milista, product, mitabla, tipored, "redC", "si");
            }
        });

        function agregarFilasTabla(LDetalle, puntos, milista, product, tabla, tipomaterial, redz, deshabilitar) {
            var fondo = "#ffffff";
            if (indice % 2 == 0) {
                fondo = "#CFEFCA";
            }
            var redx = "";
            if (redz == "redC") {
                redx = "'redx'";
            }
            filaDetalle = '<tr id="fila' + indice + '" style="background-color:' + fondo + ';" >' +
                '<input type="hidden" name="Ltipo' + redz + '[]" value="' + tipomaterial + '" />' +
                '<td style="width: 8%;"><div class="input-group"><input min="0" readonly style="border: solid 1px silver; width:52%; background-color: ' +
                fondo + ';" class="input-group" type="number" name="Lcantidadenviada' + redz + '[]" id="enviado' + redz +
                indice + '" value="' + LDetalle[3] + '" max="' + parseInt(LDetalle[2]) * parseInt(cantidadcarros) +
                '" ><span style="width:48%;" class="input-group-text" id="spancantidadenviada' + redz +
                indice + '" >/' + parseInt(LDetalle[2]) * parseInt(cantidadcarros) +
                ' </span></div>  </td><td style="text-align: center; width: 9%;">' +
                '<input  type="hidden" name="Lcantidad' + redz + '[]" id="cantidad' + redz + indice + '" value="' +
                LDetalle[2] + '" >' + LDetalle[2] + '</td><td style="width:47%;"><input  type="hidden" name="Lproduct' +
                redz + '[]" value="' + LDetalle[0] + '"><b>' + LDetalle[1] + '</b>' + puntos + milista +
                '</td><td style="width:13%;"><input  type="hidden" name="Lempresa' +
                redz + '[]" value="' + LDetalle[4] + '" />' + LDetalle[5] +
                '</td><td style="width:19%;"><textarea style="font-size: 11px; width:100%; height:100%; background-color: ' +
                fondo + ';" name="Lobservacion' + redz + '[]" id="observacion' + redz + indice +
                '" rows="3" ></textarea></td><td style="width:8%;"><button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(' +
                indice + ',' + product + ',' + redx + ');" data-id="0">ELIMINAR</button></td></tr>';
            $("#" + tabla + ">tbody").append(filaDetalle);

            indice++;
            limpiarinputs();
            if (redz == "redC") {
                if (tipomaterial == "A" || deshabilitar == "si") {
                    try {
                        document.getElementById('mired' + product).disabled = true;
                    } catch (error) {}
                }
            } else {
                if (tipomaterial == "A" || deshabilitar == "si") {
                    try {
                        document.getElementById('miproducto' + product).disabled = true;
                    } catch (error) {}
                }
            }

            var funcion = "agregar";
            botonguardar(funcion);
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('cantidad').value = null;
            document.getElementById('enviado').value = "";
            $('#red').val(null).trigger('change');
            document.getElementById('cantidadred').value = null;
            document.getElementById('enviadored').value = "";
            $('.toast').toast('hide');
        }

        function eliminarFila(ind, idproductox, redx) {
            $('#fila' + ind).remove();
            //indice--;
            if (redx == "redx") {
                try {
                    document.getElementById('mired' + idproductox).disabled = false;
                } catch (error) {}

            } else {
                try {
                    document.getElementById('miproducto' + idproductox).disabled = false;
                } catch (error) {}
            }
            var funcion = "eliminar";
            botonguardar(funcion);
            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            //indice--;
            var funcion = "eliminar";
            botonguardar(funcion);
            nameproduct = 0;
            return false;
        }

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

        $("#descuento").change(function() {
            var descuento = $(this).val();
            for (var i = 0; i < indicec; i++) {
                document.getElementById('porcentajedescuento' + i).value = descuento;
            }
        });

        function agregarcarro(i) {
            //datos del detalle
            var descuento = document.getElementById("descuento").value;
            var milista = '<br>';
            var puntos = '';
            var LVenta = [];
            LVenta.push('OP-A' + i, '0' + i, descuento, 'NO', 'NO');
            agregarFilasTablaCarro(LVenta, puntos, milista, product);

        };


        function agregarFilasTablaCarro(LDetalle, puntos, milista, product) {
            var fondo = "#ffffff";
            if (indicec % 2 == 0) {
                fondo = "#CFEFCA";
            }
            filaDetalle = '<tr id="filac' + indicec + '" style="background-color: ' + fondo +
                ';" ><td><input class="form-control sinborde mayusculas" type="text" name="Lnumeroordenproduccion[]" value="' +
                LDetalle[0] + '" style="text-align: center; background-color: ' + fondo +
                ';" /></td><td><input class="form-control sinborde mayusculas" type="text" name="Lchasis[]" value="' +
                LDetalle[1] +
                '" style="text-align: center; background-color: ' + fondo +
                ';"/> </td><td><input class="form-control sinborde" type="number" min="0" step="0.01" name="Lporcentajedescuento[]" value="' +
                LDetalle[2] + '" id="porcentajedescuento' + indicec + '" style="text-align: center; background-color: ' +
                fondo +
                ';" /> </td><td><select class="form-select sinborde" name="Lbonificacion[]"  style="text-align: center; background-color: ' +
                fondo +
                ';"><option value="SI">SI</option><option value="NO"selected>NO</option></select></td><td><input class="form-control sinborde mayusculas" type="text" name="Lmesbonificacion[]" style="text-align: center; background-color: ' +
                fondo +
                ';"/> </td></tr>';
            $("#tablacarros>tbody").append(filaDetalle);
            indicec++;
        }

        function habilitarProductos() {
            const productos = document.querySelectorAll(`[name="mioptionproduct"]`);
            productos.forEach(element => element.disabled = false);
        }
    </script>
@endpush
