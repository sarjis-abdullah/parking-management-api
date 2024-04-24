<?php

namespace App\Services\Trading\Upvest;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;


trait UpvestBase
{
    /**
     * @var string
     */
    public string $baseUrl;

    /**
     * @var Client
     */
    public Client $tradingClient;

    /**
     * UpvestBase constructor.
     */
    public function __construct()
    {
        $this->baseUrl = env('UPVEST_API_URL');
        $this->tradingClient = new Client(['base_uri' => $this->baseUrl]);
    }

    /**
     * Send request to Upvest API
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function sendRequest(string $method, string $uri, array $options = []) : array
    {
        try {
            $accessToken = $options['access_token'] ?? $this->getAccessToken()['access_token'];

            $defaultHeaders = $this->getDefaultHeaders();
            $defaultHeaders['Authorization'] = 'Bearer ' . $accessToken;
            $options['headers'] = array_merge($defaultHeaders, $options['headers'] ?? []);

            $response = $this->tradingClient->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['status' => $e->getCode(), 'message' => $e->getMessage()];
        }
    }

    /**
     * Get default headers
     *
     * @param array $options
     * @return array
     */
    protected function getDefaultHeaders(array $options = []) : array
    {

        return [
            'idempotency-key' => $options['idempotency_key'] ?? Str::uuid()->toString(),
            'upvest-client-id' => env('UPVEST_CLIENT_ID'),
            //'signature' => '', // todo: implement signature
            // signature-input => '', todo: implement signature input
            'upvest-api-version' => '1',
            'Content-Type' => $options['contentType'] ?? 'application/json',
        ];
    }

    /**
     * Get access token
     *
     * @return array
     */
    public function getAccessToken(): array
    {
        try {
            $response = $this->tradingClient->request('POST', '/auth/token', [
                'headers' => $this->getDefaultHeaders(['contentType' => 'application/x-www-form-urlencoded']),
                'form_params' => [
                    'client_id' => env('UPVEST_CLIENT_ID'),
                    'client_secret' => env('UPVEST_CLIENT_SECRET'),
                    'grant_type' => 'client_credentials',
                    'scope' => 'users:admin users:read instruments:read checks:admin accounts:admin' // todo: add more scopes
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['status' => $e->getCode(), 'message' => $e->getMessage()];
        }
    }

}
