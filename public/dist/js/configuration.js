// SUCCESS AND ERROR MESSAGES USING SWEETALERT2
document.addEventListener("DOMContentLoaded", function () {
    const session_message = document.getElementById("session_message");

    if (session_message) {
        switch (session_message.value) {
            case "error":
                showErrorMessage(
                    "Ocorreu alguma inconsistência nas informações."
                );
                break;
            case "success":
                showSuccessToast("Status adicionado com sucesso!");
                break;
            case "success_create_permanent_position":
                showSuccessToast("Cargo permanente adicionado com sucesso!");
                break;
            case "destroy_success":
                showSuccessToast("Status excluído com sucesso");
                break;
            case "success_process_type_destroy":
                showSuccessToast("Tipo de processo excluído com sucesso!");
                break;
            case "success_permanent_position_destroy":
                showSuccessToast("Cargo permanente excluído com sucesso!");
                break;
            case "success_commission_position_destroy":
                showSuccessToast("Cargo comissionado excluído com sucesso!");
                break;
            case "error_commission_position_destroy":
                showErrorMessage(
                    "Solicitação negada! Existem processos cadastrados com esse cargo comissionado."
                );
                break;
            case "error_process_type_destroy":
                showErrorMessage(
                    "Solicitação negada! Existem processos cadastrados com esse tipo de processo."
                );
                break;
            case "error_permanent_position_destroy":
                showErrorMessage(
                    "Solicitação negada! Existem processos cadastrados com esse cargo permanente."
                );
                break;
            case "destroy_error":
                showErrorMessage(
                    "Solicitação negada! Existem processos cadastrados com esse Status."
                );
                break;
            case "success_update":
                showSuccessToast("Status atualizado com sucesso!");
                break;
            case "success_process_type_update":
                showSuccessToast("Tipo de processo atualizado com sucesso!");
                break;
            case "success_permanent_position_id_update":
                showSuccessToast("Cargo permanente atualizado com sucesso!");
                break;
            case "success_commission_position_update":
                showSuccessToast("Cargo de comissão atualizado com sucesso!");
                break;
            case "success_created_process_type":
                showSuccessToast("Tipo de processo adicionado com sucesso!");
                break;
            default:
                showErrorMessage("Aconteceu um erro desconhecido!");
                break;
        }
    }
});

function showErrorMessage(text) {
    Swal.fire({
        icon: "error",
        title: "Atenção!",
        text: text,
    });
}

function showSuccessToast(title) {
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
        title: title,
    });
}

// Update Function
function update(id, type) {
    if (type == "status") {
        link = "/dropdown_list/status/update";

        // Faz uma solicitação AJAX para buscar o processo selecionado
        fetch(`/dropdown_list/status/select/${id}`)
            .then((response) => response.json())
            .then((data) => {
                // Exibir o processo no modal
                let modal = new bootstrap.Modal(
                    document.getElementById("updateStatus")
                );
                let selected_status_name = document.getElementById(
                    "selected_status_name"
                );
                let selected_status_situation = document.getElementById(
                    "selected_status_situation"
                );
                let selected_status_description = document.getElementById(
                    "selected_status_description"
                );
                let selected_status_id =
                    document.getElementById("selected_status_id");

                selected_status_description.value = data.status.description;
                selected_status_situation.value = data.status.situation;
                selected_status_name.value = ` ${data.status.name}`;
                selected_status_id.value = data.status.id;
                modal.show();
                // Define o atributo "action" do formulário com o link desejado
                let updateForm = document.getElementById("update_form");
                updateForm.action = link;
            })
            .catch((error) => {
                console.error("Erro ao buscar a marca: " + error);
            });
    } else if (type == "process_type") {
        link = "/dropdown_list/process_type/update";

        // // Faz uma solicitação AJAX para buscar o processo selecionado
        fetch(`/dropdown_list/process_type/select/${id}`)
            .then((response) => response.json())
            .then((data) => {
                // Exibir o processo no modal
                let modal = new bootstrap.Modal(
                    document.getElementById("updateProcessType")
                );
                let selected_process_type_name = document.getElementById(
                    "selected_process_type_name"
                );
                let selected_process_type_situation = document.getElementById(
                    "selected_process_type_situation"
                );
                let selected_process_type_description = document.getElementById(
                    "selected_process_type_description"
                );
                let selected_process_type_id = document.getElementById(
                    "selected_process_type_id"
                );

                selected_process_type_description.value =
                    data.process_type.description;
                selected_process_type_situation.value =
                    data.process_type.situation;
                selected_process_type_name.value = `${data.process_type.name}`;
                selected_process_type_id.value = data.process_type.id;
                modal.show();
                // Define o atributo "action" do formulário com o link desejado
                let updateForm = document.getElementById("update_form");
                updateForm.action = link;
            })
            .catch((error) => {
                console.error("Erro ao buscar a marca: " + error);
            });
    } else if (type == "permanent_position") {
        link = "/dropdown_list/permanent_position/update";
        // // // Faz uma solicitação AJAX para buscar o processo selecionado
        fetch(`/dropdown_list/permanent_position/select/${id}`)
            .then((response) => response.json())
            .then((data) => {
                //         // Exibir o processo no modal
                let modal = new bootstrap.Modal(
                    document.getElementById("updatePermanentProsition")
                );
                let selected_permanent_position_name = document.getElementById(
                    "selected_permanent_position_name"
                );
                let selected_permanent_position_situation =
                    document.getElementById(
                        "selected_permanent_position_situation"
                    );
                let selected_permanent_positions_description =
                    document.getElementById(
                        "selected_permanent_positions_description"
                    );
                let selected_permanent_position_id = document.getElementById(
                    "selected_permanent_position_id"
                );
                selected_permanent_positions_description.value =
                    data.permanent_position.description;
                selected_permanent_position_situation.value =
                    data.permanent_position.situation;
                selected_permanent_position_name.value = `${data.permanent_position.name}`;
                selected_permanent_position_id.value =
                    data.permanent_position.id;
                modal.show();
                // Define o atributo "action" do formulário com o link desejado
                let updateForm = document.getElementById("update_form");
                updateForm.action = link;
            })
            .catch((error) => {
                console.error("Erro ao buscar a marca: " + error);
            });
    } else if (type == "commission_position") {
        link = "/dropdown_list/commission_position/update";

        fetch(`/dropdown_list/commission_position/select/${id}`)
            .then((response) => response.json())
            .then((data) => {
                // Exibir o processo no modal
                let modal = new bootstrap.Modal(
                    document.getElementById("updateCommissionProsition")
                );
                let selected_commission_position_name = document.getElementById(
                    "selected_commission_position_name"
                );
                let selected_commission_position_id = document.getElementById(
                    "selected_commission_position_id"
                );
                let selected_commission_position_situation =
                    document.getElementById(
                        "selected_commission_position_situation"
                    );
                let selected_commission_positions_description =
                    document.getElementById(
                        "selected_commission_positions_description"
                    );

                selected_commission_position_name.value = `${data.commission_position.name}`;
                selected_commission_position_id.value =
                    data.commission_position.id;
                selected_commission_position_situation.value =
                    data.commission_position.situation;
                selected_commission_positions_description.value =
                    data.commission_position.description;

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

// Function to delete a status
function addIdForDelete(id, type) {
    const linkForDelete = document.getElementById("linkForDelete");
    let link;

    if (type === "status") {
        link = "/dropdown_list/destroy/" + id;
    } else if (type === "process_type") {
        link = "/dropdown_list/process_type/destroy/" + id;
    } else if (type === "permanent_position") {
        link = "/dropdown_list/permanent_position/destroy/" + id;
    } else if (type === "commission_position") {
        link = "/dropdown_list/commission_position/destroy/" + id;
    }

    linkForDelete.setAttribute("href", link);
}
