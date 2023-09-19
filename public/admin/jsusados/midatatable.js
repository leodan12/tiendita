var mitabla;
var tablamodal;
function iniciarTablaIndex(tabla, ruta, columnas, btns) {
    //para agregar un input de busqueda en cada columna
    $(tabla + ' thead th').each(function () {
        var title = $(this).text();
        if (title != "ACCIONES") {
            $(this).html(title + ' <br>  <input style="width:100%;"   type="text"/>');
        }
    });

    //se inicia la tabla como datatables 
    mitabla = $(tabla).DataTable({
        processing: true,
        serverSide: true,
        ajax: ruta,
        dataType: 'json',
        type: "POST",
        columns: columnas,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
            "sInfoPostFix": "",
            "sSearch": "Buscar Registro:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "order": [
            [0, "desc"]
        ],
        "sScrollX": "100%",
        scrollX: true,
        "pageLength": 10,
        autoFill: true,
        dom: btns,
        buttons: [{
            extend: 'excel',
            className: 'btn-success',
            text: 'Descargar Excel',
            exportOptions: {
                columns: ':visible'
            }
        }],
    });

    //para hacer la busqueda al cambiar el valor de un input de una columna
    mitabla.columns().every(function () {
        var table = this;
        $('input', this.header()).on('keyup change', function () {
            if (table.search() !== this.value) {
                table.search(this.value).draw();
            }
        });
    });
}

//para recargar la tabla despues de hacer un cambio
function recargartabla() {
    mitabla.ajax.reload(null, false);
}
//iniciar la tabla 2 del modal 
function inicializartabla1(inicializart) { 
    $('#mitabla1').DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
            "sInfoPostFix": "",
            "sSearch": "Buscar Registro:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "order": [[0, "desc"]],
        "pageLength": 5,
        "aLengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
        scrollX: true,
        autoFill: true,
    });
}

//iniciar la tabla de reportes con botones
function inicializartabladatos(btns, tabla, titulo) {
    //para agregar un input de busqueda en cada columna
    $(tabla + ' thead th').each(function () {
        var title = $(this).text();
        if (title != "VER REGISTROS") {//  && para excluir otra columna
            $(this).html(title + ' <br>  <input style="width:100%;"   type="text"/>');
        }
    });

    mitabladatos = $(tabla).DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
            "sInfoPostFix": "",
            "sSearch": "Buscar Registro:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "order": [[0, "desc"]],
        "pageLength": 10,
        scrollX: true,
        autoFill: true,
        dom: btns,
        buttons: [{
            extend: 'excel',
            className: 'btn-success',
            text: 'Descargar <svg xmlns="http://www.w3.org/2000/svg" height="1.4em" viewBox="0 0 384 512"><path d="M48 448V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/></svg>',
            title: titulo,
            exportOptions: {
                columns: ':visible'
            }
        }],
    });

    //para hacer la busqueda al cambiar el valor de un input de una columna
    mitabladatos.columns().every(function () {
        var table = this;
        $('input', this.header()).on('keyup change', function () {
            if (table.search() !== this.value) {
                table.search(this.value).draw();
            }
        });
    });

}

function inicializartabladatos2(btns, tabla, titulo) {
    //para agregar un input de busqueda en cada columna
    $(tabla + ' thead th').each(function () {
        var title = $(this).text();
        if (title != "VER REGISTROS") {//  && para excluir otra columna
            $(this).html(title + ' <br>  <input style="width:100%;"   type="text" />');
        }
    });

    mitabladatos2 = $(tabla).DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
            "sInfoPostFix": "",
            "sSearch": "Buscar Registro:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "order": [[0, "desc"]],
        "pageLength": 10,
        scrollX: true,
        autoFill: true,
        dom: btns,
        buttons: [{
            extend: 'excel',
            className: 'btn-success',
            text: 'Descargar <svg xmlns="http://www.w3.org/2000/svg" height="1.4em" viewBox="0 0 384 512"><path d="M48 448V64c0-8.8 7.2-16 16-16H224v80c0 17.7 14.3 32 32 32h80V448c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16zM64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V154.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0H64zm90.9 233.3c-8.1-10.5-23.2-12.3-33.7-4.2s-12.3 23.2-4.2 33.7L161.6 320l-44.5 57.3c-8.1 10.5-6.3 25.5 4.2 33.7s25.5 6.3 33.7-4.2L192 359.1l37.1 47.6c8.1 10.5 23.2 12.3 33.7 4.2s12.3-23.2 4.2-33.7L222.4 320l44.5-57.3c8.1-10.5 6.3-25.5-4.2-33.7s-25.5-6.3-33.7 4.2L192 280.9l-37.1-47.6z"/></svg>',
            title: titulo,
            exportOptions: {
                columns: ':visible'
            }
        }, {
            extend: 'pdf',
            className: 'btn-danger ',
            text: 'Descargar <svg xmlns="http://www.w3.org/2000/svg" height="1.4em" viewBox="0 0 512 512"><path d="M64 464H96v48H64c-35.3 0-64-28.7-64-64V64C0 28.7 28.7 0 64 0H229.5c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3V288H336V160H256c-17.7 0-32-14.3-32-32V48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16zM176 352h32c30.9 0 56 25.1 56 56s-25.1 56-56 56H192v32c0 8.8-7.2 16-16 16s-16-7.2-16-16V448 368c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24H192v48h16zm96-80h32c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H304c-8.8 0-16-7.2-16-16V368c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H320v96h16zm80-112c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v32h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H448v48c0 8.8-7.2 16-16 16s-16-7.2-16-16V432 368z"/></svg>',
            title: titulo,
            exportOptions: {
                columns: ':visible'
            }
        }],
    });

    //para hacer la busqueda al cambiar el valor de un input de una columna
    mitabladatos2.columns().every(function () {
        var table = this;
        $('input', this.header()).on('keyup change', function () {
            if (table.search() !== this.value) {
                table.search(this.value).draw();
            }
        });
    });
}
