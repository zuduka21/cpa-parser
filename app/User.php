<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password', 'allow_export'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function exports_info()
    {
        return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function checkRole($role)
    {
        return (bool)$this->roles()->where('name', $role)->count();
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'user_allowed', 'user_id', 'brand_id');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'user_allowed', 'user_id', 'country_id');
    }

    public function partners()
    {
        return $this->belongsToMany(Partner::class, 'user_allowed', 'user_id', 'partner_id');
    }

    public function NotEmptyBrands()
    {
        return (bool)$this->brands()->count();
    }

    public function NotEmptyCountries()
    {
        return (bool)$this->countries()->count();
    }

    public function NotEmptyPartners()
    {
        return (bool)$this->partners()->count();
    }

    public function getAllowBrands($allowType, $allowId)
    {
        if (Auth::user()->checkRole('admin')) {
            $partners = Partner::find($allowId);
            if (!empty($partners)) {
                return $partners->brands()->get();
            }
        } else if ($allowType === 'countries') {
            $countries = $this->countries()->get()->where('id','=',$allowId)->first();
            if (!empty($countries)) {
                return $countries->brands()->get();
            }
        } else if ($allowType === 'partners') {
            $partners = $this->partners()->get()->where('id','=',$allowId)->first();
            if (!empty($partners)) {
                return $partners->brands()->get();
            }
        }
        return $this->brands()->get();
    }
}
