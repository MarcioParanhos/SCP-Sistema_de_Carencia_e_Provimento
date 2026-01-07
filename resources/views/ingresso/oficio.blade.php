@extends('layout.main')

@section('title','Ofício de Assunção - Ingresso')

@section('content')
<div class="card">
    <div class="card-panel p-4">
        <div style="max-width:800px;margin:0 auto;font-family:Arial,Helvetica,sans-serif;">
            <div style="text-align:right;font-size:12px;color:#333;">{{ now()->format('d/m/Y') }}</div>
            <h3 style="text-align:center;margin-top:8px;">OFÍCIO DE ASSUNÇÃO</h3>

            <p>À {{ $candidate['name'] ?? $candidate['nome'] ?? '-' }},</p>

            <p>Informamos que o ingresso referente ao candidato de inscrição <strong>{{ $candidate['num_inscricao'] ?? '-' }}</strong> foi validado pela CPM.</p>

            <h4>Dados do Candidato</h4>
            <table style="width:100%;font-size:14px;margin-bottom:8px;">
                <tr><td><strong>Nome:</strong> {{ $candidate['name'] ?? $candidate['nome'] ?? '-' }}</td></tr>
                <tr><td><strong>CPF:</strong> {{ $candidate['cpf'] ?? '-' }}</td></tr>
                <tr><td><strong>Nº Inscrição:</strong> {{ $candidate['num_inscricao'] ?? '-' }}</td></tr>
                <tr><td><strong>SEI:</strong> {{ $candidate['sei_number'] ?? '-' }}</td></tr>
            </table>

            <h4>Encaminhamentos</h4>
            @if (count($encaminhamentos))
                <ul>
                    @foreach ($encaminhamentos as $e)
                        <li>
                            <strong>{{ $e->uee_name ?? $e->uee_code ?? '-' }}</strong>
                            — Disciplina: {{ $e->disciplina_name ?? $e->disciplina_code ?? '-' }}
                            (Mat: {{ $e->quant_matutino ?? '0' }}, Vesp: {{ $e->quant_vespertino ?? '0' }}, Not: {{ $e->quant_noturno ?? '0' }})
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Nenhum encaminhamento registrado.</p>
            @endif

            <p style="margin-top:16px;">Atenciosamente,</p>
            <div style="height:80px;"></div>
            <div style="border-top:1px solid #000;padding-top:6px;">Assinatura da CPM</div>

            <div style="margin-top:16px;font-size:12px;color:#666;">Este documento foi gerado pelo sistema SCP.</div>

            <div style="text-align:center;margin-top:12px;">
                <button class="btn btn-primary" onclick="(function(){var u=window.location.href;u += (window.location.search? '&':'?') + 'print=1'; window.open(u,'_blank');})();">Abrir versão para impressão</button>
            </div>
        </div>
    </div>
</div>
@endsection
