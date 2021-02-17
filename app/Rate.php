<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = ['agreement_id', 'designation_id', 'rate'];
}
