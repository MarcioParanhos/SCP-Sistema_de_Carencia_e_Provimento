document.addEventListener("DOMContentLoaded", function () {
    // Passo 1: Encontre o seu botão na página pelo ID.
    // Certifique-se de que seu botão no HTML tenha: id="reservarBtn"
    const reservarBtn = document.getElementById("reservarBtn");

    if (reservarBtn) {
        reservarBtn.addEventListener("click", function () {
            // Passo 2: Pega todos os checkboxes com a classe '.row-checkbox' que estão marcados.
            const checkedBoxes = document.querySelectorAll(
                ".row-checkbox:checked"
            );

            // Passo 3: Cria um array contendo apenas os valores (os IDs) dos checkboxes marcados.
            const selectedIds = Array.from(checkedBoxes).map(
                (checkbox) => checkbox.value
            );

            // Passo 4: Verifica se o usuário selecionou pelo menos um item.
            if (selectedIds.length === 0) {
                Swal.fire({
                    title: "Por favor, selecione ao menos uma carência para reservar.",
                    icon: "warning",
                    draggable: true,
                    customClass: {
                        popup: "swal-title-sm", // Aplicamos a classe no container principal
                    },
                });
                return; // Para a execução se nada foi selecionado.
            }

            // Passo 5: Envia o array de IDs para a sua rota no Laravel.
            fetch("/reservar-carencias", {
                // Esta é a rota que vamos criar no próximo passo.
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    // Pega o token CSRF de uma tag <meta> no <head> do seu HTML.
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                // Converte o array de IDs para o formato JSON para envio.
                // O controller receberá um objeto com a chave "carencia_ids".
                body: JSON.stringify({ carencia_ids: selectedIds }),
            })
                .then((response) => response.json()) // Converte a resposta do servidor de JSON para objeto.
                .then((data) => {
                    // Exibe a mensagem de sucesso que veio do controller.
                    Swal.fire({
                        text: data.message,
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ok, Atualizar",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Opcional: Recarrega a página para atualizar a lista após o sucesso.
                            window.location.reload();
                        }
                    });
                })
                .catch((error) => {
                    // Em caso de erro na comunicação, exibe no console.
                    console.error("Erro ao enviar dados:", error);
                    Swal.fire({
                        title: "Ocorreu uma falha na comunicação com o servidor",
                        icon: "warning",
                        draggable: true,
                        customClass: {
                            popup: "swal-title-sm", // Aplicamos a classe no container principal
                        },
                    });
                });
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const session_message = document.getElementById("session_message");

    if (session_message) {
        if (session_message.value === "error") {
            Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: "Não é possível excluir a carência porque existem provimentos associados a ela.",
            });
        } else {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Carência Excluída com sucesso!",
                showConfirmButton: true,
            });
        }
    }
});

document.getElementById("selectAll").addEventListener("change", function () {
    let checkboxes = document.querySelectorAll(".column-checkbox");
    checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
});

function exportExcel() {
    let selectedColumns = [];
    document
        .querySelectorAll(".column-checkbox:checked")
        .forEach((checkbox) => {
            selectedColumns.push(checkbox.value);
        });

    let url = "/excel/carencias?columns=" + selectedColumns.join(",");
    window.open(url, "_blank");
}

