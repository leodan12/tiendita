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
                    <h4>EDITAR SNACK
                        <a href="{{ url('admin/snacks') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/snacks/' . $snack->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas" required
                                    value="{{ $snack->nombre }}" />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">TAMAÑO</label>
                                <input type="text" name="tamanio" id="tamanio"  
                                    class="form-control " value="{{ $snack->tamanio }}" required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO(soles)</label>
                                <input type="number" name="precio" id="precio" min="0" step="0.0001"
                                    class="form-control " value="{{ $snack->precio }}" required />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">MARCA</label>
                                <select name="marcasnack" id="marcasnack" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($marcasnacks as $marcasnack)
                                        <option value="{{ $marcasnack->id }}"
                                            {{ $marcasnack->id == $snack->marcasnack_id ? 'selected' : '' }}>
                                            {{ $marcasnack->marcasnack }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">COLOR</label>
                                <select name="saborsnack" id="saborsnack" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @foreach ($saborsnacks as $saborsnack)
                                        <option value="{{ $saborsnack->id }}"
                                            {{ $saborsnack->id == $snack->saborsnack_id ? 'selected' : '' }}>
                                            {{ $saborsnack->saborsnack }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">FECHA VENCIMIENTO</label>
                                <input type="text" name="fechavencimiento" id="fechavencimiento"  
                                    class="form-control " value="{{ $snack->tamanio }}" required />
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
