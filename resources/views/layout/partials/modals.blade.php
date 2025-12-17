<!-- Modal Selecionar Carencia -->
<div class="modal fade" id="ExemploModalCentralizado" tabindex="-1" role="dialog"
    aria-labelledby="TituloModalCentralizado" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModalCentralizado">INCLUIR CARÊNCIA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a id="btn_real" class="btn btn-primary" href="{{ route('carencia.real') }}">REAL</a>
                <a id="btn_temp" class="btn btn-primary" href="{{ route('carencia.temp') }}">TEMPORÁRIA</a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="TitulommodalDelete"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TitulommodalDelete"><strong>Excluir Dados</strong>
                </h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente !</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa-solid fa-xmark"></i>
                    Fechar</button>
                <a title="Excluir Carência"><button id="btn-delete" type="button" class="btn float-right btn-danger"><i
                            class="fas fa-trash-alt"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Provimento-->
<div class="modal fade" id="modalDeleteProvimento" tabindex="-1" role="dialog"
    aria-labelledby="TituloModalDeleteProvimento aria-hidden=" true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TituloModalDeleteProvimento"><strong>Excluir
                        Dados</strong></h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4><strong>Tem certeza?</strong></h4>
                <h4><strong>O registro sera excluido permanentemente e a carência de origem será atualizada!</strong>
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                <a title="Excluir Provimento"><button id="btn_delete_provimento" type="button"
                        class="btn float-right btn-danger"><i class="fas fa-trash-alt"></i> Excluir</button></a>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Provimentos Efetivos-->
<div class="modal fade" id="ModalDeleteProvimentosEfetivos" tabindex="-1" role="dialog"
    aria-labelledby="TituloModalDeleteProvimentosEfetivos" aria-hidden=" true">
    <div class="modal-dialog modal-dialog-centered" role="document" style=" margin-top: 0px; margin-bottom: 0px;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h4 class="modal-title text-center text-dark" id="TituloModalDeleteProvimentosEfetivos">
                    <strong>Excluir Dados</strong>
                </h4>
            </div>
            <div class="modal-body modal-destroy">
                <h4 class="subheader"><strong>Tem certeza?</strong></h4>
                <h4 class="subheader"><strong>O registro sera excluido permanentemente!</strong></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
                <a title="Excluir Encaminhamento"><button id="btn_delete_provimento" type="button"
                        class="btn float-right btn-danger">Excluir</button></a>
            </div>
        </div>
    </div>
</div>