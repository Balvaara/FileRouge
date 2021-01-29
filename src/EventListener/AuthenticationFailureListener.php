<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;


class AuthenticationFailureListener{
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
{
    
    $data = [
        'status'  => '401',
        'message' => 'Login ou Mot de passe Incorrect',
    ];

    $response = new JWTAuthenticationFailureResponse($data,401);

    $event->setResponse($response);
}
}