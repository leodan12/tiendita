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
                    <h4>EDITAR UNIFORME
                        <a href="{{ url('admin/uniformes') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/uniformes/' . $uniforme->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas" required
                                    value="{{ $uniforme->nombre }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO(soles)</label>
                                <input type="number" name="precio" id="precio" min="0" step="0.0001"
                                    class="form-control " value="{{ $uniforme->precio }}" required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">GENERO</label>
                                <select name="genero" id="genero" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="MASCULINO" {{ "MASCULINO" == $uniforme->genero ? 'selected' : '' }}>
                                        MASCULINO</option>
                                    <option value="FEMENINO" {{ "FEMENINO" == $uniforme->genero ? 'selected' : '' }}>
                                        FEMENINO</option>
                                    <option value="UNISEX" {{ "UNISEX" == $uniforme->genero ? 'selected' : '' }}>UNISEX
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TALLA</label>
                                <select name="talla" id="talla" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tallas as $talla)
                                        <option value="{{ $talla->id }}"
                                            {{ $talla->id == $uniforme->talla_id ? 'selected' : '' }}>
                                            {{ $talla->talla }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIPO DE TELA</label>
                                <select name="tipotela" id="tipotela" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tipotelas as $tela)
                                        <option value="{{ $tela->id }}"
                                            {{ $tela->id == $uniforme->tipotela_id ? 'selected' : '' }}>
                                            {{ $tela->tela }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">COLOR</label>
                                <select name="color" id="color" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($colores as $color)
                                        <option value="{{ $color->id }}"
                                            {{ $color->id == $uniforme->color_id ? 'selected' : '' }}>
                                            {{ $color->color }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

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
        $(document).ready(function() {
            $('.select2').select2({});
        });
    </script>
@endpush
