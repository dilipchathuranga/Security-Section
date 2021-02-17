<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Employee;

class Supplier extends Model
{
    protected $fillable = ['br_no', 'bp_no', 'name', 'address', 'email', 'tele_no'];


    public function employee(){
        return $this->hasMany(Employee::class);
    }
}
