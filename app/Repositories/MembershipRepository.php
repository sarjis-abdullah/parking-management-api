<?php

namespace App\Repositories;

use App\Exceptions\CustomValidationException;
use App\Models\MembershipType;
use App\Repositories\Contracts\MembershipInterface;

class MembershipRepository extends EloquentBaseRepository implements MembershipInterface
{
    /**
     * @throws CustomValidationException
     */
    public function save(array $data): \ArrayAccess
    {
        $type = MembershipType::where('default', true)->orderBy('id', 'desc')->first();
        if ($type == null){
            throw new CustomValidationException('The name field must be an array.', 422, [
                'membership_type' => ['No default membership found, add a default membership type.'],
            ]);
        }else{
            $data['membership_type_id'] = $type->id;
        }
        return parent::save($data);
    }
}
