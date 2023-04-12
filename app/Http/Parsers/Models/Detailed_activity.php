<?php

namespace App\Http\Parsers\Models;

use Illuminate\Database\Eloquent\Model;

class Detailed_activity extends Model
{
    protected $table = 'detailed_activity';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        $array = config('parser.detailed_activity');
        array_push($array,'brand_id');
        $this->fillable = $array;
        parent::__construct($attributes);
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
