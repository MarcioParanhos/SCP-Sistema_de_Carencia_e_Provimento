@extends('layout.main')

@section('title', 'SCP - Home')

@section('content')


    <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="title-app font-weight-bold">VISÃO GERAL</h3>
                    <h6 class="user_auth font-weight-normal mb-0"><span
                            class="text-primary subheader">{{ Auth::user()->name }} | {{ Auth::user()->sector->name }} - {{ Auth::user()->sector->tag }}</span></h6>
                </div>
                <div class="justify-content-end align-items-center d-flex col-12 col-xl-4">
                    <label class="mr-3 pt-2 ano-title" for="">Ano de Referência</label>
                    <select id="ref_year" class="form-control-sm dropdown-toggle">
                        @if ($anoRef === '2025')
                            <option selected>{{ $anoRef }}</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        @endif
                        @if ($anoRef === '2024')
                            <option value="2025">2025</option>
                            <option selected>{{ $anoRef }}</option>
                            <option value="2023">2023</option>
                        @endif
                        @if ($anoRef === '2023')
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                            <option selected>{{ $anoRef }}</option>
                        @endif
                    </select>
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
