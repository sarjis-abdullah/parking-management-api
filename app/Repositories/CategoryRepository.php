<?php

namespace App\Repositories;

use App\Exceptions\CustomValidationException;
use App\Repositories\Contracts\CategoryInterface;

class CategoryRepository extends EloquentBaseRepository implements CategoryInterface
{
    /**
     * @throws CustomValidationException
     */
    public function delete(\ArrayAccess $model): bool
    {
        if ($model->has_parking()){
            throw new CustomValidationException('The name field must be an array.', 422, [
                'parking' => ["Can't be deleted, This is belongs to parking calculation."],
            ]);
        }
        return parent::delete($model);
    }
}
