<?php
declare(strict_types=1);

namespace Api;

use JsonException;
use RuntimeException;
use Slim\Http\Response;
use Slim\Http\StatusCode;

/**
 * Class ResponseFormafter
 * mise en forme de la réponse pour l'API
 * @package Api
 */
class ResponseFormatter
{

    /**
     * Reponse de base
     * @param Response $response
     */
    private Response $response;

    /**
     * Constructeur
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Formate une réponse avec succes avec un objet $data
     * @param object|array|null $data (null par defaut)
     * @return Response
     */
    public function formatSuccess($data = null): Response
    {
        $response = $this->response->withHeader('Content-type', 'application/vdn.ws-aru.v1+json');
        try {
            $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            throw new RuntimeException(
                "Un problème est survenu lors de la mise en forme de la réponse.",
                $e->getCode(),
                $e
            );
        }

        return $response;
    }

    /**
     * Formate une réponse redirect avec un objet $data
     * @param string $url
     * @param object|mixed[]|null $data (null par defaut)
     * @return Response
     */
    public function formatRedirect(string $url, $data = null): Response
    {
        $response = $this->response->withHeader('Content-type', 'application/vdn.ws-aru.v1+json');
        try {
            $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));
            $response = $response->withStatus(StatusCode::HTTP_FOUND);
            $response = $response->withRedirect($url);
        } catch (JsonException $e) {
            throw new RuntimeException(
                "Un problème est survenu lors de la mise en forme de la réponse.",
                $e->getCode(),
                $e
            );
        }

        return $response;
    }

    /**
     * Formate une réponse avec erreur en spécifiant tout les elements
     * @param integer $code
     * @param string $message
     * @param object|mixed[]|null $detail
     * @return Response
     */
    public function formatDirectError(int $code, string $message, $detail = null): Response
    {
        $objet = (object)[
            'message' => $message,
        ];

        if ($detail !== null) {
            $objet->detail = $detail;
        }

        try {
            $this->response->getBody()->write(json_encode($objet, JSON_THROW_ON_ERROR));
        } catch (JsonException $e) {
            throw new RuntimeException(
                "Un problème est survenu lors de la mise en forme de la réponse.",
                $e->getCode(),
                $e
            );
        }
        return $this->response
            ->withHeader('Content-type', 'application/json')
            ->withStatus($code);
    }
}
