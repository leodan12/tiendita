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
                    <h4>AÑADIR KIT
                        <a href="{{ url('admin/kits') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/kits') }}" method="POST" enctype="multipart/form-data">
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
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" class="form-control mayusculas" required
                                    value="{{ old('nombre') }}" />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">CÓDIGO</label>
                                <input type="text" name="codigo" class="form-control mayusculas"
                                    value="{{ old('codigo') }}" />
                                @error('codigo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">TASA CAMBIO</label>
                                <input type="number" id="tasacambio" name="tasacambio" min="0" step="0.0001"
                                    class="form-control " value="{{ old('tasacambio') }}" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">Tipo de Moneda</label>
                                <select name="moneda" id="moneda" class="form-select" required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="dolares" {{ old('moneda') == 'dolares' ? 'selected' : '' }}
                                        data-moneda="dolares">Dolares Americanos</option>
                                    <option value="soles" {{ old('moneda') == 'soles' ? 'selected' : '' }}
                                        data-moneda="soles">Soles</option>
                                </select>
                                @error('moneda')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO COMPRA</label>
                                    <span class="input-group-text" id="spanpreciocompra"></span>
                                    <input type="number" name="preciocompra" id="preciocompra" min="0"
                                        step="0.0001" class="form-control " required value="{{ old('preciocompra') }}" />
                                    @error('preciocompra')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO VENTA SIN IGV </label>
                                    <span class="input-group-text" id="spanNoIGV"></span>
                                    <input type="number" name="NoIGV" id="NoIGV" min="0" step="0.0001"
                                        class="form-control  " required value="{{ old('NoIGV') }}" />
                                    @error('NoIGV')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO CON IGV </label>
                                    <span class="input-group-text" id="spanSiIGV"></span>
                                    <input type="number" name="SiIGV" id="SiIGV" min="0.1" step="0.0001"
                                        class="form-control  " required readonly value="{{ old('SiIGV') }}" />
                                    @error('SiIGV')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <br>
                                <input class="form-check-input" type="checkbox" value="" name="precioxmayor"
                                    id="precioxmayor">
                                <label class="form-check-label" for="flexCheckDefault">
                                    ¿Agregar Precio por Mayor?
                                </label>
                            </div>
                            <div class="col-md-4 mb-3" id="dcantidad2" name="dcantidad2" style="display: none;">
                                <label class="form-label ">CANTIDAD 2</label>
                                <input type="number" name="cantidad2" id="cantidad2" min="1" step="1"
                                    class="form-control " value="{{ old('cantidad2') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="dprecio2" name="dprecio2" style="display: none;">
                                <label class="form-label">PRECIO SIN IGV 2</label>
                                <input type="number" name="precio2" id="precio2" min="0" step="0.0001"
                                    class="form-control " value="{{ old('precio2') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="saltodelinea1" style="display: none;"> </div>
                            <div class="col-md-4 mb-3" id="dcantidad3" name="dcantidad3" style="display: none;">
                                <label class="form-label ">CANTIDAD 3</label>
                                <input type="number" name="cantidad3" id="cantidad3" min="1" step="1"
                                    class="form-control " value="{{ old('cantidad3') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="dprecio3" name="dprecio3" style="display: none;">
                                <label class="form-label">PRECIO SIN IGV 3</label>
                                <input type="number" name="precio3" id="precio3" min="0" step="0.0001"
                                    class="form-control " value="{{ old('precio3') }}" />
                            </div>
                            <div class="col-md-4 mb-3" id="saltodelinea" style="display: none;"> </div>
                            @can('ver-preciofob')
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">PRECIO FOB</label>
                                    <input type="number" name="preciofob" id="preciofob" min="0" step="0.0001"
                                        class="form-control " value="{{ old('preciofob') }}" />
                                </div>
                            @endcan
                            <div class="col-md-4 mb-3">
                                <br>
                                <input class="form-check-input" type="checkbox" value="" name="arneselectrico"
                                    id="arneselectrico">
                                <label class="form-check-label" for="flexCheckDefault">
                                    ¿Este Kit es un arnes electrico?
                                </label>
                            </div>
                            <div class="col-md-4 mb-3" id="saltodelinea"> </div>
                            <div class="col-md-4 mb-3" id="divcarroceria" style="display: none;">
                                <label class="form-label is-required">CARROCERIA</label>
                                <select class="form-select  " name="carroceria_id" id="carroceria_id">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($carrocerias as $carroceria)
                                        <option value="{{ $carroceria->id }}"> {{ $carroceria->tipocarroceria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="divmodelo" style="display: none;">
                                <label class="form-label is-required">MODELO</label>
                                <select class="form-select  " name="modelo_id" id="modelo_id" disabled>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($modelos as $modelo)
                                        <option value="{{ $modelo->id }}"> {{ $modelo->modelo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <hr>
                            <h4>Agregar Detalle del kit</h4>
                            <div id="divdetalles">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PRODUCTO</label>
                                        <select class="form-select select2 " name="product" id="product" disabled>
                                            <option value="" disabled selected>Seleccione una opción</option>
                                            @foreach ($products as $product)
                                                <option id="miproducto{{ $product->id }}" value="{{ $product->id }}"
                                                    data-name="{{ $product->nombre }}"
                                                    data-moneda="{{ $product->moneda }}"
                                                    data-stock="{{ $product->stockempresa }}"
                                                    data-price="{{ $product->NoIGV }}">
                                                    {{ $product->nombre }} - {{ $product->codigo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                        <input type="number" name="cantidad" id="cantidad" min="1"
                                            step="1" class="form-control " />
                                        @error('cantidad')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="input-group">
                                            <label class="form-label input-group" id="labelpreciounitarioref">PRECIO
                                                UNITARIO
                                                (REFERENCIAL):</label>
                                            <span class="input-group-text" id="spanpreciounitarioref"></span>
                                            <input type="number" name="preciounitario" min="0" step="0.0001"
                                                id="preciounitario" readonly class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="input-group">
                                            <label class="form-label input-group" id="labelpreciounitario">PRECIO
                                                UNITARIO:</label>
                                            <span class="input-group-text" id="spanpreciounitario"></span>
                                            <input type="number" name="preciounitariomo" min="0" step="0.0001"
                                                id="preciounitariomo" class="form-control " />
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="input-group">
                                            <label class="form-label input-group" id="labelpreciototal">PRECIO TOTAL POR
                                                PRODUCTO:</label>
                                            <span class="input-group-text" id="spanpreciototal"></span>
                                            <input type="number" name="preciofinal" min="0" step="0.0001"
                                                id="preciofinal" readonly class="form-control " />
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-info" id="addDetalleBatch"><i
                                            class="fa fa-plus"></i>
                                        Agregar Producto al Kit</button>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-row-bordered gy-5 gs-5" id="detallesKit">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>PRODUCTO</th>
                                                    <th>CANTIDAD</th>
                                                    <th>PRECIO UNITARIO(REFERENCIAL)</th>
                                                    <th>PRECIO UNITARIO</th>
                                                    <th>PRECIO FINAL DEL PRODUCTO</th>
                                                    <th>ELIMINAR</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="divmaterialesyredes" style="display:none;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-row-bordered gy-5 gs-5" id="materialesredes">
                                        <thead class="fw-bold text-primary">
                                            <tr>
                                                <th>CANTIDAD</th>
                                                <th>UNIDAD</th>
                                                <th>PRODUCTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>

                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end" name="btnguardar"
                                    id="btnguardar" disabled>Guardar</button>
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
        var indice = 0;
        var ventatotal = 0;
        var preciounit = 0;
        var nameproduct = 0;
        var preciototalI = 0;
        var estadoguardar = 0;
        var monedafactura = "";
        var monedaproducto = "";
        var monedaantigua = 0;
        var simbolomonedaproducto = "";
        var simbolomonedafactura = "";
        var indicex = 0;
        var micantidad2 = "";
        var micantidad3 = "";
        var miprecio2 = "";
        var miprecio3 = "";
        var misproductos = @json($products);

        $(document).ready(function() {
            miprecioxmayor();
            traertasacambio();
            document.getElementById("NoIGV").onchange = function() {
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
            $("#btnguardar").prop("disabled", true);
        });

        document.getElementById("cantidad").onchange = function() {
            preciofinal();
        };
        document.getElementById("preciounitariomo").onchange = function() {
            preciofinal();
        };

        var checkbox = document.getElementById("arneselectrico");
        checkbox.addEventListener("change", function() {
            if (checkbox.checked) {
                document.getElementById("divdetalles").style.display = "none";
                document.getElementById("divmaterialesyredes").style.display = "inline";
                document.getElementById("divcarroceria").style.display = "inline";
                document.getElementById("divmodelo").style.display = "inline";
                limpiardatos();
            } else {
                document.getElementById("divdetalles").style.display = "inline";
                document.getElementById("divmaterialesyredes").style.display = "none";
                document.getElementById("divcarroceria").style.display = "none";
                document.getElementById("divmodelo").style.display = "none";
                estadoguardar = 1;
                botonguardar("eliminar");
                document.getElementById("modelo_id").disabled = true;
                limpiardatos();
            }

        });

        function limpiardatos() {
            ventatotal = 0;
            preciounit = 0;
            nameproduct = 0;
            preciototalI = 0;
            document.getElementById('NoIGV').value = 0
            document.getElementById('SiIGV').value = 0
            $('#detallesKit tbody tr').slice().remove();
            $('#materialesredes tbody tr').slice().remove();
            $('#carroceria_id').val(null).trigger('change');
            $('#modelo_id').val(null).trigger('change');
             
            var select = document.getElementById("product"); 
            for (var i = 0; i < select.options.length; i++) {
                select.options[i].removeAttribute("disabled");
            }

        }

        function traerMR() {
            var urltraerMR = "{{ url('admin/kits/traermaterialyredes') }}";
            var idcarroceria = document.getElementById("carroceria_id").value;
            var idmodelo = document.getElementById("modelo_id").value;
            $.ajax({
                type: "GET",
                url: urltraerMR + '/' + idcarroceria + '/' + idmodelo,
                success: function(data) {
                    console.log(data);
                    mostrartablamateriales(data);
                    document.getElementById("divdetalles").style.display = "none";
                    document.getElementById("divmaterialesyredes").style.display = "inline";
                }
            });
        }

        function mostrartablamateriales(data) {
            $('#materialesredes tbody tr').slice().remove();
            for (var x = 0; x < data.length; x++) {
                var filaDetalle = "";
                if (data[x].tipo == "estandar") {
                    filaDetalle = '<tr id="fila' + indice +
                        '"><td>' + data[x].cantidad +
                        '</td><td>' + data[x].unidad +
                        '</td><td> <b>' + data[x].nombre +
                        '</b> </td> </tr>';
                } else if (data[x].tipo == "kit") {
                    var urldetalles = "{{ url('admin/venta/productosxkit') }}";
                    $.ajax({
                        type: "GET",
                        url: urldetalles + '/' + data[x].idproducto,
                        async: false,
                        success: function(datak) {
                            console.log(datak);
                            var milista = '<br>';
                            var puntos = ': ';
                            for (var j = 0; j < datak.length; j++) {
                                var coma = '<br>';
                                milista = milista + '-' + datak[j].cantidad + ' ' +
                                    datak[j].producto + coma;
                            }
                            filaDetalle = '<tr id="fila' + indice +
                                '"><td>' + data[x].cantidad +
                                '</td><td>' + data[x].unidad +
                                '</td><td><b>' + data[x].nombre + '</b>' + milista + '</td> </tr>';
                        }
                    });
                }
                $("#materialesredes>tbody").append(filaDetalle);
                botonguardar("agregar");
            }
        }

        $("#carroceria_id").change(function() {
            $("#carroceria_id option:selected").each(function() {
                var carroceria = $(this).val();
                if (carroceria) {
                    document.getElementById("modelo_id").disabled = false;
                }
            });
        });
        $("#modelo_id").change(function() {
            $("#modelo_id option:selected").each(function() {
                var modelo = $(this).val();
                if (modelo) {
                    traerMR();
                }
            });
        });

        function preciofinal() {
            var cantidad = $('[name="cantidad"]').val();
            var preciounit = $('[name="preciounitariomo"]').val();
            if (cantidad >= 1 && preciounit >= 0) {
                preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)));
                document.getElementById('preciofinal').value = parseFloat(preciototalI.toFixed(4));
            }
        }

        function habilitaroptionsproductos() {
            for (var i = 0; i < misproductos.length; i++) {
                document.getElementById('miproducto' + misproductos[i].id).disabled = false;
            }
        }

        function IGVtotal() {
            preciototal = 0;
            var cantidad = $('[name="NoIGV"]').val();
            if (cantidad.length != 0) {
                //alert("final");
                preciototal = parseFloat(cantidad) + (parseFloat(cantidad) * 0.18);
                document.getElementById('SiIGV').value = parseFloat((preciototal).toFixed(4));
            }
        }

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

        $("#moneda").change(function() {
            $('#product').removeAttr('disabled');
            $("#moneda option:selected").each(function() {
                $mimoneda = $(this).data("moneda");
                if ($mimoneda == "dolares") {
                    simbolomonedafactura = "$";
                } else if ($mimoneda == "soles") {
                    simbolomonedafactura = "S/.";
                }
                document.getElementById('spanNoIGV').innerHTML = simbolomonedafactura;
                document.getElementById('spanSiIGV').innerHTML = simbolomonedafactura;
                document.getElementById('spanpreciocompra').innerHTML = simbolomonedafactura;
                if (monedaantigua = 0) {
                    monedafactura = $mimoneda;
                    monedaantigua = 1;
                } else {
                    monedaantigua = monedafactura;
                    monedafactura = $mimoneda;
                    var indice3 = indice;
                    for (var i = 0; i < indice3; i++) {
                        eliminarTabla(i);
                    }
                    habilitaroptionsproductos();
                }
            });
            limpiarinputs();
        });

        $("#product").change(function() {
            $("#product option:selected").each(function() {
                $price = $(this).data("price");
                $named = $(this).data("name");
                $moneda = $(this).data("moneda");
                monedaproducto = $moneda;

                var mitasacambio1 = $('[name="tasacambio"]').val();
                //var mimoneda1 = $('[name="moneda"]').val();

                if ($price != null) {
                    preciounit = parseFloat(($price).toFixed(4));
                    if (monedaproducto == "dolares" && monedafactura == "dolares") {
                        simbolomonedaproducto = "$";
                        preciototalI = parseFloat(($price).toFixed(4));
                        document.getElementById('preciounitario').value = parseFloat(($price).toFixed(4));
                        document.getElementById('preciounitariomo').value = parseFloat(($price).toFixed(4));
                        document.getElementById('preciofinal').value = parseFloat(($price).toFixed(4));
                    } else if (monedaproducto == "soles" && monedafactura == "soles") {
                        simbolomonedaproducto = "S/.";
                        preciototalI = parseFloat(($price).toFixed(4));
                        document.getElementById('preciounitario').value = parseFloat(($price).toFixed(4));
                        document.getElementById('preciounitariomo').value = parseFloat(($price).toFixed(4));
                        document.getElementById('preciofinal').value = parseFloat(($price).toFixed(4));
                    } else if (monedaproducto == "dolares" && monedafactura == "soles") {
                        simbolomonedaproducto = "$";
                        preciototalI = parseFloat(($price * mitasacambio1).toFixed(4));
                        document.getElementById('preciounitario').value = parseFloat(($price).toFixed(4));
                        document.getElementById('preciounitariomo').value = parseFloat(($price *
                            mitasacambio1).toFixed(4));
                        document.getElementById('preciofinal').value = parseFloat(($price * mitasacambio1)
                            .toFixed(4));
                    } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                        simbolomonedaproducto = "S/.";
                        preciototalI = parseFloat(($price / mitasacambio1).toFixed(4));
                        document.getElementById('preciounitario').value = parseFloat(($price).toFixed(4));
                        document.getElementById('preciounitariomo').value = parseFloat(($price /
                            mitasacambio1).toFixed(4));
                        document.getElementById('preciofinal').value = parseFloat(($price / mitasacambio1)
                            .toFixed(4));
                    }
                    document.getElementById('labelpreciounitarioref').innerHTML =
                        "PRECIO UNITARIO(REFERENCIAL): " + monedaproducto;
                    document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: " +
                        monedafactura;
                    document.getElementById('labelpreciototal').innerHTML = "PRECIO TOTAL POR PRODUCTO: " +
                        monedafactura;
                    document.getElementById('spanpreciounitarioref').innerHTML = simbolomonedaproducto;
                    document.getElementById('spanpreciounitario').innerHTML = simbolomonedafactura;
                    document.getElementById('spanpreciototal').innerHTML = simbolomonedafactura;
                    document.getElementById('cantidad').value = 1;
                    nameproduct = $named;
                } else if ($price == null) {
                    document.getElementById('cantidad').value = "";
                    document.getElementById('preciofinal').value = "";
                    document.getElementById('preciounitario').value = "";
                    document.getElementById('preciounitariomo').value = "";
                }
                //alert(nameprod);
            });
        });

        $('#addDetalleBatch').click(function() {

            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitario"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
            //alertas para los detallesBatch

            if (!product) {
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!preciounitariomo) {
                alert("Ingrese un precio unitario");
                return;
            }


            //$("#product option:contains('Seleccione una opción')").attr('selected',false);  
            var LVenta = [];
            var tam = LVenta.length;
            LVenta.push(product, nameproduct, cantidad, preciounitario, preciounitariomo, preciofinal);

            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required>' + LVenta[1] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[
                    2] + '"required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice +
                '" value="' + LVenta[3] + '"required>' + simbolomonedaproducto + LVenta[3] +
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice +
                '" value="' + LVenta[4] + '"required>' + simbolomonedafactura + LVenta[4] +
                '</td><td><input  type="hidden" name="Lpreciofinal[]" id="preciof' + indice + '" value="' + LVenta[
                    5] + '"required>' + simbolomonedafactura + LVenta[5] +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' +
                product +
                ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesKit>tbody").append(filaDetalle);

            indice++;
            ventatotal = (parseFloat(ventatotal) + parseFloat(preciototalI)).toFixed(4);

            limpiarinputs();
            document.getElementById('NoIGV').value = parseFloat(ventatotal);
            document.getElementById('SiIGV').value = parseFloat((ventatotal * 1.18).toFixed(4));
            document.getElementById('miproducto' + product).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        });

        function eliminarFila(ind, product) {
            var resta = 0;
            resta = $('[id="preciof' + ind + '"]').val();
            //alert(resta);
            ventatotal = ventatotal - resta;

            $('#fila' + ind).remove();
            indice--;
            // damos el valor
            document.getElementById('NoIGV').value = parseFloat((ventatotal).toFixed(4));
            document.getElementById('SiIGV').value = parseFloat((ventatotal * 1.18).toFixed(4));
            //alert(resta);
            document.getElementById('miproducto' + product).disabled = false;

            var funcion = "eliminar";
            botonguardar(funcion);

            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            indice--;

            // damos el valor
            document.getElementById('NoIGV').value = 0
            document.getElementById('SiIGV').value = 0

            var funcion = "eliminar";
            botonguardar(funcion);

            ventatotal = 0;
            preciounit = 0;
            nameproduct = 0;
            preciototalI = 0;

            return false;
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: ";
            document.getElementById('labelpreciounitarioref').innerHTML = "PRECIO UNITARIO(REFERENCIAL): ";
            document.getElementById('labelpreciototal').innerHTML = "PRECIO TOTAL POR PRODUCTO:";
            document.getElementById('spanpreciounitarioref').innerHTML = "";
            document.getElementById('spanpreciounitario').innerHTML = "";
            document.getElementById('spanpreciototal').innerHTML = "";
            document.getElementById('cantidad').value = "";
            document.getElementById('preciofinal').value = "";
            document.getElementById('preciounitario').value = "";
            document.getElementById('preciounitariomo').value = "";
            monedaproducto = "";
            simbolomonedaproducto = "";
        }

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar <= 1) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar > 1) {
                $("#btnguardar").prop("disabled", false);
            }
        }
    </script>
@endpush
