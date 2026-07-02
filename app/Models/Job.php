<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'description',
        'requirements',
        'location',
        'salary'
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}