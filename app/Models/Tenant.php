<?php

namespace App\Models;

use App\Traits\HasLoggable;
use Endropie\LumenMicroServe\Traits\HasFilterable;
use Endropie\LumenMicroServe\Traits\UniqueIdentifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes, HasFilterable, HasLoggable, UniqueIdentifiable;

    protected $guarded = ["*"];

    public function subtenants()
    {
        return $this->hasMany(\App\Models\Subtenant::class);
    }

    public function accessables()
    {
        return $this->morphMany(Accessable::class, 'model');
    }
}
