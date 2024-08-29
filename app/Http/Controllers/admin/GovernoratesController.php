<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Countries;
use App\Models\Governorates;
use Response;

class GovernoratesController extends Controller
{
    public function index()
    {
        $governorates = Governorates::orderBy('name','desc')->paginate(25);
        return view('AdminPanel.governorates.index',[
            'active' => 'governorates',
            'title' => trans('common.governorates'),
            'governorates' => $governorates,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.governorates')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $governorate = Governorates::create($data);
        if ($governorate) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function update(Request $request, $governorateId)
    {
        $data = $request->except(['_token']);

        $update = Governorates::find($governorateId)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function delete($governorateId)
    {
        $governorate = Governorates::find($governorateId);
        if ($governorate->delete()) {
            return Response::json($governorateId);
        }
        return Response::json("false");
    }
}
