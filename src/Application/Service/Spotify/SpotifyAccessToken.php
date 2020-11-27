<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Service\Spotify;

use GuzzleHttp\Client as GuzzleClient;
use Predis\Client as RedisClient;
use SpotifyTest\Application\Exception\MethodNotImplementedException;
use SpotifyTest\Domain\Model\Spotify\Scope;
use SpotifyTest\Domain\Model\Spotify\Token;
use SpotifyTest\Domain\Model\Spotify\BaseUri;
use SpotifyTest\Domain\Model\Spotify\ClientId;
use SpotifyTest\Domain\Model\Spotify\ClientSecret;
use SpotifyTest\Domain\Model\Spotify\RedirectUri;
use SpotifyTest\Domain\Model\Spotify\TokenType;

class SpotifyAccessToken
{
    const TOKEN_ENDPOINT = 'api/token';

    const TOKEN_REDIS_KEY = 'shopify_test:shopify_service:app_token';

    /**
     * @var GuzzleClient
     */
    private $guzzle_client;

    /**
     * @var BaseUri
     */
    private $base_uri;

    /**
     * @var ClientId
     */
    private $client_id;

    /**
     * @var ClientSecret
     */
    private $client_secret;

    /**
     * @var RedirectUri
     */
    private $redirect_uri;

    /**
     * @var Token
     */
    private $token;

    /**
     * @var TokenType
     */
    private $token_type;

    /**
     * @var int
     */
    private $expires_in;

    /**
     * @var Token
     */
    private $refresh_token;

    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var RedisClient
     */
    private $redis_client;


    public function __construct(
        TokenType $token_type,
        BaseUri $base_uri,
        ClientId $client_id,
        ClientSecret $client_secret,
        RedirectUri $redirect_uri,
        Scope $scope,
        RedisClient $redis_client
    ) {
        $this->base_uri = $base_uri;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
        $this->guzzle_client = new GuzzleClient();
        $this->token_type = $token_type;
        $this->redis_client = $redis_client;

        $this->token = null;
        $this->expires_in = 0;
        $this->refresh_token = null;
    }

    public function getBaseUri() : BaseUri {
        return $this->base_uri;
    }

    public function getToken() : Token {
        if (empty($this->token)) {
            $this->generateToken();
        }
        return $this->token;
    }

    private function generateToken() {
        switch ($this->token_type) {
            case TokenType::APP_TOKEN;
                $this->generateAppAuthorizationToken();
                break;
            case TokenType::USER_TOKEN:
                throw new MethodNotImplementedException();
        }
    }

    private function generateAppAuthorizationToken()
    {
        if($this->redis_client->exists(self::TOKEN_REDIS_KEY)) {
            $access_token = $this->redis_client->get(self::TOKEN_REDIS_KEY);
            $expires_in = $this->redis_client->ttl(self::TOKEN_REDIS_KEY);
        } else {
            $url = sprintf('%s/%s', $this->base_uri->getValue(), self::TOKEN_ENDPOINT);
            $options = [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
                'headers' => [
                    'Content-Type'=> 'application/x-www-form-urlencoded',
                    'Authorization'=> sprintf(
                        'Basic %s',
                        base64_encode(sprintf('%s:%s', $this->client_id->getValue(), $this->client_secret->getValue()))
                    )
                ]
            ];

            $response = $this->guzzle_client->request('POST', $url, $options);
            $json_response = json_decode($response->getBody()->getContents(), true);

            $access_token = $json_response['access_token'];
            $expires_in = $json_response['expires_in'];

            $this->redis_client->setex(self::TOKEN_REDIS_KEY, $expires_in, $access_token);
        }

        $this->token = new Token($access_token);
        $this->expires_in = $expires_in;
    }
}