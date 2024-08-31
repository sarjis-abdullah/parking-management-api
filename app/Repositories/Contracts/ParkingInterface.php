<?php

namespace App\Repositories\Contracts;

use ArrayAccess;

interface ParkingInterface extends BaseRepository
{
    /**
     * update a resource
     *
     * @param ArrayAccess $model
     * @param array $data
     * @return ArrayAccess
     */
    public function handleCheckout(ArrayAccess $model, array $data);
}
