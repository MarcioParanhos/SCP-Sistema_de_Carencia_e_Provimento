const btn_submit = document.getElementById('btn_submit')
const encaminhamento_btn = document.getElementById('encaminhamento_btn')
const vaga_real_btn = document.getElementById('vaga_real_btn')
const hidden_select_unidade_escolar_vaga_real = document.getElementById("hidden_select_unidade_escolar_vaga_real");
const hidden_select_unidade_escolar = document.getElementById("hidden_select_unidade_escolar");
const cadastro_segundo_servidor_btn = document.getElementById('cadastro_segundo_servidor_btn');
const segundo_servidor = document.getElementById('segundo_servidor');
const icon_segundo_servidor = document.getElementById('icon_segundo_servidor')



btn_submit.addEventListener('click', function (event) {
    const nte = document.getElementById('nte')
    const municipio = document.getElementById('municipio')
    const vinculo = document.getElementById('vinculo')
    const regime = document.getElementById('regime')

    if ((nte.value == "") || (municipio.value == "")) {
        event.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Nenhuma unidade escolar foi selecionada!",
        });
    } else if ((vinculo.value == "") || (regime.value == "")) {
        event.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Nenhum servidor foi selecionado!",
        });
    } else {

    }

});

cadastro_segundo_servidor_btn.addEventListener('click', () => {
    if (servidor_subistituido.value == '') {

        Swal.fire({
            icon: "warning",
            title: "Atenção!",
            text: "Para liberar o segundo servidor é preciso ter o primeiro servidor selacionado",
        });

    } else {
        if (cadastro_segundo_servidor_btn.classList.contains('btn-primary')) {
            cadastro_segundo_servidor_btn.classList.remove('btn-primary');
            cadastro_segundo_servidor_btn.classList.add('btn-danger');
            icon_segundo_servidor.classList.remove('fa-solid', 'fa-user-plus');
            icon_segundo_servidor.classList.add('fa-solid', 'fa-user-xmark');
            segundo_servidor.hidden = false;

        } else {
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
            const cadastro_segundo_servidor = document.getElementById(
                "cadastro_segundo_servidor"
            );
            cadastro_segundo_servidor_btn.classList.remove('btn-danger');
            cadastro_segundo_servidor_btn.classList.add('btn-primary');
            icon_segundo_servidor.classList.add('fa-solid', 'fa-user-plus');
            icon_segundo_servidor.classList.remove('fa-solid', 'fa-user-xmark');
            segundo_servidor.hidden = true
            segundo_servidor_name.value = ''
            vinculo_segundo_servidor.value = ''
            regime_segundo_servidor.value = ''
            id_segundo_servidor_subistituido.value = ''
            cadastro_segundo_servidor.value = ''
        }
    }
})

encaminhamento_btn.addEventListener('click', () => {
    hidden_select_unidade_escolar.hidden = !hidden_select_unidade_escolar.hidden;
    encaminhamento_btn.classList.toggle('btn-primary');
    encaminhamento_btn.classList.toggle('btn-info');

    if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == false)) {
        btn_submit.hidden = false
    } else if ((hidden_select_unidade_escolar_vaga_real.hidden == false) && (hidden_select_unidade_escolar.hidden == true)) {
        btn_submit.hidden = false
    } else if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == true)) {
        btn_submit.hidden = true
    } else if ((hidden_select_unidade_escolar_vaga_real.hidden == false) && (hidden_select_unidade_escolar.hidden == false)) {
        btn_submit.hidden = false
    }
})

vaga_real_btn.addEventListener('click', () => {
    hidden_select_unidade_escolar_vaga_real.hidden = !hidden_select_unidade_escolar_vaga_real.hidden;
    vaga_real_btn.classList.toggle('btn-primary');
    vaga_real_btn.classList.toggle('btn-info');
    if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == true)) {
        btn_submit.hidden = true
    } else if ((hidden_select_unidade_escolar_vaga_real.hidden == true) && (hidden_select_unidade_escolar.hidden == false)) {
        btn_submit.hidden = false
    } else if ((hidden_select_unidade_escolar_vaga_real.hidden == false) && (hidden_select_unidade_escolar.hidden == true)) {
        btn_submit.hidden = false
    }
})



// --------------------------


function searchUnidadeForCodSap() {

    let codUe = document.getElementById('cod_ue').value.trim();

    if (codUe == "") {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "UE não informada. Tente novamente.",
        });
    }
    $.post("/consultarUnidadeForCodSap/" + codUe, function (response) {
        let data = response[0];

        if (data) {

            const unidade_id = document.getElementById("unidade_id");

            nte.value = data.nte;
            municipio.value = data.municipio;
            unidade_escolar.value = data.unidade_escolar;

            if (unidade_id != null) {
                unidade_id.value = data.id;

            }

        } else {

            Swal.fire({
                position: 'center',
                icon: 'danger',
                title: 'Unidade escolar não encontrada!',
                showConfirmButton: true,
            })
        }

    });

}


function adicionarDisciplina() {
    let container = document.getElementById('disciplinas-container');

    let novaDisciplina = document.createElement('div');
    novaDisciplina.classList.add('form-row', 'disciplina-row');

    novaDisciplina.innerHTML = `
        <div class="col-md-6">
            <div class="form-group_disciplina">
                <label class="control-label">Disciplina</label>
                <input value="" name="disciplinas[]" required id="" type="text" class="form-control form-control-sm">
            </div>
        </div>

        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label for="mat">MAT</label>
                <input type="text" name="matutino[]" class="form-control form-control-sm" required>
            </div>
        </div>

        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label for="vesp">VESP</label>
                <input type="text" name="vespertino[]" class="form-control form-control-sm" required>
            </div>
        </div>

        <div class="col-md-1">
            <div class="form-group_disciplina">
                <label for="not">NOT</label>
                <input type="text" name="noturno[]" class="form-control form-control-sm" required>
            </div>
        </div>

        <div class="col-md-2 d-flex align-items-center">
            <button type="button" class="btn btn-danger btn-remove-disciplina" onclick="removerDisciplina(this)">
                <i class="ti-trash"></i>
            </button>
        </div>
    `;

    container.appendChild(novaDisciplina);
}

function removerDisciplina(botao) {
    botao.closest('.disciplina-row').remove();
}
