<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'category_id','subcategory_id', 'role_id', 'file_path', 'file_path_doc','file_path_pdf', 'upload_date', 'original_filename_doc', 'original_filename_pdf',];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    protected $casts = [
        'upload_date' => 'datetime',
    ];
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
        
    }
    public function getPdfUrlAttribute()
    {
        return $this->file_path_pdf ? asset('storage/' . $this->file_path_pdf) : null;
    }

    public function getDocUrlAttribute()
    {
        return $this->file_path_doc ? asset('storage/' . $this->file_path_doc) : null;
    }
}
