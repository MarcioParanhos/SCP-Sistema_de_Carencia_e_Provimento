const hideForm = document.getElementById("form-hide");
const hideTable = document.getElementById("table_form");
const buttonSelectTipo = document.getElementById("dropdownMenuDate2");
const cadastroButton = document.getElementById("cadastro");
const btnCadastro = document.getElementById("btn-cadastro");
const typeTitle = document.getElementById("type");
const cod_unidade = document.getElementById("cod_ue");
const nte = document.getElementById("nte");
const municipio = document.getElementById("municipio");
const unidade_escolar = document.getElementById("unidade_escolar");
const mat = document.getElementById("matutino");
const vesp = document.getElementById("vespertino");
const not = document.getElementById("noturno");
const total = document.getElementById("total");
const cadastro = document.getElementById("cadastro_btn");
const btnFormCarencia = document.getElementById("btn_submit_carencia");
const cadastroServidor = document.getElementById("cadastro");
const servidor = document.getElementById("servidor");
const vinculo = document.getElementById("vinculo");
const regime = document.getElementById("regime");
const disciplina = document.getElementById("disciplina");
const disciplina_especial = document.getElementById("disciplina_especial");
const motivo_vaga = document.getElementById("motivo_vaga");
const inicio_vaga = document.getElementById("inicio_vaga");
const tipo_vaga = document.getElementById("tipo_vaga");
const curso = document.getElementById("curso");
const eixo = document.getElementById("eixo");
const disciplina_row = document.getElementById("disciplina_row");
const disciplina_especial_row = document.getElementById(
    "disciplina_especial_row"
);
const eixo_row = document.getElementById("eixo_row");
const curso_row = document.getElementById("curso_row");
const motivo_vaga_row = document.getElementById("motivo_vaga_row");
const inicio_vaga_row = document.getElementById("inicio_vaga_row");
const fim_vaga_row = document.getElementById("fim_vaga_row");
const servidor_row = document.getElementById("servidor_row");
const table_form = document.getElementById("table_form");
const final_vaga_row = document.getElementById("final_vaga_row");
const tipo_carencia = document.getElementById("tipo_carencia");
const transferButtons = document.querySelectorAll(".transferir");
const class_button = document.getElementById("class-button");
const btn_submit_provimento = document.getElementById("btn_submit_provimento");
const dataFimInput = document.getElementById("fim_vaga");
const situacao_ue = document.getElementById("situacao_ue");
const title_situacao = document.getElementById("title_situacao");
const num_rim_row = document.getElementById("num_rim_row");
const num_rim = document.getElementById("num_rim");
const area_row = document.getElementById("area_row");
const area = document.getElementById("area");

cod_unidade.addEventListener("blur", checkIfUnitHasBeenSelected);
function checkIfUnitHasBeenSelected() {
    if (btnCadastro.disabled === true) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Atenção!",
            text: "É preciso selecionar um tipo de carência!",
            showConfirmButton: true,
        });
    }
}

// Não permite data fim da vaga ser menor que o inicio
inicio_vaga.addEventListener("change", function () {
    dataFimInput.min = inicio_vaga.value;
});

mat.addEventListener("blur", addTotal);
vesp.addEventListener("blur", addTotal);
not.addEventListener("blur", addTotal);

// ADICIONA O TOTAL DE FORMA ASSINCRONA
function addTotal() {
    let matModify = parseFloat(mat.value);
    let vespModify = parseFloat(vesp.value);
    let notpModify = parseFloat(not.value);

    total.value = matModify + vespModify + notpModify;
}

// PESQUISA UNIDADE ESCOLAR E ATUALIZA A TABELA COM AS INFORMAÇÕES VINDAS DO BANCO
function addNewCarencia() {
    let codigo_unidade = cod_unidade.value;

    if (codigo_unidade == "") {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "UE não informada. Tente novamente.",
        });
    }

    $.post("/consultarUnidade/" + codigo_unidade, function (response) {
        let data = response[0];

        if (data) {

            const unidade_id = document.getElementById("unidade_id");
            const uo_origem = document.getElementById("uo_origem");

            nte.value = data.nte;
            municipio.value = data.municipio;
            unidade_escolar.value = data.unidade_escolar;
            // uo_origem.value = data.uo_cod[0].uo;

            if (uo_origem != null) {
                uo_origem.value = data.uo_cod[0].uo;
            }

            if (unidade_id != null) {
                unidade_id.value = data.id;

            }

            // Faz a validação se a unidade esta homologada e permite ou não lançar uma nova vaga
            if (data.situacao === "HOMOLOGADA") {
                btn_submit_carencia.disabled = true;
                title_situacao.hidden = false;
                title_situacao.classList.add("badge-success");
                title_situacao.innerHTML =
                    "<strong>UNIDADE HOMOLOGADA</strong> <button id='button_homologar' class='btn btn-sm btn-primary'><strong>RETIRAR HOMOLOGAÇÃO</strong></button>";
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Atenção!",
                    text: "Retire a Homologação para lançar uma nova carência!.",
                    showConfirmButton: true,
                });
                homologar();
            } else if (data.situacao === "PENDENTE") {
                btn_submit_carencia.disabled = false;
                title_situacao.hidden = false;
                title_situacao.classList.add("badge-danger");
                title_situacao.innerHTML =
                    "<strong>UNIDADE PENDENTE HOMOLOGAÇÃO</strong> <button id='button_homologar' class='btn btn-sm btn-primary'><strong>HOMOLOGAR</strong></button>";
                homologar();
            }

            let tipo_carenciaParaConsulta = tipo_carencia.value;

            $.post(
                "/consultarCarencias/" +
                codigo_unidade +
                "," +
                tipo_carenciaParaConsulta,
                function (response) {
                    // Função para verificar e converter o formato da data, se necessário
                    function formatDate(dateString) {
                        // Verifica se a data é válida
                        if (moment(dateString, "YYYY-MM-DD", true).isValid()) {
                            return moment(dateString).format("DD/MM/YYYY"); // Formato esperado
                        } else {
                            return ""; // Retorna uma string vazia se a data for inválida
                        }
                    }

                    let generateTable = function () {
                        var dataSet = [];
                        var matSum = 0;
                        var vespSum = 0;
                        var notSum = 0;
                        var totalSum = 0;

                        $.each(response, function (index, data) {
                            dataSet.push([
                                data.area,
                                data.disciplina,
                                data.matutino,
                                data.vespertino,
                                data.noturno,
                                data.total,
                                data.servidor,
                                data.cadastro,
                                data.motivo_vaga,
                                data.tipo_vaga,
                                formatDate(data.inicio_vaga), // Chama a função para formatar a data
                            ]);

                            matSum += data.matutino;
                            vespSum += data.vespertino;
                            notSum += data.noturno;
                            totalSum += data.total;
                        });

                        dataSet.push([
                            '',
                            'TOTAL',
                            matSum,
                            vespSum,
                            notSum,
                            totalSum,
                            '',
                            '',
                            '',
                            '',
                            '',
                        ]);

                        $("#table_carencia").DataTable({
                            data: dataSet,
                            columns: [
                                { title: "ÁREA" },
                                { title: "DISCIPLINA" },
                                { title: "MAT", className: "text-center" },
                                { title: "VESP", className: "text-center" },
                                { title: "NOT", className: "text-center" },
                                { title: "TOTAL", className: "text-center" },
                                { title: "SERVIDOR" },
                                { title: "MATRICULA", className: "text-center" },
                                { title: "MOTIVO" },
                                { title: "TIPO", className: "text-center" },
                                { title: "INICIO", className: "text-center" },
                            ],
                            ordering: false,
                            paging: true,
                            info: false,
                            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                            language: {
                                lengthMenu: "Exibindo _MENU_ Registros por página",
                                zeroRecords: "Nada encontrado! desculpe =(",
                                info: "Mostrando Pagina _PAGE_ de _PAGES_",
                                infoEmpty: "Não há registros disponíveis",
                                infoFiltered: "(filtrado de _MAX_ registros totais)",
                                sSearch: "Buscar",
                                oPaginate: {
                                    sFirst: "Primeira",
                                    sNext: "Próxima",
                                    sPrevious: "Anterior",
                                    sLast: "Última",
                                },
                            },
                            bDestroy: true,
                        });
                    };

                    generateTable();
                }
            );
        } else {
            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "UEE não encontrada. Tente novamente.",
            });
        }
    });
}

function searchUnidadeEscolar () {
    const cod_ue_destino = document.getElementById("cod_ue_destino")

    if (cod_ue_destino.value == "") {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "UE não informada. Tente novamente.",
        });
    }

    $.post("/consultarUnidade/" + cod_ue_destino.value, function (response) {
        let data = response[0];

        if (data) {

            const nte_destino = document.getElementById("nte_destino")
            const municipio_destino = document.getElementById("municipio_destino")
            const unidade_escolar_destino = document.getElementById("unidade_escolar_destino")
            const uo_destino = document.getElementById("uo_destino");

            nte_destino.value = data.nte;
            municipio_destino.value = data.municipio;
            unidade_escolar_destino.value = data.unidade_escolar;
            uo_destino.value = data.uo_cod[0].uo;
            
        } else {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "UE não Encontrada. Tente novamente.",
            });
        }

    });
}

// PESQUISA O CADASTRO DO SERVIDOR NO BANCO
function searchServidor() {
    let cadastro_servidor = cadastroServidor.value;

    if (cadastro_servidor == "") {
        Swal.fire({
            icon: "error",
            title: "Atenção!",
            text: "Matrícula não informada. Tente novamente.",
        });
    }

    $.post("/consultarServidor/" + cadastro_servidor, function (response) {
        let data = response[0];

        if (data) {
            const cadastro = document.getElementById("cadastro");
            const servidor_subistituido = document.getElementById(
                "servidor_subistituido"
            );
            servidor.value = data.nome;
            vinculo.value = data.vinculo;
            regime.value = data.regime;
            cadastro.value = data.cadastro;
            servidor_subistituido.value = data.id;
        } else {
            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Servidor não encontrado. Tente novamente.",
            });
        }
    });
}
// SELECIONA A MODALIDADE DA CAÊNCIA E AJUSTA A DELA DE ACORDO COM A MODALIDADE ESCOLHIDA
function addTipoCarencia(tipo) {
    if (tipo_carencia.value === "Real") {
        if (tipo == 1) {
            buttonSelectTipo.innerHTML = "EDUCAÇÃO BÁSICA";
            typeTitle.innerHTML = "EDUCAÇÃO BÁSICA";
            btnCadastro.disabled = false;
            cadastro.disabled = false;
            // btnFormCarencia.disabled = false
            tipo_vaga.value = "Basica";
            disciplina_row.classList = "visible, col-md-4";
            motivo_vaga_row.classList = "visible, col-md-3";
            inicio_vaga_row.classList = "visible, col-md-2";
            servidor_row.classList = "visible";
            area_row.classList = "visible, col-md-3";
            disciplina_especial_row.classList = "hidden";
            table_form.hidden = false;
            eixo_row.classList = "hidden";
            curso_row.classList = "hidden";
            eixo.required = false;
            curso.required = false;
            num_rim_row.hidden = true;
            disciplina_especial.required = false;
            num_rim.value = "";
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
            $("#disciplina_especial").val(null).trigger("change");
        } else if (tipo == 2) {
            buttonSelectTipo.innerHTML = "PROFISSIONALIZANTE";
            typeTitle.innerHTML = "PROFISSIONALIZANTE";
            btnCadastro.disabled = false;
            cadastro.disabled = false;
            // btnFormCarencia.disabled = false
            tipo_vaga.value = "Profissionalizante";
            disciplina_row.classList = "visible, col-md-4";
            motivo_vaga_row.classList = "visible, col-md-3";
            inicio_vaga_row.classList = "visible, col-md-2";
            area_row.classList = "visible, col-md-3";
            servidor_row.classList = "visible";
            disciplina_especial_row.classList = "hidden";
            table_form.hidden = false;
            eixo_row.classList = "visible, col-md-6";
            curso_row.classList = "visible, col-md-6";
            num_rim_row.hidden = true;
            disciplina_especial.required = false;
            num_rim.value = "";
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
        } else if (tipo == 3) {
            $("#componenteEspecial").modal({
                show: true,
            });

            buttonSelectTipo.innerHTML = "EDUCAÇÃO ESPECIAL";
            typeTitle.innerHTML = "EDUCAÇÃO ESPECIAL";
            btnCadastro.disabled = false;
            cadastro.disabled = false;
            // btnFormCarencia.disabled = false
            tipo_vaga.value = "Especial";
            disciplina_row.classList = "hidden";
            disciplina_especial_row.classList = "visible, col-md-4";
            motivo_vaga_row.classList = "visible, col-md-3";
            inicio_vaga_row.classList = "visible, col-md-2";
            servidor_row.classList = "visible";
            area_row.classList = "visible, col-md-3";
            table_form.hidden = false;
            eixo_row.classList = "hidden";
            curso_row.classList = "hidden";
            eixo.required = false;
            curso.required = false;
            disciplina.required = false;
            num_rim_row.hidden = true;
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
            area.required = true;
        }
    } else if (tipo_carencia.value === "Temp") {
        if (tipo == 1) {
            buttonSelectTipo.innerHTML = "BÁSICA";
            typeTitle.innerHTML = "BÁSICA";
            btnCadastro.disabled = false;
            cadastro.disabled = false;
            // btnFormCarencia.disabled = false
            tipo_vaga.value = "Basica";
            disciplina_row.classList = "visible, col-md-4";
            motivo_vaga_row.classList = "visible, col-md-3";
            inicio_vaga_row.classList = "visible, col-md-2";
            fim_vaga_row.classList = "visible, col-md-2";
            area_row.classList = "visible, col-md-3";
            servidor_row.classList = "visible";
            disciplina_especial_row.classList = "hidden";
            table_form.hidden = false;
            eixo_row.classList = "hidden";
            curso_row.classList = "hidden";
            eixo.required = false;
            curso.required = false;
            num_rim_row.hidden = true;
            disciplina_especial.required = false;
            num_rim.value = "";
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
            $("#disciplina_especial").val(null).trigger("change");
        } else if (tipo == 2) {
            buttonSelectTipo.innerHTML = "PROFISSIONALIZANTE";
            typeTitle.innerHTML = "PROFISSIONALIZANTE";
            btnCadastro.disabled = false;
            cadastro.disabled = false;
            // btnFormCarencia.disabled = false
            tipo_vaga.value = "Profissionalizante";
            disciplina_row.classList = "visible, col-md-4";
            disciplina_especial_row.classList = "hidden";
            motivo_vaga_row.classList = "visible, col-md-3";
            inicio_vaga_row.classList = "visible, col-md-2";
            area_row.classList = "visible, col-md-3";
            fim_vaga_row.classList = "visible, col-md-2";
            servidor_row.classList = "visible";
            table_form.hidden = false;
            eixo_row.classList = "visible, col-md-6";
            curso_row.classList = "visible, col-md-6";
            num_rim_row.hidden = true;
            disciplina_especial.required = false;
            num_rim.value = "";
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
        } else if (tipo == 3) {
            $("#componenteEspecial").modal({
                show: true,
            });

            buttonSelectTipo.innerHTML = "EDUCAÇÃO ESPECIAL";
            typeTitle.innerHTML = "EDUCAÇÃO ESPECIAL";
            btnCadastro.disabled = false;
            cadastro.disabled = false;
            // btnFormCarencia.disabled = false
            tipo_vaga.value = "Especial";
            disciplina_row.classList = "hidden";
            disciplina_especial_row.classList = "visible, col-md-4";
            motivo_vaga_row.classList = "visible, col-md-3";
            inicio_vaga_row.classList = "visible, col-md-2";
            fim_vaga_row.classList = "visible, col-md-2";
            area_row.classList = "visible, col-md-3";
            servidor_row.classList = "visible";
            table_form.hidden = false;
            eixo_row.classList = "hidden";
            curso_row.classList = "hidden";
            eixo.required = false;
            curso.required = false;
            disciplina.required = false;
            num_rim_row.hidden = true;
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
            area.required = false;
        }
    }
}

// SALVAR NO BANCO DE FORMA ASSINCRONA COM VALIDAÇÕES
$("#InsertForm").submit(function (e) {
    // previne o reload
    e.preventDefault();
    // salva no banco

    if ((mat.value || vesp.value || not.value) > 20 && servidor.value != "") {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Atenção!",
            text: "É permitido apenas 20h por turno.",
            showConfirmButton: true,
        });
    } else if (area.value === "") {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Atenção!",
            text: "É preciso selecionar uma área.",
            showConfirmButton: true,
        });
    } else if (unidade_escolar.value === "") {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Atenção!",
            text: "Selecione uma unidade valida!.",
            showConfirmButton: true,
        });
    } else if (
        servidor.value === "" &&
        motivo_vaga.value !== "AUMENTO DE TURMA" &&
        motivo_vaga.value !== "VAGA DE SERVIDOR MILITAR" &&
        motivo_vaga.value !== "VAGA DE SERVIDOR MUNICIPAL" &&
        motivo_vaga.value !== "AULAS RESIDUAIS" &&
        motivo_vaga.value !== "RPP - MULTIDISCIPLINAR"
    ) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Atenção!",
            text: "Selecione um servidor valido!.",
            showConfirmButton: true,
        });

        // Se for vaga temporaria valida se o RIM esta digitado
    } else if (tipo_carencia.value === "Temp") {
        if (
            num_rim.value === "" &&
            motivo_vaga.value === "LICENÇA POR APRAZAMENTO"
        ) {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Atenção!",
                text: "Por favor informe o numero do RIM.",
                showConfirmButton: true,
            });
        } else {
            if (total.value > 0) {
                $.ajax({
                    url: "/incluir_carencia",
                    type: "post",
                    data: $(this).serialize(),
                    dataType: "jason",
                });
                // Mensagem de sucesso
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Carência cadastrada com sucesso!",
                    showConfirmButton: false,
                    timer: 2500,
                });
                // reseta os campos
                cadastroServidor.value = "";
                servidor.value = "";
                vinculo.value = "";
                regime.value = "";
                inicio_vaga.value = "";
                if (tipo_carencia.value === "Temp") {
                    num_rim.value = "";
                }
                mat.value = 0;
                vesp.value = 0;
                not.value = 0;
                total.value = 0;
                $("#eixo").val(null).trigger("change");
                $("#curso").val(null).trigger("change");
                $("#disciplina").val(null).trigger("change");
                $("#disciplina_especial").val(null).trigger("change");
                $("#motivo_vaga").val(null).trigger("change");
            } else {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Atenção!",
                    text: "O total de carência não pode ser menor ou igua a 0!",
                    showConfirmButton: true,
                });
            }
        }
    } else {
        if (total.value > 0) {

            $.ajax({
                url: "/incluir_carencia",
                type: "post",
                data: $(this).serialize(),
                dataType: "jason",
            });
            // Mensagem de sucesso
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Carência cadastrada com sucesso!",
                showConfirmButton: false,
                timer: 2500,
            });
            // reseta os campos
            cadastroServidor.value = "";
            servidor.value = "";
            vinculo.value = "";
            regime.value = "";
            inicio_vaga.value = "";
            if (tipo_carencia.value === "Temp") {
                num_rim.value = "";
            }
            mat.value = 0;
            vesp.value = 0;
            not.value = 0;
            total.value = 0;
            $("#eixo").val(null).trigger("change");
            $("#curso").val(null).trigger("change");
            $("#disciplina").val(null).trigger("change");
            $("#disciplina_especial").val(null).trigger("change");
            $("#motivo_vaga").val(null).trigger("change");
            $("#area").val(null).trigger("change");
        } else {
            Swal.fire({
                position: "center",
                icon: "warning",
                title: "Atenção!",
                text: "O total de carência não pode ser menor ou igua a 0!",
                showConfirmButton: true,
            });
        }
    }
    // ATUALIZA A TABELA DINAMICA DE ACORDO COM A UNIDADE ESCOLHIDA
    let codigo_unidade = cod_unidade.value;
    let tipo_carenciaParaConsulta = tipo_carencia.value;

    $.post(
        "/consultarCarencias/" +
        codigo_unidade +
        "," +
        tipo_carenciaParaConsulta,
        function (response) {
            var generateTable = function () {
                var dataSet = [];
                $.each(response, function (index, data) {
                    dataSet.push([
                        data.area,
                        data.disciplina,
                        data.matutino,
                        data.vespertino,
                        data.noturno,
                        data.total,
                        data.servidor,
                        data.cadastro,
                        data.motivo_vaga,
                        data.tipo_vaga,
                        data.inicio_vaga,
                    ]);
                });

                $("#table_carencia").DataTable({
                    data: dataSet,
                    columns: [
                        { title: "ÁREA" },
                        { title: "DISCIPLINA" },
                        { title: "MAT", className: "text-center" },
                        { title: "VESP", className: "text-center" },
                        { title: "NOT", className: "text-center" },
                        { title: "TOTAL", className: "text-center" },
                        { title: "SERVIDOR" },
                        { title: "MATRICULA", className: "text-center" },
                        { title: "MOTIVO" },
                        { title: "TIPO", className: "text-center" },
                        {
                            title: "INICIO",
                            className: "text-center",
                            render: function (data) {
                                return moment(data).format("DD/MM/YYYY");
                            },
                        },
                    ],
                    ordering: false, //Oculta a ordem da tabela
                    paging: true, //Oculta a quantidade de registros por pagina
                    info: false, // Oculta informação de numeros por pagina
                    lengthMenu: [
                        //Quantidade de registros por paginas
                        [10, 25, 50, 100],
                        [10, 25, 50, 100],
                    ],
                    language: {
                        // Tradução do DataTables
                        lengthMenu: "Exibindo _MENU_ Registros por página",
                        zeroRecords: "Nada encontrado! desculpe =(",
                        info: "Mostrando Pagina _PAGE_ de _PAGES_",
                        infoEmpty: "Não há registros disponíveis",
                        infoFiltered: "(filtrado de _MAX_ registros totais)",
                        sSearch: "Buscar",
                        oPaginate: {
                            sFirst: "Primeira",
                            sNext: "Próxima",
                            sPrevious: "Anterior",
                            sLast: "Última",
                        },
                    },

                    // Serve para limpar os dados da tabela antes de chamar outra
                    bDestroy: true,
                });
            };

            generateTable();
        }
    );
});

// SELECIONA O EIXO DO CURSO ESCOLHIDO
let selectedEixo = $("#curso");
selectedEixo.on("select2:select", addCursoEixo);

function addCursoEixo() {
    let cursoSelected = curso.value;

    $.post("/consultarCurso/" + cursoSelected, function (response) {
        let eixoSelected = response[0].eixo;
        eixo.value = eixoSelected;
    });
}
// LIBERA INPUT PARA O USUARIO CADASTRAR O NUMERO DO RIM
let selectedMotivo = $("#motivo_vaga");
selectedMotivo.on("select2:select", addRim);

function addRim() {
    let motivo = motivo_vaga.value;

    if (motivo === "LICENÇA POR APRAZAMENTO") {
        num_rim_row.hidden = false;
    } else if (motivo != "LICENÇA POR APRAZAMENTO") {
        num_rim_row.hidden = true;
        num_rim.value = "";
    }
}

function destroyVacancyPedagogical(id) {
    const ModalDeletvacancyPedagogical = document.querySelector(
        "#ModalDeletvacancyPedagogical a"
    );
    // Declarando a variavel Link
    let link = "/deletar_vacancy_pedagogical/" + id;
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    ModalDeletvacancyPedagogical.setAttribute("href", link);
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#ModalDeletvacancyPedagogical").modal({
        show: true,
    });
}