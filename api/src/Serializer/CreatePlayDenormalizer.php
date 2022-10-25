<?php

namespace App\Serializer;

use App\Awareness\TokenStorageInterfaceAwareTrait;
use App\Entity\Play;
use App\Entity\Player;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Does the following, upon denormalization of a Play resource:
 *
 * - Attaches the current Player to the submitted Play. (users may not write there)
 * - …
 *
 * Class CreatePlayDenormalizer
 * @package App\Serializer
 */
class CreatePlayDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    use TokenStorageInterfaceAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        $play = $this->denormalizer->denormalize($data, $type, $format, $context + [__CLASS__ => true]);

        if ($play instanceof Play) {
            $player = $this->getPlayerConnected();
            if (null !== $player) {
                $play->addPlayer($player);  // skips duplicates internally
            } else {
                // getPlayerConnected() should never fail, given our firewall config, but you never know…
                // Code changes over time; best be safe and yell, so I know about it as early as possible.
                throw new AccessDeniedException("Tried to push a Play but you are not authenticated.");
            }
        }

        return $play;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = []): bool
    {
        return
//            in_array($format, ['json', 'jsonld'], true) &&
            is_a($type, Play::class, true) &&
            !isset($context[__CLASS__]);
    }
}
