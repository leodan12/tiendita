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
                    <h4>EDITAR UTIL
                        <a href="{{ url('admin/utiles') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/utiles/' . $utile->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-9 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas" required
                                    value="{{ $utile->nombre }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO(soles)</label>
                                <input type="number" name="precio" id="precio" min="0" step="0.0001"
                                    class="form-control " value="{{ $utile->precio }}" required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">MARCA</label>
                                <select name="marcautil" id="marcautil" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($marcautils as $marcautil)
                                        <option value="{{ $marcautil->id }}"
                                            {{ $marcautil->id == $utile->marcautil_id ? 'selected' : '' }}>
                                            {{ $marcautil->marcautil }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">COLOR</label>
                                <select name="colorutil" id="colorutil" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($colorutils as $colorutil)
                                        <option value="{{ $colorutil->id }}"
                                            {{ $colorutil->id == $utile->colorutil_id ? 'selected' : '' }}>
                                            {{ $colorutil->colorutil }}
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
