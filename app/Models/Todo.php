<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'task',
        'completed',
        'priority',
        'order'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    protected $hidden = [
        'user'
    ];
}
