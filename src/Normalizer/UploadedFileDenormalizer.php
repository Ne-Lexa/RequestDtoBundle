<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Normalizer;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UploadedFileDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        return $data;
    }

    public function supportsDenormalization($data, string $type, ?string $format = null): bool
    {
        return $type === UploadedFile::class || $type === File::class;
    }
}
