<?php

declare(strict_types=1);


namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Csrf\Guard;
use Slim\Views\Twig;

class CsrfFieldsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ContainerInterface $container,
    ) {}

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler,
    ): ResponseInterface {

        /** @var Guard $csrf */
        $csrf = $this->container->get('csrf');

        $csrf_name_key  = $csrf->getTokenNameKey();
        $csrf_value_key = $csrf->getTokenValueKey();
        $csrf_name  = $csrf->getTokenName();
        $csrf_value = $csrf->getTokenValue();

        $fields = <<<FIELDS
<!-- CSRF -->
<input type="hidden" name="$csrf_name_key" value="$csrf_name">
<input type="hidden" name="$csrf_value_key" value="$csrf_value">
FIELDS;

        $this->twig->getEnvironment()->addGlobal('csrf', [
            'fields' => $fields,
        ]);

        return $handler->handle($request);
    }
}