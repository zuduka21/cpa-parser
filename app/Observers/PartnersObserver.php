<?php

namespace App\Observers;

use App\Partner;

class PartnersObserver
{
    /**
     * Handle the partner "creating" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function creating(Partner $partner)
    {
        if(!empty($partner['indication'])){
            $temp_indication = strtolower($partner['indication']);
            $temp_indication = str_replace(" ", "_", $temp_indication);

            $partner['indication'] = $temp_indication;
        }else{
            $temp_indication = strtolower($partner['name']);
            $temp_indication = str_replace(" ", "_", $temp_indication);

            $partner['indication'] = $temp_indication;
        }
        if($partner['working'] == 'on'){
            $partner['working'] = true;
        }else{
            $partner['working'] = false;
        }
    }

    /**
     * Handle the partner "created" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function created(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "updating" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function updating(Partner $partner)
    {
    }
    /**
     * Handle the partner "updated" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function updated(Partner $partner)
    {
    }

    /**
     * Handle the partner "deleted" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function deleted(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "restored" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function restored(Partner $partner)
    {
        //
    }

    /**
     * Handle the partner "force deleted" event.
     *
     * @param  \App\Partner  $partner
     * @return void
     */
    public function forceDeleted(Partner $partner)
    {
        //
    }
}
