<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Normalizer;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UploadedFileDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param mixed  $data    Data to restore
     * @param string $type    The expected class to instantiate
     * @param string $format  Format the given data was extracted from
     * @param array  $context Options available to the denormalizer
     *
     * @return mixed Data
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        return $data;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_a($type, File::class, true);
    }
}
