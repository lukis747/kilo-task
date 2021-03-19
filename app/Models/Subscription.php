<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public const ACTIVE = 'active';
    public const FAILED_TO_EXTEND = 'failed_to_extend';
    public const CANCELED = 'canceled';


    // TODO change to fillable
    protected $guarded= [];
}
