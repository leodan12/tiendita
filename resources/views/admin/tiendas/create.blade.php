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
                <h4>AÑADIR TIENDA
                    <a href="{{ url('admin/tienda') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/tienda') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label is-required">Nombre</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control mayusculas" value="{{ old('nombre') }}" required />
                                    @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label is-required">Persona a cargo</label>
                                    <input type="text" name="encargado" id="encargado" class="form-control mayusculas" value="{{ old('encargado') }}" required/>
                                    @error('encargado')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label is-required">UBICACION</label>
                                    <input type="text" name="ubicacion" id="ubicacion" class="form-control " required value="{{ old('ubicacion') }}"  />
                                    @error('ubicacion')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type="submit" id="enviar" class="btn btn-primary text-white float-end">Guardar</button>
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

</script>
@endpush