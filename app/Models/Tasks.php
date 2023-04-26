<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';
    
    public function project(){
        return $this->belongsTo(Projects::class);
    }

    public function owner(){
        return $this->belongsTo(User::class);
    }

    public function scopeWhereProject($q, $id){
        $q->where('project_id', $id);
    }

}
