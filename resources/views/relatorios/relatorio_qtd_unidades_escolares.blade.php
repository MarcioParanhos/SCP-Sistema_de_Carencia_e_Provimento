@extends('layout.main')

@section('title', 'SCP - Unidades Escolares')

@section('content')

<div class="bg-primary card text-white card_title">
    <h3 class=" title_show_carencias">RELATÓRIOS DE QUANTIDADE DE UNIDADES ESCOLARES</h3>
</div>

<div class="">
    <table class="table mb-4 table-sm table-hover print-table" id="resumoTable">
        <thead>
            <tr class="bg-dark text-white">
                <th class="text-center" colspan="29">QTD. UNIDADES ESCOLARES POR NTE</th>
            </tr>
            <tr>
                <th>NTE</th>
                <th class="text-center">01</th>
                <th class="text-center">02</th>
                <th class="text-center">03</th>
                <th class="text-center">04</th>
                <th class="text-center">05</th>
                <th class="text-center">06</th>
                <th class="text-center">07</th>
                <th class="text-center">08</th>
                <th class="text-center">09</th>
                <th class="text-center">10</th>
                <th class="text-center">11</th>
                <th class="text-center">12</th>
                <th class="text-center">13</th>
                <th class="text-center">14</th>
                <th class="text-center">15</th>
                <th class="text-center">16</th>
                <th class="text-center">17</th>
                <th class="text-center">18</th>
                <th class="text-center">19</th>
                <th class="text-center">20</th>
                <th class="text-center">21</th>
                <th class="text-center">22</th>
                <th class="text-center">23</th>
                <th class="text-center">24</th>
                <th class="text-center">25</th>
                <th class="text-center">26</th>
                <th class="text-center">27</th>
                <th class="text-center">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="width: 10%;">QTD. SEDE</th>
                <?php
                $total = 0; // Inicializa a variável total
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valor = $nteCountsSedes[$i];
                                                $total += $valor; // Adiciona o valor atual ao total
                                                ?> <td class="text-center">{{ $valor }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $total }}</strong></td>
            </tr>

            <tr>
                <th>QTD. ANEXO</th>
                <?php
                $totalAnexo = 0; // Inicializa a variável total para os anexos
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorAnexo = $nteCountsAnexos[$i];
                                                $totalAnexo += $valorAnexo; // Adiciona o valor atual ao total dos anexos
                                                ?> <td class="text-center">{{ $valorAnexo }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalAnexo }}</strong></td>
            </tr>

            <tr>
                <th>QTD. CEMIT/EMITEC</th>
                <?php
                $totalCemits = 0; // Inicializa a variável total para os CEMIT/EMITEC
                ?>
                @for ($i = 1; $i <= 27; $i++) <?php
                                                $valorCemits = $nteCountsCemits[$i];
                                                $totalCemits += $valorCemits; // Adiciona o valor atual ao total dos CEMIT/EMITEC
                                                ?> <td class="text-center">{{ $valorCemits }}</td>
                    @endfor
                    <td class="text-center"><strong>{{ $totalCemits }}</strong></td>
            </tr>

            <tr class="table-primary mb-4">
                <th>TOTAL</th>
                <?php
                $totalTotal = array_sum($nteCountsTotal); // Calcula a soma total dos valores
                ?>
                @foreach ($nteCountsTotal as $count)
                <td class="text-center"><strong>{{ $count }}</strong></td>
                @endforeach
                <td class="text-center"><strong>{{ $totalTotal }}</strong></td>
            </tr>
        <tbody>
    </table>
</div>
@endsection