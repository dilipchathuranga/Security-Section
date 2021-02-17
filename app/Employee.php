<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Supplier;

class Employee extends Model
{
    protected $fillable = ['designation_id', 'supplier_id', 'nic', 'name', 'address', 'tele_no'];


    public function supplier(){

        return $this->belongsTo(Supplier::class);
    }

}

