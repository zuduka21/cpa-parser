<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    public $incrementing = 'id';

    public $timestamps = TRUE;

    protected $fillable = ['name'];

    public function users(){
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
