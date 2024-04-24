<?php

namespace App\Services\Trading;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;


class FondService
{
    /**
     * @var string
     */
    public string $baseUrl;

    /**
     * @var Client
     */
    public Client $serviceClient;

    /**
     * FondService constructor.
     */
    public function __construct()
    {
        $this->baseUrl = env('FOND_SERVICE_API_URL');
        $this->serviceClient = new Client(['base_uri' => $this->baseUrl]);
    }

    /**
     * Send request to FondService API
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return array
     */
    public function sendRequest(string $method, string $uri, array $options = []) : array
    {
        try {
            $options['headers'] = $options['headers'] ?? [ 'Content-Type' => 'application/json', 'Accept' => 'application/json' ];
            $response = $this->serviceClient->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return ['status' => $e->getCode(), 'message' => $e->getMessage()];
        }
    }

    /**
     * Get instrument details by ISIN
     *
     * @param string $isin
     * @return array
     */
    public function getInstrumentDetails(string $isin) : array
    {
        $cacheKey = 'isin_details_' . $isin;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $instrumentDetails = $this->sendRequest('GET', '/instrument-details/' . $isin);

        $lastPriceDetails = last($instrumentDetails['Kurse']['NAV']);
        $latestPriceData['Last_Price'] = $lastPriceDetails['Price'];
        $latestPriceData['Last_Price_Date'] = $lastPriceDetails['Date'];
        $latestPriceData['Last_Price_Ccy'] = $lastPriceDetails['Ccy'];
        $instrumentData = array_merge($latestPriceData, $instrumentDetails);

        // set cache ttl to 12:00 and 00:00
        $cacheTtl = now() < now()->setTime(12, 0) ? now()->setTime(12, 0)->diffInMinutes(now()) : now()->endOfDay()->diffInMinutes(now());

        Cache::put($cacheKey, $instrumentData, $cacheTtl);

        return $instrumentData;
    }

    /**
     * Get latest price of the instrument
     *
     * @param string $isin
     * @return array
     */
    public function getLatestPriceOfTheInstrument(string $isin) : array
    {
        $instrumentDetails = $this->getInstrumentDetails($isin);
        return [
            'value' => $instrumentDetails['Last_Price'],
            'date' => $instrumentDetails['Last_Price_Date'],
            'ccy' => $instrumentDetails['Last_Price_Ccy'],
        ];
    }



}
