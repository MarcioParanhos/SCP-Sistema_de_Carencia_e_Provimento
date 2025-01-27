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
                showSuccessToast("Usuário adicionado com sucesso!");
                break;
            case "success_user_update":
                showSuccessToast("Usuário atualizado com sucesso!");
                break;
            case "success_destroy":
                showSuccessToast("Usuário excluido com sucesso!");
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
function updateUser(id) {
    link = "/users/update";
    // Faz uma solicitação AJAX para buscar o processo selecionado
    fetch(`/user/select/${id}`)
        .then((response) => response.json())
        .then((data) => {
            // Exibir o processo no modal
            let modal = new bootstrap.Modal(
                document.getElementById("updateModalUser")
            );
            let selected_user_name =
                document.getElementById("selected_user_name");
            let selected_user_sector = document.getElementById(
                "selected_user_sector"
            );
            let selected_user_email = document.getElementById(
                "selected_user_email"
            );
            let selected_user_profile = document.getElementById(
                "selected_user_profile"
            );
            let selected_user_cpf =
                document.getElementById("selected_user_cpf");
            let selected_user_situation = document.getElementById(
                "selected_user_situation"
            );
            let selected_user_description = document.getElementById(
                "selected_user_description"
            );
            let selected_user_id = document.getElementById("selected_user_id");

            selected_user_situation.value = data.user.situation;
            selected_user_situation.innerHTML = data.user.situation;
            selected_user_profile.innerHTML = data.user.profile.name;
            selected_user_profile.value = data.user.profile.id;
            selected_user_email.value = data.user.email;
            selected_user_name.value = data.user.name;
            selected_user_sector.innerHTML = data.user.sector.name;
            selected_user_sector.value = data.user.sector.id;
            selected_user_id.value = data.user.id;
            selected_user_description.value = data.user.description;
            selected_user_cpf.value = `${data.user.cpf}`;
            modal.show();
            // Define o atributo "action" do formulário com o link desejado
            let updateForm = document.getElementById("update_form");
            updateForm.action = link;
        })
        .catch((error) => {
            console.error("Erro ao buscar o usuário: " + error);
        });
}

// Function to delete a User
function addIdForDelete(id) {
    const linkForDelete = document.getElementById("linkForDelete");

    link = "/user/destroy/" + id;

    linkForDelete.setAttribute("href", link);
}
