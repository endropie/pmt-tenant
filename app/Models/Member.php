<?php

namespace App\Models;

use App\Traits\HasLoggable;
use Attribute;
use Endropie\LumenMicroServe\Traits\HasFilterable;
use Endropie\LumenMicroServe\Traits\UniqueIdentifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes, HasFilterable, HasLoggable, UniqueIdentifiable;

    protected $guarded = ["*"];

    public function persons()
    {
        return $this->hasMany(\App\Models\Person::class);
    }

    public function subtenant()
    {
        return $this->belongsTo(\App\Models\Subtenant::class);
    }

    public function tenant(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->subtenant->tenant,
        );
    }
}
