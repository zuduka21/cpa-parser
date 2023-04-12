<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Export_info extends Model
{
    protected $table = 'export_info';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = ['user_id','info'];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
