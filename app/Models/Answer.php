<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
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
        'question_id',
        'student_id',
        'question_order',
        'answer_order',
        'answer',
        'selected_answers', // Field for multiple-choice answers
        'essay_answer',     // Field for essay responses
        'is_correct',
        'score',            // Field for partial credit scoring
        'similarity_score', // Field for essay similarity scoring
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'selected_answers' => 'array',
        'score' => 'float',
        'similarity_score' => 'float',
    ];

    /**
     * question
     *
     * @return void
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
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
     * exam
     *
     * @return void
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
