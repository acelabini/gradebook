<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;

    protected $casts = [
        'homeworks' => 'array',
        'exams' => 'array',
    ];

    protected $fillable = [
        'student_id',
        'year',
        'quarter',
        'homeworks',
        'exams',
        'average',
    ];

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
