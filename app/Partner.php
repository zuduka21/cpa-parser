<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = ['id','name','shift_date','working','key','indication','login','password'];

    public function brands(){
        return $this->hasMany(Brand::class, 'partner_id', 'id');
    }

}
