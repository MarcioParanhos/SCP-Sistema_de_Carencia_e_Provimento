@props(['status'])

@php
    $anoAtual = date('Y');
    session(['ano_ref' => $anoAtual]);
@endphp

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 dark:text-green-400']) }}>
        {{ $status }}
    </div>

@endif
