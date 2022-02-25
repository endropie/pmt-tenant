<?php

namespace App\Models;

use App\Traits\HasLoggable;
use Attribute;
use Endropie\LumenMicroServe\Traits\HasFilterable;
use Endropie\LumenMicroServe\Traits\UniqueIdentifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes, HasFilterable, HasLoggable, UniqueIdentifiable;
    
    protected $table = "persons";
    
    protected $guarded = ["*"];

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class);
    }

    public function subtenant(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->member->subtenant,
        );
    }

    public function tenant(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->subtenant->tenant,
        );
    }
}
