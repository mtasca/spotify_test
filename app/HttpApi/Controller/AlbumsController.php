<?php
declare(strict_types=1);

namespace SpotifyTest\HttpApi\Controller;


use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SpotifyTest\Application\Exception\EntityNotFoundException;
use SpotifyTest\Application\Response\ApiResponse;
use SpotifyTest\Application\Response\BadRequestResponse;
use SpotifyTest\Application\Service\Spotify\SpotifyService;
use SpotifyTest\Domain\Model\Artist\ArtistName;

class AlbumsController extends ApiController
{
    public function getAlbumsList(RequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $spotify_service = $this->container->get(SpotifyService::class);

        $query_string_params = $request->getQueryParams();

        if(empty($query_string_params['q'])){
            return new BadRequestResponse(['message' => 'the `q` parameter is required']);
        }

        try {
            $artist = $spotify_service->getArtistByName(new ArtistName($query_string_params['q']));
        } catch (EntityNotFoundException $e) {
            return new BadRequestResponse(['message' => 'Artist not found']);
        }

        $albums = $spotify_service->getAlbumsByArtistId($artist->getEntityId());

        return new ApiResponse($albums->toArray());
    }
}