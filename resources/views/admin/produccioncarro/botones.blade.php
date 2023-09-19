@can('editar-produccion-carro')
    <a href="{{ url('admin/produccioncarro/' . $produccioncarros->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $produccioncarros->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-produccion-carro')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $produccioncarros->id }}">Eliminar</button>
@endcan
