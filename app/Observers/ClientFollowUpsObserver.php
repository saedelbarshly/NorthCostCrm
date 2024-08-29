<?php

namespace App\Observers;

use App\Models\ClientFollowUps;
use App\Models\Clients;
use App\Models\ClientUnit;
use App\Models\Units;

class ClientFollowUpsObserver
{
    /**
     * Handle the client follow ups "created" event.
     *
     * @param  \App\Models\ClientFollowUps  $clientFollowUps
     * @return void
     */
    public function created(ClientFollowUps $clientFollowUps)
    {
        //
        if ($clientFollowUps->status == 'no_interest' || $clientFollowUps->status == 'checkout_reject') {
            $clientFollowUps->client()->update(['status'=>'archive']);
        } else {
            $clientFollowUps->client()->update(['status'=>$clientFollowUps->status]);
        }
        if ($clientFollowUps->status == 'booking_done') {
            $clientFollowUps->client()->update(['position'=>'contractFollowUp']);
            if ($clientFollowUps->client != '' && $clientFollowUps->UnitID != '') {
                if ($clientFollowUps->client->units()->where('unit_id',$clientFollowUps->UnitID)->first() == '') {
                    $booking = ClientUnit::create([
                        'unit_id' => $clientFollowUps->UnitID,
                        'client_id' => $clientFollowUps->ClientID,
                        'status' => 'booking',
                        'agent_id' => auth()->user()->id,
                        'booking_day' => date('Y-m-d')
                    ]);
                }
            }
        }
        if ($clientFollowUps->status == 'booking_contract'
            && $clientFollowUps->UnitID != 'All'
            && $clientFollowUps->UnitID != '') {
            if($clientFollowUps->client != ''){
                if ($clientFollowUps->client->bookedUnit() != '') {
                    $clientFollowUps->client->bookedUnit()->update(['status'=>'contract','contract_day'=>date('Y-m-d')]);
                } else {
                    $booking = ClientUnit::create([
                        'unit_id' => $clientFollowUps->UnitID,
                        'client_id' => $clientFollowUps->ClientID,
                        'status' => 'contract',
                        'agent_id' => auth()->user()->id,
                        'contract_day' => date('Y-m-d')
                    ]);
                }
                $clientFollowUps->client()->update(['position'=>'contracts']);
            }
        }
        if($clientFollowUps->rejictionCause != '') {
            $clientFollowUps->client()->update(['rejictionCause'=>$clientFollowUps->rejictionCause]);
        }

    }

    /**
     * Handle the client follow ups "updated" event.
     *
     * @param  \App\Models\ClientFollowUps  $clientFollowUps
     * @return void
     */
    public function updated(ClientFollowUps $clientFollowUps)
    {
        //
    }

    /**
     * Handle the client follow ups "deleted" event.
     *
     * @param  \App\Models\ClientFollowUps  $clientFollowUps
     * @return void
     */
    public function deleted(ClientFollowUps $clientFollowUps)
    {
        //
    }

    /**
     * Handle the client follow ups "restored" event.
     *
     * @param  \App\Models\ClientFollowUps  $clientFollowUps
     * @return void
     */
    public function restored(ClientFollowUps $clientFollowUps)
    {
        //
    }

    /**
     * Handle the client follow ups "force deleted" event.
     *
     * @param  \App\Models\ClientFollowUps  $clientFollowUps
     * @return void
     */
    public function forceDeleted(ClientFollowUps $clientFollowUps)
    {
        //
    }
}
