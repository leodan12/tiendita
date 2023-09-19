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
                    <h4>AÑADIR PRODUCTO
                        <a href="{{ url('admin/products') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/products') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">CATEGORIA</label>
                                <select class="form-select select2" id="category_id" name="category_id" required
                                    data-show-subtext="true" data-live-search="true">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" id="nombre" class="form-control mayusculas" required
                                    value="{{ old('nombre') }}" />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">CÓDIGO</label>
                                <input type="text" name="codigo" class="form-control mayusculas" value="{{ old('codigo') }}" />
                                @error('codigo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">UNIDAD</label>
                                <input type="text" name="unidad" class="form-control mayusculas" value="{{ old('unidad') }}"
                                    required />
                                @error('unidad')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">Tipo de Moneda</label>
                                <select name="moneda" id="moneda" class="form-select" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="dolares" {{ old('moneda') == 'dolares' ? 'selected' : '' }}>Dolares
                                        Americanos</option>
                                    <option value="soles" {{ old('moneda') == 'soles' ? 'selected' : '' }}>Soles</option>
                                </select>
                                @error('moneda')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO COMPRA</label>
                                <input type="number" name="preciocompra" id="preciocompra" min="0" step="0.0001"
                                    class="form-control " required value="{{ old('preciocompra') }}" />
                                @error('preciocompra')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO VENTA SIN IGV</label>
                                <input type="number" name="NoIGV" id="cantidad" min="0" step="0.0001"
                                    class="form-control " required value="{{ old('NoIGV') }}" />
                                @error('NoIGV')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">PRECIO CON IGV</label>
                                <input type="number" name="SiIGV" id="SiIGV" min="0" step="0.0001" readonly
                                    class="form-control " required value="{{ old('SiIGV') }}" />
                                @error('SiIGV')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-check-input" type="checkbox" value="" name="precioxmayor"
                                    id="precioxmayor">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Agregar Precio por Mayor
                                </label>
                            </div>

                            <div class="col-md-4 mb-3" id="dcantidad2" name="dcantidad2">
                                <label class="form-label ">CANTIDAD 2</label>
                                <input type="number" name="cantidad2" id="cantidad2" min="1" step="1"
                                    class="form-control " value="{{ old('cantidad2') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="dprecio2" name="dprecio2">
                                <label class="form-label">PRECIO SIN IGV 2</label>
                                <input type="number" name="precio2" id="precio2" min="0" step="0.0001"
                                    class="form-control " value="{{ old('precio2') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="saltodelinea1"> </div>
                            <div class="col-md-4 mb-3" id="dcantidad3" name="dcantidad3">
                                <label class="form-label ">CANTIDAD 3</label>
                                <input type="number" name="cantidad3" id="cantidad3" min="1" step="1"
                                    class="form-control " value="{{ old('cantidad3') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="dprecio3" name="dprecio3">
                                <label class="form-label">PRECIO SIN IGV 3</label>
                                <input type="number" name="precio3" id="precio3" min="0" step="0.0001"
                                    class="form-control " value="{{ old('precio3') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="saltodelinea"> </div>
                            @can('ver-preciofob')
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">PRECIO FOB</label>
                                    <input type="number" name="preciofob" id="preciofob" min="0" step="0.0001"
                                        class="form-control " value="{{ old('preciofob') }}" />
                                </div>
                            @endcan
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
        var micantidad2 = "";
        var micantidad3 = "";
        var miprecio2 = "";
        var miprecio3 = "";
        $(document).ready(function() {

            miprecioxmayor();
            document.getElementById("cantidad").onchange = function() {
                IGVtotal();
            };
            $('.select2').select2();

            document.getElementById("precioxmayor").onchange = function() {
                miprecioxmayor();
            };
            $("#cantidad2").change(function() {
                micantidad2 = document.getElementById('cantidad2').value;
            });
            $("#cantidad3").change(function() {
                micantidad3 = document.getElementById('cantidad3').value;
            });
            $("#precio2").change(function() {
                miprecio2 = document.getElementById('precio2').value;
            });
            $("#precio3").change(function() {
                miprecio3 = document.getElementById('precio3').value;
            });

        });

        function miprecioxmayor() {
            if ($('#precioxmayor').prop('checked')) {
                document.getElementById('dcantidad2').style.display = 'inline';
                document.getElementById('dcantidad3').style.display = 'inline';
                document.getElementById('dprecio2').style.display = 'inline';
                document.getElementById('dprecio3').style.display = 'inline';
                document.getElementById('cantidad2').value = micantidad2;
                document.getElementById('cantidad3').value = micantidad3;
                document.getElementById('precio2').value = miprecio2;
                document.getElementById('precio3').value = miprecio3;
                document.getElementById('saltodelinea').style.display = 'inline';
                document.getElementById('saltodelinea1').style.display = 'inline';
            } else {
                document.getElementById('dcantidad2').style.display = 'none';
                document.getElementById('dcantidad3').style.display = 'none';
                document.getElementById('dprecio2').style.display = 'none';
                document.getElementById('dprecio3').style.display = 'none';
                document.getElementById('cantidad2').value = "";
                document.getElementById('cantidad3').value = "";
                document.getElementById('precio2').value = "";
                document.getElementById('precio3').value = "";
                document.getElementById('saltodelinea').style.display = 'none';
                document.getElementById('saltodelinea1').style.display = 'none';
            }
        }

        function IGVtotal() {
            preciototal = 0;
            var cantidad = $('[name="NoIGV"]').val();
            if (cantidad.length != 0) {
                //alert("final");
                preciototal = parseFloat(cantidad) + (parseFloat(cantidad) * 0.18);
                document.getElementById('SiIGV').value = parseFloat(preciototal.toFixed(4));
            }
        }
    </script>
@endpush
