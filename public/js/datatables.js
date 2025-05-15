$(document).ready(function() {
    $('#consultarCarencias').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": false,
        "info": false,
        "autoWidth": false,
        "serverSide": false,
        "stateSave": true,
        "language": {
            "lengthMenu": "Exibindo _MENU_ Registros por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum Registro Disponível",
            "infoFiltered": "(Filtrado _MAX_ Registros no Total)",
            "sSearch": "Pesquisa Dinâmica dos Filtros (Não é filtro)",
            "oPaginate": {
                "sFirst": "Primeira",
                "sNext": "Próxima",
                "sPrevious": "Anterior",
                "sLast": "Última"
            }
        }
    });
});
