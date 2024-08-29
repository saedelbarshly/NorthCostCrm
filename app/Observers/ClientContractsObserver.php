<?php

namespace App\Observers;

use App\ClientContracts;
use App\ContractServices;
use App\Revenues;
use App\Models\Clients;

use Carbon\Carbon;

class ClientContractsObserver
{
    /**
     * Handle the client contracts "created" event.
     *
     * @param  \App\ClientContracts  $clientContracts
     * @return void
     */
    public function created(ClientContracts $contract)
    {
        //
        $request = request();
        $services = [];
        for ($i=0; $i < count($request->service_id); $i++) {
            $services[] = [
                'service_id' => $request['service_id'][$i],
                'service_price' => $request['service_price'][$i],
                'service_renewal' => $request['service_renewal'][$i]
            ];
        }

        foreach ($services as $key => $value) {
            $serviceData = [
                'contract_id' => $contract->id,
                'service_id' => $value['service_id'],
                'price' => $value['service_price'],
                'renewal_type' => $value['service_renewal']
            ];
            if ($value['service_renewal'] != '0') {
                $MyDateCarbon = Carbon::parse($request->contractDate);
                $MyDateCarbon->addMonths($value['service_renewal']);
                $serviceData['next_renewal_date'] = date('Y-m-d',strtotime($MyDateCarbon));
                $serviceData['renewal_status'] = 'pending';
            }
            $createServices = ContractServices::create($serviceData);
        }

        if ($request->paid > 0) {
            $payment = Revenues::create([
                'UID' => auth()->user()->id,
                'Type' => 'contract',
                'amount' => $request->paid,
                'Date' => $request->contractDate,
                'DateStr' => strtotime($request->contractDate),
                'month' => date('m',strtotime($request->contractDate)),
                'year' => date('Y',strtotime($request->contractDate)),
                'safe_id' => $request->SafeID,
                'branch_id' => $contract->branch_id,
                'contract_id' => $contract->id,
                'client_id' => $contract->ClientID
            ]);
        }
        $client = Clients::find($contract->ClientID);
        $client->status = 'current';
        $client->save();

    }

    /**
     * Handle the client contracts "updated" event.
     *
     * @param  \App\ClientContracts  $clientContracts
     * @return void
     */
    public function updated(ClientContracts $contract)
    {
        $contract->servicesList()->delete();

        $request = request();
        $services = [];
        for ($i=0; $i < count($request->service_id); $i++) {
            $services[] = [
                'service_id' => $request['service_id'][$i],
                'service_price' => $request['service_price'][$i],
                'service_renewal' => $request['service_renewal'][$i]
            ];
        }

        foreach ($services as $key => $value) {
            $serviceData = [
                'contract_id' => $contract->id,
                'service_id' => $value['service_id'],
                'price' => $value['service_price'],
                'renewal_type' => $value['service_renewal']
            ];
            if ($value['service_renewal'] != '0') {
                $MyDateCarbon = Carbon::parse($request->contractDate);
                $MyDateCarbon->addMonths($value['service_renewal']);
                $serviceData['next_renewal_date'] = date('Y-m-d',strtotime($MyDateCarbon));
                $serviceData['renewal_status'] = 'pending';
            }
            $createServices = ContractServices::create($serviceData);
        }

    }

    /**
     * Handle the client contracts "deleted" event.
     *
     * @param  \App\ClientContracts  $clientContracts
     * @return void
     */
    public function deleted(ClientContracts $contract)
    {
        //
        $contract->payments()->delete();
        $contract->expenses()->delete();
        $contract->servicesList()->delete();
    }

    /**
     * Handle the client contracts "restored" event.
     *
     * @param  \App\ClientContracts  $clientContracts
     * @return void
     */
    public function restored(ClientContracts $clientContracts)
    {
        //
    }

    /**
     * Handle the client contracts "force deleted" event.
     *
     * @param  \App\ClientContracts  $clientContracts
     * @return void
     */
    public function forceDeleted(ClientContracts $clientContracts)
    {
        //
    }
}
