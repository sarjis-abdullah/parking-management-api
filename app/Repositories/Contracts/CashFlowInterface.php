<?php

namespace App\Repositories\Contracts;

interface CashFlowInterface extends BaseRepository
{
    public function endDay();
    public function startDay(array $criteria);
}
