<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $clave;

    /**
     * Crea una nueva instancia del Mailable.
     */
    public function __construct($usuario, $clave)
    {
        $this->usuario = $usuario;
        $this->clave = $clave;
    }

    /**
     * Construye el mensaje.
     */
    public function build()
    {
        return $this->subject('Credenciales de Acceso')
                    ->view('opciones.usuarios.mailCredenciales')
                    ->with([
                        'usuario' => $this->usuario,
                        'clave' => $this->clave,
                    ]);
    }
}

