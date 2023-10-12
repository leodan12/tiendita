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
                    @if (auth()->user()->can('ver-uniforme') ||
                            auth()->user()->can('crear-uniforme') ||
                            auth()->user()->can('editar-uniforme') ||
                            auth()->user()->can('eliminar-uniforme'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/uniformes') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">UNIFORMES</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-libro') ||
                            auth()->user()->can('crear-libro') ||
                            auth()->user()->can('editar-libro') ||
                            auth()->user()->can('eliminar-libro'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/libros') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">LIBROS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-instrumento') ||
                            auth()->user()->can('crear-instrumento') ||
                            auth()->user()->can('editar-instrumento') ||
                            auth()->user()->can('eliminar-instrumento'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/instrumentos') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">INSTRUMENTOS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-util') ||
                            auth()->user()->can('crear-util') ||
                            auth()->user()->can('editar-util') ||
                            auth()->user()->can('eliminar-util'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/utiles') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">UTILES</span>
                            </a>
                        </li>
                    @endif 
                    @if (auth()->user()->can('ver-golosina') ||
                            auth()->user()->can('crear-golosina') ||
                            auth()->user()->can('editar-golosina') ||
                            auth()->user()->can('eliminar-golosina'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/golosinas') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">GOLOSINAS</span>
                            </a>
                        </li>
                    @endif 
                    @if (auth()->user()->can('ver-snack') ||
                            auth()->user()->can('crear-snack') ||
                            auth()->user()->can('editar-snack') ||
                            auth()->user()->can('eliminar-snack'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/snacks') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">SNACKS</span>
                            </a>
                        </li>
                    @endif 
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#inventarios" aria-expanded="false"
                aria-controls="inventarios">
                <i class="mdi mdi-home-modern menu-icon"></i>
                <span class="menu-title">INVENTARIOS</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="inventarios">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-inventario')|| auth()->user()->can('editar-inventario')   )
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventariouf') }}">
                                <i class="mdi mdi-store menu-icon"></i>
                                <span class="menu-title">I. UNIFORMES</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventariolb') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">I. LIBROS</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventarioit') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">I. INSTRUMENTOS</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventariout') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">I. UTILES</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventariogl') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">I. GOLOSINAS</span>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventariosn') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">I. SNACKS</span>
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
                    @if (auth()->user()->can('ver-tienda') ||
                            auth()->user()->can('crear-tienda') ||
                            auth()->user()->can('editar-tienda') ||
                            auth()->user()->can('eliminar-tienda'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/tienda') }}">
                                <i class="mdi mdi-store menu-icon"></i>
                                <span class="menu-title">MIS TIENDAS</span>
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
                                <span class="menu-title">CLIENTES</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-proveedor') ||
                            auth()->user()->can('crear-proveedor') ||
                            auth()->user()->can('editar-proveedor') ||
                            auth()->user()->can('eliminar-proveedor'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/proveedor') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">PROVEEDORES</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ventas" aria-expanded="false"
                aria-controls="ventas">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                        viewBox="0 0 122.43 122.88" style="enable-background:new 0 0 122.43 122.88">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                        viewBox="0 0 122.43 122.88">
                                        <path
                                            d="M22.63 12.6h93.3c6.1 0 5.77 2.47 5.24 8.77l-3.47 44.23c-0.59 7.05-0.09 5.34-7.56 6.41l-68.62 8.73 l3.63 10.53c29.77 0 44.16 0 73.91 0c1 3.74 2.36 9.83 3.36 14h-12.28l-1.18-4.26c-24.8 0-34.25 0-59.06 0 c-13.55-0.23-12.19 3.44-15.44-8.27L11.18 8.11H0V0h19.61C20.52 3.41 21.78 9.15 22.63 12.6z M62.4 62.12h17.76V44.11 h15.98L71.3 19.93L46.43 44.11H62.4V62.12L62.4 62.12z M53.69 103.92c5.23 0 9.48 4.25 9.48 9.48c0 5.24-4.24 9.48-9.48 9.48 c-5.24 0-9.48-4.24-9.48-9.48C44.21 108.16 48.45 103.92 53.69 103.92L53.69 103.92z M92.79 103.92c5.23 0 9.48 4.25 9.48 9.48 c0 5.24-4.25 9.48-9.48 9.48c-5.24 0-9.48-4.24-9.48-9.48C83.31 108.16 87.55 103.92 92.79 103.92L92.79 103.92z" />
                                    </svg>

                                </i>
                                SALIDA</a></li>
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

                    </ul>
                </div>
            </li>
        @endif

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
