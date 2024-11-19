<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'is_completed',
        'is_paid',
    ];
    protected $casts = [
        'is_completed' => 'boolean',
        'is_paid' => 'boolean',
        'due_date' => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
