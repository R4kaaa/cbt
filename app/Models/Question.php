<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'exam_id',
        'question',
        'question_image',
        'audio_file',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'option_5',
        'answer',
        'essay_answer',
        'question_type', // Single or multiple
        'answers', // JSON array of correct answers for multiple type
        'media_type', // None, image, audio
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'answers' => 'array',
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
     * isMultipleChoice
     * 
     * @return boolean
     */
    public function isMultipleChoice()
    {
        return $this->question_type === 'multiple';
    }

    /**
     * hasImage
     * 
     * @return boolean
     */
    public function hasImage()
    {
        return $this->media_type === 'image' && !empty($this->question_image);
    }

    /**
     * hasAudio
     * 
     * @return boolean
     */
    public function hasAudio()
    {
        return $this->media_type === 'audio' && !empty($this->audio_file);
    }

    /**
     * Get the question image URL
     * 
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if ($this->hasImage()) {
            return asset('storage/questions/' . $this->question_image);
        }
        return null;
    }

    /**
     * Get the audio file URL
     * 
     * @return string|null
     */
    public function getAudioUrlAttribute()
    {
        if ($this->hasAudio()) {
            return asset('storage/questions/' . $this->audio_file);
        }
        return null;
    }
}
