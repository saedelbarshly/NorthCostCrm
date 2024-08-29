<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientContracts;
use App\Models\Clients;
use App\Models\Units;
use Carbon\Carbon;
use Response;

class ContractsController extends Controller
{
    public function index()
    {
        if (!userCan('contracts_view') && !userCan('contracts_view_branch') && !userCan('contracts_view_mine')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        $contracts = ClientContracts::orderBy('id','desc');

        //for client id
        if (isset($_GET['client_id'])) {
            if ($_GET['client_id'] != 'all') {
                $contracts = $contracts->where('ClientID',$_GET['client_id']);
            }
        }
        //for agent id
        if (isset($_GET['agent_id'])) {
            if ($_GET['agent_id'] != 'all') {
                $contracts = $contracts->where('AgentID',$_GET['agent_id']);
            }
        }
        //for contract status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'all') {
                $contracts = $contracts->where('Status',$_GET['status']);
            }
        }
        //for contract month
        if (isset($_GET['month'])) {
            if ($_GET['month'] != 'all') {
                $contracts = $contracts->where('month',$_GET['month']);
            }
        }
        //for contract year
        if (isset($_GET['year'])) {
            if ($_GET['year'] != 'all') {
                $contracts = $contracts->where('year',$_GET['year']);
            }
        }
        //for contract paymentStatus
        if (isset($_GET['paymentStatus'])) {
            if ($_GET['paymentStatus'] == 'noPayment') {
                $contracts = $contracts->doesntHave('payments');
            }
            if ($_GET['paymentStatus'] == 'partialPayment') {
                $contracts = $contracts->whereColumn('paid','<', 'Total');
            }
            if ($_GET['paymentStatus'] == 'donePayment') {
                $contracts = $contracts->whereColumn('paid','=', 'Total');
            }
        }
        $contracts = $contracts->paginate(25);
        return view('AdminPanel.contracts.index',[
            'active' => 'contracts',
            'title' => trans('common.contracts'),
            'contracts' => $contracts->appends($_GET),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.contracts')
                ]
            ]
        ]);
    }

    public function edit($id)
    {
        if (!userCan('contracts_edit')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        $contract = ClientContracts::find($id);
        return view('AdminPanel.contracts.edit',[
            'active' => 'contracts',
            'title' => trans('common.contracts'),
            'contract' => $contract,
            'breadcrumbs' => [
                [
                    'url' => route('admin.contracts.index'),
                    'text' => trans('common.contracts')
                ],
                [
                    'url' => '',
                    'text' => $contract->name
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        // if (!userCan('contracts_create')) {
        //     return redirect()->back()
        //                         ->with('faild',trans('common.youAreNotAuthorized'));
        // }
        $data = $request->except(['_token']);
        $data['client_id'] = auth()->user()->id;
        $data['UnitID'] = auth()->user()->branch_id;

        $unit = Units::find($data['UnitID']);
        return $unit;
        if ($unit->ClientID == '') {
            $unit->ClientID = $data['client_id'];
            $unit->save();

            $client = Clients::find($data['client_id']);
            $client->position = 'contracts';
            $client->save();

            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function update(Request $request, $id)
    {
        if (!userCan('contracts_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token','service_id','service_price','service_renewal','Files','SafeID','paid']);
        $data['UID'] = auth()->user()->id;
        $data['branch_id'] = auth()->user()->branch_id;
        $data['year'] = date('Y',strtotime($request->contractDate));
        $data['month'] = date('m',strtotime($request->contractDate));
        $MyDateCarbon = Carbon::parse($request->contractDate);
        $MyDateCarbon->addWeekdays($request->contractWorkDays);
        $data['contractEndDate'] = date('Y-m-d',strtotime($MyDateCarbon));

        $update = ClientContracts::find($id)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function delete($id)
    {
        if (!userCan('contracts_delete')) {
            return Response::json("false");
        }
        $contract = ClientContracts::find($id);
        if ($contract->delete()) {
            delete_folder('uploads/contracts/'.$id);
            return Response::json($id);
        }
        return Response::json("false");
    }

    public function deleteAttachment($id,$photo,$X)
    {
        if (!userCan('contracts_delete_attachment')) {
            return Response::json("false");
        }
        $contract = ClientContracts::find($id);
        $Files = [];
        if ($contract->Files != '') {
            $Files = unserialize(base64_decode($contract->Files));
        }
        if (in_array($photo,$Files)) {
            delete_image('uploads/contracts/'.$id , $photo);
            if (($key = array_search($photo, $Files)) !== false) {
                unset($Files[$key]);
            }
            $contract['Files'] = base64_encode(serialize($Files));
            $contract->update();
            return Response::json('photo_'.$X);
        }
        return Response::json("false");
    }

}
