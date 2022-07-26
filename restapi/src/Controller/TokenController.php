<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Token;

class TokenController extends AbstractController
{
    public function AddToken($username,$token)
    {
        $rec = new Token();
        $rec->setUsername($username);
        $rec->setToken($token);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($rec);
        $entityManager->flush();
    }
}
