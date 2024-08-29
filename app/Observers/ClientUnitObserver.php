<?php

namespace App\Observers;

use App\Models\ClientUnit;

class ClientUnitObserver
{
    /**
     * Handle the client unit "created" event.
     *
     * @param  \App\Models\ClientUnit  $clientUnit
     * @return void
     */
    public function created(ClientUnit $clientUnit)
    {
        //
    }

    /**
     * Handle the client unit "updated" event.
     *
     * @param  \App\Models\ClientUnit  $clientUnit
     * @return void
     */
    public function updated(ClientUnit $clientUnit)
    {
        //
    }

    /**
     * Handle the client unit "deleted" event.
     *
     * @param  \App\Models\ClientUnit  $clientUnit
     * @return void
     */
    public function deleted(ClientUnit $clientUnit)
    {
        //
    }

    /**
     * Handle the client unit "restored" event.
     *
     * @param  \App\Models\ClientUnit  $clientUnit
     * @return void
     */
    public function restored(ClientUnit $clientUnit)
    {
        //
    }

    /**
     * Handle the client unit "force deleted" event.
     *
     * @param  \App\Models\ClientUnit  $clientUnit
     * @return void
     */
    public function forceDeleted(ClientUnit $clientUnit)
    {
        //
    }
}
