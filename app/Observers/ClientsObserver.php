<?php

namespace App\Observers;

use App\Models\Clients;

class ClientsObserver
{
    /**
     * Handle the clients "created" event.
     *
     * @param  \App\Models\Clients  $clients
     * @return void
     */
    public function created(Clients $clients)
    {
        //
    }

    /**
     * Handle the clients "updated" event.
     *
     * @param  \App\Models\Clients  $clients
     * @return void
     */
    public function updated(Clients $clients)
    {
        //
        if ($clients->status == 'archive') {
            if ($clients->units != '') {
                $clients->units()->delete();
            }
        }
    }

    /**
     * Handle the clients "deleted" event.
     *
     * @param  \App\Models\Clients  $clients
     * @return void
     */
    public function deleted(Clients $clients)
    {
        //
        if ($clients->units != '') {
            $clients->units()->delete();
        }
    }

    /**
     * Handle the clients "restored" event.
     *
     * @param  \App\Models\Clients  $clients
     * @return void
     */
    public function restored(Clients $clients)
    {
        //
    }

    /**
     * Handle the clients "force deleted" event.
     *
     * @param  \App\Models\Clients  $clients
     * @return void
     */
    public function forceDeleted(Clients $clients)
    {
        //
    }
}
