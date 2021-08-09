<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['payload', 'transaction_id', 'original_transaction_id', 'notification_type'];
}
