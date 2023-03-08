<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class GetMeController extends AbstractController
{
    public function __invoke(): ?UserInterface
    {
        $user = $this->getUser();

        if (null == $user) {
            throw $this->createNotFoundException("IL n'y a pas d'utilisateur connecté.$");
        }

        return $this->getUser();
    }
}
