<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updating(User $user)
    {
           if(!empty($user->password)){
               $user->password = Hash::make($user['password']);
               $user->setRememberToken(Str::random(60));
           }else{
               $user->password = $user->getOriginal()['password'];
           }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
