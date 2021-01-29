<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
 {
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();
    
       
    
        $data['data'] =array(
             'libelle'=>$user->getProfil()->getLibelle(),
             'nomcomplet'=>$user->getNomComplet()


        );
    
        $event->setData($data);
    }

    

}