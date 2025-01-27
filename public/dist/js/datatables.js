function initializeDataTable(tableId, enableExcelButton = false) {
    const dataTableConfig = {
        ordering: false,
        info: false,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Todos"],
        ],
        dom: "Blfrtip",
        buttons: [],
        language: {
            lengthMenu: "Exibindo _MENU_ Registros por página",
            zeroRecords: "Nenhum registro encontrado",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "Nenhum Registro Disponível",
            infoFiltered: "(Filtrado _MAX_ Registros no Total)",
            sSearch: "Buscar",
            oPaginate: {
                sFirst: "Primeira",
                sNext: "Próxima",
                sPrevious: "Anterior",
                sLast: "Última",
            },
        },
    };

    if (enableExcelButton) {
        dataTableConfig.buttons.push({
            extend: "excelHtml5",
            className: "mb-3 custom-excel-button bg-primary text-white",
        });
    }

    $("#" + tableId).DataTable(dataTableConfig);
}

$(document).ready(function () {
    initializeDataTable("basicTable");
    initializeDataTable("dinamicTable");
});
