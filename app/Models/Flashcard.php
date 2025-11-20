<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'term',
        'translation',
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
