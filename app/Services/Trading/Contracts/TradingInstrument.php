<?php

namespace App\Services\Trading\Contracts;

interface TradingInstrument
{
    /**
     * Get all instruments
     *
     * @param array $searchParams
     * @return array
     */
    public function getInstruments(array $searchParams = []) : array;

    /**
     * Get a single instrument
     *
     * @param string $isinId
     * @return array
     */
    public function getInstrumentByISN(string $isinId) : array;
}
