<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['contract_id', 'supplier_id', 'name','address','tele_no'];
}
