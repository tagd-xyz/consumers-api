<?php

namespace App\Http\V1\Resources\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasPagination
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (is_a($this->resource, LengthAwarePaginator::class)) {
            return [
                'meta' => [
                    'total' => $this->total(),
                    'to' => $this->lastItem(),
                    'from' => $this->firstItem(),
                    'currentPage' => $this->currentPage(),
                    'perPage' => $this->perPage(),
                    'lastPage' => $this->lastPage(),
                ],
                'items' => $this->collection,
            ];
        } else {
            return $this->collection;
        }
    }
}
