<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MensajeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $nombre;
    public $correo;
    public $telefono;
    public $mensaje;
    public function __construct($nombre, $correo, $telefono, $mensaje)
    {
    $this->nombre = $nombre;
    $this->correo = $correo;
    $this->telefono = $telefono;
    $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nuevo mensaje')->view('admin.mensajes.nuevo') ->withSwiftMessage(function ($message) {
            $message->getHeaders()
                ->addTextHeader('X-Gmail-Labels', 'Notificaciones');
        });
    }
}
