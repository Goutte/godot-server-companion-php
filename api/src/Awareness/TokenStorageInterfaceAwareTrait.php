<?php

namespace App\Awareness;

use App\Entity\Player;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

trait TokenStorageInterfaceAwareTrait {

    private TokenStorageInterface $token;

    /**
     * @required
     * @param TokenStorageInterface $token
     */
    public function setToken(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function getPlayerConnected(): ?Player
    {
        $token = $this->token->getToken();

        if ( ! $token) {
            return null;
        }

        $user = $token->getUser();

        if ( ! ($user instanceof Player)) {
            return null;
        }

        return $user;
    }
}
