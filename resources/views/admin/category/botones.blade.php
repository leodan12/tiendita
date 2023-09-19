@can('editar-categoria')
    <button type="button" class="btn btn-success btneditar" data-bs-toggle="modal" data-bs-target="#editCategoria"  
    data-idcategoria="{{ $categorias->id }}" data-categoria="{{ $categorias->nombre }}" >
        Editar 
    </button> 
@endcan
@can('eliminar-categoria')
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $categorias->id }}" >Eliminar</button>
@endcan
 