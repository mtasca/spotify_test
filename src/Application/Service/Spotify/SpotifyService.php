<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Service\Spotify;

use GuzzleHttp\Client as GuzzleClient;
use SpotifyTest\Application\Exception\EntityNotFoundException;
use SpotifyTest\Domain\Model\Album\Album;
use SpotifyTest\Domain\Model\Album\AlbumCollection;
use SpotifyTest\Domain\Model\Album\AlbumId;
use SpotifyTest\Domain\Model\Artist\ArtistId;
use SpotifyTest\Domain\Model\Artist\Artist;
use SpotifyTest\Domain\Model\Artist\ArtistName;
use SpotifyTest\Domain\Model\Environment\Token;
use SpotifyTest\Domain\Model\Spotify\BaseUri;

class SpotifyService
{
    const SEARCH_ENDPOINT = 'search';

    const ALBUMS_BY_ARTIST_ENDPOINT = 'artists/%s/albums';

    const ARTIST_SEARCH_TYPE = 'artist';

    /**
     * @var SpotifyAccessToken
     */
    private $app_access_token;

    /**
     * @var BaseUri
     */
    private $api_base_uri;

    /**
     * @var GuzzleClient
     */
    private $guzzle_client;

    public function __construct(SpotifyAccessToken $app_access_token, BaseUri $api_base_uri)
    {
        $this->app_access_token = $app_access_token;
        $this->api_base_uri = $api_base_uri;
        $this->guzzle_client = new GuzzleClient();
    }

    public function getArtistByName(ArtistName $artist_name) : Artist
    {
        $url = sprintf(
            "%s/%s?q=%s&type=%s",
            $this->api_base_uri->getValue(),
            self::SEARCH_ENDPOINT,
            $artist_name->getValue(),
            self::ARTIST_SEARCH_TYPE
        );

        $limit = 30;
        $offset = 0;

        do {
            $response = $this->guzzle_client->get(
                sprintf('%s&limit=%s&offset=%s', $url, $limit, $offset),
                $this->getBasicRequestOptions()
            );
            $json_response = json_decode($response->getBody()->getContents(), true);
            $total_records = $json_response['artists']['total'];

            $artist_found = null;

            foreach ($json_response['artists']['items'] as $artist_data) {
                if(strtolower($artist_data['name']) == strtolower($artist_name->getValue())) {
                    //artist found
                    $artist_found = new Artist(new ArtistId($artist_data['id']), $artist_data);
                    break;
                }
            }

            $offset += $limit;

        } while ($total_records >= $offset && is_null($artist_found));

        if (is_null($artist_found)) {
            throw new EntityNotFoundException();
        }

        return $artist_found;
    }

    public function getAlbumsByArtistId(ArtistId $artist_id) : AlbumCollection
    {
        $albums = new AlbumCollection();
        $url = sprintf("%s/%s", $this->api_base_uri->getValue(), self::ALBUMS_BY_ARTIST_ENDPOINT);
        $url = sprintf($url, $artist_id->getId());

        $limit = 30;
        $offset = 0;

        do {
            $response = $this->guzzle_client->get(
                sprintf('%s?limit=%s&offset=%s', $url, $limit, $offset),
                $this->getBasicRequestOptions()
            );
            $json_response = json_decode($response->getBody()->getContents(), true);

            $total_records = $json_response['total'];

            foreach ($json_response['items'] as $album_data) {
                $albums->addEntity(new Album(new AlbumId($album_data['id']), $album_data));
            }

            $offset += $limit;

        } while ($total_records >= $offset);

        return $albums;
    }

    private function getBasicRequestOptions()
    {
        return [
            'headers' => [
                'Authorization' => $this->app_access_token->getToken()->getBearerToken()
            ]
        ];
    }
}