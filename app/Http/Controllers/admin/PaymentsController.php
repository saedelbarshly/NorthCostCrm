<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientContracts;
use App\Models\SafesBanks;
use App\Models\Revenues;
use Response;

class PaymentsController extends Controller
{
    public function index($contract_id)
    {
        //check if authenticated
        if (!userCan('payments_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        $contract = ClientContracts::find($contract_id);

        //select from DB
        $revenues = Revenues::where('contract_id',$contract_id)->orderBy('id','desc')->get();

        return view('AdminPanel.payments.index',[
            'active' => 'revenues',
            'title' => trans('common.payments'),
            'contract' => $contract,
            'payments' => $revenues,
            'breadcrumbs' => [
                [
                    'url' => route('admin.contracts.index'),
                    'text' => trans('common.contracts')
                ],
                [
                    'url' => '',
                    'text' => trans('common.payments').': '.$contract->name
                ]
            ]
        ]);
    }

    public function store(Request $request, $contract_id)
    {
        if (!userCan('payments_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //check if user assigned to branch
        if (auth()->user()->branch == '') {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAssgnedToAnyBranchPleaseContactAdmin'));
        }
        $data = $request->except(['_token','Attachments']);
        $data['UID'] = auth()->user()->id;
        $data['Type'] = 'contract';
        $data['contract_id'] = $contract_id;
        $data['DateStr'] = strtotime($request['Date']);
        $data['month'] = date('m',strtotime($request['Date']));
        $data['year'] = date('Y',strtotime($request['Date']));
        $safe = SafesBanks::find($request['safe_id']);
        $data['branch_id'] = $safe->branch_id;

        $revenue = Revenues::create($data);

        if ($request->Attachments != '') {
            $Files = [];
            if ($request->hasFile('Attachments')) {
                foreach ($request->Attachments as $file) {
                    $Files[] = upload_image_without_resize('revenues/'.$revenue->id , $file );
                }
                $revenue['Files'] = base64_encode(serialize($Files));
                $revenue->update();
            }
        }
        if ($revenue) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function update(Request $request, $id)
    {
        if (!userCan('payments_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $revenue = Revenues::find($id);
        $data = $request->except(['_token','Attachments']);
        $data['DateStr'] = strtotime($request['Date']);
        $data['month'] = date('m',strtotime($request['Date']));
        $data['year'] = date('Y',strtotime($request['Date']));
        $data['UID'] = auth()->user()->id;

        $safe = SafesBanks::find($request['safe_id']);
        $data['branch_id'] = $safe->branch_id;

        if ($request->Attachments != '') {
            if ($revenue->Files != '') {
                $Files = unserialize(base64_decode($revenue->Files));
            } else {
                $Files = [];
            }
            if ($request->hasFile('Attachments')) {
                foreach ($request->Attachments as $file) {
                    $Files[] = upload_image_without_resize('revenues/'.$id , $file );
                }
                $data['Attachments'] = base64_encode(serialize($Files));
            }
        }

        $update = Revenues::find($id)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function deletePhoto($id,$photo,$X)
    {
        if (!userCan('payments_delete_photo')) {
            return Response::json("false");
        }
        $revenue = Revenues::find($id);
        $Files = [];
        if ($revenue->Files != '') {
            $Files = unserialize(base64_decode($revenue->Files));
        }
        if (in_array($photo,$Files)) {
            delete_image('uploads/revenues/'.$id , $photo);
            if (($key = array_search($photo, $Files)) !== false) {
                unset($Files[$key]);
            }
            $revenue['Files'] = base64_encode(serialize($Files));
            $revenue->update();
            return Response::json('photo_'.$X);
        }
        return Response::json("false");
    }

    public function delete($id)
    {
        if (!userCan('payments_delete')) {
            return Response::json("false");
        }
        $revenue = Revenues::find($id);
        if ($revenue->delete()) {
            delete_folder('uploads/revenues/'.$id);
            return Response::json($id);
        }
        return Response::json("false");
    }
}
