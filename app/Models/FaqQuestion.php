<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqQuestion extends Model
{
    use HasFactory;
    
    protected $fillable = ['faq_subject_id', 'title', 'content'];

    public function faqSubject()
    {
        return $this->belongsTo(FaqSubject::class);
    }
}
