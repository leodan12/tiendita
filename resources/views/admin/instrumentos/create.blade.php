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
                    <h4>AÑADIR INSTRUMENTO
                        <a href="{{ url('admin/instrumentos') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/instrumentos') }}" method="POST" enctype="multipart/form-data">
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
                                <label class="form-label is-required">MARCAS</label>
                                <select name="marca" id="marca" class="form-select select22" onchange="agregarmarca();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" >{{ $marca->marca }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">MODELO</label>
                                <select name="modelo" id="modelo" class="form-select select22" onchange="agregarmodelo();" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->id }}" >{{ $modelo->modelo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">GARANTIA (MESES)</label>
                                <input type="number" name="garantia" id="garantia" min="0"  
                                    class="form-control " required />
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

        function agregarmodelo() {
            var modelo = document.getElementById("modelo").value;
            var urladd = "{{ url('admin/instrumentos/addmodelo') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + modelo,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        } 

        function agregarmarca() {
            var marca = document.getElementById("marca").value;
            var urladd = "{{ url('admin/instrumentos/addmarca') }}";
            $.ajax({
                type: "GET",
                url: urladd + '/' + marca,
                async: true,
                success: function(data) {
                    console.log(data);
                }
            });
        }
 
    </script>
@endpush
