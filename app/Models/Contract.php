<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'client',
        'subject',
        'value',
        'type',
        'start_date',
        'end_date',
        'description',
        'created_by',
    ];

    public function clients()
    {
        return $this->hasOne('App\Models\User', 'id', 'client');
    }

    public function types()
    {
        return $this->hasOne('App\Models\ContractType', 'id', 'type');
    }
}
