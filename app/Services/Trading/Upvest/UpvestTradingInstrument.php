<?php

namespace App\Services\Trading\Upvest;

use App\Services\Trading\Contracts\TradingInstrument;

class UpvestTradingInstrument implements TradingInstrument
{
    use UpvestBase;

    /**
     * Get a list of instruments
     *
     * @param array $searchParams
     * @return array
     */
    public function getInstruments(array $searchParams = []) : array
    {
        $queryParams['limit'] = $searchParams['per_page'] ?? 100;
        $queryParams['sort'] = $searchParams['order_by'] ?? 'created_at';
        $queryParams['order'] = strtoupper($searchParams['order_direction'] ?? 'asc');
        $queryParams['trading_status'] = strtoupper($searchParams['trading_status'] ?? 'active');

        return $this->sendRequest('GET', '/instruments?' .  http_build_query($queryParams));
    }

    /**
     * Get a single instrument
     *
     * @param string $id
     * @return array
     */
    public function getInstrumentByISN(string $isinId) : array
    {
        return $this->sendRequest('GET', '/instruments/isin:' . $isinId);
    }
}
