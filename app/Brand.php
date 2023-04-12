<?php

namespace App;

use App\Http\Parsers\CoreParser;
use App\Http\Parsers\ParsersConfig;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = ['id','name','partner_id'];

    public function partner(){
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }

    public function countries(){
        return $this->belongsToMany(Country::class, 'country_brand', 'brand_id', 'country_id');
    }

    public function brandTable($table){
        return $this->hasMany( $table, 'brand_id', 'id');
    }

    public function brandModels($indication){
        return ParsersConfig::BrandModels($indication);
    }
}
