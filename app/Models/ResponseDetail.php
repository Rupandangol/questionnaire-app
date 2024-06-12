<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResponseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'response_id',
        'question_id',
        'answer_id'
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class, 'response_id');
    }

    public function questions(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
    public function answers(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }
}
