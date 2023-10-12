@can('editar-libro')
    <a href="{{ url('admin/libros/' . $libros->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $libros->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>

@can('eliminar-libro')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $libros->id }}">Eliminar</button>
@endcan
