<?php

namespace App\Models;

use App\Models\Installment;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clients extends Model
{
    protected $guarded = [];
    //
    public function followups()
    {
        return $this->hasMany(ClientFollowUps::class,'ClientID');
    }
    public function unit()
    {
        return $this->hasOne(Units::class,'ClientID');
    }
    public function lastFollowUp()
    {
        return $this->followups()->where('offerDetails','!=',null)->orderBy('id','desc')->first();
    }
    public function nextFollowUp()
    {
        return $this->followups()->whereNull('offerDetails')->orderBy('id','desc')->first();
    }
    public function source()
    {
        return $this->belongsTo(ClientSources::class,'referral');
    }
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class,'client_id');
    }
    public function sourceText()
    {
        $text = '-';
        if ($this->source != '') {
            $text = $this->source->name;
        } elseif (isset(refferalList('ar')[$this->referral])) {
            $text = refferalList('ar')[$this->referral];
        }
        return $text;
    }

    public function duration($position)
    {
        $data = [];
        if($position == 'call_center')
        {
            $data ['period'] = 7;
            $data ['numberOfDays'] = $this->durationCountner($this->call_center_date);
            $data ['date'] = $this->call_center_date;
        }elseif($position == 'sales'){
            $data ['period'] = 14;
            $data ['numberOfDays'] = $this->durationCountner($this->sales_date);
            $data ['date'] = $this->sales_date;
        }elseif($position == 'salesManger'){
            $data ['period'] = 14;
            $data ['numberOfDays'] = $this->durationCountner($this->sales_manger_date);
            $data ['date'] = $this->sales_manger_date;
        }else{
            $data = [
                'period' => '',
                'numberOfDays' => '',
                'date' => '',
            ];
        }
        return $data;
    }

    public function durationCountner($data)
    {
        $currentDate = Carbon::today();
        $numberOfDays = $currentDate->diffInDays($data);
        return $numberOfDays;
    }

    public function checkDurationForNoification($period,$days,$clientId)
    {
        $client = Clients::find($clientId);
        if($period == $days)
        {
            pushNotify($client->name,$client->cellphone,$client->position,$clientId,'clientDuration');
        }
    }


    public function statusText()
    {
        $status = '';
        if (isset(clientStatusArray(session()->get('Lang'))[$this->status])) {
            $status = clientStatusArray(session()->get('Lang'))[$this->status];
        }
        if (isset(clientSalesStatusArray(session()->get('Lang'))[$this->status])) {
            $status = clientSalesStatusArray(session()->get('Lang'))[$this->status];
        }
        return $status;
    }
    public function worthyStatusTxt()
    {
        $list = [
            'text' => supportHousingList(session()->get('Lang'))['worthy'],
            'color' => 'success'
        ];
        if($this->supportHousing != 'worthy') {
            $list = [
                'text' => supportHousingList(session()->get('Lang'))['not_worthy'],
                'color' => 'danger'
            ];
        }
        return $list;
    }
    public function projectDetailsFiles()
    {
        $list = [];
        $followups = $this->followups()->where('project_details','!=',null)->get();
        foreach ($followups as $value) {
            $list[] = [
                'date' => $value['contactingDateTime'],
                'file' => $value['project_details']
            ];
        }
        return $list;
    }
    public function projectDetails()
    {
        $html = '';
        if (count($this->projectDetailsFiles()) > 0) {
            foreach ($this->projectDetailsFiles() as $key => $value) {
                $html .= '<h5><b>وصف المشروع:</b></h5>';
                $html .= '<a href="'.$value['file'].'" target="_blank" class="btn btn-sm btn-info mb-1 mr-1">';
                $html .= $value['date'];
                $html .= '</a>';
            }
        }
        return $html;
    }
    public function projectOffersFiles()
    {
        $list = [];
        $followups = $this->followups()->where('project_offer','!=',null)->get();
        foreach ($followups as $value) {
            $list[] = [
                'date' => $value['contactingDateTime'],
                'file' => $value['project_offer']
            ];
        }
        return $list;
    }
    public function offerDetails()
    {
        $html = '';
        if (count($this->projectOffersFiles()) > 0) {
            foreach ($this->projectOffersFiles() as $key => $value) {
                $html .= '<h5><b>عروض:</b></h5>';
                $html .= '<a href="'.$value['file'].'" target="_blank" class="btn btn-sm btn-info mb-1 mr-1">';
                $html .= $value['date'];
                $html .= '</a>';
            }
        }
        return $html;
    }

    public function units()
    {
        return $this->hasMany(ClientUnit::class,'client_id');
    }
    public function bookedUnit()
    {
        return $this->units()->where('status','booking')->first();
    }
}
