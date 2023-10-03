@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>EDITAR TIENDA
                        <a href="{{ url('admin/tienda') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/tienda/' . $tienda->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label is-required">Nombre</label>
                                        <input type="text" name="nombre" class="form-control mayusculas" required
                                            value="{{ $tienda->nombre }}" />
                                        @error('nombre')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Persona a cargo</label>
                                        <input type="text" name="encargado" id="encargado"
                                            class="form-control mayusculas" value="{{ $tienda->encargado }}" />
                                        @error('encargado')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label is-required">UBICACION</label>
                                        <input type="text" name="ubicacion" id="ubicacion" class="form-control mayusculas" required
                                            value="{{ $tienda->ubicacion }}" />
                                        @error('ubicacion')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
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
    <script> 
    </script>
@endpush
