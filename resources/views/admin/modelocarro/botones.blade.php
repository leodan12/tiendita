@can('editar-modelo-carro')
    <button type="button" class="btn btn-success btneditar" data-bs-toggle="modal" data-bs-target="#editModelo"  
    data-idmodelo="{{ $modelocarros->id }}" data-modelo="{{ $modelocarros->modelo }}" >
        Editar
    </button>
@endcan
@can('eliminar-modelo-carro')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $modelocarros->id }}">Eliminar</button>
@endcan
