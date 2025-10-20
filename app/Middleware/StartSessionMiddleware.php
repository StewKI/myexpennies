<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\SessionException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StartSessionMiddleware implements MiddlewareInterface
{

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException('Session is already started');
        }

        if (headers_sent($fine, $line)) {
            throw new SessionException('Headers already sent');
        }

        session_set_cookie_params([
            'httponly' => true,
            'samesite' => 'lax',
        ]);

        session_start();
        $response = $handler->handle($request);

        session_write_close();

        return $response;
    }
}