<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function users()
{
    return $this->belongsToMany(User::class);
}

}
