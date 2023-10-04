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
                    <h4>AÑADIR UTIL
                        <a href="{{ url('admin/utiles') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/utiles') }}" method="POST" enctype="multipart/form-data">
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
                                <label class="form-label is-required">MARCA</label>
                                <select name="marcautil" id="marcautil" class="form-select select22" onchange="agregarmarcautil();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($marcautils as $marcautil)
                                        <option value="{{ $marcautil->id }}" >{{ $marcautil->marcautil }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> 
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">COLOR</label>
                                <select name="colorutil" id="colorutil" class="form-select select22" onchange="agregarcolorutil();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($colorutils as $colorutil)
                                        <option value="{{ $colorutil->id }}" >{{ $colorutil->colorutil }}
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

        function agregarmarcautil() {
            var marcautil = document.getElementById("marcautil").value;
            var urladd = "{{ url('admin/utiles/addmarcautil') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + marcautil,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        } 

        function agregarcolorutil() {
            var colorutil = document.getElementById("colorutil").value;
            var urladd = "{{ url('admin/utiles/addcolorutil') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + colorutil,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        } 
 
    </script>
@endpush
