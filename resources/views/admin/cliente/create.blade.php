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
                    <h4>AÑADIR CLIENTE / PROVEEDOR
                        <a href="{{ url('admin/cliente') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/cliente') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Nombre</label>
                                <input type="text" name="nombre" class="form-control mayusculas" required value="{{ old('nombre') }}" />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">RUC o DNI</label>
                                <input type="number" name="ruc" id="ruc" class="form-control " required value="{{ old('ruc') }}" />
                                @error('ruc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Direccion</label>
                                <input type="text" name="direccion" class="form-control mayusculas" value="{{ old('direccion') }}"/>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefono</label>
                                <input type="text" name="telefono" class="form-control mayusculas" value="{{ old('telefono') }}"/>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control "  value="{{ old('email') }}"/>

                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="enviar" class="btn btn-primary text-white float-end"
                                    disabled>Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        ruc.oninput = function() {
            //result.innerHTML = password.value;
            verificar();
        }

        function verificar() {

            ruc1 = document.getElementById('ruc');
            enviar = document.getElementById('enviar');

            if (ruc1.value.length == 11 || ruc1.value.length == 8) {
                ruc1.style.borderColor = "green";
                enviar.disabled = false;
            } else {
                ruc1.style.borderColor = "red";
                enviar.disabled = true;
            }
        }
    </script>
@endpush
