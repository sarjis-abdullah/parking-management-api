<?php

namespace App\Services\Trading\Contracts;

interface TradingUser
{
    /**
     * create a new trading user
     *
     * @param array $data
     * @return array
     */
    public function createUser(array $data) : array;

    /**
     * create a new trading user KYC check
     *
     * @param string $tradingUserId
     * @param array $data
     * @return array
     */
    public function createKycCheck(string $tradingUserId, array $data) : array;


    /**
     * create an account group
     *
     * @param array $data
     * @return array
     */
    public function createAccountGroup(array $data) : array;


    /**
     * create an account
     *
     * @param array $data
     * @return array
     */
    public function createAccount(array $data) : array;




}
