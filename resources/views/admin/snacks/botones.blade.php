@can('editar-producto')
    <a href="{{ url('admin/snacks/' . $snacks->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $snacks->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
    
@can('eliminar-producto')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $snacks->id }}">Eliminar</button>
@endcan
