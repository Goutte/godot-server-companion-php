<?php

namespace App\Controller\Action;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class GetMeAction extends AbstractController
{
    public function __invoke(): ?Player
    {
        /** @var ?Player $user */
        $user = $this->getUser();

        return $user;
    }
}
