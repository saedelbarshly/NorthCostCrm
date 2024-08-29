<?php

namespace App\Http\Controllers\admin\accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MoneyTransfeers;
use Response;


class MoneyTransfeersController extends Controller
{

    public function index()
    {
        //check if authenticated
        if (!userCan('money_transfeers_view') && !userCan('money_transfeers_view_branch')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //select from DB
        $transfeers = MoneyTransfeers::orderBy('id','desc');

        //filter by month
        $month = date('m');
        $year = date('Y');
        if (isset($_GET['month']) && isset($_GET['year'])) {
            if ($_GET['month'] != '' && $_GET['year'] != '') {
                $month = $_GET['month'];
                $year = $_GET['year'];
            }
        }
        $transfeers = $transfeers->where('month',$month)->where('year',$year);
        $transfeers = $transfeers->get();

        return view('AdminPanel.accounts.transfeers.index',[
            'active' => 'moneyTransfeers',
            'title' => trans('common.moneyTransfeers'),
            'transfeers' => $transfeers,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.moneyTransfeers')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (!userCan('money_transfeers_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //check if user assigned to branch
        if (auth()->user()->branch == '') {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAssgnedToAnyBranchPleaseContactAdmin'));
        }
        $data = $request->except(['_token','Attachments']);
        $data['month'] = date('m',strtotime($request['date']));
        $data['year'] = date('Y',strtotime($request['date']));
        $data['user_id'] = auth()->user()->id;
        $transfeer = MoneyTransfeers::create($data);
        
        if ($request->Attachments != '') {
            $Files = [];
            if ($request->hasFile('Attachments')) {
                foreach ($request->Attachments as $file) {
                    $Files[] = upload_image_without_resize('transfeers/'.$transfeer->id , $file );
                }
                $transfeer['Attachments'] = base64_encode(serialize($Files));
                $transfeer->update();
            }
        }
        if ($transfeer) {
            return redirect()->route('admin.moneyTransfeers')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function update(Request $request, $id)
    {
        if (!userCan('money_transfeers_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $transfeer = MoneyTransfeers::find($id);
        $data = $request->except(['_token','Attachments']);
        $data['month'] = date('m',strtotime($request['date']));
        $data['year'] = date('Y',strtotime($request['date']));

        if ($request->Attachments != '') {
            if ($transfeer->Attachments != '') {
                $Files = unserialize(base64_decode($transfeer->Attachments));
            } else {
                $Files = [];
            }
            if ($request->hasFile('Attachments')) {
                foreach ($request->Attachments as $file) {
                    $Files[] = upload_image_without_resize('transfeers/'.$id , $file );
                }
                $data['Attachments'] = base64_encode(serialize($Files));
            }
        }

        $update = MoneyTransfeers::find($id)->update($data);
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
        if (!userCan('money_transfeers_delete_photo')) {
            return Response::json("false");
        }
        $transfeer = MoneyTransfeers::find($id);
        $Files = [];
        if ($transfeer->Attachments != '') {
            $Files = unserialize(base64_decode($transfeer->Attachments));
        }
        if (in_array($photo,$Files)) {
            delete_image('uploads/transfeers/'.$id , $photo);
            if (($key = array_search($photo, $Files)) !== false) {
                unset($Files[$key]);
            }
            $transfeer['Attachments'] = base64_encode(serialize($Files));
            $transfeer->update();
            return Response::json('photo_'.$X);
        }
        return Response::json("false");
    }

    public function delete($id)
    {
        if (!userCan('money_transfeers_delete')) {
            return Response::json("false");
        }
        $transfeer = MoneyTransfeers::find($id);
        if ($transfeer->delete()) {
            delete_folder('uploads/transfeers/'.$id);
            return Response::json($id);
        }
        return Response::json("false");
    }
}
