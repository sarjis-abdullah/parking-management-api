<?php

namespace App\Services\Trading\Upvest;

use App\Services\Trading\Contracts\TradingUser;

class UpvestTradingUser implements TradingUser
{
    use UpvestBase;

    /**
     * create a new trading user
     *
     * @param array $data
     * @return array
     */
    public function createUser(array $data) : array
    {
        return $this->sendRequest('POST', '/users', ['json' => $data]);
    }


    /**
     * create a new trading user check\
     *
     * @param string $tradingUserId
     * @param array $data
     * @return array
     */
    public function createKycCheck(string $tradingUserId, array $data) : array
    {
        return $this->sendRequest('POST', "/users/$tradingUserId/checks", ['json' => $data]);
    }

    /**
     * create an account group
     *
     * @param array $data
     * @return array
     */
    public function createAccountGroup(array $data) : array
    {
        return $this->sendRequest('POST', '/account_groups', ['json' => $data]);
    }

    /**
     * create an account
     *
     * @param array $data
     * @return array
     */
    public function createAccount(array $data) : array
    {
        return $this->sendRequest('POST', '/accounts', ['json' => $data]);
    }


}
