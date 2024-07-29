<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['title', 'category_id','subcategory_id', 'role_id', 'file_path', 'upload_date'];

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
}
