<?php

namespace App\Http\Resources;

use Endropie\LumenMicroServe\Http\Resource;

class MemberResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            $this->mergeAttributes(),
        ];
    }
}
