<?php

namespace App\Models;

use App\Traits\HasLoggable;
use Endropie\LumenMicroServe\Traits\HasFilterable;
use Endropie\LumenMicroServe\Traits\UniqueIdentifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtenant extends Model
{
    use HasFactory, SoftDeletes, HasFilterable, HasLoggable, UniqueIdentifiable;

    protected $guarded = ["*"];

    public function members()
    {
        return $this->hasMany(\App\Models\Member::class);
    }
    
    public function persons()
    {
        return $this->hasManyThrough(\App\Models\Person::class, 'members');
    }

    public function tenant()
    {
        return $this->hasMany(\App\Models\Tenant::class);
    }

    public function accessables()
    {
        return $this->morphMany(Accessable::class, 'model');
    }
}
