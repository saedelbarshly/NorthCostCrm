<?php

namespace App\Http\Controllers\admin\units;

use App\Http\Controllers\Controller;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Response;
class UniteTypeController extends Controller
{
    public function index()
    {
        $unitsType = UnitType::all();
        return view('AdminPanel.units.unitType',[
            'active' => 'unitType',
            'title' => trans('common.units'),
            'unitsType' => $unitsType,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.units')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $unitType = UnitType::create($data);
        if($unitType)
        {
            return redirect()->back()->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }
    }

    public function update(Request $request,$id)
    {
        $unitType = UnitType::find($id);
        $data = $request->except(['_token']);
        $unitType->update($data);
        if($unitType)
        {
            return redirect()->back()->with('success', trans('common.successMessageText'));
        } else {
            return redirect()->back()
                ->with('faild', trans('common.faildMessageText'));
        }

    }

    public function delete($id)
    {
        $unitType = UnitType::find($id);
        if($unitType->delete())
        {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
