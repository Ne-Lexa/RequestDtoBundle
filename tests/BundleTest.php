<?php

declare(strict_types=1);

namespace Nelexa\RequestDtoBundle\Tests;

use Nelexa\RequestDtoBundle\Exception\RequestDtoValidationException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @internal
 *
 * @small
 */
final class BundleTest extends KernelTestCase
{
    /**
     * @dataProvider provideRequestObjects
     * @dataProvider provideQueryObjects
     * @dataProvider provideRequestObjectBody
     *
     * @throws \Throwable
     */
    public function testRequestAndQueryObjects(
        Request $request,
        array $headers,
        int $responseStatusCode,
        string $contentType,
        array $responseData
    ): void {
        $kernel = self::bootKernel();

        foreach ($headers as $ket => $value) {
            $request->headers->set($ket, $value);
        }

        $response = $kernel->handle($request);
        self::assertSame($response->getStatusCode(), $responseStatusCode);
        self::assertNotFalse($response->getContent());
        self::assertSame($contentType, $response->headers->get('Content-Type'));

        $json = json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        foreach ($responseData as $key => $value) {
            self::assertSame($json[$key], $value);
        }
    }

    public function provideRequestObjects(): ?\Generator
    {
        yield 'Valid POST RequestObject with ConstraintViolationList' => [
            Request::create(
                '/register',
                'POST',
                [
                    'name' => 'John Doe',
                    'password' => 'pa$$w00rd',
                    'email' => 'john@doe.com',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'name' => 'John Doe',
                    'password' => 'pa$$w00rd',
                    'email' => 'john@doe.com',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'violations' => [],
                ],
            ],
        ];

        yield 'Valid GET RequestObject with ConstraintViolationList' => [
            Request::create(
                '/register',
                'GET',
                [
                    'name' => 'John Doe',
                    'password' => 'pa$$w00rd',
                    'email' => 'john@doe.com',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'name' => 'John Doe',
                    'password' => 'pa$$w00rd',
                    'email' => 'john@doe.com',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'violations' => [],
                ],
            ],
        ];

        yield 'Invalid POST RequestObject with ConstraintViolationList' => [
            Request::create(
                '/register',
                'POST',
                [
                    'name' => 'John Doe',
                    'password' => 'pass',
                    'email' => 'john@doe.com',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'name' => 'John Doe',
                    'password' => 'pass',
                    'email' => 'john@doe.com',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'detail' => 'password: This value is too short. It should have 6 characters or more.',
                    'violations' => [
                        [
                            'propertyPath' => 'password',
                            'title' => 'This value is too short. It should have 6 characters or more.',
                            'parameters' => [
                                '{{ value }}' => '"pass"',
                                '{{ limit }}' => '6',
                            ],
                            'type' => 'urn:uuid:9ff3fdc4-b214-49db-8718-39c315e33d45',
                        ],
                    ],
                ],
            ],
        ];

        yield 'Valid POST RequestObject without ConstraintViolationList' => [
            Request::create(
                '/register-exception',
                'POST',
                [
                    'name' => 'John Doe',
                    'password' => 'pa$$w0rd',
                    'email' => 'john@doe.com',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'name' => 'John Doe',
                'password' => 'pa$$w0rd',
                'email' => 'john@doe.com',
            ],
        ];

        yield 'Invalid POST RequestObject without ConstraintViolationList' => [
            Request::create(
                '/register-exception',
                'POST',
                [
                    'name' => 'John Doe',
                    'password' => 'pass',
                    'email' => 'john@doe.com',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            400,
            'application/problem+json',
            [
                'type' => 'https://tools.ietf.org/html/rfc7807',
                'title' => 'Validation Failed',
                'detail' => 'password: This value is too short. It should have 6 characters or more.',
                'violations' => [
                    [
                        'propertyPath' => 'password',
                        'title' => 'This value is too short. It should have 6 characters or more.',
                        'parameters' => [
                            '{{ value }}' => '"pass"',
                            '{{ limit }}' => '6',
                        ],
                        'type' => 'urn:uuid:9ff3fdc4-b214-49db-8718-39c315e33d45',
                    ],
                ],
                'status' => 400,
            ],
        ];
    }

    public function provideQueryObjects(): ?\Generator
    {
        yield 'Valid QueryObject with ConstraintViolationList' => [
            Request::create(
                '/search',
                'GET',
                [
                    'query' => 'php',
                    'page' => '2',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'query' => 'php',
                    'page' => '2',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'violations' => [],
                ],
            ],
        ];

        yield 'Invalid QueryObject with ConstraintViolationList' => [
            Request::create(
                '/search',
                'GET',
                [
                    'page' => '2',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'query' => null,
                    'page' => '2',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'detail' => 'query: This value should not be blank.',
                    'violations' => [
                        [
                            'propertyPath' => 'query',
                            'title' => 'This value should not be blank.',
                            'parameters' => [
                                '{{ value }}' => 'null',
                            ],
                            'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                        ],
                    ],
                ],
            ],
        ];

        yield 'Valid QueryObject without ConstraintViolationList' => [
            Request::create(
                '/search-exception',
                'GET',
                [
                    'query' => 'php',
                    'page' => '2',
                    'limit' => '50',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'query' => 'php',
                'page' => '2',
            ],
        ];

        yield 'Invalid QueryObject without ConstraintViolationList' => [
            Request::create(
                '/search-exception',
                'GET',
                [
                    'page' => '2',
                    'limit' => '50',
                ]
            ),
            [
                'Accept' => 'application/json',
            ],
            400,
            'application/problem+json',
            [
                'type' => 'https://tools.ietf.org/html/rfc7807',
                'title' => 'Validation Failed',
                'detail' => 'query: This value should not be blank.',
                'violations' => [
                    [
                        'propertyPath' => 'query',
                        'title' => 'This value should not be blank.',
                        'parameters' => [
                            '{{ value }}' => 'null',
                        ],
                        'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                    ],
                ],
                'status' => 400,
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function provideRequestObjectBody(): ?\Generator
    {
        yield 'Valid Request Body with ConstraintViolationList' => [
            Request::create(
                '/user-token',
                'POST',
                [],
                [],
                [],
                [],
                json_encode(
                    [
                        'token' => 'rTHyIkZf0ykKhGtN58b0mNY5PoxDOS4j',
                    ],
                    \JSON_THROW_ON_ERROR
                )
            ),
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'token' => 'rTHyIkZf0ykKhGtN58b0mNY5PoxDOS4j',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'violations' => [],
                ],
            ],
        ];

        yield 'Invalid Request Body with ConstraintViolationList' => [
            Request::create(
                '/user-token',
                'POST',
                [],
                [],
                [],
                [],
                json_encode(
                    [
                        'token' => '',
                    ],
                    \JSON_THROW_ON_ERROR
                )
            ),
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'dto' => [
                    'token' => '',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'detail' => 'token: This value should not be blank.',
                    'violations' => [
                        [
                            'propertyPath' => 'token',
                            'title' => 'This value should not be blank.',
                            'parameters' => [
                                '{{ value }}' => '""',
                            ],
                            'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                        ],
                    ],
                ],
            ],
        ];

        yield 'Valid Request Body without ConstraintViolationList' => [
            Request::create(
                '/user-token-exception',
                'POST',
                [],
                [],
                [],
                [],
                json_encode(
                    [
                        'token' => 'rTHyIkZf0ykKhGtN58b0mNY5PoxDOS4j',
                    ],
                    \JSON_THROW_ON_ERROR
                )
            ),
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            200,
            'application/json',
            [
                'token' => 'rTHyIkZf0ykKhGtN58b0mNY5PoxDOS4j',
            ],
        ];

        yield 'Invalid Request Body without ConstraintViolationList' => [
            Request::create(
                '/user-token-exception',
                'POST',
                [],
                [],
                [],
                [],
                json_encode(
                    [
                        'token' => '',
                    ],
                    \JSON_THROW_ON_ERROR
                )
            ),
            [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            400,
            'application/problem+json',
            [
                'type' => 'https://tools.ietf.org/html/rfc7807',
                'title' => 'Validation Failed',
                'detail' => 'token: This value should not be blank.',
                'violations' => [
                    [
                        'propertyPath' => 'token',
                        'title' => 'This value should not be blank.',
                        'parameters' => [
                            '{{ value }}' => '""',
                        ],
                        'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                    ],
                ],
                'status' => 400,
            ],
        ];
    }

    /**
     * @throws \Exception
     */
    public function testInvalidConstraintViolationListArgument(): void
    {
        $kernel = self::bootKernel();
        $response = $kernel->handle(Request::create('/errors'));
        self::assertSame($response->getStatusCode(), 500);

        if ($kernel->isDebug()) {
            self::assertStringStartsWith(
                '<!-- The action argument &quot;Symfony\Component\Validator\ConstraintViolationListInterface \$errors&quot; is required. (500 Internal Server Error) -->',
                $response->getContent()
            );
        }
    }

    /**
     * @throws \Throwable
     */
    public function testMultipleObjects(): void
    {
        $kernel = self::bootKernel();
        $userTokenParams = [
            'token' => '',
        ];
        $searchGetParams = http_build_query(
            [
                'limit' => 10,
            ]
        );
        $userRegistrationParams = [
            'name' => 'John Doe',
        ];
        $request = Request::create(
            '/multiple/objects?' . $searchGetParams,
            'POST',
            $userRegistrationParams,
            [],
            [],
            [],
            json_encode($userTokenParams, \JSON_THROW_ON_ERROR)
        );
        $request->headers->set('Accept', 'application/json');

        $response = $kernel->handle($request);
        $json = json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame(
            $json,
            [
                'userToken' => [
                    'dto' => [
                        'token' => '',
                    ],
                    'errors' => [
                        'type' => 'https://symfony.com/errors/validation',
                        'title' => 'Validation Failed',
                        'detail' => 'token: This value should not be blank.',
                        'violations' => [
                            [
                                'propertyPath' => 'token',
                                'title' => 'This value should not be blank.',
                                'parameters' => [
                                    '{{ value }}' => '""',
                                ],
                                'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                            ],
                        ],
                    ],
                ],
                'searchQuery' => [
                    'dto' => [
                        'query' => null,
                        'page' => '1',
                    ],
                    'errors' => [
                        'type' => 'https://symfony.com/errors/validation',
                        'title' => 'Validation Failed',
                        'detail' => 'query: This value should not be blank.',
                        'violations' => [
                            [
                                'propertyPath' => 'query',
                                'title' => 'This value should not be blank.',
                                'parameters' => [
                                    '{{ value }}' => 'null',
                                ],
                                'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                            ],
                        ],
                    ],
                ],
                'userRegistration' => [
                    'dto' => [
                        'name' => 'John Doe',
                        'password' => null,
                        'email' => null,
                    ],
                    'errors' => [
                        'type' => 'https://symfony.com/errors/validation',
                        'title' => 'Validation Failed',
                        'detail' => 'password: This value should not be blank.' . "\n"
                            . 'email: This value should not be blank.',
                        'violations' => [
                            [
                                'propertyPath' => 'password',
                                'title' => 'This value should not be blank.',
                                'parameters' => [
                                    '{{ value }}' => 'null',
                                ],
                                'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                            ],
                            [
                                'propertyPath' => 'email',
                                'title' => 'This value should not be blank.',
                                'parameters' => [
                                    '{{ value }}' => 'null',
                                ],
                                'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                            ],
                        ],
                    ],
                ],
            ]
        );
    }

    public function testXmlBody(): void
    {
        $kernel = self::bootKernel();
        $request = Request::create(
            '/user-token',
            'POST',
            [],
            [],
            [],
            [],
            '<?xml version="1.0" encoding="UTF-8"?>
<request>
    <token>7AtSV5KFjsTdiEwW6RC59v8iWs0iLm7o</token>
</request>'
        );

        $request->headers->set('Accept', 'text/xml');
        $request->headers->set('Content-Type', 'application/xml');
        $response = $kernel->handle($request);
        self::assertSame($response->getStatusCode(), 200);

        $actualContent = '<?xml version="1.0"?>' . "\n"
            . '<response><dto><token>7AtSV5KFjsTdiEwW6RC59v8iWs0iLm7o</token></dto><errors><type>https://symfony.com/errors/validation</type><title>Validation Failed</title><violations/></errors></response>' . "\n";

        self::assertSame($response->getContent(), $actualContent);
    }

    public function testInvalidXmlBody(): void
    {
        $kernel = self::bootKernel();
        $request = Request::create(
            '/user-token-exception',
            'POST',
            [],
            [],
            [],
            [],
            '<?xml version="1.0" encoding="UTF-8"?>
<request>
    <token></token>
</request>'
        );

        $request->headers->set('Accept', 'text/xml');
        $request->headers->set('Content-Type', 'application/xml');
        $response = $kernel->handle($request);

        self::assertSame($response->getStatusCode(), 400);
        self::assertSame($response->headers->get('Content-Type'), 'application/problem+xml');

        $xmlResponse = $response->getContent();

        if (\extension_loaded('simplexml')) {
            $dom = simplexml_load_string($xmlResponse);
            self::assertSame((string) $dom->type, 'https://tools.ietf.org/html/rfc7807');
            self::assertSame((string) $dom->title, 'Validation Failed');
            self::assertSame((string) $dom->detail, 'token: This value should not be blank.');
            self::assertSame((string) $dom->violations->propertyPath, 'token');
            self::assertSame((string) $dom->violations->title, 'This value should not be blank.');
            self::assertSame((string) $dom->violations->parameters->item->attributes()['key'], '{{ value }}');
            self::assertSame((string) $dom->violations->parameters->item, '""');
            self::assertSame((string) $dom->violations->type, 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3');

            if ($kernel->isDebug()) {
                self::assertSame((string) $dom->class, RequestDtoValidationException::class);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function testEmptyRequestBody(): void
    {
        $kernel = self::bootKernel();
        $request = Request::create('/user-token', 'POST');
        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('Accept', 'application/json');
        $response = $kernel->handle($request);
        $json = json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame($response->getStatusCode(), 400);
        self::assertSame($response->headers->get('Content-Type'), 'application/problem+json');
        self::assertSame($json['type'], 'https://tools.ietf.org/html/rfc2616#section-10');
        self::assertSame($json['title'], 'An error occurred');
        self::assertSame($json['status'], 400);
        self::assertSame($json['detail'], 'Bad Request');

        if ($kernel->isDebug()) {
            self::assertSame($json['class'], HttpException::class);
        }
    }

    /**
     * @dataProvider provideNullableConstraintList
     *
     * @throws \Exception
     */
    public function testNullableConstraintList(string $url, int $statusCode, array $actualData): void
    {
        $kernel = self::bootKernel();
        $request = Request::create($url, 'GET');
        $request->headers->set('Accept', 'application/json');
        $response = $kernel->handle($request);
        $json = json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame($response->getStatusCode(), $statusCode);
        self::assertSame($json, $actualData);
    }

    public function provideNullableConstraintList(): ?\Generator
    {
        yield 'Valid query' => [
            '/search-nullable-errors?query=php',
            200,
            [
                'dto' => [
                    'query' => 'php',
                    'page' => '1',
                ],
                'errors' => null,
            ],
        ];

        yield 'Invalid query' => [
            '/search-nullable-errors',
            200,
            [
                'dto' => [
                    'query' => null,
                    'page' => '1',
                ],
                'errors' => [
                    'type' => 'https://symfony.com/errors/validation',
                    'title' => 'Validation Failed',
                    'detail' => 'query: This value should not be blank.',
                    'violations' => [
                        [
                            'propertyPath' => 'query',
                            'title' => 'This value should not be blank.',
                            'parameters' => [
                                '{{ value }}' => 'null',
                            ],
                            'type' => 'urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testScalarCastTyped(): void
    {
        $kernel = self::bootKernel();
        $data = [
            'offset' => '-4',
            'limit' => '20',
            'collapse' => '1',
            'freq' => '0.0433',
            'array' => [
                'str',
                '332',
                '33.2332',
            ],
        ];
        $request = Request::create(
            '/limit',
            Request::METHOD_POST,
            $data
        );
        $request->headers->set('Accept', 'application/json');
        $response = $kernel->handle($request);

        $json = json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertSame($response->getStatusCode(), 200);
        self::assertSame(
            $json,
            [
                'dto' => [
                    'offset' => -4,
                    'limit' => 20,
                    'collapse' => true,
                    'freq' => 0.0433,
                    'array' => [
                        'str',
                        '332',
                        '33.2332',
                    ],
                ],
                'errors' => null,
            ]
        );
    }

    public function testErrorTyped(): void
    {
        $kernel = self::bootKernel();
        $data = [
            'offset' => 'a',
        ];
        $request = Request::create(
            '/limit',
            Request::METHOD_GET,
            $data
        );
        $request->headers->set('Accept', 'application/json');
        $response = $kernel->handle($request);

        $json = json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        self::assertSame($response->headers->get('Content-Type'), 'application/problem+json');
        self::assertSame($response->getStatusCode(), 400);
        self::assertSame($json['status'], 400);
        self::assertSame($json['type'], 'https://tools.ietf.org/html/rfc2616#section-10');
        self::assertSame($json['title'], 'An error occurred');
        self::assertSame($json['detail'], 'Bad Request');

        if ($kernel->isDebug()) {
            self::assertSame($json['class'], HttpException::class);
        }
    }
}
