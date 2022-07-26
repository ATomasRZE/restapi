<?php
namespace AppBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener extends AbstractController
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $username = $event->getUser()->getUsername();
        $token = $event->getData()["token"];

        $response = $this->forward('App\Controller\TokenController::AddToken', [
            'username' => $username,
            'token' => $token,
        ]);
    }
}
