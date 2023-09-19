@can('editar-material-electrico')
    <a href="{{ url('admin/redes/' . $redes->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $redes->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-material-electrico')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $redes->id }}">Eliminar</button>
@endcan
