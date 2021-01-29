<?php

// src/App/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Config\Definition\Exception\Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */
        $user = $event->getUser();

        if ($user->getIsActive()==null) {
            throw Exception('Strength must be a number, duh!');
        }

        // merge with existing event data
        $payload = array_merge(
            $event->getData(),
            [
                'password' => $user->getPassword(),
                
            ]
        );

        $event->setData($payload);
    }
}