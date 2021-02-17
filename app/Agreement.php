<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    protected $fillable = ['project_id','supplier_id','agreement_no','agreement_date','expire_date'];
}
