<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row print-hidden">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        @if (Auth::user()->profile != 'cgi_tecnico')
            <a class="navbar-brand brand-logo mr-5" href="/"><img src="/images/logo.svg" class="mr-2"
                    alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="/"><img src="/images/logo.svg" alt="logo" /></a>
        @else
            <a class="navbar-brand brand-logo mr-5"><img src="/images/logo.svg" class="mr-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini"><img src="/images/logo.svg" alt="logo" /></a>
        @endif
    </div>
    <div class="print-none  navbar-menu-wrapper d-flex align-items-center justify-content-end">
        {{-- <div class="d-flex justify-content-center">
                    <button class="d-flex justify-content-center align-items-center navbar-toggler navbar-toggler " type="button" data-toggle="minimize">
                        <i class="ti-angle-double-left"></i>&nbsp;
                        <span id="recolhermenu"></span>
                    </button>
                </div> --}}
        <span class="mobile-hidden subheader"><strong><span class="">ANO DE REFERÊNCIA -
                </span>{{ session('ano_ref') }}</strong></span>

        <ul class="d-flex align-items-center navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <span class="name_profile subheader"><strong>{{ Auth::user()->name }}</strong></span>
                    <i class="ti-angle-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <a href="/profile" class="dropdown-item"><i class="ti-settings text-primary"></i>Configurações</a>
                    <form id="logout-form" action="/logout" method="POST">
                        @csrf
                        <a class="dropdown-item" onclick="logout()"><i class="ti-power-off text-primary"></i>Sair</a>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-toggle="offcanvas">
            <span class="icon-menu"></span>
        </button>
    </div>
</nav>
