@can('editar-producto')
    <a href="{{ url('admin/golosinas/' . $golosinas->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $golosinas->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
    
@can('eliminar-producto')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $golosinas->id }}">Eliminar</button>
@endcan
