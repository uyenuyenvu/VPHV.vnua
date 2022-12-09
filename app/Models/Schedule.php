<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';

    protected $fillable = [
        'start_time',
        'end_time',
        'location_id',
        'location_other_name',
        'leader_id',
        'leader_orther_name',
        'title',
        'description',
        'type',
        'department_id',
        'create_by',
        'update_by',
        'close_by',
        'status'
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(User::class, 'location_id');
    }

    public function updateBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function createBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_by');
    }
    public function closeBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'close_by');
    }

}
