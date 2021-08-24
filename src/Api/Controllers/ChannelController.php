<?php /** @noinspection PhpUnusedParameterInspection */
declare(strict_types=1);

namespace Api\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Gestion des 'channel'
 */
class ChannelController
{
    /**
     * liste les 'channel'
     * @param \Slim\Http\Request $req
     * @param \Slim\Http\Response $rep
     * @param array<string,string> $args
     * @return \Slim\Http\Response
     */
    public function list(Request $req, Response $rep, array $args): Response
    {
        return $rep;
    }
}