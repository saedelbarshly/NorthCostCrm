<?php

namespace App\Http\Controllers\admin\brokerOwnor;

use App\Models\Broker;
use App\Models\Cities;
use App\Models\Installment;
use App\Models\Governorates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class BrokerController extends Controller
{
    public function index()
    {
        $brokers = Broker::orderBy('name','desc')->paginate(25);
        $governorates = Governorates::pluck('name','id')->all();
        $Cities = Cities::pluck('name','id')->all();
        return view('AdminPanel.brokers.index',[
            'active' => 'broker',
            'title' => trans('common.broker'),
            'brokers' => $brokers,
            'governorates' => $governorates,
            'cities' => $Cities,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.broker')
                ]
            ]
        ]);
    }

    public function getZone($id)
    {
        $gov = Governorates::find($id);
        $cities = Cities::where('GovernorateID',$gov->id)->get();
        return $cities;
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $request->except('_token', 'dept');
        $data['UID'] = auth()->user()->id;
        $broker = Broker::create($data);
        if ($broker) {
            return redirect()->back()->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }
    }
    public function update(Request $request,$id)
    {
        $data = $request->except('_token', 'dept', 'duration');
        $broker = Broker::find($id);
        $broker->update($data);
        if($broker)
        {
            return redirect()->back()->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }
    }


    public function delete($id)
    {
        $broker = Broker::find($id);
        $instllment = Installment::where('broker_id',$broker->id)->delete();
        if($broker->delete())
        {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
