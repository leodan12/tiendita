@can('editar-instrumento')
    <a href="{{ url('admin/instrumentos/' . $instrumentos->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $instrumentos->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
    
@can('eliminar-instrumento')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $instrumentos->id }}">Eliminar</button>
@endcan
