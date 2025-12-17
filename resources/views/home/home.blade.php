@extends('layout.main')

@section('title', 'SCP - Home')

@section('content')


    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="d-flex col-12 col-xl-8 mb-4 mb-xl-0">
                    <div>
                        <h3 class="title-app font-weight-bold">VISÃO GERAL</h3>
                        <h6 class="user_auth font-weight-normal mb-0"><span 80
                                class="text-primary subheader">{{ Auth::user()->name }} | {{ Auth::user()->sector->name }} -
                                {{ Auth::user()->sector->tag }}</span></h6>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center col-12 col-xl-4">
                    <div class="d-flex align-items-center bg-white shadow-sm px-3 py-2 rounded-pill border"
                        style="gap:.5rem;">
                        <span class="d-inline-flex align-items-center" aria-hidden="true" title="Ano de Referência">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler" width="20" height="20"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round" role="img" aria-hidden="true">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                                <line x1="16" y1="3" x2="16" y2="7" />
                                <line x1="8" y1="3" x2="8" y2="7" />
                                <line x1="4" y1="11" x2="20" y2="11" />
                            </svg>
                        </span>

                        <label for="ref_year" class="sr-only">Ano de Referência</label>

                        @php
                            $years = ['2025', '2024', '2023'];
                        @endphp

                        <select id="ref_year" class="custom-select custom-select-sm border-0 bg-transparent"
                            style="min-width:100px;">
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ $anoRef == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cards_system" class="row">

        {{-- Logo --}}
        @include('home.partials.logo')

        {{-- Cards --}}
        @include('home.partials.cards')

    </div>

    <div id="status_system" class="mobile-hidden row">

        {{-- Carousel --}}
        @include('home.partials.carousel')

    </div>


    <script>
        // Altera o ano de referencia do sistema para consultas de acordo com o ano escolhido
        const selectElement = document.getElementById("ref_year");

        selectElement.addEventListener("change", function() {
            let selectedValue = selectElement.value;

            Swal.fire({
                title: 'Tem certeza?',
                text: "Você esta prestes a mudar o ano de referência do sistema!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Não, cancelar!',
                confirmButtonText: 'Sim, alterar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envia o valor selecionado para o backend
                    $.post('/atualizar_ano_ref/' + selectedValue, function(response) {

                        window.location.reload();

                    })
                }
            })
        });
    </script>

@endsection
