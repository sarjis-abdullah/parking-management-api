<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as JsonResource;

class Resource extends JsonResource
{
    /**
     * check it needs include an optional value
     *
     * @param Request $request
     * @param string $key
     * @return bool
     */
    public function needToInclude(Request $request, string $key)
    {
        return in_array($key, explode(',', $request->get('include')));
    }

    /**
     * Return with all the relations if `detailed=true` query param presents
     *
     * @param Request $request
     * @return bool
     */
    public function needDetailed(Request $request)
    {
        return $request->has('detailed');
    }

    /**
     * This method is necessary just to preserve backward compatibility with non-Eloquent resources.
     * The objects inside a JsonResource now must implement ArrayAccess. This means you cannot create a Resource
     * of a Collection of plain objects like we're doing here with a RAW Query.
     *
     * We're applying the changes suggested by Graham Campbell in #29860.
     *
     * More information
     *      https://github.com/laravel/framework/issues/29916
     *      https://github.com/laravel/framework/issues/29858
     *      https://github.com/laravel/framework/pull/29860
     *
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return (is_array($offset) || $offset instanceof ArrayAccess) && isset($this->resource[$offset]);
    }
}
