<?php

namespace sistema\Http\Controllers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use sistema\User;

class EmailConfirmacion extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;
    public $user;
    public $emailConfirmacion;
    public $password;

    public function __construct(User $user,$password){
        $this->user     = $user;
        $this->password = $password;
        $this->emailConfirmacion = url('register/verify'.$user->id);
    }

    public function build(){
        return $this->markdown('emails.contact')->subject('Registro en sistema Seguros BAM');
    }
}
