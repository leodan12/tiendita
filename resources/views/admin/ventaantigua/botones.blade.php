
<button type="button" class="btn btn-secondary" data-id="{{ $ventas->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-venta')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $ventas->id }}">Eliminar</button>
@endcan
