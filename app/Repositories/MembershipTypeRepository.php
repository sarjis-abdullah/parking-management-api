<?php

namespace App\Repositories;

use App\Models\MembershipType;
use App\Repositories\Contracts\MembershipTypeInterface;

class MembershipTypeRepository extends EloquentBaseRepository implements MembershipTypeInterface
{
    public function save(array $data): \ArrayAccess
    {
        if (!isset($data['default']) || !$data['default']){
            $oldTariff = MembershipType::where('default', true)->first();
            if ($oldTariff == null){
                $data['default'] = true;
            }
        }
        return parent::save($data);
    }
}
