<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests\App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController
{
    protected SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @param mixed $data
     */
    protected function serializeResponse(
        Request $request,
        $data,
        int $status = 200,
        array $headers = [],
        array $context = []
    ): Response {
        $format = $request->getPreferredFormat('json');

        if (!\in_array($format, ['json', 'xml'], true)) {
            $format = 'json';
        }

        $serializeData = $this->serializer->serialize(
            $data,
            $format,
            $context
        );

        $headers += [
            'Content-Type' => $request->getMimeType($format),
        ];

        return new Response($serializeData, $status, $headers);
    }
}
