<?php
declare(strict_types=1);

namespace Handlers\Middlewares;

use Slim\App;

return
    /**
     * @param \Slim\App $app
     * @return \Slim\App
     */
    static function (App $app): App {
        return $app;
    };
