<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'trainer_option',
        'training_type',
        'trainer',
        'training_cost',
        'employee',
        'start_date',
        'end_date',
        'description',
        'created_by',
    ];


    public static $options = [
        'Internal',
        'External',
    ];

    public static $performance = [
        'Not Concluded',
        'Satisfactory',
        'Average',
        'Poor',
        'Excellent',
    ];

    public static $Status = [
        'Pending',
        'Started',
        'Completed',
        'Terminated',
    ];


    public function types()
    {
        return $this->hasOne('App\Models\TrainingType', 'id', 'training_type');
    }

    public function employees()
    {
        return $this->hasOne('App\Models\User', 'id', 'employee');
    }

    public function trainers()
    {
        return $this->hasOne('App\Models\Trainer', 'id', 'trainer');
    }
}
