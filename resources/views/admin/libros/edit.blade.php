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
                    <h4>EDITAR LIBRO
                        <a href="{{ url('admin/libros') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/libros/' . $libro->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">TITULO</label>
                                <input type="text" name="titulo" id="titulo" class="form-control mayusculas" required
                                    value="{{ $libro->titulo }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">AÑO</label>
                                <input type="number" name="anio" id="anio" min="0"  
                                    class="form-control " value="{{ $libro->anio }}" required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO(soles)</label>
                                <input type="number" name="precio" id="precio" min="0" step="0.0001"
                                    class="form-control " value="{{ $libro->precio }}" required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">AUTOR</label>
                                <input type="text" name="autor" id="autor" class="form-control mayusculas" required
                                    value="{{ $libro->autor }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">ESPECIALIZACION</label>
                                <select name="especializacion" id="especializacion" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($especializaciones as $especializacion)
                                        <option value="{{ $especializacion->id }}"
                                            {{ $especializacion->id == $libro->especializacion_id ? 'selected' : '' }}>
                                            {{ $especializacion->especializacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">EDICION</label>
                                <select name="edicion" id="edicion" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($ediciones as $edicion)
                                        <option value="{{ $edicion->id }}"
                                            {{ $edicion->id == $libro->edicion_id ? 'selected' : '' }}>
                                            {{ $edicion->edicion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">FORMATO</label>
                                <select name="formato" id="formato" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($formatos as $formato)
                                        <option value="{{ $formato->id }}"
                                            {{ $formato->id == $libro->formato_id ? 'selected' : '' }}>
                                            {{ $formato->formato }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIPO DE PAPEL</label>
                                <select name="tipopapel" id="tipopapel" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tipopapeles as $tipopapel)
                                        <option value="{{ $tipopapel->id }}"
                                            {{ $tipopapel->id == $libro->tipopapel_id ? 'selected' : '' }}>
                                            {{ $tipopapel->tipopapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIPO DE PASTA</label>
                                <select name="tipopasta" id="tipopasta" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tipopastas as $tipopasta)
                                        <option value="{{ $tipopasta->id }}"
                                            {{ $tipopasta->id == $libro->tipopasta_id ? 'selected' : '' }}>
                                            {{ $tipopasta->tipopasta }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">ORIGINAL?</label>
                                <select name="genero" id="genero" class="form-select" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="ORIGINAL" {{ 'ORIGINAL' == $libro->original ? 'selected' : '' }}>
                                        ORIGINAL</option>
                                    <option value="COPIA" {{ 'COPIA' == $libro->original ? 'selected' : '' }}>
                                        COPIA</option>
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
