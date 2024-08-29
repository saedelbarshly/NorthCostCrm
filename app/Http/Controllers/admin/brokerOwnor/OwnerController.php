<?php

namespace App\Http\Controllers\admin\brokerOwnor;

use App\Models\Owner;
use App\Models\Cities;
use App\Models\Installment;
use App\Models\Governorates;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
class OwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::orderBy('name','desc')->paginate(25);
        $governorates = Governorates::pluck('name','id')->all();
        $Cities = Cities::pluck('name','id')->all();
        return view('AdminPanel.owners.index',[
            'active' => 'owner',
            'title' => trans('common.owner'),
            'owners' => $owners,
            'governorates' => $governorates,
            'cities' => $Cities,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.owner')
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
        $data = $request->except('_token', 'dept', 'duration');
        $data['UID'] = auth()->user()->id;
        $owner = Owner::create($data);
        if ($owner) {
            for ($i = 0; $i < count($request->dept); $i++) {
                if ($request['dept'][$i] != null && $request['duration'][$i] != null) {
                    $instllment = Installment::create([
                        'owner_id' => $owner['id'],
                        'dept' => $request['dept'][$i],
                        'duration' => $request['duration'][$i],
                    ]);
                }
            }
            return redirect()->back()->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }
    }
    public function update(Request $request,$id)
    {
        $data = $request->except('_token', 'dept', 'duration');
        $owner = Owner::find($id);
        $owner->update($data);
        if($owner)
        {
            Installment::where('owner_id',$id)->delete();
            for ($i = 0; $i < count($request->dept); $i++) {
                if ($request['dept'][$i] != null && $request['duration'][$i] != null) {
                    $instllment = Installment::create([
                        'owner_id' => $id,
                        'dept' => $request['dept'][$i],
                        'duration' => $request['duration'][$i],
                    ]);
                }
            }
            return redirect()->back()->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }
    }


    public function delete($id)
    {
        $owner = Owner::find($id);
        $instllment = Installment::where('owner_id',$owner->id)->delete();
        if($owner->delete())
        {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
