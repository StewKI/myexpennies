<?php

declare(strict_types=1);

use App\Config;
use Slim\App;

return function (App $app) {
    $container = $app->getContainer();
    $config    = $container->get(Config::class);


};