//FUNCTION TO CALCULATE DAYS BETWEEN REQUEST AND DOE
const aplicationDateInput = document.getElementById("aplication_date");
const doeInput = document.getElementById("doe");
const instruction_time = document.getElementById("instruction_time");

// Event listener to detect changes in dates
aplicationDateInput.addEventListener("change", calcularDiferencaDeDias);
doeInput.addEventListener("change", calcularDiferencaDeDias);

// Function to calculate the difference in days
function calcularDiferencaDeDias() {
    const dataRequerimento = new Date(aplicationDateInput.value);
    const dataDoe = new Date(doeInput.value);

    if (!isNaN(dataRequerimento) && !isNaN(dataDoe)) {
        const diferencaEmMilissegundos = dataDoe - dataRequerimento;
        const diferencaEmDias = Math.floor(
            diferencaEmMilissegundos / (1000 * 60 * 60 * 24)
        );

        instruction_time.value = diferencaEmDias;
    }
}

// SUCCESS AND ERROR MESSAGES USING SWEETALERT2
document.addEventListener("DOMContentLoaded", function () {
    const session_message = document.getElementById("session_message");

    if (session_message) {
        if (session_message.value === "error") {
            Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: "Ocorreu alguma inconsistência nas informações.",
            });
        } else if (session_message.value === "success") {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            Toast.fire({
                icon: "success",
                title: "Processo adicionado com sucesso",
            });
        } else if (session_message.value === "success_destroy") {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            Toast.fire({
                icon: "success",
                title: "Processo excluido com sucesso",
            });
        } else if (session_message.value === "Duplicated Process") {
            Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: "Já existe um registro com esse número de processo em nossa base de dados.",
            });
        } else if (session_message.value === "success_update") {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });

            Toast.fire({
                icon: "success",
                title: "Processo atualizado com sucesso",
            });
        } else {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Motivo de vaga adicionado com sucesso!",
                showConfirmButton: true,
            });
        }
    }
});

// Update Function
function update(id, type) {
    if (type == "retirement") {
        link = "/retirements/update";

        // Faz uma solicitação AJAX para buscar o processo selecionado
        fetch(`/retirements/select/${id}`)
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                // Exibir o processo no modal
                let modal = new bootstrap.Modal(
                    document.getElementById("updateRetirementModal")
                );
                let process = document.getElementById("process");
                let aplication_date =
                    document.getElementById("aplication_date");
                let first_aplication_date = document.getElementById(
                    "first_aplication_date"
                );
                let last_diligence_date = document.getElementById(
                    "last_diligence_date"
                );
                let registration = document.getElementById("registration");
                let server_name = document.getElementById("server_name");
                let doe = document.getElementById("doe");
                let instruction_time =
                    document.getElementById("instruction_time");
                let date = document.getElementById("date");
                let public = document.getElementById("public");
                let description = document.getElementById("description");
                let selected_status =
                    document.getElementById("selected_status");
                let selected_type_process = document.getElementById(
                    "selected_type_process"
                );
                let selected_permanent_position = document.getElementById(
                    "selected_permanent_position"
                );
                let selected_commission_position = document.getElementById(
                    "selected_commission_position"
                );
                let selected_situation =
                    document.getElementById("selected_situation");
                let selected_specificity = document.getElementById(
                    "selected_specificity"
                );
                let selected_id = document.getElementById("selected_id");
                process.value = data.retirement.process;
                aplication_date.value = data.retirement.aplication_date;
                first_aplication_date.value =
                    data.retirement.first_aplication_date;
                last_diligence_date.value = data.retirement.last_diligence_date;
                registration.value = data.retirement.registration;
                server_name.value = data.retirement.server_name;
                doe.value = data.retirement.doe;
                instruction_time.value = data.retirement.instruction_time;
                date.value = data.retirement.date;
                public.value = data.retirement.public;
                description.innerHTML = data.retirement.description;
                selected_status.innerHTML = ` ${data.retirement.status.name}`;
                selected_status.value = ` ${data.retirement.status.id}`;
                selected_type_process.innerHTML =
                    data.retirement.processtype.name;
                selected_type_process.value = data.retirement.processtype.id;
                selected_nte.innerHTML = data.retirement.nte.name;
                selected_nte.value = data.retirement.nte.id;
                selected_permanent_position.innerHTML =
                    data.retirement.permanentposition.name;
                selected_permanent_position.value =
                    data.retirement.permanentposition.id;
                selected_commission_position.innerHTML =
                    data.retirement.comiissionposition.name;
                selected_commission_position.value =
                    data.retirement.comiissionposition.id;
                selected_situation.innerHTML = data.retirement.situation.name;
                selected_situation.value = data.retirement.situation.id;
                selected_specificity.innerHTML =
                    data.retirement.specificity.name;
                selected_specificity.value = data.retirement.specificity.id;
                selected_id.value = data.retirement.id;
                modal.show();
                // Define o atributo "action" do formulário com o link desejado
                let updateForm = document.getElementById("update_form");
                updateForm.action = link;
            })
            .catch((error) => {
                console.error("Erro ao buscar a marca: " + error);
            });
    }
}

var dataHoraElement = document.getElementById("dataHora");
var dataHoraAtual = new Date();
var dataHoraFormatada = dataHoraAtual.toLocaleString();
dataHoraElement.textContent = dataHoraFormatada;

// Function to delete a retirement
function addIdForDelete(id) {
    const linkForDelete = document.getElementById("linkForDelete");

    let link = "/retirements/destroy/" + id;

    linkForDelete.setAttribute("href", link);
}
