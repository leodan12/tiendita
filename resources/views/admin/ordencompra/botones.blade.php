@can('editar-orden-compra')
<a href="{{ url('admin/ordencompra/' . $ordencompras->id . '/edit') }}"
    class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $ordencompras->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
@can('eliminar-orden-compra')
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $ordencompras->id }}" >Eliminar</button>
 @endcan
 