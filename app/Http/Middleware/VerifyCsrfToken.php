<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/consultarUnidade/*',
        '/consultarServidor/*',
        '/consultarCarencias/*',
        '/consultarDisciplina',
        '/consultarCurso/*',
        '/consultarMunicipio/*',
        '/consultarUees/*',
        '/consultarUnidadeProvimento/*',
        '/processData',
        '/homologarUnidade/*',
        '/servidores/update/*',
        '/atualizar_ano_ref/*',
        '/users/*',
        '/consultar/efetivo/*',
        '/provimento/efetivo/*',
        '/update/*',
        '/consultarServidorCompleto/*',
        '/*'
    ];
}
