<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningOutcome extends Model
{
    use HasFactory;

    protected $table = 'learning_outcomes';

    protected $fillable = [
        'semester',
        'year_school_start',
        'year_school_end',
        'semester_average_10',
        'semester_average_4',
        'cumulative_average_10',
        'cumulative_average_4',
        'credits',
        'cumulative_credits',
        'type_average',
        'student_id',
    ];

    public function students(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(LearningOutcomeDetail::class);
    }
}
