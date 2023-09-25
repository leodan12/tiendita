<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ELECTROBUS S.A.C') }}</title>
    {{-- <script src="{{ asset('admin/jsusados/popover.min.js') }}"></script> --}}

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- inject:css -->


    <link rel="stylesheet" href="{{ asset('admin/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <!-- endinject -->

    {{-- librerias para el datatables server side --}}
    <link rel="stylesheet" href="{{ asset('admin/jsusados/DataTables/datatables.min.css') }}">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"
        rel="stylesheet" /> --}}
    {{-- <link
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.css"
        rel="stylesheet" /> --}}

    @stack('css')
</head>

<body>

    @yield('alertax')

    <div class="container-scroller">
        @include('layouts.inc.admin.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('layouts.inc.admin.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- ------------------ espacio content-------------------------- --}}
                    @yield('content')
                    {{-- ------------------ espacio content -------------------------- --}}

                    <div class="modal fade" id="modaltasacambio" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="modaltasacambioLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modaltasacambioLabel">ACTUALIZAR LA TASA CAMBIO</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label" name="labeltipocambioayer"
                                            id="labeltipocambioayer">TIPO CAMBIO</label>
                                        <input type="number" name="tasacambioayer" min="0" step="0.001"
                                            class="form-control " id="tasacambioayer" readonly />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label is-required" id="labeltipocambiohoy">TIPO CAMBIO
                                            HOY</label>
                                        <input type="number" name="tasacambiom" min="0" step="0.001"
                                            class="form-control " id="tasacambiom" />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label" id="labeltipocambiocompra">TIPO CAMBIO
                                            COMPRA:</label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="actualizartasa();"
                                        class="btn btn-success">ACTUALIZAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="{{ asset('admin/vendors/base/vendor.bundle.base.js') }}"></script>

    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>


    {{-- librerias para el datatables serverside --}}

    <script src="{{ asset('admin/jsusados/DataTables/datatables.min.js') }}"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
     --}}
    {{-- <script
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.js">
    </script> --}}

    <!-- js para la alerta-->

    <script src="{{ asset('admin/jsusados/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        //para ingresar solo mayusculas cuando tengan la clase mayusculas
        document.querySelectorAll(".mayusculas").forEach(input => input.addEventListener("input", () => input.value = input
            .value.toUpperCase()));
        //token
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        //para select2 responsive
        document.querySelectorAll(".select2").forEach(elemento => elemento.style.width = "100%");
      
    function mensaje(texto,icono){
        Swal.fire({
            text: texto,
            icon:icono
        });
    }  

    </script>

    @livewireScripts
    @stack('script')
    @yield('js')


</body>

</html>
