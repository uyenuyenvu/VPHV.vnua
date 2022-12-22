<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'element_schedules';

    protected $fillable = [
        'name',
        'user_id',
        'schedule_id'
    ];

}
