@can('editar-tienda')
    <a href="{{ url('admin/tienda/' . $tiendas->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $tiendas->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
    
@can('eliminar-tienda')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $tiendas->id }}">Eliminar</button>
@endcan
