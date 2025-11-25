<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row print-hidden">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
 @php $ano = session('ano_ref') ?? '—'; @endphp

            <span class="text-muted small mr-2" style="line-height:1;">Ano de referência</span>

            <span class="badge badge-primary rounded-pill d-inline-flex align-items-center px-3 py-1" role="status"
                aria-label="Ano de referência {{ $ano }}" data-toggle="tooltip" data-placement="bottom"
                title="Ano de referência: {{ $ano }}">
                <i class="ti-calendar mr-2" aria-hidden="true"></i>
                <span style="font-weight:700;">{{ $ano }}</span>
            </span>
    </div>
    <div class="print-none  navbar-menu-wrapper d-flex align-items-center justify-content-end">
        {{-- <div class="d-flex justify-content-center">
                    <button class="d-flex justify-content-center align-items-center navbar-toggler navbar-toggler " type="button" data-toggle="minimize">
                        <i class="ti-angle-double-left"></i>&nbsp;
                        <span id="recolhermenu"></span>
                    </button>
                </div> --}}
        <div class="mobile-hidden d-flex align-items-center mr-3">
           
        </div>

        <script>
            (function() {
                // Inicializa tooltips se o projeto já carregar Bootstrap/jQuery ou Bootstrap 5
                try {
                    if (typeof $ !== 'undefined' && $.fn.tooltip) {
                        $('[data-toggle="tooltip"]').tooltip({
                            trigger: 'hover'
                        });
                    } else if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        var els = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
                        els.forEach(function(el) {
                            new bootstrap.Tooltip(el);
                        });
                    }
                } catch (e) {
                    /* fail silently */ }
            })();
        </script>

        <ul class="d-flex align-items-center navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                @php
                    $user = Auth::user();
                    $name = trim($user->name ?? 'Usuário');

                    // partes do nome (filtra espaços extras)
                    $parts = collect(preg_split('/\s+/', $name))->filter()->values();

                    // iniciais: primeira e última palavra (ex: "Marcio Borba" => MB, "Marcio José Borba" => MB)
                    if ($parts->count() === 0) {
                        $initials = 'U';
                    } elseif ($parts->count() === 1) {
                        $initials = strtoupper(substr($parts->first(), 0, 1));
                    } else {
                        $initials = strtoupper(substr($parts->first(), 0, 1) . substr($parts->last(), 0, 1));
                    }

                    // avatar
                    $avatarUrl = $user->avatar ?? null;

                    // nome curto (primeiro + último)
                    $shortName =
                        $parts->count() > 1 ? $parts->first() . ' ' . $parts->last() : $parts->first() ?? 'Usuário';
                @endphp

                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-toggle="dropdown"
                    id="profileDropdown" aria-haspopup="true" aria-expanded="false">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="avatar" class="rounded-circle"
                            style="width:40px;height:40px;object-fit:cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-primary"
                            style="width:40px;height:40px;font-weight:600;">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="ml-2 d-none d-md-block text-left">
                        <div class="name_profile" style="font-weight:700;line-height:1;">{{ $shortName }}</div>
                        <div class="small text-muted" style="font-size:0.75rem;">{{ Auth::user()->sector->name }} -
                            {{ Auth::user()->sector->tag }}</div>
                    </div>

                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown"
                    style="min-width:220px;">
                    <div class="px-3 py-2 border-bottom">
                        <div style="font-weight:700;">{{ $shortName }}</div>
                        <div class="small text-muted">{{ $user->email ?? '' }}</div>
                    </div>
                    <a href="/profile" class="dropdown-item d-flex align-items-center">
                        <i class="ti-settings text-primary mr-2"></i>
                        <span>Configurações</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form id="logout-form" action="/logout" method="POST" class="m-0 w-100">
                        @csrf
                        <button type="button" id="logout-button"
                            class="dropdown-item d-flex align-items-center w-100">
                            <i class="ti-power-off text-primary mr-2"></i>
                            <span>Sair</span>
                        </button>
                    </form>

                    <script>
                        document.getElementById('logout-button').addEventListener('click', function(e) {
                            e.preventDefault();

                            // Se SweetAlert2 não estiver carregado, fallback para confirm nativo
                            if (typeof Swal === 'undefined') {
                                if (confirm('Deseja realmente sair do sistema?')) {
                                    document.getElementById('logout-form').submit();
                                }
                                return;
                            }

                            Swal.fire({
                                title: 'Sair',
                                text: 'Deseja realmente sair do sistema?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Sim, sair',
                                cancelButtonText: 'Cancelar',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // previne múltiplos envios
                                    document.getElementById('logout-button').disabled = true;
                                    document.getElementById('logout-form').submit();
                                }
                            });
                        });
                    </script>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
