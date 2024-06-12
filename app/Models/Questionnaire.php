<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'expiry_date'
    ];

    public function questionnaireDetails()
    {
        return $this->hasMany(QuestionnaireDetail::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
