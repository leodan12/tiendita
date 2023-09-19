<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">INICIO</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#products" aria-expanded="false"
                aria-controls="products">
                <i class="mdi mdi-shape-plus menu-icon"></i>
                <span class="menu-title">PRODUCTOS</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="products">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-categoria') ||
                            auth()->user()->can('crear-categoria') ||
                            auth()->user()->can('editar-categoria') ||
                            auth()->user()->can('eliminar-categoria'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/category') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">CATEGORÍAS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-producto') ||
                            auth()->user()->can('crear-producto') ||
                            auth()->user()->can('editar-producto') ||
                            auth()->user()->can('eliminar-producto'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/products') }}">
                                <i class="mdi mdi-book menu-icon"></i>
                                <span class="menu-title">PRODUCTOS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-kit') ||
                            auth()->user()->can('crear-kit') ||
                            auth()->user()->can('editar-kit') ||
                            auth()->user()->can('eliminar-kit'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/kits') }}">
                                <i class="mdi mdi-google-circles-communities menu-icon"></i>
                                <span class="menu-title">KITS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-inventario') ||
                            auth()->user()->can('crear-inventario') ||
                            auth()->user()->can('editar-inventario') ||
                            auth()->user()->can('eliminar-inventario'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventorystock') }}">
                                <i class="mdi mdi-playlist-check menu-icon"></i>
                                <span class="menu-title">INVENTARIO</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-lista-precios') ||
                            auth()->user()->can('crear-lista-precios') ||
                            auth()->user()->can('editar-lista-precios') ||
                            auth()->user()->can('eliminar-lista-precios'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/listaprecios') }}">
                                <i class="mdi mdi-playlist-play menu-icon"></i>
                                <span class="menu-title">LISTA PRECIOS</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#companies" aria-expanded="false"
                aria-controls="companies">
                <i class="mdi mdi-home-modern menu-icon"></i>
                <span class="menu-title">COMPAÑIAS</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="companies">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-empresa') ||
                            auth()->user()->can('crear-empresa') ||
                            auth()->user()->can('editar-empresa') ||
                            auth()->user()->can('eliminar-empresa'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/company') }}">
                                <i class="mdi mdi-store menu-icon"></i>
                                <span class="menu-title">MIS EMPRESAS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-cliente') ||
                            auth()->user()->can('crear-cliente') ||
                            auth()->user()->can('editar-cliente') ||
                            auth()->user()->can('eliminar-cliente'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/cliente') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">CLIENTES/PROVEEDORES</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ventas" aria-expanded="false" aria-controls="ventas">
                <i class="mdi mdi-cart menu-icon"></i>
                <span class="menu-title">FACTURACIÓN</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ventas">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-ingreso') ||
                            auth()->user()->can('crear-ingreso') ||
                            auth()->user()->can('editar-ingreso') ||
                            auth()->user()->can('eliminar-ingreso'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/ingreso') }}">
                                <i class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 122.43 122.88"
                                        style="enable-background:new 0 0 122.43 122.88">
                                        <style type="text/css">
                                            .st0 {
                                                fill-rule: evenodd;
                                                clip-rule: evenodd;
                                            }
                                        </style>
                                        <path class="st0"
                                            d="M22.63,12.6h93.3c6.1,0,5.77,2.47,5.24,8.77l-3.47,44.23c-0.59,7.05-0.09,5.34-7.56,6.41l-68.62,8.73 l3.63,10.53c29.77,0,44.16,0,73.91,0c1,3.74,2.36,9.83,3.36,14h-12.28l-1.18-4.26c-24.8,0-34.25,0-59.06,0 c-13.55-0.23-12.19,3.44-15.44-8.27L11.18,8.11H0V0h19.61C20.52,3.41,21.78,9.15,22.63,12.6L22.63,12.6z M63.49,23.76h17.76v18.02 h15.98L72.39,65.95L47.51,41.78h15.98V23.76L63.49,23.76z M53.69,103.92c5.23,0,9.48,4.25,9.48,9.48c0,5.24-4.24,9.48-9.48,9.48 c-5.24,0-9.48-4.24-9.48-9.48C44.21,108.17,48.45,103.92,53.69,103.92L53.69,103.92z M92.79,103.92c5.23,0,9.48,4.25,9.48,9.48 c0,5.24-4.25,9.48-9.48,9.48c-5.24,0-9.48-4.24-9.48-9.48C83.31,108.17,87.55,103.92,92.79,103.92L92.79,103.92z" />
                                    </svg>
                                </i>
                                INGRESO</a></li>
                    @endif
                    @if (auth()->user()->can('ver-venta') ||
                            auth()->user()->can('crear-venta') ||
                            auth()->user()->can('editar-venta') ||
                            auth()->user()->can('eliminar-venta'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/venta') }}">
                                <i class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 122.43 122.88">
                                        <path
                                            d="M22.63 12.6h93.3c6.1 0 5.77 2.47 5.24 8.77l-3.47 44.23c-0.59 7.05-0.09 5.34-7.56 6.41l-68.62 8.73 l3.63 10.53c29.77 0 44.16 0 73.91 0c1 3.74 2.36 9.83 3.36 14h-12.28l-1.18-4.26c-24.8 0-34.25 0-59.06 0 c-13.55-0.23-12.19 3.44-15.44-8.27L11.18 8.11H0V0h19.61C20.52 3.41 21.78 9.15 22.63 12.6z M62.4 62.12h17.76V44.11 h15.98L71.3 19.93L46.43 44.11H62.4V62.12L62.4 62.12z M53.69 103.92c5.23 0 9.48 4.25 9.48 9.48c0 5.24-4.24 9.48-9.48 9.48 c-5.24 0-9.48-4.24-9.48-9.48C44.21 108.16 48.45 103.92 53.69 103.92L53.69 103.92z M92.79 103.92c5.23 0 9.48 4.25 9.48 9.48 c0 5.24-4.25 9.48-9.48 9.48c-5.24 0-9.48-4.24-9.48-9.48C83.31 108.16 87.55 103.92 92.79 103.92L92.79 103.92z" />
                                    </svg>

                                </i>
                                SALIDA</a></li>
                    @endif
                    @if (auth()->user()->can('ver-cotizacion') ||
                            auth()->user()->can('crear-cotizacion') ||
                            auth()->user()->can('editar-cotizacion') ||
                            auth()->user()->can('eliminar-cotizacion'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/cotizacion') }}">
                                <i class="mdi mdi-currency-usd menu-icon"></i>
                                <span class="menu-title">COTIZACIÓN</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-orden-compra') ||
                            auth()->user()->can('crear-orden-compra') ||
                            auth()->user()->can('editar-orden-compra') ||
                            auth()->user()->can('eliminar-orden-compra'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/ordencompra') }}">
                                <i class=" menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                                        <path
                                            d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zM272 192H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H272c-8.8 0-16-7.2-16-16s7.2-16 16-16zM256 304c0-8.8 7.2-16 16-16H496c8.8 0 16 7.2 16 16s-7.2 16-16 16H272c-8.8 0-16-7.2-16-16zM164 152v13.9c7.5 1.2 14.6 2.9 21.1 4.7c10.7 2.8 17 13.8 14.2 24.5s-13.8 17-24.5 14.2c-11-2.9-21.6-5-31.2-5.2c-7.9-.1-16 1.8-21.5 5c-4.8 2.8-6.2 5.6-6.2 9.3c0 1.8 .1 3.5 5.3 6.7c6.3 3.8 15.5 6.7 28.3 10.5l.7 .2c11.2 3.4 25.6 7.7 37.1 15c12.9 8.1 24.3 21.3 24.6 41.6c.3 20.9-10.5 36.1-24.8 45c-7.2 4.5-15.2 7.3-23.2 9V360c0 11-9 20-20 20s-20-9-20-20V345.4c-10.3-2.2-20-5.5-28.2-8.4l0 0 0 0c-2.1-.7-4.1-1.4-6.1-2.1c-10.5-3.5-16.1-14.8-12.6-25.3s14.8-16.1 25.3-12.6c2.5 .8 4.9 1.7 7.2 2.4c13.6 4.6 24 8.1 35.1 8.5c8.6 .3 16.5-1.6 21.4-4.7c4.1-2.5 6-5.5 5.9-10.5c0-2.9-.8-5-5.9-8.2c-6.3-4-15.4-6.9-28-10.7l-1.7-.5c-10.9-3.3-24.6-7.4-35.6-14c-12.7-7.7-24.6-20.5-24.7-40.7c-.1-21.1 11.8-35.7 25.8-43.9c6.9-4.1 14.5-6.8 22.2-8.5V152c0-11 9-20 20-20s20 9 20 20z" />
                                    </svg>
                                </i>
                                <span class="menu-title">ORDEN COMPRA</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-venta') ||
                            auth()->user()->can('eliminar-venta'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/ventasantiguas') }}"><i
                                    class="mdi mdi-av-timer menu-icon"></i>VENTAS ANTIGUAS</a></li>
                    @endif
                </ul>
            </div>
        </li>
        @if (auth()->user()->can('ver-reporte'))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic1" aria-expanded="false"
                    aria-controls="ui-basic1">
                    <i class="mdi mdi-chart-bar menu-icon"></i>
                    <span class="menu-title">REPORTES</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic1">
                    <ul class="flex-column sub-menu" style="list-style: none;">
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte') }}"><i
                                    class="mdi mdi-chart-line menu-icon"></i>GRÁFICOS</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/tabladatos') }}"><i
                                    class="mdi mdi-file-excel menu-icon"></i>DATOS VENTAS</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/rotacionstock') }}"><i
                                    class="mdi mdi-timetable menu-icon"></i>ROTACIÓN STOCK</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/pagocompras') }}"><i
                                    class="mdi mdi-wallet menu-icon"></i>PAGO DE COMPRAS</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/cobrovent') }}"><i
                                    class="mdi mdi-wallet menu-icon"></i>COBRO DE VENTAS</a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/reporte/precioscompra') }}">
                                <i class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512">
                                        <path
                                            d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM64 80c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm128 72c8.8 0 16 7.2 16 16v17.3c8.5 1.2 16.7 3.1 24.1 5.1c8.5 2.3 13.6 11 11.3 19.6s-11 13.6-19.6 11.3c-11.1-3-22-5.2-32.1-5.3c-8.4-.1-17.4 1.8-23.6 5.5c-5.7 3.4-8.1 7.3-8.1 12.8c0 3.7 1.3 6.5 7.3 10.1c6.9 4.1 16.6 7.1 29.2 10.9l.5 .1 0 0 0 0c11.3 3.4 25.3 7.6 36.3 14.6c12.1 7.6 22.4 19.7 22.7 38.2c.3 19.3-9.6 33.3-22.9 41.6c-7.7 4.8-16.4 7.6-25.1 9.1V440c0 8.8-7.2 16-16 16s-16-7.2-16-16V422.2c-11.2-2.1-21.7-5.7-30.9-8.9l0 0c-2.1-.7-4.2-1.4-6.2-2.1c-8.4-2.8-12.9-11.9-10.1-20.2s11.9-12.9 20.2-10.1c2.5 .8 4.8 1.6 7.1 2.4l0 0 0 0 0 0c13.6 4.6 24.6 8.4 36.3 8.7c9.1 .3 17.9-1.7 23.7-5.3c5.1-3.2 7.9-7.3 7.8-14c-.1-4.6-1.8-7.8-7.7-11.6c-6.8-4.3-16.5-7.4-29-11.2l-1.6-.5 0 0c-11-3.3-24.3-7.3-34.8-13.7c-12-7.2-22.6-18.9-22.7-37.3c-.1-19.4 10.8-32.8 23.8-40.5c7.5-4.4 15.8-7.2 24.1-8.7V232c0-8.8 7.2-16 16-16z" />
                                    </svg>
                                </i>
                                <span class="menu-title">PRECIOS DE COMPRAS</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/reporte/precioespecial') }}">
                                <i class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                                        <path
                                            d="M312 24V34.5c6.4 1.2 12.6 2.7 18.2 4.2c12.8 3.4 20.4 16.6 17 29.4s-16.6 20.4-29.4 17c-10.9-2.9-21.1-4.9-30.2-5c-7.3-.1-14.7 1.7-19.4 4.4c-2.1 1.3-3.1 2.4-3.5 3c-.3 .5-.7 1.2-.7 2.8c0 .3 0 .5 0 .6c.2 .2 .9 1.2 3.3 2.6c5.8 3.5 14.4 6.2 27.4 10.1l.9 .3c11.1 3.3 25.9 7.8 37.9 15.3c13.7 8.6 26.1 22.9 26.4 44.9c.3 22.5-11.4 38.9-26.7 48.5c-6.7 4.1-13.9 7-21.3 8.8V232c0 13.3-10.7 24-24 24s-24-10.7-24-24V220.6c-9.5-2.3-18.2-5.3-25.6-7.8c-2.1-.7-4.1-1.4-6-2c-12.6-4.2-19.4-17.8-15.2-30.4s17.8-19.4 30.4-15.2c2.6 .9 5 1.7 7.3 2.5c13.6 4.6 23.4 7.9 33.9 8.3c8 .3 15.1-1.6 19.2-4.1c1.9-1.2 2.8-2.2 3.2-2.9c.4-.6 .9-1.8 .8-4.1l0-.2c0-1 0-2.1-4-4.6c-5.7-3.6-14.3-6.4-27.1-10.3l-1.9-.6c-10.8-3.2-25-7.5-36.4-14.4c-13.5-8.1-26.5-22-26.6-44.1c-.1-22.9 12.9-38.6 27.7-47.4c6.4-3.8 13.3-6.4 20.2-8.2V24c0-13.3 10.7-24 24-24s24 10.7 24 24zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5H192 32c-17.7 0-32-14.3-32-32V416c0-17.7 14.3-32 32-32H68.8l44.9-36c22.7-18.2 50.9-28 80-28H272h16 64c17.7 0 32 14.3 32 32s-14.3 32-32 32H288 272c-8.8 0-16 7.2-16 16s7.2 16 16 16H392.6l119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384l0 0-.9 0c.3 0 .6 0 .9 0z" />
                                    </svg>
                                </i> 
                                <span class="menu-title">PRECIOS ESPECIALES</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#production" aria-expanded="false"
                aria-controls="production">
                <i class="mdi mdi-factory menu-icon"></i>
                <span class="menu-title">PRODUCCIÓN</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="production">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-modelo-carro') ||
                            auth()->user()->can('crear-modelo-carro') ||
                            auth()->user()->can('editar-modelo-carro') ||
                            auth()->user()->can('eliminar-modelo-carro')||
                            auth()->user()->can('ver-carroceria') ||
                            auth()->user()->can('crear-carroceria') ||
                            auth()->user()->can('editar-carroceria') ||
                            auth()->user()->can('eliminar-carroceria'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/modelocarro') }}">
                                <i class="mdi mdi-account-multiple menu-icon"></i>
                                <span class="menu-title">MODELOS Y CARROCERIAS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-material-electrico') ||
                            auth()->user()->can('crear-material-electrico') ||
                            auth()->user()->can('editar-material-electrico') ||
                            auth()->user()->can('eliminar-material-electrico'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/materialelectrico') }}">
                                <i class="mdi mdi-account-settings menu-icon"></i>
                                <span class="menu-title">MATERIAL ELECTRICO</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-material-electrico') ||
                            auth()->user()->can('crear-material-electrico') ||
                            auth()->user()->can('editar-material-electrico') ||
                            auth()->user()->can('eliminar-material-electrico'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/redes') }}">
                                <i class="mdi mdi-account-settings menu-icon"></i>
                                <span class="menu-title">REDES</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-produccion-carro') ||
                            auth()->user()->can('crear-produccion-carro') ||
                            auth()->user()->can('editar-produccion-carro') ||
                            auth()->user()->can('eliminar-produccion-carro'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/produccioncarro') }}">
                                <i class="mdi mdi-timetable menu-icon"></i>
                                <span class="menu-title">PRODUCCION DE CARROS</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#auth" aria-expanded="false"
                aria-controls="auth">
                <i class="mdi mdi-security menu-icon"></i>
                <span class="menu-title">SEGURIDAD</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-usuario') ||
                            auth()->user()->can('crear-usuario') ||
                            auth()->user()->can('editar-usuario') ||
                            auth()->user()->can('eliminar-usuario'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/users') }}">
                                <i class="mdi mdi-account-multiple menu-icon"></i>
                                <span class="menu-title">USUARIOS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-rol') ||
                            auth()->user()->can('crear-rol') ||
                            auth()->user()->can('editar-rol') ||
                            auth()->user()->can('eliminar-rol'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/rol') }}">
                                <i class="mdi mdi-account-settings menu-icon"></i>
                                <span class="menu-title">ROLES</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-historial') ||
                            auth()->user()->can('eliminar-historial'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/historial') }}">
                                <i class="mdi mdi-timetable menu-icon"></i>
                                <span class="menu-title">HISTORIAL DE CAMBIOS</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>

    </ul>
</nav>
