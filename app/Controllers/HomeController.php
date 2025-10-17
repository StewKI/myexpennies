<?php

namespace App\Controllers;

use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Views\Twig;

class HomeController
{
    public function __construct(private readonly Twig $twig) {}

    public function index(Request $request, Response $response) : Response
    {
        return $this->twig->render($response, 'index.twig');
    }
}