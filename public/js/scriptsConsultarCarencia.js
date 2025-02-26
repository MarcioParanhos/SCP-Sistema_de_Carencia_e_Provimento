const nte_seacrh = document.getElementById("nte_seacrh")
const municipio_search = document.getElementById("municipio_search")
const codigo_unidade_escolar = document.getElementById("codigo_unidade_escolar")

const nte_ap = document.getElementById("nte_ap")
const municipio_ap = document.getElementById("municipio_ap")
const uee_ap = document.getElementById("uee_ap")


let selectedNteAP = $("#nte_ap")
selectedNteAP.on("select2:select", searchMunicipioAp)

function searchMunicipioAp() {

    let search_nte = nte_ap.value

    $.post('/consultarMunicipio/' + search_nte, function (response) {


        let options = "<option value=''></option>";
        let i = 0
        let newArr = response.map(function (obj) {
            return obj.municipio;
        });
        $.each(response, function () {
            options += "<option value='" + newArr[i] + "'>" + newArr[i] + "</option>";
            i = i + 1
        });
        $("#municipio_ap").html(options);

    }) 
}

let selectedMunicipioAP = $("#municipio_ap")
selectedMunicipioAP.on("select2:select", searchUeesAP)

function searchUeesAP () {

    let search_municipio = municipio_ap.value

    $.post('/consultarUees/' + search_municipio, function (response) {

        let options = "<option value=''></option>";
        let i = 0
        let newArr = response.map(function (obj) {
            return obj.unidade_escolar;
        });
        $.each(response, function () {
            options += "<option value='" + newArr[i] + "'>" + newArr[i] + "</option>";
            i = i + 1
        });
        $("#uee_ap").html(options);
    })
    
}

let selectedNTE = $("#nte_seacrh")
selectedNTE.on("select2:select", searchMunicipio)

function searchMunicipio() {

    let search_nte = nte_seacrh.value

    $.post('/consultarMunicipio/' + search_nte, function (response) {


        let options = "<option value=''></option>";
        let i = 0
        let newArr = response.map(function (obj) {
            return obj.municipio;
        });
        $.each(response, function () {
            options += "<option value='" + newArr[i] + "'>" + newArr[i] + "</option>";
            i = i + 1
        });
        $("#municipio_search").html(options);
    })
}

let selectedMunicipio = $("#municipio_search")
selectedMunicipio.on("select2:select", searchUees)

function searchUees () {

    let search_municipio = municipio_search.value

    $.post('/consultarUees/' + search_municipio, function (response) {

        let options = "<option value=''></option>";
        let i = 0
        let newArr = response.map(function (obj) {
            return obj.unidade_escolar;
        });
        $.each(response, function () {
            options += "<option value='" + newArr[i] + "'>" + newArr[i] + "</option>";
            i = i + 1
        });
        $("#search_uee").html(options);
    })
    
}

// Função para Deletar um registro
function destroy (id) {

    const modalDelete = document.querySelector("#modalDelete a");
    // Declarando a variavel Link
    let link = "/deletar_carencia/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDelete.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDelete").modal({
      show: true
    });
    
}

function destroyArea (id) {

    const modalDelete = document.querySelector("#modalDelete a");
    // Declarando a variavel Link
    let link = "/deletar_area/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDelete.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDelete").modal({
      show: true
    });
    
}

function destroyCurso (id) {

    const modalDelete = document.querySelector("#modalDelete a");
    // Declarando a variavel Link
    let link = "/deletar_curso/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDelete.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDelete").modal({
      show: true
    });
    
}

function destroyComponenteEspecial (id) {

    const modalDelete = document.querySelector("#modalDelete a");
    // Declarando a variavel Link
    let link = "/deletar_componente_especial/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDelete.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDelete").modal({
      show: true
    });
    
}

function destroyDisciplina (id) {

    const modalDelete = document.querySelector("#modalDelete a");
    // Declarando a variavel Link
    let link = "/deletar_disciplina/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDelete.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDelete").modal({
      show: true
    });
    
}

function destroyRegularizacao (id) {

    const modalDelete = document.querySelector("#modalDelete a");
    // Declarando a variavel Link
    let link = "/deletar_regularizacao/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    modalDelete.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#modalDelete").modal({
      show: true
    });
    
}

function destroyProvimentoEfetivo (id) {

    const ModalDeleteProvimentosEfetivos = document.querySelector("#ModalDeleteProvimentosEfetivos a");
    // Declarando a variavel Link
    let link = "/provimento/efetivo/destroy/" + id 
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    ModalDeleteProvimentosEfetivos.setAttribute("href", link)
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#ModalDeleteProvimentosEfetivos").modal({
      show: true
    });
    
}
$(document).ready(function () {
    // Aguarda o modal ser totalmente exibido
    $('#addNewProcess').on('shown.bs.modal', function () {
      // Ouve o evento 'change' do <select> com id 'nte'
      $('#nte').on('change', function () {
        var valorSelecionado = $(this).val(); // Obtém o valor selecionado
        $.post('/consultarMunicipio/' + valorSelecionado, function (response) {


            let options = "<option value=''></option>";
            let i = 0
            let newArr = response.map(function (obj) {
                return obj.municipio;
            });
            $.each(response, function () {
                options += "<option value='" + newArr[i] + "'>" + newArr[i] + "</option>";
                i = i + 1
            });
            $("#municipio_search").html(options);
        })
      });
    });
  });

  $('#carencia').on('change', function () {

    var valorSelecionado = $(this).val(); // Obtém o valor selecionado
     
    const remove_hidden = document.getElementById('remove_hidden')

    if(valorSelecionado == 'Sim') {
        remove_hidden.hidden = false
    } else if (valorSelecionado == 'Não') {
        $("#local_carencia").val(null).trigger("change");
        remove_hidden.hidden = true
    }

  });