<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'flag_emoji',
        'target_word_count',
    ];

    protected $casts = [
        'target_word_count' => 'integer',
    ];

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class);
    }
}
