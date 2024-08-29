<?php

namespace App\Http\Controllers\admin\ClientsFollowUps;

use Response;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Cities;
use App\Models\Clients;
use App\Models\ClientUnit;
use App\Models\Installment;
use App\Models\Governorates;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Clients\EditClient;
use App\Http\Requests\Clients\CreateClient;
// use Illuminate\Support\Carbon;

class ClientsController extends Controller
{
    public function getZone($id)
    {
        $gov = Governorates::find($id);
        $cities = Cities::where('GovernorateID',$gov->id)->get();
        return $cities;
    }

    public function index()
    {

        if (!userCan('clients_view') && !userCan('clients_view_current') && !userCan('clients_view_mine_only')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $clients = Clients::orderBy('id','desc')->whereIn('position',['sales','contractFollowUp']);



        if (!userCan('clients_view')) {
            if (!userCan('clients_view_branch')) {
                if (!userCan('clients_view_team')) {
                    $teamMembers = [];
                    $teamMembers[] = auth()->user()->id;
                    $myTeam = User::where('status','Active')->where('leader',auth()->user()->id)->get();
                    foreach ($myTeam as $myTeamKey => $myTeamV) {
                        $teamMembers[] = $myTeamV['id'];
                    }
                    $clients = $clients->whereIn('UID',$teamMembers);

                } else {
                    $clients = $clients->where('UID',auth()->user()->id);
                }
            } else {
                $clients = $clients->where('branch_id',auth()->user()->branch_id);
            }
        } else {
            //filter by client
            if (isset($_GET['client_id'])) {
                if ($_GET['client_id'] != 'all') {
                    $clients = $clients->where('client_id',$_GET['client_id']);
                }
            }
            //filter by agent
            if (isset($_GET['employee'])) {
                if ($_GET['employee'] != 'all') {
                    $clients = $clients->where('UID',$_GET['employee']);
                }
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all' && $_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }
        if (isset($_GET['id'])) {
            if ($_GET['id'] != '') {
                $clients = Clients::find($_GET['id']);
                // dd($clients);
            }
        }
        $clients = $clients->paginate(25);
        return view('AdminPanel.clients.index',[
            'active' => 'clients',
            'title' => trans('common.clients'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.clients')
                ]
            ]
        ]);
    }

    public function possibleClients()
    {
        if (!userCan('clients_view') && !userCan('clients_view_call_center')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        if (isset($_GET['position'])) {
            $clients = Clients::whereIn('position',$_GET['position']);
        } else {
            $clients = Clients::where('position','call_center');
        }
        //filter by client
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all') {
                $clients = $clients->where('client_id',$_GET['client_id']);
            }
        }
        //filter by agent
        if (isset($_GET['employee'])) {
            if ($_GET['employee'] != 'all' && $_GET['employee'] != '') {
                $clients = $clients->where('UID',$_GET['employee']);
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '' && $_GET['supportHousing'] != 'all') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all' && $_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }

        if (isset($_GET['id'])) {
            if ($_GET['id'] != '') {
                $clients = $clients->find($_GET['id']);
                // dd($clients);
            }
        }
        $clients = $clients->orderBy('updated_at','desc')->paginate(25);
        return view('AdminPanel.clients.possibleClients',[
            'active' => 'possibleClients',
            'title' => trans('common.possibleClients'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.possibleClients')
                ]
            ]
        ]);
    }

    public function salesManger()
    {
        if (!userCan('clients_view') && !userCan('clients_view_reception')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $clients = Clients::where('position','salesManger');
        //filter by client
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all') {
                $clients = $clients->where('client_id',$_GET['client_id']);
            }
        }
        //filter by agent
        if (isset($_GET['employee'])) {
            if ($_GET['employee'] != 'all' && $_GET['employee'] != '') {
                $clients = $clients->where('UID',$_GET['employee']);
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '' && $_GET['supportHousing'] != 'all') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all' && $_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }

        if (isset($_GET['id'])) {
            if ($_GET['id'] != '') {
                $clients = $clients->find($_GET['id']);
                // dd($clients);
            }
        }

        $clients = $clients->orderBy('updated_at','desc')->paginate(25);
        return view('AdminPanel.clients.salesMangerClients',[
            'active' => 'salesMangerClients',
            'title' => trans('common.salesMangerClients'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.salesMangerClients')
                ]
            ]
        ]);
    }

    public function receptionClients()
    {
        if (!userCan('clients_view') && !userCan('clients_view_reception')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $clients = Clients::where('position','reception');
        //filter by client
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all') {
                $clients = $clients->where('client_id',$_GET['client_id']);
            }
        }
        //filter by agent
        if (isset($_GET['employee'])) {
            if ($_GET['employee'] != 'all' && $_GET['employee'] != '') {
                $clients = $clients->where('UID',$_GET['employee']);
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '' && $_GET['supportHousing'] != 'all') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all' && $_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }
        $clients = $clients->orderBy('updated_at','desc')->paginate(25);
        return view('AdminPanel.clients.receptionClients',[
            'active' => 'receptionClients',
            'title' => trans('common.receptionClients'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.receptionClients')
                ]
            ]
        ]);
    }
    public function clientsContracts()
    {
        if (!userCan('clients_view') && !userCan('clients_view_contracts') && !userCan('contracts_view_mine')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $clients = Clients::where('position','contracts');

        //filter by client
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all') {
                $clients = $clients->where('client_id',$_GET['client_id']);
            }
        }
        //filter by agent
        if (isset($_GET['employee'])) {
            if ($_GET['employee'] != 'all' && $_GET['employee'] != '') {
                $clients = $clients->where('UID',$_GET['employee']);
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '' && $_GET['supportHousing'] != 'all') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all' && $_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }

        if (auth()->user()->hisRole->canDo('contracts_view_mine') && !auth()->user()->hisRole->canDo('clients_view_contracts')) {
            $clients = $clients->where('UID', auth()->user()->id);
        }
        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }

        $clients = $clients->orderBy('updated_at','desc')->paginate(25);
        return view('AdminPanel.clients.index',[
            'active' => 'clientsContracts',
            'title' => trans('common.clientsContracts'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.clientsContracts')
                ]
            ]
        ]);
    }
    public function clientsArchive()
    {
        if (!userCan('clients_view') && !userCan('clients_view_archive')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        if (isset($_GET['position'])) {
            $clients = Clients::whereIn('position',$_GET['position']);
        } else {
            $clients = Clients::where('position','archive');
        }

        if (!userCan('clients_view')) {
            if (!userCan('clients_view_branch')) {
                if (!userCan('clients_view_team')) {
                    $teamMembers = [];
                    $teamMembers[] = auth()->user()->id;
                    $myTeam = User::where('status','Active')->where('leader',auth()->user()->id)->get();
                    foreach ($myTeam as $myTeamKey => $myTeamV) {
                        $teamMembers[] = $myTeamV['id'];
                    }
                    $clients = $clients->whereIn('UID',$teamMembers);
                } else {
                    $clients = $clients->where('UID',auth()->user()->id);
                }
            } else {
                $clients = $clients->where('branch_id',auth()->user()->branch_id);
            }
        } else {
            //filter by client
            if (isset($_GET['client_id'])) {
                if ($_GET['client_id'] != 'all') {
                    $clients = $clients->where('client_id',$_GET['client_id']);
                }
            }
            //filter by agent
            if (isset($_GET['employee'])) {
                if ($_GET['employee'] != 'all') {
                    $clients = $clients->where('UID',$_GET['employee']);
                }
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }

        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }

        //filter by rejictionCause
        if (isset($_GET['rejictionCause'])) {
            if ($_GET['rejictionCause'] != '') {
                $clients = $clients->where('rejictionCause',$_GET['rejictionCause']);
            }
        }
        $clients = $clients->orderBy('updated_at','desc')->paginate(25);
        return view('AdminPanel.clients.archive',[
            'active' => 'clientsArchive',
            'title' => trans('common.clientsArchive'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.clientsArchive')
                ]
            ]
        ]);
    }
    public function changeAgent(Request $request)
    {
        // dd($request);
        $date = Carbon::now()->toDateString();
        $this->validate($request, [
            'clients' => 'required'
        ]);
        foreach ($request['clients'] as $key => $client) {
            $update = Clients::find($client);
            // dd($update);
            if ($update != '') {
                if ($request['AgentID'] != '') {
                    $update->UID = $request['AgentID'];
                }
                // if ($request['position'] != '') {
                //     if($request['position'] == 'call_center')
                //     {
                //         $update->call_center_date = $date;
                //         $update->sales_date = null;
                //         $update->sales_manger_date = null;
                //     }elseif($request['position'] == 'sales')
                //     {
                //         $update->sales_date = $date;
                //         $update->call_center_date = null;
                //         $update->sales_manger_date = null;
                //     }elseif($request['position'] == 'salesManger')
                //     {
                //         $update->sales_manger_date = $date;
                //         $update->call_center_date = null;
                //         $update->sales_date = null;
                //     }
                //     $update->position = $request['position'];
                // }
                $update->update();
                if ($request['position'] == 'delete') {
                    $update->delete();
                }
            }
        }
        return redirect()->back()
                        ->with('success',trans('common.successMessageText'));
    }



    public function store(CreateClient $request)
    {
        dd($request);
        $date = Carbon::now()->toDateString();
        if (!userCan('clients_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token','dept', 'duration']);
        // $data['UID'] = auth()->user()->id;
        dd($data);
        $client = Clients::create($data);
        if ($client) {
            for ($i = 0; $i < count($request->dept); $i++) {
                if ($request['dept'][$i] != null && $request['duration'][$i] != null) {
                    $instllment = Installment::create([
                        'client_id' => $client['id'],
                        'dept' => $request['dept'][$i],
                        'duration' => $request['duration'][$i],
                    ]);
                }
            }
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function storeExcelClient(Request $request)
    {
        $this->validate($request, [
            'excel' => 'required|mimes:xlsx'
        ]);
        Excel::import(new ClientsImport(), request()->file('excel'));

        $request->session()->put('PopSuccess', trans('Site.SavedSuccessfully'));
        return back();
    }

    public function update(EditClient $request, $id)
    {
        if (!userCan('clients_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token','dept', 'duration']);
        // return $data;
        $client = Clients::find($id);
        $update = Clients::find($id)->update($data);

        if ($update) {

            for ($i = 0; $i < count($request->dept); $i++) {
                if ($request['dept'][$i] != null && $request['duration'][$i] != null)
                {
                    $importance  [] = [
                        'client_id' => $id,
                        'dept' => $request['dept'][$i],
                        'duration' => $request['duration'][$i],
                    ];
                }

                if ($request['dept'][$i] != null && $request['duration'][$i] != null) {
                    $client->installments()->delete();
                    $instllment = Installment::create([
                        'client_id' => $id,
                        'dept' => $request['dept'][$i],
                        'duration' => $request['duration'][$i],
                    ]);
                }
            }

            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function search()
    {

        $clients = Clients::orderBy('id','desc')->whereNot('position','archive');
        if (isset($_GET['position'])) {
            $clients = Clients::whereIn('position',$_GET['position']);
        }
        //filter by client
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all') {
                $clients = $clients->where('client_id',$_GET['client_id']);
            }
        }
        //filter by agent
        if (isset($_GET['employee'])) {
            if ($_GET['employee'] != 'all' && $_GET['employee'] != '') {
                $clients = $clients->where('UID',$_GET['employee']);
            }
        }
        //filter by cellphone
        if (isset($_GET['cellphone'])) {
            if ($_GET['cellphone'] != '') {
                $clients = $clients->where('cellphone',$_GET['cellphone']);
            }
        }
        //filter by supportHousing
        if (isset($_GET['supportHousing'])) {
            if ($_GET['supportHousing'] != '' && $_GET['supportHousing'] != 'all') {
                $clients = $clients->where('supportHousing',$_GET['supportHousing']);
            }
        }
        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all' && $_GET['status'] != '') {
                $clients = $clients->where('status',$_GET['status']);
            }
        }
        //filter by time
        if (isset($_GET['time'])) {
            if ($_GET['time'] == 'today') {
                $clients = $clients->whereDate('created_at', Carbon::today());
            }
            if ($_GET['time'] == 'month') {
                $clients = $clients->whereMonth('created_at', Carbon::now());
            }
        }
        //filter by Name
        if (isset($_GET['Name'])) {
            if ($_GET['Name'] != '') {
                $clients = $clients->where('Name','like','%'.$_GET['Name'].'%');
            }
        }
        if (isset($_GET['identity'])) {
            if ($_GET['identity'] != '') {
                $clients = $clients->where('identity',$_GET['identity']);
            }
        }
        if (isset($_GET['source_id'])) {
            if ($_GET['source_id'] != '') {
                $clients = $clients->where('referral',$_GET['source_id']);
            }
        }

        if (isset($_GET['id'])) {
            if ($_GET['id'] != '') {
                $clients = $clients->find($_GET['id']);
                // dd($clients);
            }
        }
        $clients = $clients->orderBy('updated_at','desc')->paginate(25);
        return view('AdminPanel.search',[
            // 'active' => 'possibleClients',
            'title' => trans('common.search'),
            'clients' => $clients->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.client')
                ]
            ]
        ]);
    }

    public function udpateSupporting($id)
    {
        $client = Clients::find($id);
        if($client->supportHousing == "worthy")
        {
            $client->update([
                'supportHousing' => 'not_worthy'
            ]);
        }else{
            $client->update([
                'supportHousing' => 'worthy'
            ]);
        }
        if ($client) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function delete($id)
    {
        if (!userCan('clients_delete')) {
            return Response::json("false");
        }
        $client = Clients::find($id);
        if ($client->delete()) {
            $unit_client = ClientUnit::where('client_id',$id)->count();
            if ($unit_client > 0) {
                $unit_client = ClientUnit::where('client_id',$id)->delete();
            }
            return Response::json($id);
        }
        return Response::json("false");
    }


    public function exportExcel()
    {
        $cellphone = isset($_GET['cellphone']) ? $_GET['cellphone'] : '';
        $position = isset($_GET['position']) ? $_GET['position'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $Name = isset($_GET['Name']) ? $_GET['Name'] : '';
        $identity = isset($_GET['identity']) ? $_GET['identity'] : '';
        $source_id = isset($_GET['source_id']) ? $_GET['source_id'] : '';

        //filter by time
        // if (isset($_GET['time'])) {
        //     if ($_GET['time'] == 'today') {
        //         $clients = $clients->whereDate('created_at', Carbon::today());
        //     }
        //     if ($_GET['time'] == 'month') {
        //         $clients = $clients->whereMonth('created_at', Carbon::now());
        //     }
        // }
        // $cellphone = isset($_GET['cellphone']) ?? '';
        return Excel::download(new ClientsExport($position, $cellphone, $status, $Name, $identity, $source_id), 'clients-'.date("Y-m-d").'.xlsx');
    }
}
