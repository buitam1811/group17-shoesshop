<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = "Brand";

    public function product()
    {
    	return $this->hasMany("App\Product","brand_id","id");
    }
}