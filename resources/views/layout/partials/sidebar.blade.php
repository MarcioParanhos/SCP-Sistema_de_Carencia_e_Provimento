<nav class="print-none  sidebar shadow rounded sidebar-offcanvas print-hidden" id="sidebar">
    <ul class="nav">
        @if (Auth::user()->profile != 'cad_tecnico' &&
                Auth::user()->profile != 'cgi_tecnico' &&
                (Auth::user()->name != 'Usuario CPG' && Auth::user()->name != 'Usuario CPM'))
            <li class="nav-item">
                <a class="nav-link" href="/">
                    <i class="icon-grid menu-icon"></i>
                    <span id="new-venda" class="menu-title">DASHBOARD</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                    aria-controls="ui-basic">
                    <i class="icon-square-plus menu-icon"></i>
                    <span class="menu-title">CARÊNCIA</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse subheader" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->profile === 'cpg_tecnico' || Auth::user()->profile === 'administrador')
                            @if (session('ano_ref') == $ano_atual || Auth::user()->profile === 'administrador')
                                <li class="nav-item text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-down">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4c.342 0 .674 .043 .99 .124" />
                                        <path d="M19 16v6" />
                                        <path d="M22 19l-3 3l-3 -3" />
                                    </svg>
                                    <a class="nav-link sub-title" href="" data-toggle="modal"
                                        data-target="#ExemploModalCentralizado">Incluir</a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-list-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 15m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M18.5 18.5l2.5 2.5" />
                                <path d="M4 6h16" />
                                <path d="M4 12h4" />
                                <path d="M4 18h4" />
                            </svg>
                            <a class="nav-link sub-title" href="/carencias/all_carencias">Buscar</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false"
                    aria-controls="form-elements">
                    <i class="ti-user menu-icon"></i>
                    <span class="menu-title">Provimento</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse subheader" id="form-elements">
                    <ul class="nav flex-column sub-menu">
                        @if (in_array(Auth::user()->profile_id, ['1', '4', '2']))
                            @if (session('ano_ref') == $ano_atual || Auth::user()->profile_id === '4')
                                <li class="nav-item text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    </svg>
                                    <a class="nav-link sub-title" href="{{ route('provimentos.add') }}">Incluir</a>
                                </li>
                            @endif
                        @endif
                        <li class="nav-item text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" />
                                <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M20.2 20.2l1.8 1.8" />
                            </svg>
                            <a class="nav-link sub-title" href="/buscar/provimento/all_provimentos">Buscar</a>
                        </li>
                        @if (Auth::user()->profile === 'cpm_tecnico' ||
                                Auth::user()->profile === 'administrador' ||
                                Auth::user()->profile === 'cpm_coordenador')
                            <li class="nav-item text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-check">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    <path d="M15 19l2 2l4 -4" />
                                </svg>
                                <a class="nav-link sub-title" href="{{ route('reserva.index') }}">RESERVA</a>
                            </li>
                            <li class="nav-item ml-0 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-cog">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h2.5" />
                                    <path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M19.001 15.5v1.5" />
                                    <path d="M19.001 21v1.5" />
                                    <path d="M22.032 17.25l-1.299 .75" />
                                    <path d="M17.27 20l-1.3 .75" />
                                    <path d="M15.97 17.25l1.3 .75" />
                                    <path d="M20.733 20l1.3 .75" />
                                </svg>
                                <a class="nav-link sub-title" title="Validar Dados" data-bs-toggle="tooltip"
                                    data-bs-placement="right"
                                    href="{{ route('provimentos.validarDocs') }}">VALIDAR</a>
                            </li>
                            {{-- <li class="nav-item"><i class="ti-download"></i><a class="nav-link sub-title"
                                                href="{{ route('provimentos.servidores_encaminhamento') }}">TERMOS</a>
                                        </li> --}}
                        @endif
                    </ul>
                </div>
            </li>
            {{-- <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#ingresso" aria-expanded="false"
                                aria-controls="ingresso">
                                <i class="ti-user menu-icon"></i>
                                <span class="menu-title">Ingresso</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse subheader" id="ingresso">
                                <ul class="nav flex-column sub-menu">
                                    @if (Auth::user()->profile === 'cpm_tecnico' || Auth::user()->profile === 'administrador' || Auth::user()->profile === 'cpm_coordenador' || Auth::user()->profile === 'cpg_tecnico')
                                        @if (session('ano_ref') == $ano_atual || Auth::user()->profile === 'administrador')
                                            <li class="nav-item"><i class="ti-plus"></i><a class="nav-link sub-title"
                                                    href="{{ route('provimentos.add') }}">Incluir</a></li>
                                        @endif
                                    @endif
                                    <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title"
                                            href="/buscar/provimento/all_provimentos">Buscar</a></li>
                                    @if (Auth::user()->profile === 'cpm_tecnico' || Auth::user()->profile === 'administrador' || Auth::user()->profile === 'cpm_coordenador')
                                        <li class="nav-item"><i class="ti-file"></i><a class="nav-link sub-title"
                                                href="{{ route('reserva.index') }}">RESERVA</a></li>
                                        <li class="nav-item"><i class="ti-download"></i><a class="nav-link sub-title"
                                                href="{{ route('provimentos.servidores_encaminhamento') }}">TERMOS</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li> --}}
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#encaminhamentos" aria-expanded="false"
                    aria-controls="encaminhamentos">
                    <i class="ti-share menu-icon"></i>
                    <span class="menu-title">Encaminhamento</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse subheader" id="encaminhamentos">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->profile === 'cpm_tecnico' ||
                                Auth::user()->profile === 'administrador' ||
                                Auth::user()->profile === 'cpm_coordenador')
                            <li class="nav-item"><i class="ti-plus"></i><a class="nav-link sub-title"
                                    href="{{ route('provimento_efetivo.create') }}">Incluir</a></li>
                        @endif
                        <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title"
                                href="/provimento/efetivo/show">Buscar</a></li>
                    </ul>
                </div>
            </li>
        @endif
        @if (Auth::user()->profile != 'cgi_tecnico')
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#aposentadoria" aria-expanded="false"
                    aria-controls="encaminhamentos">
                    <i class="ti-files menu-icon"></i>
                    <span class="menu-title">Afastamentos</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse subheader" id="aposentadoria">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"><i class="ti-zoom-in"></i><a class="nav-link sub-title"
                                href="/aposentadorias">Definitivo</a></li>
                        <!-- <li class="nav-item"><i class="ti-search"></i><a class="nav-link sub-title" href="/aposentadorias">Temporário</a></li> -->
                    </ul>
                </div>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#regfuncional" aria-expanded="false"
                aria-controls="regfuncional">
                <i class="ti-layers-alt menu-icon"></i>
                <span class="menu-title">Reg. Funcional</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse subheader" id="regfuncional">
                <ul class="nav flex-column sub-menu">
                    @if (Auth::user()->profile === 'cpg_tecnico' || Auth::user()->profile === 'administrador')
                        <li class="nav-item text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-library-plus">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M7 3m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" />
                                <path
                                    d="M4.012 7.26a2.005 2.005 0 0 0 -1.012 1.737v10c0 1.1 .9 2 2 2h10c.75 0 1.158 -.385 1.5 -1" />
                                <path d="M11 10h6" />
                                <path d="M14 7v6" />
                            </svg>
                            <a class="nav-link sub-title"
                                href="{{ route('regularizacao_funcional.create') }}">Incluir</a>
                        </li>
                    @endif
                    <li class="nav-item text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-devices-search">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M13 13v-4a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v2.5" />
                            <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h7" />
                            <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                            <path d="M20.2 20.2l1.8 1.8" />
                            <path d="M16 9h2" />
                        </svg>
                        <a class="nav-link sub-title" href="{{ route('regularizacao_funcional.show') }}">Buscar</a>
                    </li>
                </ul>
            </div>
        </li>
        @if (Auth::user()->profile != 'cad_tecnico' &&
                Auth::user()->profile != 'cgi_tecnico' &&
                (Auth::user()->name != 'Usuario CPG' && Auth::user()->name != 'Usuario CPM'))
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false"
                    aria-controls="charts">
                    <i class="icon-bar-graph menu-icon"></i>
                    <span class="menu-title">Gerencial</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse subheader" id="charts">
                    <ul class="nav flex-column sub-menu">
                        @if (Auth::user()->profile === 'cpg_tecnico' || Auth::user()->profile === 'administrador')
                            <li class="nav-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#f3f8fb" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-keyboard">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M2 6m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z" />
                                    <path d="M6 10l0 .01" />
                                    <path d="M10 10l0 .01" />
                                    <path d="M14 10l0 .01" />
                                    <path d="M18 10l0 .01" />
                                    <path d="M6 14l0 .01" />
                                    <path d="M18 14l0 .01" />
                                    <path d="M10 14l4 .01" />
                                </svg>
                                <a class="nav-link sub-title" href="/status/digitacao">DIGITAÇÃO</a>
                            </li>
                            {{-- <li class="nav-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-keyboard-hide">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M2 3m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z" />
                                                <path d="M6 7l0 .01" />
                                                <path d="M10 7l0 .01" />
                                                <path d="M14 7l0 .01" />
                                                <path d="M18 7l0 .01" />
                                                <path d="M6 11l0 .01" />
                                                <path d="M18 11l0 .01" />
                                                <path d="M10 11l4 0" />
                                                <path d="M10 21l2 -2l2 2" />
                                            </svg>
                                            <a class="nav-link sub-title" href="#">MANUTENÇÕES</a>
                                        </li> --}}
                        @endif
                        @if (Auth::user()->profile === 'cpg_tecnico' ||
                                Auth::user()->profile === 'administrador' ||
                                Auth::user()->profile === 'consulta' ||
                                Auth::user()->profile === 'cpm_coordenador')
                            <li class="nav-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-home-search">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M21 12l-9 -9l-9 9h2v7a2 2 0 0 0 2 2h4.7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2" />
                                    <path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                    <path d="M20.2 20.2l1.8 1.8" />
                                </svg>

                                <a class="nav-link sub-title" href="/uees/all_uees">Unidades</a>
                            </li>
                        @endif
                        @if (Auth::user()->profile != 'consulta')
                            <li class="nav-item text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                </svg>

                                <a class="nav-link sub-title" href="{{ route('servidores.show') }}">Servidores</a>

                            </li>
                        @endif
                    </ul>
                </div>
            </li>

            <li class="nav-item" id="active_relatorios">
                <a class="nav-link" href="/relatorios">
                    <i class="icon-paper menu-icon"></i>
                    <span id="new-venda" class="menu-title">RELATÓRIOS</span>
                </a>
            </li>
        @endif

        @if (Auth::user()->profile_id === 4)
            <li class="nav-item" id="administracao_collapse">
                <a class="nav-link" data-toggle="collapse" href="#administracao" aria-expanded="false"
                    aria-controls="administracao">
                    <i class="ti-settings menu-icon"></i>
                    <span class="menu-title">Administração</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse subheader" id="administracao">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="#f3f8fb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-scan">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 9a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
                                <path d="M4 16v2a2 2 0 0 0 2 2h2" />
                                <path d="M16 4h2a2 2 0 0 1 2 2v2" />
                                <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
                                <path d="M8 16a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2" />
                            </svg>
                            <a class="nav-link sub-title" href="{{ route('users.show') }}">Usuários</a>
                        </li>
                        <li class="nav-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="#f3f8fb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-menu-order">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 10h16" />
                                <path d="M4 14h16" />
                                <path d="M9 18l3 3l3 -3" />
                                <path d="M9 6l3 -3l3 3" />
                            </svg>
                            <a class="nav-link sub-title" href="{{ route('listas_suspensas.index') }}">Listas
                                Susp.</a>
                        </li>

                        <li class="nav-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="#f3f8fb" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-logs">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 12h.01" />
                                <path d="M4 6h.01" />
                                <path d="M4 18h.01" />
                                <path d="M8 18h2" />
                                <path d="M8 12h2" />
                                <path d="M8 6h2" />
                                <path d="M14 6h6" />
                                <path d="M14 12h6" />
                                <path d="M14 18h6" />
                            </svg>
                            <a class="nav-link sub-title" href="{{ route('logs.show') }}">Logs</a>
                        </li>

                        <!-- <li class="nav-item"><i class="fa-solid fa-user-slash"></i><a class="nav-link sub-title" href="{{ route('motivo_vagas.show') }}">Motivo Vagas</a></li> -->
                    </ul>
                </div>
            </li>
        @endif
    </ul>
</nav>
