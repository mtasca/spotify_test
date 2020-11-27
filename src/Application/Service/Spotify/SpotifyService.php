<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Service\Spotify;

use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as RedisClient;
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

    const ARTIST_REDIS_KEY = 'spotify_test:spotify_service:artist';

    const ALBUMS_REDIS_KEY = 'spotify_test:spotify_service:albums';

    const REDIS_TTL = 1800;

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

    /**
     * @var RedisClient
     */
    private $redis_client;

    public function __construct(SpotifyAccessToken $app_access_token, BaseUri $api_base_uri, RedisClient $redis_client)
    {
        $this->app_access_token = $app_access_token;
        $this->api_base_uri = $api_base_uri;
        $this->redis_client = $redis_client;
        $this->guzzle_client = new GuzzleClient();
    }

    public function getArtistByName(ArtistName $artist_name) : Artist
    {
        $artist_data_found = [];
        $artist_redis_key = sprintf("%s:%s", self::ARTIST_REDIS_KEY, md5($artist_name->getValue()));

        if ($this->redis_client->exists($artist_redis_key)) {
            $artist_data_found = $this->redis_client->get($artist_redis_key);
            $artist_data_found = json_decode($artist_data_found, true);
        } else {
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

                foreach ($json_response['artists']['items'] as $artist_data) {
                    if(strtolower($artist_data['name']) == strtolower($artist_name->getValue())) {
                        //artist found
                        $artist_data_found = $artist_data;
                        $this->redis_client->setex(
                            $artist_redis_key,
                            self::REDIS_TTL,
                            json_encode($artist_data_found)
                        );
                        break;
                    }
                }

                $offset += $limit;

            } while ($total_records >= $offset && empty($artist_data_found));
        }

        if (empty($artist_data_found)) {
            throw new EntityNotFoundException();
        }

        return new Artist(new ArtistId($artist_data_found['id']), $artist_data_found);
    }

    public function getAlbumsByArtistId(ArtistId $artist_id) : AlbumCollection
    {


        $albums_redis_key = sprintf('%s:%s', self::ALBUMS_REDIS_KEY, $artist_id->getId());

        if ($this->redis_client->exists($albums_redis_key)) {
            $albums_data = $this->redis_client->get($albums_redis_key);
            $albums_data = json_decode($albums_data, true);
        } else {
            $url = sprintf("%s/%s", $this->api_base_uri->getValue(), self::ALBUMS_BY_ARTIST_ENDPOINT);
            $url = sprintf($url, $artist_id->getId());

            $albums_data = [];
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
                    $albums_data[] = $album_data;
                }

                $offset += $limit;

            } while ($total_records >= $offset);

            $this->redis_client->setex($albums_redis_key, self::REDIS_TTL, json_encode($albums_data));
        }

        $albums = new AlbumCollection();

        foreach ($albums_data as $album_data) {
            $albums->addEntity(new Album(new AlbumId($album_data['id']), $album_data));
        }

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