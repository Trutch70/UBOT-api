<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\Receiver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ReceiverDenormalizer implements DenormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): Receiver
    {
        /** @var Receiver $receiver */
        $receiver = $this->normalizer->denormalize($data, $type, $format, $context);

        foreach ($receiver->getLinks() as $link) {
            $link->setReceiver($receiver);
        }

        return $receiver;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === Receiver::class && isset($data['links']);
    }
}
