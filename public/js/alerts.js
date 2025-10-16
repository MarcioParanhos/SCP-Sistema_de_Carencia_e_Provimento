document.addEventListener("DOMContentLoaded", function () {
    const session_message = document.getElementById("session_message");

    if (session_message) {
        if (session_message.value === "error") {
            Swal.fire({
                icon: "error",
                title: "Atenção!",
                text: "Não é possível excluir a carência porque existem provimentos associados a ela.",
            });
        } else if (session_message.value === "success_provimento_de_reserva") {
            Swal.fire({
                icon: "success",
                title: "Atenção!",
                text: "Reserva incluida em estado de Trâmite.",
            });
        } else if (session_message.value === "delete_reserva") {
            Swal.fire({
                icon: "success",
                title: "Atenção!",
                text: "Bloco de reserva excluído e Nº COP devolvido ao estoque com sucesso!",
            });
        } else if (session_message.value === "success_update_reserva") {
            Swal.fire({
                icon: "success",
                title: "Atenção!",
                text: "Bloco de vagas atualizado com sucesso!",
            });
            
        } else if (session_message.value === "error_update_reserva") {
            Swal.fire({
                icon: "info",
                title: "Atenção!",
                text: "Operação cancelada: O limite de 100 utilizações para o COP 365/2025 foi atingido.",
            });
            
        } else if (session_message.value === "success_update_servidor") {
            Swal.fire({
                icon: "success",
                title: "Atenção!",
                text: "Dados do servidor atualizados com sucesso!",
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

