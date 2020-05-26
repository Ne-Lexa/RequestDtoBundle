# RequestDtoBundle

This Symfony Bundle provides request objects support for Symfony controller actions.

![Packagist Version](https://img.shields.io/packagist/v/nelexa/RequestDtoBundle)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/nelexa/RequestDtoBundle)
![Minimum Symfony Version](https://img.shields.io/badge/Bundle%20for%20Symfony-%5E5.0-blue)
[![Build Status](https://travis-ci.org/Ne-Lexa/RequestDtoBundle.svg?branch=master)](https://travis-ci.org/Ne-Lexa/RequestDtoBundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ne-Lexa/RequestDtoBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Ne-Lexa/RequestDtoBundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Ne-Lexa/RequestDtoBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Ne-Lexa/RequestDtoBundle/?branch=master)
![Packagist License](https://img.shields.io/packagist/l/nelexa/RequestDtoBundle)

# Installation
Require the bundle with composer:
```bash
composer require nelexa/RequestDtoBundle
```

# Examples of using
To specify an object as an argument of a controller action, an object must implement one of 3 interfaces:
- `\Nelexa\RequestDtoBundle\Dto\QyeryObjectInterface` query parameters for GET or HEAD request methods.
- `\Nelexa\RequestDtoBundle\Dto\RequestObjectInterface` request parameters for POST, PUT or DELETE request methods (ex. Content-Type: application/x-www-form-urlencoded) or query parameters for GET and HEAD request methods.
- `\Nelexa\RequestDtoBundle\Dto\RequestBodyObjectInterface` for POST, PUT, DELETE request body contents (ex. Content-Type: application/json).

Create request DTO:
```php
use Nelexa\RequestDtoBundle\Dto\RequestObjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationRequest implements RequestObjectInterface
{
    /** @Assert\NotBlank() */
    public ?string $login = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="6")
     */
    public ?string $password = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public ?string $email = null;
}
``` 
Use in the controller:
```php
<?php

declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AppController extends AbstractController
{
    /**
     * @Route("/sign-up", methods={"POST"})
     */
    public function registration(
        UserRegistrationRequest $userRegistrationRequest,
        ConstraintViolationListInterface $errors
    ): Response {
        $data = ['success' => $errors->count() === 0];
        
        if ($errors->count() > 0){
            $data['errors'] = $errors;
        }
        else{
            $data['data'] = $userRegistrationRequest;
        }
        
        return $this->json($data);
    }
}
```
If you declare an argument with type `\Symfony\Component\Validator\ConstraintViolationListInterface` as nullable, then if there are no errors, it will be` null`.
```php
...

    /**
     * @Route("/sign-up", methods={"POST"})
     */
    public function registration(
        UserRegistrationRequest $userRegistrationRequest,
        ?ConstraintViolationListInterface $errors
    ): Response {
        return $this->json(
            [
                'success' => $errors === null,
                'errors' => $errors,
            ]
        );
    }

...
```
If the argument `\Symfony\Component\Validator\ConstraintViolationListInterface` is not declare, then the exception `\Nelexa\RequestDtoBundle\Exception\RequestDtoValidationException` will be thrown, which will be converted to the `json` or` xml` format.
```php
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController{
    /**
     * @Route("/sign-up", methods={"POST"})
     */
    public function registration(UserRegistrationRequest $userRegistrationRequest): Response {
        return $this->json(['success' => true]);
    }
}
```
Send POST request:
```
curl 'https://127.0.0.1/registration' -H 'Accept: application/json' -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'login=johndoe'
```
Response:
```text
HTTP/1.1 400 Bad Request
Content-Type: application/problem+json
```
Content response:
```json
{
    "type": "https://tools.ietf.org/html/rfc7807",
    "title": "Validation Failed",
    "detail": "password: This value should not be blank.\nemail: This value should not be blank.",
    "violations": [
        {
            "propertyPath": "password",
            "title": "This value should not be blank.",
            "parameters": {
                "{{ value }}": "null"
            },
            "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
        },
        {
            "propertyPath": "email",
            "title": "This value should not be blank.",
            "parameters": {
                "{{ value }}": "null"
            },
            "type": "urn:uuid:c1051bb4-d103-4f74-8988-acbcafc7fdc3"
        }
    ]
}
```

# Changelog
Changes are documented in the [releases page](https://github.com/Ne-Lexa/RequestDtoBundle/releases).

# License
The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
