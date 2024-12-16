<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'user_id',
        'task_name',
        'status',
        'due_date',
        'due_time',
    ];


    public function user() {
        return $this->belongsTo(User::class);
    }
}
