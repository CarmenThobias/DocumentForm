<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqQuestion extends Model
{
    protected $fillable = ['title', 'content', 'faq_subject_id']; // Ensure faq_subject_id is in your table
    
    public function subject()
    {
        return $this->belongsTo(FaqSubject::class, 'faq_subject_id');
    }
}

