<?php

namespace App\Http\Controllers\admin\ClientsFollowUps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clients;
use App\Models\ClientFollowUps;
use App\Models\FollowUpService;
use App\Models\User;
use Response;

class FollowUpsController extends Controller
{
    public function index()
    {
        if (!userCan('followups_view') && !userCan('followups_view_team') && !userCan('followups_view_mine_only')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $followups = ClientFollowUps::orderBy('id','desc');
        //if not allowed to view all just return his client employees
        if (!userCan('followups_view')) {
            if (userCan('followups_view_team')) {
                $teamMembers = [];
                $teamMembers[] = auth()->user()->id;
                $myTeam = User::where('status','Active')->where('leader',auth()->user()->id)->get();
                foreach ($myTeam as $myTeamKey => $myTeamV) {
                    $teamMembers[] = $myTeamV['id'];
                }
                $followups = $followups->whereIn('UID',$teamMembers);
            } else {
                $followups = $followups->where('UID',auth()->user()->id);
            }
        }
        //filter by client
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all' && $_GET['client_id'] != '') {
                $followups = $followups->where('ClientID',$_GET['client_id']);
            }
        }
        //filter by agent
        if (isset($_GET['AgentID'])) {
            if ($_GET['AgentID'] != 'all') {
                $followups = $followups->where('UID',$_GET['AgentID']);
            }
        }
        $followups = $followups->paginate(25);
        return view('AdminPanel.followups.index',[
            'active' => 'followups',
            'title' => trans('common.followups'),
            'followups' => $followups,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.followups')
                ]
            ]
        ]);
    }

    public function nextFollowups()
    {
        $followups = ClientFollowUps::orderBy('contactingDateTimeStr','asc');
        //if not allowed to view all just return his client employees
        if (!userCan('followups_view')) {
            if (userCan('followups_view_team')) {
                $teamMembers = [];
                $teamMembers[] = auth()->user()->id;
                $myTeam = User::where('status','Active')->where('leader',auth()->user()->id)->get();
                foreach ($myTeam as $myTeamKey => $myTeamV) {
                    $teamMembers[] = $myTeamV['id'];
                }
                $followups = $followups->whereIn('UID',$teamMembers);
            } else {
                $followups = $followups->where('UID',auth()->user()->id);
            }
        } else {
            //filter by client
            if (isset($_GET['client_id'])) {
                if ($_GET['client_id'] != 'all') {
                    $followups = $followups->where('ClientID',$_GET['client_id']);
                }
            }
            //filter by agent
            if (isset($_GET['AgentID'])) {
                if ($_GET['AgentID'] != 'all') {
                    $followups = $followups->where('UID',$_GET['AgentID']);
                }
            }
        }
        if (isset($_GET['contactingType'])) {
            if ($_GET['contactingType'] != 'all') {
                $followups = $followups->where('contactingType',$_GET['contactingType']);
            }
        } else {
            $followups = $followups->where('status','pinding');
        }
        $followups = $followups->paginate(25);
        return view('AdminPanel.followups.index',[
            'active' => 'nextFollowups',
            'title' => trans('common.nextFollowups'),
            'followups' => $followups,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.nextFollowups')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (!userCan('followups_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        // dd($request->ClientID);
        $data = $request->except(['_token','nextFollowUpType','service_id','nextContactingType']);
        $data['UID'] = auth()->user()->id;
        $data['contactingDateTime'] = date('Y-m-d');
        $data['contactingDateTimeStr'] = strtotime(date('Y-m-d'));
        $data['month'] = date('m',strtotime(date('Y-m-d')));
        $data['year'] = date('Y',strtotime(date('Y-m-d')));
        // if ($request['nextContactingDateTime'] != '') {
        //     $data['nextContactingDateTime'] = date('Y',strtotime($request['nextContactingDateTime']));
            $data['nextContactingDateTimeStr'] = strtotime($request['nextContactingDateTime']);
        // }
        if($request->status == 'checkout_reject')
        {
            $client = Clients::find($request->ClientID);
            $client->update([
                'position' => 'archive'
            ]);
        }
        $data['status'] = $request['status'];

        $followup = ClientFollowUps::create($data);
        // dd($followup);
        if ($followup) {
            if ($request->service_id != '') {
                if (count($request->service_id) > 0) {
                    foreach ($request->service_id as $value) {
                        if ($value != '') {
                            $service = FollowUpService::create([
                                'UID' => auth()->user()->id,
                                'client_id' => $followup->client->id,
                                'followup_id' => $followup->id,
                                'service_id' => $value
                            ]);
                        }
                    }
                }
            }

            if ($request['nextContactingDateTime'] != '') {
                $next_followup = ClientFollowUps::create([
                    'UID' => auth()->user()->id,
                    'contactingDateTime' => $request['nextContactingDateTime'],
                    'contactingDateTimeStr' => strtotime($request['nextContactingDateTime']),
                    'nextContactingTime' => $request['nextContactingTime'],
                    'month' => date('m',strtotime($request['nextContactingDateTime'])),
                    'year' => date('Y',strtotime($request['nextContactingDateTime'])),
                    'ClientID' => $request['ClientID'],
                    'status' => 'pinding',
                    'contactingType' => $request['nextContactingType']
                ]);
            }
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function update(Request $request,$id)
    {
        if (!userCan('followups_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        $followup = ClientFollowUps::find($id)->update([
            'notes'=>$request['notes'],
            'offerDetails'=>$request['offerDetails'],
            'status'=>'done'
        ]);
        $followup = ClientFollowUps::find($id);
        $followup->client->update(['status'=>$request['status']]);
        if ($followup) {
            if ($request['nextContactingDateTime'] != '') {
                $next_followup = ClientFollowUps::create([
                    'UID' => $followup->UID,
                    'contactingDateTime' => $request['nextContactingDateTime'],
                    'contactingDateTimeStr' => strtotime($request['nextContactingDateTime']),
                    'month' => date('m',strtotime($request['nextContactingDateTime'])),
                    'year' => date('Y',strtotime($request['nextContactingDateTime'])),
                    'ClientID' => $followup['ClientID'],
                    'status' => 'pinding',
                    'whoIsContacting' => 'Company',
                    'contactingType' => $request['nextFollowUpType']
                ]);
            }
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function delete($id)
    {
        if (!userCan('followups_delete')) {
            return Response::json("false");
        }
        $followup = ClientFollowUps::find($id);
        if ($followup->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
