@can('editar-carroceria')
    <button type="button" class="btn btn-success btneditar" data-bs-toggle="modal" data-bs-target="#editCarroceria"  
    data-idcarroceria="{{ $carrocerias->id }}" data-carroceria="{{ $carrocerias->tipocarroceria }}" >
        Editar 
    </button> 
@endcan
@can('eliminar-carroceria')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $carrocerias->id }}">Eliminar</button>
@endcan



