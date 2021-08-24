<?php
declare(strict_types=1);

namespace Handlers\Routes;

/**
 * format un string callable
 * @param string $controller
 * @param string $method
 * @return string
 */
function cm(string $controller, string $method): string
{
    return "$controller:$method";
}

return
    /**
     * @param \Slim\App $app
     * @return \Slim\App
     */
    static function (\Slim\App $app): \Slim\App {
        // channel
        $app->get('channel', cm(\Api\Controllers\ChannelController::class, 'list'));
        // logs
        return $app;
    };
