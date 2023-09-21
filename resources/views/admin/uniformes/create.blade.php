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
                    <h4>AÑADIR UNIFORME
                        <a href="{{ url('admin/uniformes') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/uniformes') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas" required
                                    value="{{ old('nombre') }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO(soles)</label>
                                <input type="number" name="precio" id="precio" min="0" step="0.0001"
                                    class="form-control " required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">GENERO</label>
                                <select name="genero" id="genero" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="MASCULINO">MASCULINO</option>
                                    <option value="FEMENINO">FEMENINO</option>
                                    <option value="UNISEX">UNISEX</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TALLA</label>
                                <select name="talla" id="talla" class="form-select select22" onchange="agregartalla();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tallas as $talla)
                                        <option value="{{ $talla->id }}" >{{ $talla->talla }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TIPO DE TELA</label>
                                <select name="tipotela" id="tipotela" class="form-select select22" onchange="agregartipotela();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($tipotelas as $tela)
                                        <option value="{{ $tela->id }}" >{{ $tela->tela }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">COLOR</label>
                                <select name="color" id="color" class="form-select select22" onchange="agregarcolor();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($colores as $color)
                                        <option value="{{ $color->id }}" >{{ $color->color }}
                                        </option>
                                    @endforeach
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

        function agregartalla() {
            var talla = document.getElementById("talla").value;
            var urladd = "{{ url('admin/uniformes/addtalla') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + talla,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        } 

        function agregartipotela() {
            var tipotela = document.getElementById("tipotela").value;
            var urladd = "{{ url('admin/uniformes/addtipotela') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + tipotela,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function agregarcolor() {
            var color = document.getElementById("color").value;
            var urladd = "{{ url('admin/uniformes/addcolor') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + color,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        } 
 
    </script>
@endpush
