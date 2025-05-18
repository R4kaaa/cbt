<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'exam_id',
        'exam_session_id',
        'student_id',
        'duration',
        'start_time',
        'end_time',
        'total_correct',
        'total_score', // New field for partial credit sum
        'grade',
        'total_partial', // New field for total partial score
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_score' => 'float',
        'grade' => 'float',
    ];

    /**
     * exam
     *
     * @return void
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * exam_session
     *
     * @return void
     */
    public function exam_session()
    {
        return $this->belongsTo(ExamSession::class);
    }

    /**
     * student
     *
     * @return void
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * answers
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'exam_id', 'exam_id')
            ->where('student_id', $this->student_id)
            ->where('exam_session_id', $this->exam_session_id);
    }
}
