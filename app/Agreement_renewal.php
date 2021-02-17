<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement_renewal extends Model
{
    protected $fillable = ['agreement_id','agreement_no','from_date','to_date'];
}
