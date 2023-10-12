@can('editar-uniforme')
    <a href="{{ url('admin/uniformes/' . $uniformes->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $uniformes->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
    
@can('eliminar-uniforme')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $uniformes->id }}">Eliminar</button>
@endcan
