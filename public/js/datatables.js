$(document).ready(function () {
    // Inicializa a tabela de carencias do DataTables
    var table = $("#carenciaTable").DataTable({
        // Suas configurações originais
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: false,
        info: false,
        autoWidth: false,
        serverSide: false,
        stateSave: true,
        language: {
            lengthMenu: "Exibindo _MENU_ Registros por página",
            zeroRecords: "Nenhum registro encontrado",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "Nenhum Registro Disponível",
            infoFiltered: "(Filtrado _MAX_ Registros no Total)",
            sSearch: "Pesquisa Dinâmica:",
            oPaginate: {
                sFirst: "Primeira",
                sNext: "Próxima",
                sPrevious: "Anterior",
                sLast: "Última",
            },
        },

        // 3. Adicionar o 'dom' para posicionar os botões
        // 'l' = length changing input (o seletor de quantidade)
        // 'B' = Buttons (Botões)
        // 'f' = filtering input (o campo de busca)
        // 'r' = processing display element
        // 't' = The table
        // 'i' = Table information summary
        // 'p' = pagination control
        dom:
            '<"d-flex flex-column"<"buttons-container text-start mb-2"B><"d-flex justify-content-between align-items-center"<"length-container"l><"search-container"f>>>' +
            "rt" +
            '<"d-flex justify-content-between align-items-center mt-2"<"info-container"i><"pagination-container"p>>',

        // 4. Configurar o botão de exportação customizado
        buttons: [
            {
                // O código SVG é colocado dentro da propriedade 'text' usando crases (`)
                text: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-xls"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M4 15l4 6" /><path d="M4 21l4 -6" /><path d="M17 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" /><path d="M11 15v6h3" /></svg>`,
                className: "btn btn-primary",
                action: function (e, dt, button, config) {
                    // Pega os cabeçalhos diretamente do <thead>
                    var headers = dt
                        .columns()
                        .header()
                        .toArray()
                        .map(function (header) {
                            return $(header).text();
                        });

                    // Encontra o índice da coluna "Ações"
                    var acoesIndex = headers.indexOf("AÇÕES");
                    var tipoIndex = headers.indexOf("TIPO");

                    // Remove o cabeçalho "Ações" se ele for encontrado
                    if (acoesIndex > -1) {
                        headers.splice(acoesIndex, 1);
                    }

                    // Pega os dados de todas as linhas que passam no filtro de busca
                    var data = dt
                        .rows({ search: "applied" })
                        .nodes()
                        .toArray()
                        .map(function (row) {
                            var rowData = [];
                            // Pega o texto de cada célula da linha
                            $(row)
                                .find("td")
                                .each(function () {
                                    rowData.push($(this).text().trim());
                                });

                            // **AQUI ACONTECE A TRANSFORMAÇÃO DOS DADOS**
                            // Verifica se a coluna "TIPO" existe e transforma seu valor
                            if (tipoIndex > -1) {
                                if (rowData[tipoIndex] === "R") {
                                    rowData[tipoIndex] = "Real";
                                } else if (rowData[tipoIndex] === "T") {
                                    rowData[tipoIndex] = "Temporária";
                                }
                            }

                            // Remove o dado da coluna "Ações" se ela foi encontrada
                            if (acoesIndex > -1) {
                                rowData.splice(acoesIndex, 1);
                            }
                            return rowData;
                        });

                    // Adiciona os cabeçalhos filtrados como a primeira linha dos dados
                    data.unshift(headers);

                    // Cria a planilha usando a biblioteca SheetJS
                    const worksheet = XLSX.utils.aoa_to_sheet(data);

                    // **AQUI ACONTECE A FORMATAÇÃO DO CABEÇALHO**
                    // Define o estilo do cabeçalho
                    const headerStyle = {
                        fill: {
                            fgColor: { rgb: "FF04287B" }, // Cor de fundo #04287b
                        },
                        font: {
                            color: { rgb: "FFFFFFFF" }, // Cor do texto branca
                            bold: true,
                        },
                        alignment: {
                            horizontal: "center",
                        },
                    };

                    // Aplica o estilo a cada célula do cabeçalho (primeira linha)
                    const range = XLSX.utils.decode_range(worksheet["!ref"]);
                    for (let C = range.s.c; C <= range.e.c; ++C) {
                        const address = XLSX.utils.encode_cell({ r: 0, c: C });
                        if (worksheet[address]) {
                            worksheet[address].s = headerStyle;
                        }
                    }

                    // Cria o arquivo Excel (workbook)
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(
                        workbook,
                        worksheet,
                        "Relatório de Carências"
                    );

                    // Dispara o download do arquivo .xlsx
                    XLSX.writeFile(workbook, "RelatorioCarencias.xlsx");
                },
            },
        ],
        
    });

    $(document).ready(function () {
        $("#consultarCarencias").DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: false,
            info: false,
            autoWidth: false,
            serverSide: false,
            stateSave: true,
            language: {
                lengthMenu: "Exibindo _MENU_ Registros por página",
                zeroRecords: "Nenhum registro encontrado",
                info: "Mostrando página _PAGE_ de _PAGES_",
                infoEmpty: "Nenhum Registro Disponível",
                infoFiltered: "(Filtrado _MAX_ Registros no Total)",
                sSearch: "Pesquisa Dinâmica dos Filtros (Não é filtro)",
                oPaginate: {
                    sFirst: "Primeira",
                    sNext: "Próxima",
                    sPrevious: "Anterior",
                    sLast: "Última",
                },
            },
        });
    });
});
