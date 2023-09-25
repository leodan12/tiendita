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
                    <h4>AÑADIR LIBRO
                        <a href="{{ url('admin/libros') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/libros') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">TITULO</label>
                                <input type="text" name="titulo" id="titulo" class="form-control mayusculas" required
                                    value="{{ old('titulo') }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO(soles)</label>
                                <input type="number" name="precio" id="precio" min="0" step="0.0001"
                                    class="form-control " required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">AÑO</label>
                                <input type="number" name="anio" id="anio" min="0"  
                                    class="form-control " required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">AUTOR</label>
                                <input type="text" name="autor" id="autor" class="form-control mayusculas" required
                                    value="{{ old('autor') }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">ESPECIALIZACION</label>
                                <select name="especializacion" id="especializacion" class="form-select select22"
                                    onchange="agregarespecializacion();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($especializaciones as $especializacion)
                                        <option value="{{ $especializacion->id }}">{{ $especializacion->especializacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">EDICION</label>
                                <select name="edicion" id="edicion" class="form-select select22"
                                    onchange="agregaredicion();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($ediciones as $edicion)
                                        <option value="{{ $edicion->id }}">{{ $edicion->edicion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">FORMATO</label>
                                <select name="formato" id="formato" class="form-select select22 "
                                    onchange="agregarformato();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($formatos as $formato)
                                        <option value="{{ $formato->id }}">{{ $formato->formato }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIPO DE PAPEL</label>
                                <select name="tipopapel" id="tipopapel" class="form-select select22"
                                    onchange="agregartipopapel();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tipopapeles as $tipopapel)
                                        <option value="{{ $tipopapel->id }}">{{ $tipopapel->tipopapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIPO DE PASTA</label>
                                <select name="tipopasta" id="tipopasta" class="form-select select22"
                                    onchange="agregartipopasta();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tipopastas as $tipopasta)
                                        <option value="{{ $tipopasta->id }}">{{ $tipopasta->tipopasta }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">ORIGINAL?</label>
                                <select name="original" id="original" class="form-select" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="ORIGINAL">ORIGINAL</option>
                                    <option value="COPIA">COPIA</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Guardar</button>
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
            $('.select22').select2({
                tags: true
            });
        });

        function agregarformato() {
            var formato = document.getElementById("formato").value;
            var urladd = "{{ url('admin/libros/addformato') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + formato,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function agregartipopapel() {
            var tipopapel = document.getElementById("tipopapel").value;
            var urladd = "{{ url('admin/libros/addtipopapel') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + tipopapel,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function agregartipopasta() {
            var tipopasta = document.getElementById("tipopasta").value;
            var urladd = "{{ url('admin/libros/addtipopasta') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + tipopasta,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function agregaredicion() {
            var edicion = document.getElementById("edicion").value;
            var urladd = "{{ url('admin/libros/addedicion') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + edicion,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function agregarespecializacion() {
            var especializacion = document.getElementById("especializacion").value;
            var urladd = "{{ url('admin/libros/addespecializacion') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + especializacion,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }
    </script>
@endpush
