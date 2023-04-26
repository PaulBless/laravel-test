<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projects extends Model
{
    // use HasFactory;
    use SoftDeletes;

    public function tasks(){
        return $this->hasMany(Tasks::class);
    }

    public function scopeHasTasks($q){
        return $q->whereHas('tasks');
    }

}
