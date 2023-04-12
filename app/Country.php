<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = ['id','name'];

    public function brands(){
        return $this->belongsToMany(Brand::class, 'country_brand', 'country_id', 'brand_id');
    }
}
