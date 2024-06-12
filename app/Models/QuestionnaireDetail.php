<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'question_id'
    ];

    
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
    
    public function questions()
    {
        return $this->belongsTo(Question::class,'question_id');
    }
}
