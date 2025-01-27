$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

$(document).ready(function() {
    $('.select2').select2();
});

function clearForm() {
    var form = document.getElementById("active_form");
    form.reset();

    // Limpar selects do Select2
    $('.select2').val(null).trigger('change');
}

$('#tipo_regularizacao').on('change', function (e) {
    // Obtém o valor selecionado
    var selectedValue = $(this).val();
    if (selectedValue === 'RETIFICAÇÃO') {
        const retificacao_row = document.getElementById("retificacao_row")
        const tornar_sem_efeito_row = document.getElementById("tornar_sem_efeito_row")
        const tipo_tornar_sem_efeito = document.getElementById('tipo_tornar_sem_efeito')
        retificacao_row.hidden = false
        retificacao_row.require = true
        tornar_sem_efeito_row.hidden = true
        tornar_sem_efeito_row.require = false
        $('#tipo_tornar_sem_efeito').val(null).trigger('change');
    } else if (selectedValue === 'TORNAR SEM EFEITO ATO') {
        const retificacao_row = document.getElementById("retificacao_row")
        const tornar_sem_efeito_row = document.getElementById("tornar_sem_efeito_row")
        const tipo_retificacao = document.getElementById("tipo_retificacao")
        retificacao_row.hidden = true
        retificacao_row.require = false
        $('#tipo_retificacao').val(null).trigger('change');
        tornar_sem_efeito_row.hidden = false
        tornar_sem_efeito_row.require = true
    } else {
        const retificacao_row = document.getElementById("retificacao_row")
        const tornar_sem_efeito_row = document.getElementById("tornar_sem_efeito_row")
        const tipo_tornar_sem_efeito = document.getElementById('tipo_tornar_sem_efeito')
        const tipo_retificacao = document.getElementById("tipo_retificacao")
        retificacao_row.hidden = true
        retificacao_row.require = false
        $('#tipo_retificacao').val(null).trigger('change');
        $('#tipo_tornar_sem_efeito').val(null).trigger('change');
        tornar_sem_efeito_row.hidden = true
        tornar_sem_efeito_row.require = false
    }
    // Exibe o valor selecionado (por exemplo, em um console)

});

// Adiciona um ouvinte de eventos para o evento 'change' usando Select2
$('#typing_started').on('change', function () {
    // Obtém o valor selecionado quando o usuário faz uma escolha
    var selectedValue = $(this).val();

    if (selectedValue == "SIM") {
        const remove_typing_hidden = document.getElementById("remove_typing_hidden")
        const description_typing_started = document.getElementById("description_typing_started")
        const remove_finished_typing = document.getElementById("remove_finished_typing")
        const finished_typing = document.getElementById("finished_typing")

        finished_typing.value = ""
        remove_finished_typing.hidden = false
        description_typing_started.value = null
        remove_typing_hidden.hidden = true
        description_typing_started.required = false
        $("#description_typing_started").val(null).trigger("change");


    } if (selectedValue == "NÃO") {
        const remove_typing_hidden = document.getElementById("remove_typing_hidden")
        const description_typing_started = document.getElementById("description_typing_started")
        const remove_finished_typing = document.getElementById("remove_finished_typing")
        const finished_typing = document.getElementById("finished_typing")
        const remove_finished_typing_description = document.getElementById("remove_finished_typing_description")
        const pch_phases = document.getElementById("pch_phases")

        description_typing_started.required = true
        remove_finished_typing_description.hidden = true;
        remove_typing_hidden.hidden = false
        remove_finished_typing.hidden = true
        finished_typing.value = ""
        if (pch_phases) {
            pch_phases.hidden = true
        }
    }

});

// Adiciona um ouvinte de eventos para o evento 'change' usando Select2
$('#finished_typing').on('change', function () {
    // Obtém o valor selecionado quando o usuário faz uma escolha
    var selectedValue = $(this).val();

    if (selectedValue == "SIM") {
        const finished_typing_description = document.getElementById("finished_typing_description")
        const remove_finished_typing_description = document.getElementById("remove_finished_typing_description")

        finished_typing_description.value = ""
        remove_finished_typing_description.hidden = true;

    } if (selectedValue == "NÃO") {
        const remove_finished_typing_description = document.getElementById("remove_finished_typing_description")
        const pch_phases = document.getElementById("pch_phases")

        remove_finished_typing_description.hidden = false
        if (pch_phases) {
            pch_phases.hidden = true
        }

    }
});


// Filtrar Curso pelo Eixo
let selectedEixoByForm = $("#search_eixo");
selectedEixoByForm.on("select2:select", addCursoEixoByForm);

function addCursoEixoByForm() {
    const search_eixo = document.getElementById("search_eixo");

    let eixoSelectedByForm = search_eixo.value;

    $.post("/consultarCurso/" + eixoSelectedByForm, function (response) {
        let options = "<option value=''></option>";
        let numIndice = 0;
        let newArr = response.map(function (obj) {
            return obj.curso;
        });
        $.each(response, function () {
            options +=
                "<option value='" +
                newArr[numIndice] +
                "'>" +
                newArr[numIndice] +
                "</option>";
            numIndice = numIndice + 1;
        });
        $("#search_curso").html(options);
    });
}

// HOMOLOGAR E RETIRAR HOMOLOGAÇÃO DA UNIDADE ESCOLAR
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".toggle-checkbox");

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("click", function () {
            if (this.checked) {
                let cod_ue =
                    this.closest("tr").querySelector(
                        "td:nth-child(4)"
                    ).textContent;

                let new_cod = parseInt(cod_ue);

                $.get(
                    "/homologarUnidade/" + new_cod + "/HOMOLOGADA",
                    function (response) {
                        Swal.fire(
                            "Bom Trabalho!",
                            `A UEE FOI HOMOLOGADA!`,
                            "success"
                        );
                    }
                );
            } else {
                let cod_ue =
                    this.closest("tr").querySelector(
                        "td:nth-child(4)"
                    ).textContent;

                let new_cod = parseInt(cod_ue);

                $.get(
                    "/homologarUnidade/" + new_cod + "/NAO_HOMOLOGADA",
                    function (response) {
                        Swal.fire(
                            "Atenção!",
                            `HOMOLOGAÇÃO RETIRADA!`,
                            "warning"
                        );
                    }
                );
            }
        });
    });
});

const checked_pch = document.getElementById("check-pch");
checked_pch.addEventListener("click", function () {
    const check_provimento_id = document.getElementById("check_provimento_id");
    let id_provimento = check_provimento_id.value;

    if (this.checked) {
        $.get(
            "/validarProvimento/" + id_provimento + "/OK",
            function (response) {
                swal.fire(
                    "Todas as etapas concluidas!",
                    `Provimento validado!`,
                    "success"
                );
            }
        );
    } else {
        $.get(
            "/validarProvimento/" + id_provimento + "/PENDENTE",
            function (response) {
                Swal.fire("Atenção!", `Você retirou a validação!`, "warning");
            }
        );
    }
});

function logout() {
    Swal.fire({
        title: "Deseja sair?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim",
        cancelButtonText: "Não",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("logout-form").submit();
        }
    });
}

let d = new Date();
let year = d.getFullYear();
let lastDayOfYear = new Date(year, 11, 31);
document
    .getElementById("inicio_vaga")
    .setAttribute("max", lastDayOfYear.toISOString().split("T")[0]);

// mascara para o input do RIM
$(document).ready(function () {
    $("#num_rim").mask("00000/0000");
});


// Mostrar e esconder filtros
function active_filters() {
    const active_form = document.getElementById("active_form");
    const active_filters = document.getElementById("active_filters");
    active_form.hidden = active_form.hidden ? false : true;

    if (active_form.hidden === true) {
        // active_filters.innerHTML = "<i class='fa-solid fa-filter'></i>";
        active_filters.classList = "mb-2 btn bg-primary text-white";
    } else {
        // active_filters.innerHTML = "<i class='fa-solid fa-filter-circle-xmark'></i>";
        active_filters.classList = "mb-2 btn bg-danger text-white";
    }
}

function active_filters_provimento() {
    const active_form = document.getElementById("active_form");
    const active_filters = document.getElementById("active_filters");
    active_form.hidden = active_form.hidden ? false : true;

    if (active_form.hidden === true) {
        active_filters.innerHTML = "FILTROS <i class='far fa-eye'></i>";
        active_filters.classList = "mb-2 btn bg-primary text-white";
    } else {
        active_filters.innerHTML = "FILTROS <i class='far fa-eye-slash'></i>";
        active_filters.classList = "mb-2 btn bg-danger text-white";
    }
}

function active_filters_uees() {
    const active_form = document.getElementById("active_form");
    const active_filters = document.getElementById("active_filters");

    // Use a classe d-block para mostrar/ocultar o formulário
    active_form.classList.toggle("d-block");

    if (active_form.classList.contains("d-block")) {
        active_filters.innerHTML = "FILTROS <i class='far fa-eye'></i>";
        active_filters.classList = "mb-2 btn bg-primary text-white";
    } else {
        active_filters.innerHTML = "FILTROS <i class='far fa-eye-slash'></i>";
        active_filters.classList = "mb-2 btn bg-danger text-white";
    }
}

function homologar() {
    const button_homologar = document.getElementById("button_homologar");

    button_homologar.addEventListener("click", function (e) {
        const btn_submit_carencia = document.getElementById(
            "btn_submit_carencia"
        );
        const title_situacao = document.getElementById("title_situacao");
        const cod_ue = document.getElementById("cod_ue");

        if (btn_submit_carencia.disabled === false) {
            btn_submit_carencia.disabled = true;
            title_situacao.classList.remove("badge-danger");
            title_situacao.classList.add("badge-success");
            title_situacao.innerHTML =
                "<strong>UNIDADE HOMOLOGADA</strong> <button id='button_homologar' class='btn btn-sm btn-primary'><strong>RETIRAR HOMOLOGAÇÃO</strong></button>";
            $.get(
                "/homologarUnidade/" + cod_ue.value + "/HOMOLOGADA",
                function (response) {
                    Swal.fire("Sucesso!", `A UEE FOI HOMOLOGADA!`, "success");
                }
            );
            homologar();
        } else {
            btn_submit_carencia.disabled = false;
            title_situacao.classList.remove("badge-success");
            title_situacao.classList.add("badge-danger");
            title_situacao.innerHTML =
                "<strong>UNIDADE PENDENTE HOMOLOGAÇÃO</strong> <button id='button_homologar' class='btn btn-sm btn-primary'><strong>HOMOLOGAR</strong></button>";
            $.get(
                "/homologarUnidade/" + cod_ue.value + "/NAO_HOMOLOGADA",
                function (response) {
                    Swal.fire("Atenção!", `HOMOLOGAÇÃO RETIRADA!`, "warning");
                }
            );
            homologar();
        }
    });
}

function resetPass(id) {
    $.ajax({
        url: "/users/update/pass/" + id,
        type: "POST",
        success: function (response) {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Senha resetada com sucesso!",
                text: "Senha Padrão 123456789",
                showConfirmButton: false,
                timer: 3500,
            });
        },
        error: function () {
            // Mensagem de erro caso ocorra
            Swal.fire({
                icon: "error",
                title: "Erro!",
                text: "Ocorreu um erro na Solicitação.",
            });
        },
    });
}

function searchEfetivo() {
    const cpf_cervidor = document.getElementById("cpf_cervidor");

    let cpf = cpf_cervidor.value;

    if (cpf == "") {
        Swal.fire({
            icon: "error",
            title: "Atenção!",
            text: "CPF não informada. Tente novamente.",
        });
    } else {
        const nte_efetivo = document.getElementById("nte_efetivo");
        const servidor_efetivo = document.getElementById("servidor_efetivo");
        const disciplina_efetivo =
            document.getElementById("disciplina_efetivo");
        const hidden_select_unidade_escolar = document.getElementById(
            "hidden_select_unidade_escolar"
        );
        const encaminhamento_btn =
            document.getElementById("encaminhamento_btn");
        const vaga_real_btn = document.getElementById("vaga_real_btn");
        const cargo_efetivo = document.getElementById("cargo_efetivo");
        const servidor_id = document.getElementById("servidor_id");
        const disciplina_row = document.getElementById("disciplina_row");

        $.post("/consultar/efetivo/" + cpf, function (response) {
            let data = response[0];

            if (data) {
                servidor_efetivo.value = data.nome;
                disciplina_efetivo.value = data.formacao;
                nte_efetivo.value = data.nte;
                cargo_efetivo.value = data.cargo;
                servidor_id.value = data.id;
                encaminhamento_btn.hidden = false;
                if (data.cargo != "PROFESSOR") {
                    disciplina_row.hidden = true
                    $('#disciplina_efetivo').val(null).trigger('change');
                } else {
                    disciplina_row.hidden = false
                }
            } else {
                Swal.fire({
                    icon: "warning",
                    title: "Atenção!",
                    text: "Servidor não encontrado. Tente novamente!",
                });
            }
        });
    }
}
function searchSegundoServidor() {

    const cadastro_segundo_servidor = document.getElementById(
        "cadastro_segundo_servidor"
    );

    let segundo_servidor = cadastro_segundo_servidor.value;

    if (segundo_servidor == "") {
        Swal.fire({
            icon: "error",
            title: "Atenção!",
            text: "Matrícula não informada. Tente novamente.",
        });
    }

    $.post("/consultarServidor/" + segundo_servidor, function (response) {
        let data = response[0];

        if (data) {
            const segundo_servidor_name = document.getElementById(
                "segundo_servidor_name"
            );
            const vinculo_segundo_servidor = document.getElementById(
                "vinculo_segundo_servidor"
            );
            const regime_segundo_servidor = document.getElementById(
                "regime_segundo_servidor"
            );
            const id_segundo_servidor_subistituido = document.getElementById(
                "id_segundo_servidor_subistituido"
            );
            id_segundo_servidor_subistituido.value = data.id;
            segundo_servidor_name.value = data.nome;
            vinculo_segundo_servidor.value = data.vinculo;
            regime_segundo_servidor.value = data.regime;

        } else {
            Swal.fire({
                icon: "warning",
                title: "Atenção!",
                text: "Servidor não encontrado. Tente novamente.",
            });
        }
    });
}

function buscarUeeParaVagaReal() {

    const cod_ue_provimento = document.getElementById('cod_ue_provimento')
    const nte_provimento_vaga_real = document.getElementById('nte_provimento_vaga_real')
    const municipio_provimento_vaga_real = document.getElementById('municipio_provimento_vaga_real')
    const unidade_escolar_provimento_vaga_real = document.getElementById('unidade_escolar_provimento_vaga_real')

    let codigo_unidade = cod_ue_provimento.value

    $.post("/consultarUnidade/" + codigo_unidade, function (response) {
        let data = response[0];
        nte_provimento_vaga_real.value = data.nte
        municipio_provimento_vaga_real.value = data.municipio
        unidade_escolar_provimento_vaga_real.value = data.unidade_escolar
    });

}

function limparDadosDigitação() {
    // Seleciona o elemento do Select2
    const typing_started = $('#typing_started');
    const description_typing_started = $('#description_typing_started');
    const finished_typing = $('#finished_typing');
    const finished_typing_description = $('#finished_typing_description');

    // Limpa os dados do Select2
    typing_started.val(null).trigger('change');
    description_typing_started.val(null).trigger('change');
    finished_typing.val(null).trigger('change');
    finished_typing_description.val(null).trigger('change');
}

