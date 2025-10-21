@extends('layout.main')

@section('title', 'SCP - Provimento')

@section('content')

    <style>
        .btn {
            padding: 5px !important;
        }
    </style>

    @if (session('msg'))
        <div class="col-12">
            <div class="alert text-center text-white bg-success container alert-success alert-dismissible fade show"
                role="alert" style="min-width: 100%">
                <strong>{{ session('msg') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
    <div class="bg-primary card text-white card_title">
        <h3 class=" title_show_carencias">Lista de Servidores Providos</h3>
    </div>
    <div class="form_content mb-0">

    </div>
    <div class="mb-2 d-flex justify-content-end " style="gap: 3px;">
        <a class="mb-2 btn bg-primary text-white" title="Filtros Personalizaveis" data-toggle="collapse"
            href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-filter">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path
                    d="M4 4h16v2.172a2 2 0 0 1 -.586 1.414l-4.414 4.414v7l-6 2v-8.5l-4.48 -4.928a2 2 0 0 1 -.52 -1.345v-2.227z" />
            </svg>
        </a>
        {{-- <a class="mb-2 btn bg-primary text-white" target="_blank" href="{{ route('reservas.excel') }}" data-toggle="tooltip"
            data-placement="top" title="Download em Excel">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icons-tabler-outline icon-tabler-file-download">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                <path d="M12 17v-6" />
                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5" />
            </svg>
        </a> --}}
    </div>

    <div class="collapse mb-4" id="collapseExample">
        <div class="card card-body border shadow bg-light rounded pt-3 pl-3 pr-3">
            <form id="active_form" action="{{ route('provimentos.validarDocs') }}" method="get">
                @csrf
                <div class="form-row">
                    <div class="col-md-1">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="nte_seacrh">NTE</label>
                            {{-- Adicionamos a lógica para manter o valor selecionado --}}
                            <select name="nte_seacrh" id="nte_seacrh" class="form-control form-control-sm select2">
                                <option value=""></option>
                                @for ($i = 1; $i <= 27; $i++)
                                    <option value="{{ $i }}" @if (isset($input['nte_seacrh']) && $input['nte_seacrh'] == $i) @endif>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="municipio_search">MUNICIPIO</label>
                            <select name="municipio_search" id="municipio_search"
                                class="form-control form-control-sm select2">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group_disciplina">
                            <label class="control-label" for="search_uee">NOME DA UNIDADE ESCOLAR</label>
                            <select name="search_uee" id="search_uee" class="form-control form-control-sm select2">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group_disciplina">
                            <label for="search_servidor_matricula" class="">MATRÍCULA/CPF</label>
                            <input name="search_servidor_matricula" id="search_servidor_matricula" type="text" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
                <div id="buttons" class="mt-3 buttons d-flex align-items-center">
                    <button id="" class="button" type="submit">
                        <span class="button__text">BUSCAR</span>
                        <span class="button__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </span>
                    </button>
                    <button id="" class="ml-2 button" type="button" onclick="clearForm()">
                        <span class="button__text text-danger">LIMPAR</span>
                        <span class="button__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="red" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-eraser">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3" />
                                <path d="M18 13.3l-6.3 -6.3" />
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="table-responsive">
        <table id="consultarCarencias" class="table table-sm table-hover table-bordered">
            <thead class="bg-primary text-white">
                <tr>
                    <th class="text-center" scope="col">NTE</th>
                    <th class="text-center" scope="col">MUNICIPIO</th>
                    <th class="text-center" scope="col">UNIDADE ESCOLAR</th>
                    <th class="text-center" scope="col">SERVIDOR</th>
                    <th class="text-center" scope="col">MATRICULA / CPF</th>
                    <th class="text-center" scope="col">VINCULO</th>
                    <th class="text-center" scope="col">SITUAÇÃO</th>
                    <th class="text-center" scope="col">Nº COPE</th>
                    <th class="text-center" scope="col">AÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servidores as $servidor)
                    <tr>
                        <td class="text-center">{{ $servidor->nte }}</td>
                        <td>{{ $servidor->municipio }}</td>
                        <td>{{ $servidor->unidade_escolar }}</td>
                        <td>{{ $servidor->servidor }}</td>
                        <td class="text-center">{{ $servidor->cadastro }}</td>
                        <td class="text-center">{{ $servidor->vinculo }}</td>
                        <td class="text-center">
                            @if ($servidor->situacao_provimento === 'provida')
                                PROVIDO
                            @else
                                EM TRÂMITE
                            @endif
                        </td>
                        <td class="text-center">{{ $servidor->num_cop }}</td>
                        <td class="text-center">
                            <a title="Detalhar" href="/detalhes_servidor/{{ $servidor->cadastro }}"
                                class=" btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </a>
                        </td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleFilters() {
            const f = document.getElementById('filters_form');
            if (!f) return;
            f.hidden = !f.hidden;
        }

        function clearFilters() {
            const ids = ['nte_seacrh', 'municipio_search', 'search_uee', 'search_servidor_matricula',
                'search_situacao_provimento'
            ];
            ids.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
                else el.value = '';
            });
        }
    </script>
@endpush
