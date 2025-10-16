$(document).ready(function() {
            
    $('#btnAbrirModalEncaminhamento').on('click', function() {
        
        let carenciaIds = [];

        // SELETOR CORRIGIDO: Agora procuramos as linhas (tr) dentro de qualquer <tbody>.
        // Esta abordagem é mais flexível e funcionará com sua tabela sem exigir um ID específico.
        $('tbody tr[data-carencia-id]').each(function() {
            const id = $(this).data('carencia-id');
            // Adicionamos uma verificação para garantir que o ID não é nulo ou vazio
            if (id) {
                carenciaIds.push(id);
            }
        });
        
        // DEBUGGING: Pressione F12 no navegador e veja o Console.
        // Esta linha mostrará os IDs que o script conseguiu encontrar.
        console.log('IDs de carência encontrados:', carenciaIds);

        if (carenciaIds.length > 0) {
            $('#modalCarenciaIds').val(carenciaIds.join(','));
            $('#totalSelecionado').text(carenciaIds.length);
            $('#modalProver').modal('show');
        } else {
            // Mudei o alert para console.log para ser menos intrusivo durante o teste.
            // Se esta mensagem aparecer, significa que o seletor não encontrou nenhuma <tr> com 'data-carencia-id'.
            console.log('Nenhuma reserva com data-carencia-id foi encontrada na tabela.');
            alert('Não há reservas para encaminhar.');
        }
    });
});


function destroyReserva(bloco) {
    const ModalDeleteReserva = document.querySelector(
        "#ModalDeleteReserva a"
    );
    // Declarando a variavel Link
    let link = "/reservas/bloco/destroy/" + bloco;
    // Inserindo um elemento dentro do elemento selecionado com QuerySelector
    ModalDeleteReserva.setAttribute("href", link);
    //Apos inserir o Href no botão do modal abre o Modal para validação do usuario
    $("#ModalDeleteReserva").modal({
        show: true,
    });
}