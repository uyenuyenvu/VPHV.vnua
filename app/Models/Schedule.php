<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status',
        'is_public',
        'old_start_time',
        'old_end_time',
        'old_location_id',
        'old_location_other_name',
        'old_leader_id',
        'old_leader_orther_name',
        'old_title',
        'old_description',
        'old_type',
        'old_department_id',
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
    public function elements():HasMany{
        return $this->hasMany(Element::class, 'schedule_id');
    }

}
