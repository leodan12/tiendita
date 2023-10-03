@can('editar-produccion-carro')
    <a href="{{ url('admin/proveedor/' . $proveedores->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $proveedores->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-produccion-carro')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $proveedores->id }}">Eliminar</button>
@endcan
