@can('editar-inventario')
    <button type="button" class="btn btn-success" data-id="{{ $registros->id }}" data-accion="editar" data-bs-toggle="modal"
        data-bs-target="#mimodal">Editar</button>
@endcan
@can('ver-inventario')
    <button type="button" class="btn btn-secondary" data-id="{{ $registros->id }}" data-accion="ver" data-bs-toggle="modal"
        data-bs-target="#mimodal">Ver</button>
@endcan
