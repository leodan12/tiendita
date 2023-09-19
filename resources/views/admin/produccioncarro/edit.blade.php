@extends('layouts.admin')
@push('css')
    <style>
        .sinborde {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            @php  $detalles = count($materialcarros) @endphp
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
                    <h4>EDITAR PRODUCCION DE UN CARRO
                        <a href="{{ url('admin/produccioncarro') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/produccioncarro/' . $produccioncarro->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas"
                                    value="{{ $produccioncarro->nombre }}" required />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">NUMERO DE CARROS</label>
                                <input type="number" name="cantidadcarros" id="cantidadcarros" class="form-control "
                                    step="1" min="1" value="{{ $produccioncarro->cantidad }}" readonly
                                    required />
                                @error('cantidadcarros')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">DESCUENTO</label>
                                <input type="number" name="descuento" id="descuento" class="form-control " step="0.01"
                                    min="0" value="{{ $produccioncarro->descuento }}"
                                    onchange="actualizardescuento();" required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">ORDEN DE COMPRA</label>
                                <textarea class="mayusculas" style="font-size: 11px; width:100%; " name="ordencompra" id="ordencompra" rows="4">{{ $produccioncarro->ordencompra }}</textarea>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">CARROCERIA</label>
                                <select class="form-select select2  " name="carroceria_id" id="carroceria_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($carrocerias as $carroceria)
                                        <option value="{{ $carroceria->id }}"
                                            {{ $carroceria->id == $produccioncarro->carroceria_id ? 'selected' : 'disabled' }}>
                                            {{ $carroceria->tipocarroceria }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">MODELO</label>
                                <select class="form-select select2  " name="modelo_id" id="modelo_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->id }}"
                                            {{ $modelo->id == $produccioncarro->modelo_id ? 'selected' : 'disabled' }}>
                                            {{ $modelo->modelo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TODO ENVIADO</label>
                                <select class="form-select" name="todoenviado" id="todoenviado" required
                                    onchange="cambiotodoenviado();">
                                    <option value="" disabled>Seleccione una opción</option>
                                    @if ($produccioncarro->todoenviado == 'NO')
                                        <option value="NO" selected>NO</option>
                                        <option value="SI">SI</option>
                                    @else
                                        <option value="NO">NO</option>
                                        <option value="SI" selected>SI</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">FACTURADO</label>
                                <select class="form-select" name="facturado" id="facturado" required>
                                    <option value="" disabled>Seleccione una opción</option>
                                    @if ($produccioncarro->facturado == 'NO')
                                        <option value="NO" selected>NO</option>
                                        <option value="SI">SI</option>
                                    @else
                                        <option value="NO">NO</option>
                                        <option value="SI" selected>SI</option>
                                    @endif
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
                                                @php  $display="";  @endphp
                                                @if ($produccioncarro->todoenviado == 'SI')
                                                    @php  $display="none";  @endphp
                                                @else
                                                    @php  $display="inline";  @endphp
                                                @endif
                                                <div class="col-12" id="divmaterial"
                                                    style="display:{{ $display }}">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label id="labelproducto" class="form-label">PRODUCTO</label>
                                                            <select class="form-select select2 " name="product"
                                                                id="product">
                                                                <option value="" disabled selected>Seleccione una
                                                                    opción
                                                                </option>
                                                                @foreach ($productos as $product)
                                                                    @php $contp=0;    @endphp
                                                                    @foreach ($materialcarros as $item)
                                                                        @if ($product->id == $item->idproducto && $item->tipomaterial == 'A')
                                                                            @php $contp++;    @endphp
                                                                        @endif
                                                                    @endforeach

                                                                    @if ($contp == 0)
                                                                        <option id="miproducto{{ $product->id }}"
                                                                            value="{{ $product->id }}"
                                                                            data-name="{{ $product->nombre }}"
                                                                            data-unidad="{{ $product->unidad }}"
                                                                            data-tipo="{{ $product->tipo }}">
                                                                            {{ $product->nombre }}</option>
                                                                    @else
                                                                        <option id="miproducto{{ $product->id }}"
                                                                            value="{{ $product->id }}"
                                                                            data-name="{{ $product->nombre }}"
                                                                            data-unidad="{{ $product->unidad }}"
                                                                            data-tipo="{{ $product->tipo }}" disabled>
                                                                            {{ $product->nombre }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                                            <input type="number" name="cantidad" id="cantidad"
                                                                min="1" step="1" class="form-control " />
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <div class="input-group">
                                                                <label class="form-label input-group"
                                                                    id="labelempresa">TIPO</label>
                                                                <select class="form-select" name="tipomaterial"
                                                                    id="tipomaterial">
                                                                    <option value="" disabled>Seleccione una opción
                                                                    </option>
                                                                    <option value="E">Electrico</option>
                                                                    <option value="A" selected>Adicional</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <div class="input-group">
                                                                <label class="form-label input-group"
                                                                    id="labelunidad">EMPRESA</label>
                                                                <input type="hidden" name="enviado" id="enviado"
                                                                    readonly min="0" step="1"
                                                                    class="form-control " />
                                                                <select class="form-select" name="empresa"
                                                                    id="empresa">
                                                                    <option value="" disabled>Seleccione una opción
                                                                    </option>
                                                                    @foreach ($empresas as $empresa)
                                                                        <option value="{{ $empresa->id }}"
                                                                            data-nombreempresa="{{ $empresa->nombre }}">
                                                                            {{ $empresa->nombre }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        @php $ind=0 ; @endphp
                                                        @php $indice=count($materialcarros) ; @endphp
                                                        <button type="button" class="btn btn-info" id="addDetalleBatch"
                                                            onclick="agregarFila('{{ $indice }}')">
                                                            Agregar Material Adicional</button>
                                                    </div>
                                                </div>
                                                <div class=" table-container">
                                                    <br>
                                                    <table style="width: 100%;">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style="width: 10%;"> ENVIADO</th>
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
                                                                                    <div class="table-container">
                                                                                        <table id="detallesmaterial"
                                                                                            style="width: 100%;">
                                                                                            <tbody>
                                                                                                @php $datobd="db" ;  @endphp
                                                                                                @foreach ($materialcarros as $material)
                                                                                                    @if ($material->tipomaterial == 'E')
                                                                                                        @php
                                                                                                            $ind++;
                                                                                                            $fondo = '';
                                                                                                        @endphp
                                                                                                        @if ($ind % 2 == 0)
                                                                                                            @php  $fondo ="#CFEFCA"; @endphp
                                                                                                        @endif
                                                                                                        <tr id="fila{{ $ind }}"
                                                                                                            style="background-color:{{ $fondo }}; ">
                                                                                                            <td
                                                                                                                style="width: 8%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lidsm[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $material->idmaterialcarro }}" />
                                                                                                                <div
                                                                                                                    class="input-group">
                                                                                                                    <input
                                                                                                                        readonly
                                                                                                                        type="text"
                                                                                                                        style="width:52%; background-color:{{ $fondo }}; border:1px solid silver; padding-right: 0px !important;"
                                                                                                                        max="{{ $material->cantidad * $produccioncarro->cantidad }}"
                                                                                                                        data-cantidad="{{ $material->cantidad }}"
                                                                                                                        name="Lcantidadenviada[]"
                                                                                                                        id="enviadoMA{{ $material->idmaterialcarro }}"
                                                                                                                        class="MEenviado  "
                                                                                                                        value="{{ $material->cantidadenviada }}" />
                                                                                                                    <span
                                                                                                                        style="width:48%"
                                                                                                                        class="input-group-text sinborde">/{{ $material->cantidad * $produccioncarro->cantidad }}</span>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 9%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lcantidad[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $material->cantidad }}" />
                                                                                                                {{ $material->cantidad }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 47%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lproduct[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $material->idproducto }}" />
                                                                                                                <b> {{ $material->producto }}
                                                                                                                </b>
                                                                                                                @if ($material->tipo == 'kit')
                                                                                                                    : <br>
                                                                                                                    @foreach ($detalleskit as $kit)
                                                                                                                        @if ($material->idproducto == $kit->product_id)
                                                                                                                            -{{ $kit->cantidad }}
                                                                                                                            {{ $kit->producto }}
                                                                                                                            <br>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 13%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lempresa[]"
                                                                                                                    value="{{ $material->idempresa }}" />
                                                                                                                {{ $material->nombreempresa }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 19%;">
                                                                                                                <textarea name="Lobservacionm[]" id="observasion" rows="3" class="mayusculas"
                                                                                                                    style="font-size: 11px; width: 100%;  background-color:{{ $fondo }}; ">{{ $material->observacion }}</textarea>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 8%;">
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-sm btn-danger"
                                                                                                                    onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $material->idmaterialcarro }}', '{{ $material->idproducto }}','m' ,'si' )"
                                                                                                                    data-id="0">
                                                                                                                    ELIMINAR</button>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
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
                                                                                    <div class="table-container">
                                                                                        <table
                                                                                            id="detallesmaterialadicional"
                                                                                            style="width: 100%;">
                                                                                            <tbody>
                                                                                                @php $datobd="db" ;  @endphp
                                                                                                @foreach ($materialcarros as $material)
                                                                                                    @if ($material->tipomaterial == 'A')
                                                                                                        @php
                                                                                                            $ind++;
                                                                                                            $fondo = '';
                                                                                                        @endphp
                                                                                                        @if ($ind % 2 == 0)
                                                                                                            @php  $fondo ="#CFEFCA"; @endphp
                                                                                                        @endif
                                                                                                        <tr id="fila{{ $ind }}"
                                                                                                            style="background-color:{{ $fondo }}; ">
                                                                                                            <td
                                                                                                                style="width: 8%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lidsm[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $material->idmaterialcarro }}"
                                                                                                                    required />
                                                                                                                <div
                                                                                                                    class="input-group">
                                                                                                                    <input
                                                                                                                        readonly
                                                                                                                        type="text"
                                                                                                                        class="sinborde"
                                                                                                                        style="width:52%; background-color:{{ $fondo }}; border:1px solid silver; padding-right: 0px !important;"
                                                                                                                        max="{{ $material->cantidad * $produccioncarro->cantidad }}"
                                                                                                                        id="enviadoMA{{ $material->idmaterialcarro }}"
                                                                                                                        name="Lcantidadenviada[]"
                                                                                                                        value="{{ $material->cantidadenviada }}" />
                                                                                                                    <span
                                                                                                                        style="width:48%"
                                                                                                                        class="input-group-text sinborde">/{{ $material->cantidad * $produccioncarro->cantidad }}</span>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 9%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lcantidad[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $material->cantidad }}" />
                                                                                                                {{ $material->cantidad }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 47%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lproduct[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $material->idproducto }}" />
                                                                                                                <b> {{ $material->producto }}
                                                                                                                </b>
                                                                                                                @if ($material->tipo == 'kit')
                                                                                                                    : <br>
                                                                                                                    @foreach ($detalleskit as $kit)
                                                                                                                        @if ($material->idproducto == $kit->product_id)
                                                                                                                            -{{ $kit->cantidad }}
                                                                                                                            {{ $kit->producto }}
                                                                                                                            <br>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 13%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lempresa[]"
                                                                                                                    value="{{ $material->idempresa }}" />
                                                                                                                {{ $material->nombreempresa }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 19%;">
                                                                                                                <textarea name="Lobservacionm[]" id="observasion" rows="3" class="mayusculas"
                                                                                                                    style="font-size: 11px; width: 100%;  background-color:{{ $fondo }}; ">{{ $material->observacion }}</textarea>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 8%;">
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-sm btn-danger"
                                                                                                                    onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $material->idmaterialcarro }}', '{{ $material->idproducto }}','m' ,'no' )"
                                                                                                                    data-id="0">
                                                                                                                    ELIMINAR</button>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
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
                                                @php $display="";   @endphp
                                                @if ($produccioncarro->todoenviado == 'SI')
                                                    @php  $display="none";  @endphp
                                                @else
                                                    @php  $display="inline";  @endphp
                                                @endif
                                                <div class="col-12" id="divred"
                                                    style="display: {{ $display }};">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label id="labelred" class="form-label">REDES</label> <br>
                                                            <select class="form-select select2 " name="red"
                                                                id="red" style="width: 100%;">
                                                                <option value="" disabled selected>Seleccione una
                                                                    opción
                                                                </option>
                                                                @foreach ($redes as $red)
                                                                    @php $contr=0;    @endphp
                                                                    @foreach ($redcarros as $item)
                                                                        @if ($red->id == $item->idproducto && $item->tipored == 'A')
                                                                            @php $contr++;    @endphp
                                                                        @endif
                                                                    @endforeach
                                                                    @if ($contr == 0)
                                                                        <option id="mired{{ $red->idproducto }}"
                                                                            name="mioptionred"
                                                                            value="{{ $red->idproducto }}"
                                                                            data-name="{{ $red->producto }}"
                                                                            data-unidad="{{ $red->unidad }}"
                                                                            data-tipo="{{ $red->tipo }}">
                                                                            {{ $red->producto }}</option>
                                                                    @else
                                                                        <option id="mired{{ $red->idproducto }}"
                                                                            name="mioptionred"
                                                                            value="{{ $red->idproducto }}"
                                                                            data-name="{{ $red->producto }}"
                                                                            data-unidad="{{ $red->unidad }}"
                                                                            data-tipo="{{ $red->tipo }}" disabled>
                                                                            {{ $red->producto }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label class="form-label"
                                                                id="labelcantidadred">CANTIDAD</label>
                                                            <input type="number" name="cantidadred" id="cantidadred"
                                                                min="1" step="1" class="form-control " />
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <div class="input-group">
                                                                <label class="form-label input-group"
                                                                    id="labelempresa">TIPO</label>
                                                                <select class="form-select" name="tipored"
                                                                    id="tipored">
                                                                    <option value="" disabled>Seleccione una opción
                                                                    </option>
                                                                    <option value="E">Estandar</option>
                                                                    <option value="A" selected>Adicional</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <div class="input-group">
                                                                <label class="form-label input-group"
                                                                    id="labelunidadred">EMPRESA
                                                                </label>
                                                                <input type="hidden" name="enviadored" id="enviadored"
                                                                    readonly min="0" step="1"
                                                                    class="form-control " />
                                                                <select class="form-select" name="empresared"
                                                                    id="empresared">
                                                                    <option value="" disabled>Seleccione una opción
                                                                    </option>
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
                                                            <button type="button" class="btn btn-info"
                                                                id="addDetalleRed" style="width: 100%;"
                                                                onclick="agregarFilaRed(indice)"> Agregar
                                                                Red
                                                                Adicional</button><br>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <br>
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
                                                                                    <div class="table-container">
                                                                                        <table id="detallesred"
                                                                                            style="width: 100%;">
                                                                                            <tbody>
                                                                                                @php $datobd="db" ;  @endphp
                                                                                                @foreach ($redcarros as $red)
                                                                                                    @if ($red->tipored == 'E')
                                                                                                        @php
                                                                                                            $ind++;
                                                                                                            $fondo = '';
                                                                                                        @endphp
                                                                                                        @if ($ind % 2 == 0)
                                                                                                            @php  $fondo ="#CFEFCA"; @endphp
                                                                                                        @endif
                                                                                                        <tr id="fila{{ $ind }}"
                                                                                                            style="background-color:{{ $fondo }}; ">
                                                                                                            <td
                                                                                                                style="width: 8%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lidsred[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $red->idredcarro }}"
                                                                                                                    required />
                                                                                                                <div
                                                                                                                    class="input-group">
                                                                                                                    <input
                                                                                                                        readonly
                                                                                                                        type="text"
                                                                                                                        style="width:52%; background-color:{{ $fondo }}; border:1px solid silver; padding-right:0px !important;"
                                                                                                                        max="{{ $red->cantidad * $produccioncarro->cantidad }}"
                                                                                                                        id="enviadoRE{{ $red->idredcarro }}"
                                                                                                                        name="Lcantidadenviadared[]"
                                                                                                                        value="{{ $red->cantidadenviada }}" />
                                                                                                                    <span
                                                                                                                        style="width:48%"
                                                                                                                        class="input-group-text sinborde">/{{ $red->cantidad * $produccioncarro->cantidad }}</span>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 9%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lcantidadred[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $red->cantidad }}" />
                                                                                                                {{ $red->cantidad }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 47%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lproductred[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $red->idproducto }}" />
                                                                                                                <b> {{ $red->producto }}
                                                                                                                </b>
                                                                                                                @if ($red->tipo == 'kit')
                                                                                                                    : <br>
                                                                                                                    @foreach ($detalleskitredes as $kitred)
                                                                                                                        @if ($red->idproducto == $kitred->product_id)
                                                                                                                            -{{ $kitred->cantidad }}
                                                                                                                            {{ $kitred->producto }}
                                                                                                                            <br>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 13%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    value="{{ $red->idempresa }}" />
                                                                                                                {{ $red->nombreempresa }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 19%;">
                                                                                                                <textarea name="Lobservacionred[]" id="observasion" rows="3" class="mayusculas"
                                                                                                                    style="font-size: 11px; width: 100%;  background-color:{{ $fondo }}; ">{{ $red->observacion }}</textarea>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 8%;">
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-sm btn-danger"
                                                                                                                    onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $red->idredcarro }}', '{{ $red->idproducto }}' , 'red' ,'no')"
                                                                                                                    data-id="0">
                                                                                                                    ELIMINAR</button>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
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
                                                                                    <div class="table-container">
                                                                                        <table id="detallesredadicional"
                                                                                            style="width: 100%;">
                                                                                            <tbody>
                                                                                                @php $datobd="db" ;  @endphp
                                                                                                @foreach ($redcarros as $red)
                                                                                                    @if ($red->tipored == 'A')
                                                                                                        @php
                                                                                                            $ind++;
                                                                                                            $fondo = '';
                                                                                                        @endphp
                                                                                                        @if ($ind % 2 == 0)
                                                                                                            @php  $fondo ="#CFEFCA"; @endphp
                                                                                                        @endif
                                                                                                        <tr id="fila{{ $ind }}"
                                                                                                            style="background-color:{{ $fondo }}; ">
                                                                                                            <td
                                                                                                                style="width: 8%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lidsred[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $red->idredcarro }}"
                                                                                                                    required />
                                                                                                                <div
                                                                                                                    class="input-group">
                                                                                                                    <input
                                                                                                                        readonly
                                                                                                                        type="text"
                                                                                                                        min="0"
                                                                                                                        style="width:52%; background-color:{{ $fondo }}; border:1px solid silver; padding-right:0px !important;"
                                                                                                                        max="{{ $red->cantidad * $produccioncarro->cantidad }}"
                                                                                                                        step="1"
                                                                                                                        id="enviadoRE{{ $red->idredcarro }}"
                                                                                                                        name="Lcantidadenviadared[]"
                                                                                                                        value="{{ $red->cantidadenviada }}" />
                                                                                                                    <span
                                                                                                                        style="width:48%"
                                                                                                                        class="input-group-text sinborde">/{{ $red->cantidad * $produccioncarro->cantidad }}</span>
                                                                                                                </div>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 9%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lcantidadred[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $red->cantidad }}" />
                                                                                                                {{ $red->cantidad }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 47%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    name="Lproductred[]"
                                                                                                                    class=" form-control"
                                                                                                                    value="{{ $red->idproducto }}" />
                                                                                                                <b> {{ $red->producto }}
                                                                                                                </b>
                                                                                                                @if ($red->tipo == 'kit')
                                                                                                                    : <br>
                                                                                                                    @foreach ($detalleskitredes as $kitred)
                                                                                                                        @if ($red->idproducto == $kitred->product_id)
                                                                                                                            -{{ $kitred->cantidad }}
                                                                                                                            {{ $kitred->producto }}
                                                                                                                            <br>
                                                                                                                        @endif
                                                                                                                    @endforeach
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 13%;">
                                                                                                                <input
                                                                                                                    type="hidden"
                                                                                                                    value="{{ $red->idempresa }}" />
                                                                                                                {{ $red->nombreempresa }}
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="width: 19%;">
                                                                                                                <textarea name="Lobservacionred[]" id="observasion" rows="3" class="mayusculas"
                                                                                                                    style="font-size: 11px; width: 100%;  background-color:{{ $fondo }}; ">{{ $red->observacion }}</textarea>
                                                                                                            </td>
                                                                                                            <td
                                                                                                                style="text-align: center; width: 8%;">
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="btn btn-sm btn-danger"
                                                                                                                    onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $red->idredcarro }}', '{{ $red->idproducto }}' , 'red' ,'no')"
                                                                                                                    data-id="0">
                                                                                                                    ELIMINAR</button>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
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
                                            <br><br>
                                            <h4>Carros &nbsp;<button type="button" class="btn btn-danger"
                                                    data-bs-toggle="modal" data-bs-target="#modalFactura"> Crear Factura
                                                </button></h4>

                                            <div class="row">
                                                @php $indc=0 ; @endphp
                                                @php $indicec=count($carros) ; @endphp
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table-row-bordered gy-5 gs-5" id="tablacarros"
                                                        style="width: 1995px;">
                                                        <thead class="fw-bold text-primary">
                                                            <tr style="text-align: center;">
                                                                <th style=" width: 110px;"> NRO DE OP</th>
                                                                <th style="border-left: solid 1px silver; width: 110px;">
                                                                    NRO CHASIS</th>
                                                                <th style="border-left: solid 1px silver; width: 100px;">%
                                                                    DESCUENTO</th>
                                                                <th style="border-left: solid 1px silver; width: 200px;">
                                                                    ORDEN COMPRA</th>
                                                                <th style="border-left: solid 1px silver; width: 125px;">
                                                                    RED ENVIADA</th>
                                                                <th style="border-left: solid 1px silver; width: 120px;">
                                                                    MATERIAL ELECTRICO ENV</th>
                                                                <th style="border-left: solid 1px silver; width: 120px;">
                                                                    MATERIAL ADICIONAL ENV</th>
                                                                <th style="border-left: solid 1px silver; width: 260px;"
                                                                    colspan="2"> FACTURA ELECTROBUS <br> <br>
                                                                    <h6 style="border-top: 1px solid silver;">
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;FECHA&nbsp;&nbsp;&nbsp;
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <span
                                                                            style="border-left: 1px solid silver;"></span>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO
                                                                    </h6>
                                                                </th>
                                                                <th style="border-left: solid 1px silver; width: 260px;"
                                                                    colspan="2"> FACTURA DELMY <br> <br>
                                                                    <h6 style="border-top: 1px solid silver; ">
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;FECHA&nbsp;&nbsp;&nbsp;
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <span
                                                                            style="border-left: 1px solid silver;"></span>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO
                                                                    </h6>
                                                                </th>
                                                                <th style="border-left: solid 1px silver; width: 390px;"
                                                                    colspan="3"> FACTURA ADICIONAL <br> <br>
                                                                    <h6 style="border-top: 1px solid silver;">
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;FECHA&nbsp;&nbsp;&nbsp;
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <span
                                                                            style="border-left: 1px solid silver;"></span>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NUMERO
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                        <span
                                                                            style="border-left: 1px solid silver;"></span>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EMPRESA

                                                                    </h6>
                                                                </th>
                                                                <th style="border-left: solid 1px silver; width: 100px;">
                                                                    BONO
                                                                </th>
                                                                <th style="border-left: solid 1px silver; width: 100px;">
                                                                    MES DE BONO</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $datobd="db" ;  @endphp
                                                            @foreach ($carros as $carro)
                                                                @php
                                                                    $indc++;
                                                                    $fondo = '';
                                                                @endphp
                                                                @if ($indc % 2 == 0)
                                                                    @php  $fondo ="#CFEFCA"; @endphp
                                                                @endif
                                                                <tr id="filacarro{{ $indc }}"
                                                                    style="background-color:{{ $fondo }}; width: 110px;">
                                                                    <td>
                                                                        <input type="hidden" name="Lcarrosids[]"
                                                                            class=" form-control sinborde"
                                                                            value="{{ $carro->idcarro }}" />
                                                                        <div class="input-group">
                                                                            <input type="text"
                                                                                style="background-color:{{ $fondo }}; text-align: center;"
                                                                                name="Lnumeroordenproduccion[]"
                                                                                id="nroordenp{{ $carro->idcarro }}"
                                                                                class="form-control sinborde mayusculas"
                                                                                value="{{ $carro->nroordenp }}" />
                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align: center; width: 110px;">
                                                                        <input type="text" name="Lchasis[]"
                                                                            id="chasis{{ $carro->idcarro }}"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            class=" form-control sinborde mayusculas"
                                                                            value="{{ $carro->chasis }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width: 100px;">
                                                                        <input type="number" min="0"
                                                                            step="0.01"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="Lporcentajedescuento[]"
                                                                            class=" form-control sinborde"
                                                                            id="porcentajedescuentoC{{ $indc }}"
                                                                            value="{{ $carro->porcentajedescuento }}" />
                                                                    </td>
                                                                    <td style="text-align: left; width: 200px;">
                                                                        <textarea class="mayusculas" style="font-size: 11px; width:100%; background-color:{{ $fondo }};"
                                                                            class="" name="Lordencompra[]" id="listaordencompra{{ $carro->idcarro }}" rows="4">{{ $carro->ordencompra }}</textarea>
                                                                    </td>
                                                                    <td style="text-align: center; width: 120px;">
                                                                        <input type="hidden" name="Lredenviada[]"
                                                                            value="{{ $carro->redenviada }}" />
                                                                        <div class="infobox">
                                                                            @if (!$carro->redenviada)
                                                                                <button type="button"
                                                                                    id="btnenviarred{{ $carro->idcarro }}"
                                                                                    class="btn btn-warning"
                                                                                    onclick="mostarenvioredcarros({{ $carro->idcarro }});">
                                                                                    Enviar
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        height="1em"
                                                                                        viewBox="0 0 576 512">
                                                                                        <path
                                                                                            d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48V96H384V80c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H432c-26.5 0-48-21.5-48-48V160H192v16c0 1.7-.1 3.4-.3 5L272 288h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H272c-26.5 0-48-21.5-48-48V336c0-1.7 .1-3.4 .3-5L144 224H48c-26.5 0-48-21.5-48-48V80z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span class="infoboxtext"
                                                                                    id="spanredcarroenviado{{ $carro->idcarro }}">
                                                                                    Enviar Redes Para: <br>
                                                                                    {{ $carro->nroordenp }} </span>
                                                                            @else
                                                                                <button type="button"
                                                                                    id="btnenviarred{{ $carro->idcarro }}"
                                                                                    class="btn btn-success"
                                                                                    onclick="mostarenvioredcarros({{ $carro->idcarro }});">
                                                                                    Enviado
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        height="1em"
                                                                                        viewBox="0 0 576 512">
                                                                                        <path
                                                                                            d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48V96H384V80c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H432c-26.5 0-48-21.5-48-48V160H192v16c0 1.7-.1 3.4-.3 5L272 288h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H272c-26.5 0-48-21.5-48-48V336c0-1.7 .1-3.4 .3-5L144 224H48c-26.5 0-48-21.5-48-48V80z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span class="infoboxtext"
                                                                                    id="spanredcarroenviado{{ $carro->idcarro }}">
                                                                                    Fecha Envio: <br>
                                                                                    {{ $carro->materialelectricoenv }}
                                                                                </span>
                                                                            @endif

                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align: center; width: 120px;">
                                                                        <input type="hidden"
                                                                            name="Lmaterialelectricoenv[]"
                                                                            id="materialelectricoenviado{{ $carro->idcarro }}"
                                                                            value="{{ $carro->materialelectricoenv }}" />

                                                                        <div class="infobox">
                                                                            @if (!$carro->materialelectricoenv)
                                                                                <button type="button"
                                                                                    id="btnenviarme{{ $carro->idcarro }}"
                                                                                    class="btn btn-warning"
                                                                                    onclick="enviarmaterialelectrico({{ $carro->idcarro }});">
                                                                                    Enviar
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        height="1em"
                                                                                        viewBox="0 0 448 512">
                                                                                        <path
                                                                                            d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7H272.5L349.4 44.6z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span class="infoboxtext"
                                                                                    id="spanmaterialelectricoenviado{{ $carro->idcarro }}">
                                                                                    Enviar material electrico para: <br>
                                                                                    {{ $carro->nroordenp }} </span>
                                                                            @else
                                                                                <button type="button" disabled
                                                                                    class="btn btn-success"> Enviado
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        height="1em"
                                                                                        viewBox="0 0 448 512">
                                                                                        <path
                                                                                            d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7H272.5L349.4 44.6z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span class="infoboxtext"
                                                                                    id="spanmaterialelectricoenviado{{ $carro->idcarro }}">
                                                                                    Fecha Envio: <br>
                                                                                    {{ $carro->materialelectricoenv }}
                                                                                </span>
                                                                            @endif

                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align: center; width: 120px;">
                                                                        <input type="hidden"
                                                                            name="Lmaterialadicionalenv[]"
                                                                            value="{{ $carro->materialadicionalenv }}" />

                                                                        <div class="infobox">
                                                                            @if (!$carro->materialadicionalenv)
                                                                                <button type="button"
                                                                                    id="btnenviarMA{{ $carro->idcarro }}"
                                                                                    class="btn btn-warning"
                                                                                    onclick="enviarmaterialadicional({{ $carro->idcarro }});">
                                                                                    Enviar
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        height="1em"
                                                                                        viewBox="0 0 512 512">
                                                                                        <path
                                                                                            d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span class="infoboxtext"
                                                                                    id="spanmaterialadicionalenviado{{ $carro->idcarro }}">
                                                                                    Enviar material adicional para: <br>
                                                                                    {{ $carro->nroordenp }} </span>
                                                                            @else
                                                                                <button type="button"
                                                                                    class="btn btn-success"
                                                                                    id="btnenviarMA{{ $carro->idcarro }}"
                                                                                    onclick="enviarmaterialadicional({{ $carro->idcarro }});">
                                                                                    Enviado
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        height="1em"
                                                                                        viewBox="0 0 512 512">
                                                                                        <path
                                                                                            d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" />
                                                                                    </svg>
                                                                                </button>
                                                                                <span class="infoboxtext"
                                                                                    id="spanmaterialadicionalenviado{{ $carro->idcarro }}">
                                                                                    Fecha Envio: <br>
                                                                                    {{ $carro->materialadicionalenv }}
                                                                                </span>
                                                                            @endif

                                                                        </div>
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="date"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LfechaE[]" class="form-control sinborde"
                                                                            value="{{ $carro->fechaE }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="text"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LnumeroE[]"
                                                                            class=" form-control sinborde mayusculas"
                                                                            value="{{ $carro->numeroE }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="date"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LfechaD[]" class="form-control sinborde"
                                                                            value="{{ $carro->fechaD }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="text"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LnumeroD[]"
                                                                            class=" form-control sinborde mayusculas"
                                                                            value="{{ $carro->numeroD }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="date"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LfechaO[]" class="form-control sinborde"
                                                                            value="{{ $carro->fechaO }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="text"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LnumeroO[]"
                                                                            class=" form-control sinborde mayusculas"
                                                                            value="{{ $carro->numeroO }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:130px;">
                                                                        <input type="text"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="LempresaO[]"
                                                                            class=" form-control sinborde mayusculas"
                                                                            value="{{ $carro->empresaO }}" />
                                                                    </td>
                                                                    <td style="text-align: center; width:100px;">
                                                                        <select class="form-select sinborde"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            name="Lbonificacion[]">
                                                                            @if ($carro->bonificacion == 'SI')
                                                                                <option value="NO">NO</option>
                                                                                <option value="SI" selected>SI</option>
                                                                            @else
                                                                                <option value="NO" selected>NO</option>
                                                                                <option value="SI">SI</option>
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td style="text-align: center; width:100px;">
                                                                        <input type="text" name="Lmesbonificacion[]"
                                                                            style="background-color:{{ $fondo }}; text-align: center;"
                                                                            class=" form-control sinborde mayusculas"
                                                                            value="{{ $carro->mesbonificacion }}" />
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12 mb-3"> <br>
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Actualizar</button>
                            </div>
                        </div>
                    </form>

                    <!-- Modal -->
                    <div class="modal fade" id="modalFactura" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="modalFacturaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalFacturaLabel">Crear Facturas</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Seleccione los carros:
                                    <table id="tablafacturas" name="tablafacturas">
                                        <thead style="text-align: center;" class="fw-bold text-primary">
                                            <tr style="text-align: center;">
                                                <th style="width: 30px;"> </th>
                                                <th style="width: 100px;">Nro de OP</th>
                                                <th style="width: 140px;">Nro de Chasis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-success" id="btncrearfacturas"
                                        onclick="crearfacturaproduccion();">Crear Factura</button>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    <div class="modal fade" id="materialAdicionalModal" tabindex="-1"
                        aria-labelledby="materialAdicionalModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="materialAdicionalModalLabel">
                                        Enviar Material Adicional para OP Nro: </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table style="width: 100%;" id="TenviarMA">
                                        <thead class="fw-bold text-primary" style="width: 100%; text-align:center;">
                                            <tr style="width: 100%;">
                                                <th style="width: 4%; ">
                                                    <div class="infobox">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="seleccionartodos">
                                                        <span class="infoboxtext">
                                                            Seleccionar Todos</span>
                                                    </div>
                                                </th>
                                                <th style="border-left: solid 1px silver; width:8%; ">Cant</th>
                                                <th style="border-left: solid 1px silver; width:68%; ">Material Adicional
                                                </th>
                                                <th style="border-left: solid 1px silver; width:20%;">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" onclick="btnactualizarenvioMA();" class="btn btn-success"
                                        id="btnActualizarEnvioMA">Enviar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="redesModal" tabindex="-1" aria-labelledby="redesModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="redesModalLabel">
                                        Enviar Redes para OP Nro: </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table style="width: 100%;" id="TenviarRed">
                                        <thead class="fw-bold text-primary" style="width: 100%; text-align:center;">
                                            <tr style="width: 100%;">
                                                <th style="width: 4%; ">
                                                    <div class="infobox">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="seleccionartodosred">
                                                        <span class="infoboxtext">
                                                            Seleccionar Todos</span>
                                                    </div>
                                                </th>
                                                <th style="border-left: solid 1px silver; width:8%; ">Cant</th>
                                                <th style="border-left: solid 1px silver; width:68%; ">Red
                                                </th>
                                                <th style="border-left: solid 1px silver; width:20%;">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" onclick="btnactualizarenvioRed();" class="btn btn-success"
                                        id="btnActualizarEnvioRed">Enviar</button>
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
    <script type="text/javascript">
        var indice = 0;
        var nameproduct = 0;
        var estadoguardar = 0;
        var indicehp = 0;
        var tipoproducto = "";
        var unidad = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var misproductos;
        var idcliente = 0;
        var carroceria = 0;
        var indicec = @json($indicec);
        var cantidadcarros = 1;
        var idproduccion = @json($produccioncarro->id);
        var materialadicional = "";
        var redadicional = "";
        estadoguardar = @json($detalles);
        var cantidadenviadaMA = 0;
        var cantidadenviadaMA2 = 0;
        var cantidadenviadared = 0;
        var cantidadenviadared2 = 0;
        var cantidadMA = 0;
        var cantidadred = 0;
        var idcarroMA = "-1";
        var cantidadred = 0;
        var hoy = new Date();
        var miscarros = @json($carros);
        var idcarros = [];
        var idproductomaterial = [];
        var cantproductomaterial = [];
        var numeroproductoskit = 0;
        indice = @json($ind);
        var ordena = "";
        var todofacturado = 0;
        var tf = 0;
        var nc = "";
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
            String(hoy.getDate()).padStart(2, '0');

        $(document).ready(function() {
            cantidadcarros = document.getElementById('cantidadcarros').value;
            $('.toast').toast();
            $('.select2').select2({});
            botonguardar("inicio");
            for (var i = 0; i < miscarros.length; i++) {
                idcarros.push(miscarros[i].idcarro);
            }
            document.querySelectorAll(".mayusculas").forEach(input => input.addEventListener("input", () =>
                input.value = input.value.toUpperCase()));
        });

        const mimodal = document.getElementById('modalFactura');
        mimodal.addEventListener('show.bs.modal', event => {
            document.getElementById("btncrearfacturas").disabled = false;
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var titulo = document.getElementById("modalFacturaLabel");
            titulo.textContent = `Crear Facturas.`;
            $('#tablafacturas tbody tr').slice().remove();
            var nroordenes = document.getElementsByName("Lnumeroordenproduccion[]");
            var chasis = document.getElementsByName("Lchasis[]");
            var idcarros = document.getElementsByName("Lcarrosids[]");
            var filas = "";
            for (var i = 0; i < idcarros.length; i++) {
                filas += ' <tr style="text-align: center;" id="filafactura' + idcarros[i].value + '">' +
                    '<td> <input id="checkfactura' + idcarros[i].value + '" name="checkfactura" data-idcarro="' +
                    idcarros[i].value + '" type="checkbox"  class="form-check-input" /> </td>' +
                    '<td> ' + nroordenes[i].value + ' </td>' +
                    '<td>' + chasis[i].value + ' </td>' +
                    '</tr> ';
            }
            $("#tablafacturas>tbody").append(filas);
            var urltraercarros = "{{ url('admin/produccioncarro/traercarros') }}";
            $.get(urltraercarros + '/' + idproduccion, function(data) {
                tf = 0;
                for (var x = 0; x < data.length; x++) {
                    var check = document.getElementById("checkfactura" + data[x].idcarro);
                    var fila = document.getElementById("filafactura" + data[x].idcarro);
                    if (data[x].electrobus_id != null && data[x].electrobus_id != '') {
                        check.disabled = true;
                        check.style.backgroundColor = "#51D616";
                        check.style.border = " 1px solid black ";
                        fila.style.backgroundColor = "#1AF50050";
                        tf++;
                        nc = data.length;
                    }
                }
            });
        });

        function crearfacturaproduccion() {
            document.getElementById("btncrearfacturas").disabled = true;
            var checkfacturas = document.getElementsByName("checkfactura");
            var idCarros = [];
            for (var i = 0; i < checkfacturas.length; i++) {
                if (checkfacturas[i].checked) {
                    var idCarro = checkfacturas[i].getAttribute("data-idcarro");
                    idCarros.push(idCarro);
                }
            }
            if (idCarros.length > 0) {
                var urlfactura = "{{ url('admin/produccioncarro/crearfactura') }}";
                $.ajax({
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    url: urlfactura,
                    async: true,
                    data: {
                        idcarros: idCarros,
                        idproduccion: idproduccion,
                    },
                    success: function(data1) {
                        if (data1 == "1") {
                            Swal.fire({
                                icon: "success",
                                text: "Facturas Creadas",
                            });
                            $('#modalFactura').modal('hide');
                        } else if (data1 == "0") {
                            Swal.fire({
                                icon: "error",
                                text: "Factura No Creada",
                            });
                        }
                    }
                });
            } else {
                if (nc == tf) {
                    Swal.fire({
                        icon: "info",
                        text: "!Ya se ha Facturado todo¡",
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        text: "!Seleccione un Numero de OP¡",
                    });
                }
                document.getElementById("btncrearfacturas").disabled = false;
            }

            var urltraercarros = "{{ url('admin/produccioncarro/traercarros') }}";
            $.get(urltraercarros + '/' + idproduccion, function(data) {
                todofacturado = 0;
                for (var x = 0; x < data.length; x++) {
                    if (data[x].electrobus_id != null && data[x].electrobus_id != '') {
                        todofacturado++;
                    }
                }
                var Tenv = "NO";
                if (todofacturado == data.length) {
                    Tenv = "SI";
                    var selectElement = document.getElementById("facturado");
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value == Tenv) {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                }

            });
        }

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

        $("#product").change(function() {
            $("#product option:selected").each(function() {
                var miproduct = $(this).val();
                if (miproduct) {
                    $named = $(this).data("name");
                    $tipo = $(this).data("tipo");
                    idproducto = miproduct;
                    tipoproducto = $tipo;
                    numeroproductoskit = 0;
                    if ($tipo == "kit") {
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
                                    '"><td><input type="number" id="cantidadproductokit' +
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
                    }

                    if ($tipo == "estandar") {
                        $('.toast').toast('hide');
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                    } else if ($tipo == "kit") {
                        document.getElementById('labelproducto').innerHTML =
                            "PRODUCTO TIPO KIT";
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
                        nameproduct = $named;
                    }
                    document.getElementById('cantidadred').value = 1;
                    document.getElementById('enviadored').value = 0;

                }
            });
        });

        $("#ordencompra").change(function() {
            var orden = $(this).val();
            var ordenes = document.getElementsByName("Lordencompra[]");
            var c = 0;
            var c2 = 0;
            for (var i = 0; i < ordenes.length; i++) {
                var miorden = ordenes[i].value;
                if (miorden == null || miorden == "") {
                    c++;
                }
                if (miorden == ordena) {
                    c2++;
                }
            }
            if (c == cantidadcarros || c2 == cantidadcarros) {
                actualizadordencompras();
            } else {
                Swal.fire({
                    title: '¿Actualizar Todas las Ordenes?',
                    text: "Ya se han cambiado alguna ordenes de compra",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, Cambiar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        actualizadordencompras();
                    }
                });
            }
            ordena = orden;
        });

        function cambiotodoenviado() {
            var enviadotodo = document.getElementById("todoenviado").value;
            var divmateriales = document.getElementById("divmaterial");
            var divredes = document.getElementById("divred");
            if (enviadotodo == "SI") {
                divmateriales.style.display = "none";
                divredes.style.display = "none";
            } else {
                divmateriales.style.display = "inline";
                divredes.style.display = "inline";
            }
        }

        //var indice = 0;
        var pv = 0;
        var svgme =
            '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"> <path  d="M349.4 44.6c5.9-13.7 1.5-29.7-10.6-38.5s-28.6-8-39.9 1.8l-256 224c-10 8.8-13.6 22.9-8.9 35.3S50.7 288 64 288H175.5L98.6 467.4c-5.9 13.7-1.5 29.7 10.6 38.5s28.6 8 39.9-1.8l256-224c10-8.8 13.6-22.9 8.9-35.3s-16.6-20.7-30-20.7H272.5L349.4 44.6z" />  </svg>';
        var svgma =
            '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"> <path d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" /></svg>';
        var svgred =
            '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512"> <path d="M0 80C0 53.5 21.5 32 48 32h96c26.5 0 48 21.5 48 48V96H384V80c0-26.5 21.5-48 48-48h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H432c-26.5 0-48-21.5-48-48V160H192v16c0 1.7-.1 3.4-.3 5L272 288h96c26.5 0 48 21.5 48 48v96c0 26.5-21.5 48-48 48H272c-26.5 0-48-21.5-48-48V336c0-1.7 .1-3.4 .3-5L144 224H48c-26.5 0-48-21.5-48-48V80z" /> </svg>';

        function actualizadordencompras() {
            var orden = document.getElementById("ordencompra").value;
            for (x = 0; x < miscarros.length; x++) {
                document.getElementById("listaordencompra" + miscarros[x].idcarro).value = orden;
            }
        }

        function enviarmaterialelectrico(idcarro) {
            var botonenviar = document.getElementById("btnenviarme" + idcarro);
            var nroop = document.getElementById("nroordenp" + idcarro).value;
            Swal.fire({
                title: '¿Enviar Material Electrico?',
                text: "Para OP Nro: " + nroop,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Enviar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var urlenviarme = "{{ url('admin/produccioncarro/enviarmaterialelectrico') }}";
                    $.ajax({
                        type: "GET",
                        url: urlenviarme + '/' + idcarro + '/' + fechaActual,
                        success: function(data1) {
                            if (data1 == "1") {
                                actualizarEnvio();
                                Swal.fire({
                                    icon: "success",
                                    text: "Material Enviado",
                                });
                                botonenviar.classList.remove("btn-warning");
                                botonenviar.classList.add("btn-success");
                                botonenviar.innerHTML = 'Enviado ' + svgme;
                                botonenviar.disabled = true;
                                document.getElementById("spanmaterialelectricoenviado" + idcarro)
                                    .innerHTML = "Fecha Envio: <br> " + fechaActual;
                                document.getElementById("materialelectricoenviado" + idcarro).value =
                                    fechaActual;
                            } else if (data1 == "0") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Material No Enviado",
                                });
                            } else if (data1 == "2") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Carro No Encontrado",
                                });
                            }
                        }
                    });
                }
            });
            todoenviado();
        }

        function enviarmaterialadicional(idcarro) {
            idcarroMA = idcarro;
            cantidadenviadaMA = 0;
            document.getElementById("btnActualizarEnvioMA").disabled = false;
            var miModal = new bootstrap.Modal(document.getElementById('materialAdicionalModal'));
            var nroordenp = document.getElementById("nroordenp" + idcarro).value;
            document.getElementById('materialAdicionalModalLabel').innerHTML =
                "Enviar Material Adicional Para OP Nro: " + nroordenp;
            $('#TenviarMA tbody tr').slice().remove();
            var urlgetmaterialadicional = "{{ url('admin/produccioncarro/materialadicionalxproduccion') }}";
            $.ajax({
                type: "GET",
                url: urlgetmaterialadicional + '/' + idproduccion + '/' + idcarro,
                success: function(data1) {
                    materialadicional = data1;
                    cantidadMA = data1.length;
                    for (var i = 0; i < data1.length; i++) {
                        var fondo = "#ffffff";
                        if (i % 2 == 0) {
                            fondo = "#CFEFCA";
                        }
                        var marcado = "";
                        var cambiarfecha = "si";
                        var fechaenvMA = "";
                        if (data1[i].fecha) {
                            marcado = "checked";
                            cambiarfecha = "no";
                            fechaenvMA = data1[i].fecha;
                            cantidadenviadaMA++;
                        }
                        var lista = "<br>";
                        if (data1[i].tipo == "kit") {
                            var urlproductos = "{{ url('admin/produccioncarro/productosxkitxmaterial') }}";
                            $.ajax({
                                type: "GET",
                                url: urlproductos + '/' + data1[i].idmaterial,
                                async: false,
                                success: function(data) {
                                    for (var x = 0; x < data.length; x++) {
                                        lista = lista + '-' + data[x].cantidad + ' ' +
                                            data[x].producto + '<br>';
                                    }
                                }
                            });
                        }
                        filaDetalle = '<tr style="background-color: ' + fondo + ';" >' +
                            '<td> <input type="checkbox" class="form-check-input" data-cambiar="' +
                            cambiarfecha + '" onchange="seleccionarMA(' +
                            data1[i].idenvio + ');" name="Lcheck[]" id="checkMA' +
                            data1[i].idenvio + '" ' + marcado + ' /> </td>' +
                            '<td  style="text-align: center;">' +
                            data1[i].cantidad + '<input  type="hidden" name="LidsenvioMA[]"  value="' +
                            data1[i].idenvio + '" id="listaid' + data1[i].idenvio + '" /> </td><td> <b>' +
                            data1[i].nombre + '</b>' + lista +
                            '</td><td> <input style="width:100%; border:solid 1px silver;" type="text" value="' +
                            fechaenvMA + '" name="LfechaenvioMA[]" data-fechaenvio="' + fechaenvMA +
                            '" id="fechaMA' + data1[i].idenvio + '" /> </td> </tr>';
                        $("#TenviarMA>tbody").append(filaDetalle);
                    }
                }
            });
            miModal.show();
        }

        function mostarenvioredcarros(idcarro) {
            idcarroMA = idcarro;
            cantidadenviadared = 0;
            document.getElementById("btnActualizarEnvioRed").disabled = false;
            var miModalRed = new bootstrap.Modal(document.getElementById('redesModal'));
            var nroordenp = document.getElementById("nroordenp" + idcarro).value;
            document.getElementById('redesModalLabel').innerHTML =
                "Enviar Redes Para OP Nro: " + nroordenp;
            $('#TenviarRed tbody tr').slice().remove();
            var urlredes = "{{ url('admin/produccioncarro/redesxproduccion') }}";
            $.ajax({
                type: "GET",
                url: urlredes + '/' + idproduccion + '/' + idcarro,
                success: function(data1) {
                    redadicional = data1;
                    cantidadred = data1.length;
                    for (var i = 0; i < data1.length; i++) {
                        var fondo = "#ffffff";
                        if (i % 2 == 0) {
                            fondo = "#CFEFCA";
                        }
                        var marcado = "";
                        var cambiarfecha = "si";
                        var fechaenvMA = "";
                        if (data1[i].fecha) {
                            marcado = "checked";
                            cambiarfecha = "no";
                            fechaenvMA = data1[i].fecha;
                            cantidadenviadared++;
                        }
                        var lista = "<br>";
                        if (data1[i].tipo == "kit") {
                            var urlproductos = "{{ url('admin/venta/productosxkit') }}";
                            $.ajax({
                                type: "GET",
                                url: urlproductos + '/' + data1[i].idproducto,
                                async: false,
                                success: function(data) {
                                    for (var x = 0; x < data.length; x++) {
                                        lista = lista + '-' + data[x].cantidad + ' ' +
                                            data[x].producto + '<br>';
                                    }
                                }
                            });
                        }
                        filaDetalle = '<tr style="background-color: ' + fondo + ';" >' +
                            '<td> <input type="checkbox" class="form-check-input" data-cambiar="' +
                            cambiarfecha + '" onchange="seleccionarRed(' +
                            data1[i].idenvio + ');" name="Lcheckred[]" id="checkred' +
                            data1[i].idenvio + '" ' + marcado + ' /> </td>' +
                            '<td  style="text-align: center;">' +
                            data1[i].cantidad + '<input  type="hidden" name="Lidsenviored[]"  value="' +
                            data1[i].idenvio + '" id="listaidred' + data1[i].idenvio + '" /> </td><td> <b>' +
                            data1[i].nombre + '</b>' + lista +
                            '</td><td> <input style="width:100%; border:solid 1px silver;" type="text" value="' +
                            fechaenvMA + '" name="Lfechaenviored[]" data-fechaenvio="' + fechaenvMA +
                            '" id="fechared' + data1[i].idenvio + '" /> </td> </tr>';
                        $("#TenviarRed>tbody").append(filaDetalle);
                    }
                }
            });
            miModalRed.show();
        }

        function actualizardescuento() {
            var desc = document.getElementById("descuento").value;
            for (var i = 1; i <= indicec; i++) {
                document.getElementById("porcentajedescuentoC" + i).value = desc;
            }
        }

        function agregarFila(indice1) {
            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }
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
            idproductomaterial = [];
            cantproductomaterial = [];
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
                        idproductomaterial.push(id);
                        cantproductomaterial.push(cant);
                        milista = milista + '-' + cant + ' ' + nombre + coma;
                        listaproductoskit += '<input  type="hidden" name="Lidkit[]" value="' +
                            idproducto + '" /><input  type="hidden" name="Lcantidadproductokit[]" value="' +
                            cant + '" /><input  type="hidden" name="Lidproductokit[]" value="' +
                            id + '" />';
                    }
                }
                milista += listaproductoskit;

                agregarFilasTabla(LVenta, puntos, milista, product, mitabla, "m", tipomaterial);

            } else {
                agregarFilasTabla(LVenta, puntos, milista, product, mitabla, "m", tipomaterial);
            }
        }

        function agregarFilaRed(indice1) {
            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }
            //datos del detalle
            var product = $('[name="red"]').val();
            var cantidad = $('[name="cantidadred"]').val();
            var enviado = $('[name="enviadored"]').val();
            var tipored = $('[name="tipored"]').val();

            var selectE = document.getElementById("empresared");
            var idempresax = selectE.value;
            var empresanombre = selectE.options[selectE.selectedIndex].getAttribute(
                "data-nombreempresa");

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione una Red");
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
                    agregarFilasTabla(LVenta, puntos, milista, product, mitabla, "red", tipored);
                });
            } else {
                agregarFilasTabla(LVenta, puntos, milista, product, mitabla, "red", tipored);
            }
        }

        function agregarFilasTabla(LDetalle, puntos, milista, product, tabla, red, tipo) {
            var fondo = "#ffffff";
            if (indice % 2 == 0) {
                fondo = "#CFEFCA";
            }
            var redx = "'m'";
            if (red == "red") {
                redx = "'red'";
            }
            var esME = "'si'";
            if (tipo == "A") {
                esME = "'no'";
            }
            var urladdfila = "";
            var enviado = "";
            if (red == "m") {
                var urladdfila = "{{ url('admin/produccioncarro/addmaterialadicional') }}";
                document.getElementById('miproducto' + product).disabled = true;
                enviado = "enviadoMA";
            } else if (red == "red") {
                enviado = "enviadoRE";
                urladdfila = "{{ url('admin/produccioncarro/addredadicional') }}";
                document.getElementById('mired' + product).disabled = true;
            }
            $.ajax({
                type: "GET",
                url: urladdfila + '/' + idproduccion + '/' + LDetalle[2] + '/' + LDetalle[0] + '/' + LDetalle[4] +
                    '/' + tipo,
                success: function(data1) {
                    var clase = "input-group";
                    if (tipo == "E") {
                        clase = "MEenviado";
                    }
                    if (data1 != "-1") {
                        const elementosConNombre = document.getElementsByName("Lmaterialelectricoenv[]");
                        var numeroenv = 0;
                        var nuevacantenv = 0;
                        for (let i = 0; i < elementosConNombre.length; i++) {
                            if (elementosConNombre[i].value != null && elementosConNombre[i].value != '') {
                                numeroenv++;
                            }
                        }
                        if (numeroenv > 0) {
                            var cant = LDetalle[2];
                            if (tipo == "E" && red == "m") {
                                nuevacantenv = numeroenv * cant;
                            }
                        }
                        urladdDetalle = "{{ url('admin/produccioncarro/addmaterialdetalle') }}";
                        $.ajax({
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            url: urladdDetalle,
                            async: true,
                            data: {
                                listaids: idproductomaterial,
                                listacant: cantproductomaterial,
                                idmaterial: data1
                            },
                            success: function(dataMA) {}
                        });

                        filaDetalle = '<tr id="fila' + indice +
                            '" style="background-color: ' + fondo + ';" >' +
                            '<td><div class="input-group"><input min="0" max="' + parseInt(LDetalle[2]) *
                            parseInt(cantidadcarros) +
                            '" class="' + clase + ' sinborde" data-cantidad="' + LDetalle[2] +
                            '" type="number" readonly name="Lcantidadenviada' + red +
                            '[]" id="' + enviado + data1 + '" value="' + nuevacantenv +
                            '" style="padding-right:0px !important; border: solid 1px silver; width:52%; background-color: ' +
                            fondo +
                            ';"><span class="input-group-text sinborde" style="width:48%;" id="spancantidadenviada' +
                            indice + '">/' + parseInt(LDetalle[2]) * parseInt(cantidadcarros) +
                            ' </span></div>  </td><td  style="text-align: center;">' +
                            '<input  type="hidden" name="Lcantidad' + red + '[]" id="cantidad' + indice +
                            '" value="' + LDetalle[2] + '" > <input  type="hidden" name="Lids' + red +
                            '[]"  value="' + data1 + '" id="listaid' + indice + '" />' + LDetalle[2] +
                            '</td><td><input  type="hidden" name="Lproduct' + red + '[]" value="' +
                            LDetalle[0] + '"><b>' + LDetalle[1] + '</b>' + puntos + milista +
                            '</td><td><input  type="hidden" name="Lempresa' + red + '[]" value="' +
                            LDetalle[4] + '">' + LDetalle[5] +
                            '</td><td style="width:19%;"><textarea style="font-size:11px; width:100%; background-color: ' +
                            fondo + ';"  name="Lobservacion' + red + '[]" id="observacion' + indice +
                            '" rows="3"  ></textarea></td> <td style="text-align: center;"><button type="button" class="btn btn-sm btn-danger" onclick="eliminarFila(' +
                            indice + ',' + "'db'" + ',' + data1 + ',' + LDetalle[0] + ',' + redx + ',' + esME +
                            ' )" data-id="0" >ELIMINAR</button></td></tr>';
                        $("#" + tabla + ">tbody").append(filaDetalle);
                        actualizarbotones(tabla);
                    }
                    var mired = "";
                    if (red == "red") {
                        mired = "red";
                    } else {
                        mired = "mat";
                    }
                    urladdfecha = "{{ url('admin/produccioncarro/actualizarfecharRedMat') }}";
                    $.ajax({
                        type: "GET",
                        url: urladdfecha + '/' + idproduccion + '/' + mired + '/' + data1,
                        success: function(datax) {}
                    });
                }
            });
            limpiarinputs();
            var funcion = "agregar";
            botonguardar(funcion);
        }

        function actualizarbotones(tabla) {
            for (var i = 0; i < miscarros.length; i++) {
                if (tabla == "detallesredadicional") {
                    try {
                        document.getElementById("btnenviared" + miscarros[i].idcarro)
                        var btn = document.getElementById("btnenviarred" + miscarros[i].idcarro);
                        btn.classList.remove("btn-success");
                        btn.classList.add("btn-warning");
                        btn.innerHTML = 'Enviar ' + svgred;
                        var orden = document.getElementById("nroordenp" + miscarros[i].idcarro).value;
                        document.getElementById("spanredcarroenviado" + miscarros[i].idcarro)
                            .innerHTML = "Enviar Redes Para: <br> " + orden;
                    } catch (error) {}
                } else if (tabla == "detallesmaterialadicional") {
                    try {
                        var btn = document.getElementById("btnenviarMA" + miscarros[i].idcarro);
                        btn.classList.remove("btn-success");
                        btn.classList.add("btn-warning");
                        btn.innerHTML = 'Enviar ' + svgma;
                        var nroordenp = document.getElementById("nroordenp" + miscarros[i].idcarro).value;
                        document.getElementById("spanmaterialadicionalenviado" + miscarros[i].idcarro)
                            .innerHTML = "Enviar Material Adicional Para: <br> " + nroordenp;
                    } catch (error) {}
                }
            }
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

        function eliminarFila(ind, lugardato, iddetalle, idproducto, tiporedmat, esME) {

            var redomat = "mat";
            var cantenvRM = "";
            if (tiporedmat == "red") {
                redomat = "red";
                cantenvRM = document.getElementById("enviadoRE" + iddetalle).value;
            } else {
                cantenvRM = document.getElementById("enviadoMA" + iddetalle).value;
            }
            if (cantenvRM == 0 || esME == "si") {
                if (lugardato == "db") {
                    var texto = "No lo podra revertir!";
                    if (cantenvRM == 1) {
                        texto = "Ya se envió " + cantenvRM + " producto!";
                    } else if (cantenvRM > 1) {
                        texto = "Ya se enviaron " + cantenvRM + " productos!";
                    }
                    Swal.fire({
                        title: '¿Esta seguro de Eliminar?',
                        text: texto,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí,Eliminar!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('.product').select2("destroy");
                            var url2 = "{{ url('admin/produccioncarro/deletematerialcarro') }}";
                            if (tiporedmat == "red") {
                                url2 = "{{ url('admin/produccioncarro/deleteredcarro') }}";
                            }
                            $.get(url2 + '/' + iddetalle, function(data) {
                                if (data[0] == 1) {
                                    Swal.fire({
                                        text: "Registro Eliminado",
                                        icon: "success"
                                    });
                                    quitarFilaM(ind);
                                    var urlredmat =
                                        "{{ url('admin/produccioncarro/btnredesenviadas') }}";
                                    $.ajax({
                                        type: "POST",
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken
                                        },
                                        url: urlredmat,
                                        async: true,
                                        data: {
                                            carros: idcarros,
                                            redomat: redomat
                                        },
                                        success: function(datamr) {
                                            for (var y = 0; y < datamr[0].length; y++) {
                                                if (redomat == "red") {
                                                    if (datamr[1][y] == "NER") {
                                                        var btn = document.getElementById(
                                                            "btnenviarred" + datamr[0][y]);
                                                        btn.classList.remove("btn-warning");
                                                        btn.classList.add("btn-success");
                                                        btn.innerHTML = 'Enviado ' + svgred;
                                                        document.getElementById(
                                                                "spanredcarroenviado" + datamr[
                                                                    0][y]).innerHTML =
                                                            "Fecha Envio: <br> " + fechaActual;
                                                        fechaMA = fechaActual;
                                                    }
                                                } else {
                                                    if (datamr[1][y] == "NEM") {
                                                        var btn = document.getElementById(
                                                            "btnenviarMA" + datamr[0][y]);
                                                        btn.classList.remove("btn-warning");
                                                        btn.classList.add("btn-success");
                                                        btn.innerHTML = 'Enviado ' + svgma;
                                                        document.getElementById(
                                                                "spanmaterialadicionalenviado" +
                                                                datamr[0][y]).innerHTML =
                                                            "Fecha Envio: <br> " + fechaActual;
                                                        fechaMA = fechaActual;
                                                    }
                                                }
                                            }
                                        }
                                    });
                                } else if (data[0] == 0) {
                                    alert("no se puede eliminar");
                                } else if (data[0] == 2) {
                                    alert("registro no encontrado");
                                }
                            });
                        }
                    });
                } else {
                    quitarFilaM(ind);
                }

                if (redomat == "red") {
                    document.getElementById('mired' + idproducto).disabled = false;
                } else {
                    document.getElementById('miproducto' + idproducto).disabled = false;
                }
                return false;
            } else {
                Swal.fire({
                    title: "No se puede Eliminar!",
                    text: "Ya se Registró un Envio!",
                    icon: "error"
                });
            }
        }

        function quitarFilaM(indicador) {
            $('#fila' + indicador).remove();
            var funcion = "eliminar";
            botonguardar(funcion);
            return false;
        }

        function actualizarEnvio() {
            var elementos = document.querySelectorAll('.MEenviado');
            elementos.forEach(function(elemento) {
                const cantidad = elemento.getAttribute('data-cantidad');
                var cantEnviado = elemento.value;
                elemento.value = parseInt(cantEnviado) + parseInt(cantidad);
            });
        }

        function btnactualizarenvioMA() {
            document.getElementById("btnActualizarEnvioMA").disabled = true;
            var Lids = document.getElementsByName("LidsenvioMA[]");
            var Lfechas = document.getElementsByName("LfechaenvioMA[]");
            var ListaIdMA = [];
            var ListaFechaMA = [];
            for (const element of Lids) {
                ListaIdMA.push(parseInt(element.value));
            }
            for (const element of Lfechas) {
                ListaFechaMA.push(element.value);
            }

            var urlenvioMA = "{{ url('admin/produccioncarro/guardarenviomaterialA') }}";
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: urlenvioMA,
                async: true,
                data: {
                    listaids: ListaIdMA,
                    listafechas: ListaFechaMA
                },
                success: function(data1) {
                    if (data1 == "1") {
                        Swal.fire({
                            text: "Material Adicional Enviado",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            text: "Ocurrio un Error",
                            icon: "error"
                        });
                    }
                    $('#materialAdicionalModal').modal('hide');
                    var fechaMA = "-1";
                    var btn = document.getElementById("btnenviarMA" + idcarroMA);
                    if (cantidadenviadaMA == cantidadMA) {
                        btn.classList.remove("btn-warning");
                        btn.classList.add("btn-success");
                        btn.innerHTML = 'Enviado ' + svgma;
                        document.getElementById("spanmaterialadicionalenviado" + idcarroMA)
                            .innerHTML = "Fecha Envio: <br> " + fechaActual;
                        fechaMA = fechaActual;
                    } else {
                        btn.classList.remove("btn-success");
                        btn.classList.add("btn-warning");
                        btn.innerHTML = 'Enviar ' + svgma;
                        var nroordenp = document.getElementById("nroordenp" + idcarroMA).value;
                        document.getElementById("spanmaterialadicionalenviado" + idcarroMA)
                            .innerHTML = "Enviar Material Adicional Para: <br> " + nroordenp;
                    }
                    var urlfechaMAenv = "{{ url('admin/produccioncarro/fechaMA') }}";
                    $.ajax({
                        type: "GET",
                        url: urlfechaMAenv + '/' + idcarroMA + '/' + fechaMA,
                        async: true,
                        success: function(dataj) {}
                    });
                    var urlMAenv = "{{ url('admin/produccioncarro/cantEnviadoMA') }}";
                    $.ajax({
                        type: "GET",
                        url: urlMAenv + '/' + idproduccion,
                        async: true,
                        success: function(datax) {
                            for (var x = 0; x < datax.length; x++) {
                                document.getElementById("enviadoMA" + datax[x].idmaterial).value =
                                    datax[x].cantidadenviada;
                            }
                        }
                    });
                    todoenviado();
                }
            });
        }

        function seleccionarMA(idenvio) {
            var checkbox = document.getElementById("checkMA" + idenvio)
            var cambiar = checkbox.getAttribute("data-cambiar");
            if (cambiar == "si") {
                if (checkbox.checked) {
                    cantidadenviadaMA++;
                    document.getElementById("fechaMA" + idenvio).value = fechaActual;
                } else {
                    cantidadenviadaMA--;
                    document.getElementById("fechaMA" + idenvio).value = "";
                }
            } else {
                if (checkbox.checked) {
                    cantidadenviadaMA++;
                    var fechaenv = document.getElementById("fechaMA" + idenvio).getAttribute("data-fechaenvio");
                    document.getElementById("fechaMA" + idenvio).value = fechaenv;
                } else {
                    cantidadenviadaMA--;
                    document.getElementById("fechaMA" + idenvio).value = "";
                }
            }
        }

        function seleccionarRed(idenvio) {
            var checkbox = document.getElementById("checkred" + idenvio);
            var cambiar = checkbox.getAttribute("data-cambiar");
            if (cambiar == "si") {
                if (checkbox.checked) {
                    cantidadenviadared++;
                    document.getElementById("fechared" + idenvio).value = fechaActual;
                } else {
                    cantidadenviadared--;
                    document.getElementById("fechared" + idenvio).value = "";

                }
            } else {
                if (checkbox.checked) {
                    cantidadenviadared++;
                    var fechaenv = document.getElementById("fechared" + idenvio).getAttribute("data-fechaenvio");
                    document.getElementById("fechared" + idenvio).value = fechaenv;
                } else {
                    cantidadenviadared--;
                    document.getElementById("fechared" + idenvio).value = "";
                }
            }
        }

        function btnactualizarenvioRed() {
            document.getElementById("btnActualizarEnvioRed").disabled = true;
            var Lids = document.getElementsByName("Lidsenviored[]");
            var Lfechas = document.getElementsByName("Lfechaenviored[]");
            var ListaIdred = [];
            var ListaFechared = [];
            for (const element of Lids) {
                ListaIdred.push(parseInt(element.value));
            }
            for (const element of Lfechas) {
                ListaFechared.push(element.value);
            }

            var urlenviored = "{{ url('admin/produccioncarro/guardarenvioredes') }}";
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                url: urlenviored,
                async: true,
                data: {
                    listaids: ListaIdred,
                    listafechas: ListaFechared
                },
                success: function(data1) {
                    if (data1 == "1") {
                        Swal.fire({
                            text: "Redes Enviadas",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            text: "Ocurrio un Error",
                            icon: "error"
                        });
                    }
                    $('#redesModal').modal('hide');
                    var fechared = "-1";
                    var btn = document.getElementById("btnenviarred" + idcarroMA);
                    if (cantidadenviadared == cantidadred) {
                        btn.classList.remove("btn-warning");
                        btn.classList.add("btn-success");
                        btn.innerHTML = 'Enviado ' + svgred;
                        document.getElementById("spanredcarroenviado" + idcarroMA)
                            .innerHTML = "Fecha Envio: <br> " + fechaActual;
                        fechared = fechaActual;
                    } else {
                        btn.classList.remove("btn-success");
                        btn.classList.add("btn-warning");
                        btn.innerHTML = 'Enviar ' + svgred;
                        var orden = document.getElementById("nroordenp" + idcarroMA).value;
                        document.getElementById("spanredcarroenviado" + idcarroMA)
                            .innerHTML = "Enviar Redes Para: <br> " + orden;
                    }
                    var urlfechaRed = "{{ url('admin/produccioncarro/fechaRed') }}";
                    $.ajax({
                        type: "GET",
                        url: urlfechaRed + '/' + idcarroMA + '/' + fechared,
                        async: true,
                        success: function(dataj) {}
                    });
                    var urlRed = "{{ url('admin/produccioncarro/cantEnviadoRed') }}";
                    $.ajax({
                        type: "GET",
                        url: urlRed + '/' + idproduccion,
                        async: true,
                        success: function(datax) {
                            for (var x = 0; x < datax.length; x++) {
                                document.getElementById("enviadoRE" + datax[x].idmaterial).value =
                                    datax[x].cantidadenviada;
                            }
                        }
                    });
                    todoenviado();
                }
            });
        }

        var checkbox = document.getElementById("seleccionartodos");
        checkbox.addEventListener("change", function() {

            var seleccionado = false;
            var fecha = "";
            if (checkbox.checked) {
                seleccionado = true;
                fecha = fechaActual;
                cantidadenviadaMA = cantidadMA;
                cantidadenviadaMA2 = cantidadenviadaMA;
            } else {
                cantidadenviadaMA = cantidadenviadaMA2;
                seleccionado = false;
                fecha = "";
            }
            for (var i = 0; i < materialadicional.length; i++) {
                var checkboxMA = document.getElementById("checkMA" + materialadicional[i].idenvio);
                var fechaenvio = document.getElementById("fechaMA" + materialadicional[i].idenvio);
                var cambiar = checkboxMA.getAttribute("data-cambiar");
                if (cambiar == "si") {
                    checkboxMA.checked = seleccionado;
                    fechaenvio.value = fecha;
                }
            }
        });
        var checkboxred = document.getElementById("seleccionartodosred");
        checkboxred.addEventListener("change", function() {

            var seleccionado = false;
            var fecha = "";
            if (checkboxred.checked) {
                seleccionado = true;
                fecha = fechaActual;
                cantidadenviadared = cantidadred;
                cantidadenviadared2 = cantidadenviadared;
            } else {
                cantidadenviadared = cantidadenviadared2;
                seleccionado = false;
                fecha = "";
            }
            for (var i = 0; i < redadicional.length; i++) {
                var checkboxRed1 = document.getElementById("checkred" + redadicional[i].idenvio);
                var fechaenvio = document.getElementById("fechared" + redadicional[i].idenvio);
                var cambiar = checkboxRed1.getAttribute("data-cambiar");
                if (cambiar == "si") {
                    checkboxRed1.checked = seleccionado;
                    fechaenvio.value = fecha;
                }
            }
        });

        function todoenviado() {
            var urlTodoenv = "{{ url('admin/produccioncarro/todoenviado') }}";
            $.ajax({
                type: "GET",
                url: urlTodoenv + '/' + idproduccion,
                async: true,
                success: function(datax) {
                    var selectElement = document.getElementById("todoenviado");
                    var Tenv = "";
                    if (datax == "1") {
                        Tenv = "SI";
                    } else {
                        Tenv = "NO";
                    }
                    for (var i = 0; i < selectElement.options.length; i++) {
                        if (selectElement.options[i].value == Tenv) {
                            selectElement.selectedIndex = i;
                            break;
                        }
                    }
                    cambiotodoenviado();
                }
            });
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
    </script>
@endpush
