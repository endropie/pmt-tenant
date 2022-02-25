<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessable extends Model
{
    protected $guarded = ["*"];

    protected $attributes = [
        'abilities' => "[]",
    ];

    protected $casts = [
        'abilities' => 'array'
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
