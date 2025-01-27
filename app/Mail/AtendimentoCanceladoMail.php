<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AtendimentoCanceladoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detalhesAtendimento;

    public function __construct($detalhesAtendimento)
    {
        $this->detalhesAtendimento = $detalhesAtendimento;
    }

    public function build()
    {
        return $this->subject('Atendimento Cancelado')
                    ->view('emails.atendimento_cancelado');
    }
}
