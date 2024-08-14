<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = ['name', 'description', 'before_date', 'priority', 'status'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
