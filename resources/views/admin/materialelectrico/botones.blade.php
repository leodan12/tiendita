@can('editar-material-electrico')
    <a href="{{ url('admin/materialelectrico/' . $materiales->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $materiales->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-material-electrico')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $materiales->id }}">Eliminar</button>
@endcan
