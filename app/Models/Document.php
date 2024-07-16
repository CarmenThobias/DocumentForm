<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'category_id', 'file_path','upload_date'];

    protected $casts = [
        'upload_date' => 'date',  // Ensure the field is cast to a date
    ];

    // Define the relationship to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
