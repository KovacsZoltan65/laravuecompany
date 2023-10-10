<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'email', 'phone', 'department_id'];
    
    public function department(){
        return $this->hasOne(Department::class, 'department_id', 'id');
    }
}
