const situacao_provimento = document.getElementById("situacao_provimento")
const data_assuncao_row = document.getElementById("data_assuncao_row")
const data_encaminhamento_row = document.getElementById("data_encaminhamento_row")
const data_assuncao_row_detail = document.getElementById("data_assuncao_row_detail")
const situacao_provimento_detail = document.getElementById("situacao_provimento_detail")
const data_assuncao = document.getElementById("data_assuncao")


let selectedSituacaoDetail = $("#situacao_provimento_detail")
selectedSituacaoDetail.on("select2:select", modifyDate_detail)

function modifyDate_detail() {

    let situacao = situacao_provimento_detail.value
    let data_assuncao = document.getElementById("data_assuncao")

    if (situacao === "provida") {
        data_assuncao_row_detail.hidden = false
        data_assuncao.required = true
    } else if (situacao === "tramite") {
        data_assuncao_row_detail.hidden = true
        data_assuncao.value = ""
    }
}

// Pega o ID das carencias clicadas
let arrID = []
let BackButtonID = 0

document.querySelectorAll('a').forEach(function (row) {

    row.addEventListener('click', function () {
        // Recuperar o ID da linha
        let rowId = this.getAttribute('data-id');

        if (arrID.indexOf(rowId) === -1) {

            arrID.push(rowId);

        }

        BackButtonID = rowId

        $.ajax({
            url: '/processData',
            type: 'POST',
            data: {
                data: arrID
            },
            success: function (result) {

            }
        });
    });
});


function addNewProvimento() {

    const cod_unidade_provimento = document.getElementById("cod_unidade_provimento")

    let codigo_unidade_provimento = cod_unidade_provimento.value;

    if (codigo_unidade_provimento == "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Por favor, Selecione uma unidade escolar!',
        })
    }

    $.post('/consultarUnidadeProvimento/' + codigo_unidade_provimento, function (response) {

        let data = response[0]

        if (data) {

            window.location.replace('/provimento/' + codigo_unidade_provimento)

        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'UEE não encontrada, Tente novamente!.',
            })
        }
    })
}

//   foreach para pegar todos os botoes onde a classe é transferir
transferButtons.forEach(function (button) {
    // adiciona um evento de click em cada botão
    button.addEventListener("click", function () {

        const row = button.parentNode.parentNode;
        const table2 = document.getElementById("table2");
        const transferBackButton = document.createElement("button");
        transferBackButton.id = BackButtonID
        transferBackButton.innerHTML = "<i class='ti-close'></i>";
        transferBackButton.classList.add('btn', 'btn-sm', 'btn-danger', 'btn-show-carência')

        // ao clicar no botão de voltar, volta com a linha para sua tabela de origem
        transferBackButton.addEventListener("click", function () {

            let index = arrID.indexOf(transferBackButton.id);

            if (index > -1) {
                arrID.splice(index, 1);
            }

            const table1 = document.getElementById("table1");
            table1.appendChild(row);
            row.removeChild(row.lastChild);
            const newCell = row.insertCell(-1);
            newCell.appendChild(button);
            row.lastChild.classList.add("text-center")
            // seleciona as classes especificas da linha movida e adiciona o atriburo hidden nele
            const elements = row.querySelectorAll(".remove_hidden")
            elements.forEach(function (element) {
                element.setAttribute("hidden", "")
            })
        });
        // deleta a ultima celula e adiciona o botao de voltar no lugar da celula deletada

        let rows = table2.getElementsByTagName("tr")

        if (rows.length > 1) {
            Swal.fire({

                icon: 'warning',
                title: 'Atenção!',
                text: 'Selecione apenas uma disciplina!.',
            })

            let index = arrID.indexOf(BackButtonID);

            if (index > -1) {
                arrID.splice(index, 1);
            }

        } else {
            row.deleteCell(-1);
            const newCell = row.insertCell(-1);
            newCell.appendChild(transferBackButton);
            table2.appendChild(row);

        }

        row.lastChild.classList.add("text-center")

        // ao mover para a nova tabela onde ira suprir as vagas retorna todas os atriburos hidden 
        const elements = document.querySelectorAll("[hidden]")
        elements.forEach(function (element) {
            if (table2.contains(row)) {
                row.querySelector("[hidden]").removeAttribute("hidden");
            }
        })
    });
});

function destroyProvimento(id) {

    const modalDeleteProvimento = document.querySelector("#modalDeleteProvimento a");
    // Declarando a variavel Link
    let link = "/deletar_provimento/" + id
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDeleteProvimento.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDeleteProvimento").modal({
        show: true
    });
}



let selectedSituacao = $("#situacao_provimento")
selectedSituacao.on("select2:select", modifyDate)

function modifyDate() {

    let situacao = situacao_provimento.value
    const data_assuncao = document.getElementById("data_assuncao")
    
    if (situacao === "tramite") {
        data_encaminhamento_row.hidden = false
        data_encaminhamento_row.required = true
        data_assuncao_row.required = false
        data_assuncao_row.hidden = true
    } else if (situacao === "provida") {
        data_assuncao_row.hidden = false
        data_assuncao_row.required = true
        data_encaminhamento_row.hidden = false
        data_assuncao_row_detail.hidden = false
    }
}

const cadastroCpf = document.getElementById("cadastro")

// LIBERAR OPÇÕES ESPECIFICAS 
cadastroCpf.addEventListener("blur", addSituacao)

function addSituacao() {

    if (cadastroCpf.value.length === 11) {

        const select = document.getElementById('situacao_provimento');

        let options = [
            { text: 'EM TRÂMITE', value: 'tramite' },
            { text: 'PROVIDO', value: 'provida' }

        ];

        select.innerHTML = ""; // adiciona essa linha para limpar as opções anteriores

        for (let i = 0; i < options.length; i++) {
            const option = document.createElement('option');
            option.text = options[i].text;
            option.value = options[i].value;
            select.appendChild(option);
        }

        data_encaminhamento_row.hidden = false

    } else {

        const select = document.getElementById('situacao_provimento');


        let options = [
            { text: '', value: '' },
            { text: 'EM TRÂMITE', value: 'tramite' },
            { text: 'PROVIDO', value: 'provida' }

        ];

        select.innerHTML = ""; // adiciona essa linha para limpar as opções anteriores

        for (let i = 0; i < options.length; i++) {
            const option = document.createElement('option');
            option.text = options[i].text;
            option.value = options[i].value;
            select.appendChild(option);
        }

        const data_assuncao = document.getElementById("data_assuncao")
        data_encaminhamento_row.hidden = false
        data_assuncao_row.hidden = true
        data_assuncao.required = false
    }
}
