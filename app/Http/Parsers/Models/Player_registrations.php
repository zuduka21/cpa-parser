<?php

namespace App\Http\Parsers\Models;

use Illuminate\Database\Eloquent\Model;

class Player_registrations extends Model
{
    protected $table = 'player_registrations';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        $array = config('parser.player_registrations');
        array_push($array,'brand_id');
        $this->fillable = $array;
        parent::__construct($attributes);
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
}
