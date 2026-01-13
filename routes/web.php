<?php

use Illuminate\Support\Facades\Route;
// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarenciaController;
use App\Http\Controllers\UeeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ServidoreController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\ProvimentoController;
use App\Http\Controllers\ApoioPedagogicoController;
use App\Http\Controllers\Nota_tecnicaController;
use App\Http\Controllers\AposentadoriasController;
use App\Http\Controllers\RegularizacaoFuncionalController;
use App\Http\Controllers\ListasSuspensasController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ManutencaoController;
use App\Http\Controllers\VagareservaController;
use App\Models\VagaReserva;
use App\Http\Controllers\IngressoController;

Route::middleware('auth')->group(function () {
    //Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/atualizar_ano_ref/{ano}', [HomeController::class, 'AddAnoref']);
    //Carencias
    Route::get('/carencia/real', [CarenciaController::class, 'newCarenciaReal'])->name('carencia.real')->middleware(['auth', 'profile:cpg_tecnico,administrador']);
    Route::get('/carencia/temporaria', [CarenciaController::class, 'newCarenciaTemp'])->name('carencia.temp')->middleware(['auth', 'profile:cpg_tecnico,administrador']);
    Route::get('/carencias/{tipo}', [CarenciaController::class, 'showCarencias'])->name("carencias.index");
    Route::get('/detalhar_carencia/{carencia}', [CarenciaController::class, 'showCarenciaDetalhada']);
    Route::get('/deletar_carencia/{carencia}', [CarenciaController::class, 'destroy']);
    Route::get('/deletar_vacancy_pedagogical/{id}', [ApoioPedagogicoController::class, 'destroyVacancyPedagogical']);
    Route::get('/relatorio/carencia', [CarenciaController::class, 'printoutTable'])->name("carencia.relatorio");
    Route::get('/excel/carencias', [CarenciaController::class, 'generateExcel'])->name("carencia.excel");
    Route::post('/incluir_carencia', [CarenciaController::class, 'addCarencia']);
    Route::post('/consultarCarencias/{codigo_unidade},{tipo_carenciaParaConsulta}', [CarenciaController::class, 'searchDeficienciesByTypeAndUnitCode']);
    Route::post('/consultarCurso/{eixo}', [CarenciaController::class, 'searchCursos']);
    Route::post('/carencia/filter_carencias', [CarenciaController::class, 'showCarenciaByForm']);
    Route::put('/carencias/update/{id}', [CarenciaController::class, 'update']);
    Route::post('/addCarenciaPedagogica', [ApoioPedagogicoController::class, 'addCarenciaPedagogica']);
    Route::get('/carencia/apoio_pedagogico/add', [ApoioPedagogicoController::class, 'newCarenciaRealApoioPedagogico'])->name('carencia.real.apoioPedagogico');
    Route::post('/carencia/apoio_pedagogicos/add', [ApoioPedagogicoController::class, 'showCarenciaApoioPedagogicoByForm'])->name('carencia.ApoioPedagogicoByForm');
    Route::get('/carencias/apoioPedagogico/relatorio', [ApoioPedagogicoController::class, 'printoutTable'])->name("carencia.apoioPedagogico.relatorio");
    Route::get('/carencias/apoioPedagogico/excel', [ApoioPedagogicoController::class, 'generateExcel'])->name("carencia.apoioPedagogico.excel");
    //Provimento
    Route::get('/provimento/buscar', [ProvimentoController::class, 'newProvimento'])->name("provimentos.add");
    Route::get('/provimento/{codigo_unidade_provimento}', [ProvimentoController::class, 'newProvimentoByUee']);
    Route::get('/buscar/provimento/{tipo}', [ProvimentoController::class, 'showProvimentos'])->name("provimentos.show");
    Route::get('/provimentos/relatorio', [ProvimentoController::class, 'imprimirTabela'])->name("provimentos.relatorio");
    Route::get('/provimentos/excel', [ProvimentoController::class, 'gerarExcel'])->name("provimentos.excel");
    Route::get('/provimentos/anuencia/{cadastro}', [ProvimentoController::class, 'gerarAnuencia']);
    Route::get('/provimentos/encaminhamento/{encaminhamento}', [ProvimentoController::class, 'gerarEncaminhamento']);
    Route::get('/provimento/detalhes_provimento/{provimento}', [ProvimentoController::class, 'detailProvimento'])->name("provimentos.detalhar");
    Route::get('/prover/{id}/{cod_ue}', [ProvimentoController::class, 'provide'])->name("provimentos.prover");
    Route::get('/deletar_provimento/{provimento}', [ProvimentoController::class, 'destroy']);
    Route::get('/provimentos/servidores_encaminhamento', [ProvimentoController::class, 'provimentosForAnuencia'])->name("provimentos.servidores_encaminhamento");
    Route::post('/consultarUnidadeProvimento/{codigo_unidade_provimento}', [ProvimentoController::class, 'searchUeeProvimento']);
    Route::post('/addNewProvimento', [ProvimentoController::class, 'addNewProvimento']);
    Route::post('/processData', [ProvimentoController::class, 'processData']);
    Route::post('/provimentos', [ProvimentoController::class, 'showProvimentoByForm'])->name("provimentos.showByForm");
    Route::put('/provimento/update/{id}', [ProvimentoController::class, 'update'])->name("provimento.update");
    Route::get('/provimento/arquivo/{filename}', [ProvimentoController::class, 'viewArquivo'])->name("provimento.arquivo")->where('filename', '.*');
    Route::get('/validarProvimento/{id}/{action}', [ProvimentoController::class, 'validarProvimento']);
    Route::get('/encaminhamento/efetivo/prover', [ProvimentoController::class, 'createProvimentoEfetivo'])->name("provimento_efetivo.create");
    Route::get('/encaminhamento/efetivo/show', [ProvimentoController::class, 'showProvimentoEfetivo'])->name("provimento_efetivo.show");
    Route::post('/encaminhamento/efetivo/create', [ProvimentoController::class, 'addNewProvimentoEfetivo']);
    Route::post('/encaminhamento/efetivo/show/search', [ProvimentoController::class, 'showProvimentoEfetivoByForm'])->name("provimento_efetivo.showByForm");
    Route::get('/encaminhamento/efetivo/excel', [ProvimentoController::class, 'gerarExcelEncaminhamentoEfetivos'])->name("provimentosEfetivos.excel");
    Route::get('/encaminhamento/efetivo/detail/{id}', [ProvimentoController::class, 'detailProvimentoEfetivo'])->name("provimentosEfetivos.detail");
    // Alias route kept for backwards-compatibility / older links
    Route::get('/provimento/efetivo/detail/{id}', [ProvimentoController::class, 'detailProvimentoEfetivo'])->name("provimentosEfetivos.detail.alias");
    Route::put('/encaminhamento/efetivo/update/{id}', [ProvimentoController::class, 'updateProvimentoEfetivo'])->name("provimentosEfetivos.update");
    // Alias route for older links/forms that post to /provimento/efetivo/update/{id}
    Route::put('/provimento/efetivo/update/{id}', [ProvimentoController::class, 'updateProvimentoEfetivo'])->name("provimentosEfetivos.update.alias");
    Route::get('/encaminhamento/efetivo/destroy/{provimentosEncaminhado}', [ProvimentoController::class, 'destroyProvimentoEfetivo']);
    Route::post('/update/situation_server1/{situation}/{id}', [ProvimentoController::class, 'update_situation_server1']);
    Route::post('/update/situation_server2/{situation}/{id}', [ProvimentoController::class, 'update_situation_server2']);
    Route::get('/encaminhamento/efetivo/filter', [ProvimentoController::class, 'showProvimentoEfetivoFilter']);
    Route::post('/update/inconsistencia/{id}', [ProvimentoController::class, 'update_inconsistencia']);
    Route::get('/provimentos/validar', [ProvimentoController::class, 'validarDocs'])->name("provimentos.validarDocs");
    Route::post('/update/atualizarCOP', [ProvimentoController::class, 'update_cop']);
    Route::post('/update/atualizarAssuncao', [ProvimentoController::class, 'update_assuncao']);
    //Unidades Escolares
    Route::get('/uees/{tipo}', [UeeController::class, 'showUees'])->name('uees.show');
    Route::get('/uees/detail/{id}', [UeeController::class, 'detailUee']);
    Route::put('/uees/update/{id}', [UeeController::class, 'update']);
    Route::get('/homologarUnidade/{new_cod}/{action}', [UeeController::class, 'homologarUnidade']);
    Route::post('/consultarUnidade/{cod_unidade}', [UeeController::class, 'searchUnidade']);
    Route::post('/consultarUnidadeForCodSap/{cod_unidade}', [UeeController::class, 'searchUnidadeCodSap']);
    Route::post('/consultarMunicipio/{search_nte}', [UeeController::class, 'searchMunicipio']);
    Route::post('/consultarUees/{search_municipio}', [UeeController::class, 'searchUees']);
    Route::post('/uees/autocomplete', [UeeController::class, 'autocomplete'])->name('uees.autocomplete');
    Route::get('/uees/list', [UeeController::class, 'listAll'])->name('uees.list');
    Route::post('/uees/search', [UeeController::class, 'showUeesByForm'])->name("uees.showByForm");
    Route::get('/uee/destroy/{id}', [UeeController::class, 'destroy']);
    Route::get('/uees/excel/generate', [UeeController::class, 'gerarExcel'])->name("uees.excel");
    Route::post('/uees/desativation_uee', [UeeController::class, 'createDesativationUee'])->name("uees.desativation_uee");
    //Servidores
    Route::get('/servidores', [ServidoreController::class, 'addShowServidores'])->name("servidores.show");
    // DataTables server-side endpoint
    Route::get('/servidores/data', [ServidoreController::class, 'data'])->name('servidores.data');
    Route::get('/detalhes_servidor/{cadastro}', [ServidoreController::class, 'detalhesServidorAnuencia']);
    Route::get('/servidor/detalhes/{servidor}', [ServidoreController::class, 'detalharServidor'])->name("servidores.detail");
    Route::post('/consultarServidor/{cadastro_servidor}', [ServidoreController::class, 'searchServidor']);
    Route::post('/servidores/add', [ServidoreController::class, 'addShowServidoresByForm'])->name("servidores.add");
    Route::post('/servidores/add_encaminhamento', [ServidoreController::class, 'addShowServidoresEncaminhamentoByForm'])->name("servidores.add_encaminhamento");
    Route::put('/servidores/update/{id}', [ServidoreController::class, 'update']);
    Route::delete('/servidor/destroy/{id}', [ServidoreController::class, 'destroy']);
    Route::post('/consultar/efetivo/{cpf}', [ServidoreController::class, 'consultarEfetivo'])->name("servidores_efetivos.show");
    //Disciplinas
    Route::post('/consultarDisciplina', [DisciplinaController::class, 'searchDisciplinas']);
    Route::post('/addNewDiscipline', [DisciplinaController::class, 'create'])->name("discipline.create");
    Route::get('/deletar_disciplina/{id}', [DisciplinaController::class, 'destroy']);
    //Usuarios
    Route::get('/users', [UsersController::class, 'showUsers'])->name('users.show');
    Route::get('/detalhar_user/{id}', [UsersController::class, 'detailUser']);
    Route::put('/users/update/{id}', [UsersController::class, 'update']);
    Route::delete('/users/destroy/{id}', [UsersController::class, 'destroy']);
    Route::post('/users/update/pass/{id}', [UsersController::class, 'resetPass']);
    Route::post('/users/create', [UsersController::class, 'create'])->name("users.create");
    //Relatorios
    Route::get('/search/uee/nota_tecnica', [Nota_tecnicaController::class, 'searchUeeForNota_tecnica'])->name("nota_tecnica");
    Route::post('/consultarNotaTecnica', [Nota_tecnicaController::class, 'notaTecnica']);
    Route::get('/provimento/view/data', [ProvimentoController::class, 'viewData']);
    Route::post('/provimentos/search/data', [ProvimentoController::class, 'viewDataByForm'])->name("provimentos.viewData");
    Route::get('/data/excel', [ProvimentoController::class, 'gerarExcelPorAreas'])->name("data.excel");
    Route::get('/provimento/status/tramite', [ProvimentoController::class, 'statusProvimentoTramite'])->name("provimento.status");
    Route::get('/provimento/data/excel', [ProvimentoController::class, 'excelForDaysInTramite'])->name("provimento.statusInTramite");
    Route::get('/status/diario', [HomeController::class, 'statusDiario'])->name('status.diario');
    Route::get('/status/digitacao', [UeeController::class, 'statusDigitacao'])->name('status.digitacao');
    Route::post('/status/digitacao/search', [UeeController::class, 'buscarUnidadesPorInicioDeDigitacao']);
    Route::get('/status/digitacao/data', [UeeController::class, 'statusDigitacaoExcel']);
    Route::get('/status/digitacao/filter', [UeeController::class, 'statusDigitacaoFilter']);
    Route::get('/status/unidades_escolares', [UeeController::class, 'statusUnidadesEscolares'])->name("status.unidades_escolares");
    //Auth
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //Aposentadoria
    Route::get('/aposentadorias', [AposentadoriasController::class, 'show'])->name('aposentadorias.show');
    Route::post('/aposentadorias/create', [AposentadoriasController::class, 'create'])->name('aposentadorias.create');
    Route::get('/aposentadorias/view/{id}', [AposentadoriasController::class, 'view'])->name('aposentadorias.view');
    Route::post('/aposentadorias/update', [AposentadoriasController::class, 'update'])->name('aposentadorias.update');
    Route::get('/aposentadorias/select/{id}', [AposentadoriasController::class, 'select'])->name('aposentadorias.select');
    Route::get('/consultarServidorCompleto/{matricula}', [AposentadoriasController::class, 'searchServidor'])->name('search.servidor');
    Route::post('/aposentadorias/filter', [AposentadoriasController::class, 'filter'])->name('aposentadorias.filter');
    Route::get('/aposentadorias/print/{id}', [AposentadoriasController::class, 'print'])->name('aposentadorias.print');
    Route::get('/aposentadorias/destroy/{id}', [AposentadoriasController::class, 'destroy'])->name('aposentadorias.destroy');
    Route::get('/aposentadoria/data/excel', [AposentadoriasController::class, 'excelAposentadorias'])->name("aposentadorias.excel");
    //regularização Funcional
    Route::get('/regularizacao_funcional', [RegularizacaoFuncionalController::class, 'create'])->name('regularizacao_funcional.create');
    Route::post('/regularizacao_funcional/create', [RegularizacaoFuncionalController::class, 'store'])->name('regularizacao_funcional.store');
    Route::get('/regularizacao_funcional/view', [RegularizacaoFuncionalController::class, 'show'])->name('regularizacao_funcional.show');
    Route::get('/regularizacao_funcional/filter', [RegularizacaoFuncionalController::class, 'filter'])->name('regularizacao_funcional.filter');
    Route::get('/regularizacao_funcional/detail/{id}', [RegularizacaoFuncionalController::class, 'detail'])->name('regularizacao_funcional.detail');
    Route::put('/regularizacao_funcional/update', [RegularizacaoFuncionalController::class, 'update'])->name('regularizacao_funcional.update');
    Route::post('/regularizacao_funcional/filter', [RegularizacaoFuncionalController::class, 'filtered'])->name('reg_funcional.showByForm');
    Route::get('/regularizacao_funcional/data/excel', [RegularizacaoFuncionalController::class, 'excel_regularizacao_funcional'])->name("regularizacao_funcional.excel");
    Route::get('/deletar_regularizacao/{id}', [RegularizacaoFuncionalController::class, 'destroy_regularizacao']);
    // Listas Suepensas
    Route::get('/relatorios', [ListasSuspensasController::class, 'relatorios'])->name("listas_suspensas.relatorios");
    Route::get('/listas_suspensas', [ListasSuspensasController::class, 'index'])->name("listas_suspensas.index");
    Route::get('/disciplinas', [ListasSuspensasController::class, 'index_disciplinas'])->name("listas_suspensas_disciplinas.index");
    Route::get('/areas', [ListasSuspensasController::class, 'index_areas'])->name("listas_suspensas_areas.index");
    Route::post('/areas', [ListasSuspensasController::class, 'create_areas'])->name("areas.create");
    Route::get('/deletar_area/{id}', [ListasSuspensasController::class, 'destroy_area']);
    Route::get('/cursos', [ListasSuspensasController::class, 'index_cursos'])->name("listas_suspensas_cursos.index");
    Route::post('/cursos', [ListasSuspensasController::class, 'create_cursos'])->name("cursos.create");
    Route::get('/deletar_curso/{id}', [ListasSuspensasController::class, 'destroy_curso']);
    Route::get('/componente_especial', [ListasSuspensasController::class, 'index_componente_especial'])->name("listas_suspensas_componente_especial.index");
    Route::post('/componente_especial', [ListasSuspensasController::class, 'create_componente_especial'])->name("componente_especial.create");
    Route::get('/deletar_componente_especial/{id}', [ListasSuspensasController::class, 'destroy_componente_especial']);
    Route::get('/forma_suprimento', [ListasSuspensasController::class, 'index_forma_suprimento'])->name("forma_suprimento.index");
    //Motivo de Vagas
    Route::get('/motivo_de_vagas', [CarenciaController::class, 'motivo_vagas'])->name("motivo_vagas.show");
    Route::post('/create/motivo_de_vagas', [CarenciaController::class, 'createMotivoDeVagas'])->name("motivo_vagas.create");
    Route::delete('/motivo_de_vagas/destroy/{id}', [CarenciaController::class, 'destroyMotivo']);
    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name("logs.show");
    // DataTables server-side endpoint for logs
    Route::get('/logs/data', [LogController::class, 'data'])->name('logs.data');
    //Manutenção
    Route::get('/manutencoes', [ManutencaoController::class, 'index'])->name("manutencao.show");
    Route::get('/manutencoes/create', [ManutencaoController::class, 'create'])->name("manutencao.create");
    // Reserva de Vagas
    Route::post('/reserva/create', [VagareservaController::class, 'create'])->name('reserva.create');
    Route::get('/reserva/carencia/show', [VagareservaController::class, 'index'])->name('reserva.index');
    Route::put('/reserva/carencia/update', [VagareservaController::class, 'update'])->name('reserva.update');
    Route::put('/reserva/carencia/update/servidor', [VagareservaController::class, 'updateServidor'])->name('reserva.update_servidor');
    Route::post('/reserva/prover', [VagareservaController::class, 'createProvimento'])->name('reserva.createProvimento');
    Route::post('/reservar-carencias', [VagareservaController::class, 'receberCarenciasParaReserva']);
    Route::get('/reservas/bloco/{blocoId}', [VagareservaController::class, 'detalharBloco'])->name('reservas.detalharBloco');
    Route::get('/reservas/bloco/{blocoId}/imprimir-termo', [VagareservaController::class, 'imprimirTermo'])->name('reservas.imprimirTermo');
    Route::get('/reservas/data/excel', [VagareservaController::class, 'excel_reservas'])->name("reservas.excel");
    Route::get('/reservas/bloco/destroy/{blocoId}', [VagareservaController::class, 'destroyBloco'])->name('reservas.bloco.destroy');
    Route::post('/reservas/bloco/filter', [VagareservaController::class, 'index'])->name('reservas.index');
    // Ingresso
    Route::get('/ingresso', [IngressoController::class, 'index'])->name('ingresso.index');
    Route::get('/ingresso/dashboard', [IngressoController::class, 'dashboard'])->name('ingresso.dashboard');
    Route::post('/ingresso/search-cpf', [IngressoController::class, 'searchByCpf']);
    Route::post('/ingresso/{id}/validar', [IngressoController::class, 'validateIngresso'])->name('ingresso.validar');
    Route::post('/ingresso/{id}/retirar-validacao', [IngressoController::class, 'unvalidateIngresso'])->name('ingresso.retirar_validacao');
    Route::get('/ingresso/{id}/oficio', [IngressoController::class, 'oficio'])->name('ingresso.oficio');
    Route::get('/ingresso/export/csv', [IngressoController::class, 'exportCsv'])->name('ingresso.export.csv');
    // Debug route (authorized users only) to inspect candidate DB row and recent encaminhamentos
    Route::get('/ingresso/debug-status/{id}', [IngressoController::class, 'debugStatus'])->name('ingresso.debug');
    Route::get('/ingresso/data', [IngressoController::class, 'data'])->name('ingresso.data');
    Route::get('/ingresso/{identifier}', [IngressoController::class, 'show'])->name('ingresso.show');
    Route::get('/ingresso/{id}/documentos', [IngressoController::class, 'getDocumentChecklist'])->name('ingresso.documentos.get');
    Route::post('/ingresso/{id}/documentos', [IngressoController::class, 'storeDocumentChecklist'])->name('ingresso.documentos.store');
    Route::post('/ingresso/{id}/documentos/confirmar_cpm', [IngressoController::class, 'confirmDocumentosCpm'])->name('ingresso.documentos.confirmar_cpm');
    Route::post('/ingresso/{id}/assign', [IngressoController::class, 'assign'])->name('ingresso.assign');
    Route::match(['put','post'],'/ingresso/{id}/update', [IngressoController::class, 'updateCandidate'])->name('ingresso.update');
    Route::post('/ingresso/{id}/encaminhar', [IngressoController::class, 'forward'])->name('ingresso.encaminhar');
    Route::delete('/ingresso/{id}/encaminhar/{encaminhamento}', [IngressoController::class, 'destroyEncaminhamento'])->name('ingresso.encaminhar.destroy');
    Route::post('/ingresso/{id}/assign', [IngressoController::class, 'assign'])->name('ingresso.assign');
    Route::delete('/ingresso/{id}', [IngressoController::class, 'destroy'])->name('ingresso.destroy');
    
});

require __DIR__ . '/auth.php';