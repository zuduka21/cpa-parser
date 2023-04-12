<?php

namespace App\Http\Parsers\Models;

use Illuminate\Database\Eloquent\Model;

class Net_refer extends Model
{
    protected $table = 'net_refer';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        $array = config('parser.net_refer');
        array_push($array,'brand_id');
        $this->fillable = $array;
        parent::__construct($attributes);
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
