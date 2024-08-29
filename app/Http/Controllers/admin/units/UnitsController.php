<?php

namespace App\Http\Controllers\admin\units;

use Response;
use App\Models\Owner;
use App\Models\Units;
use App\Models\Broker;
use App\Models\Cities;
use App\Models\Accessory;
use App\Models\ClientUnit;
use App\Imports\UnitsImport;
use App\Models\Governorates;
use Illuminate\Http\Request;
use App\Models\UnitAccessory;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class UnitsController extends Controller
{

    public function getZone($id)
    {
        $gov = Governorates::find($id);
        $cities = Cities::where('GovernorateID',$gov->id)->get();
        return $cities;
    }
    public function index()
    {
        //check if authenticated
        if (!userCan('units_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //select from DB
        $govs = Governorates::pluck('name','id')->all();
        $cities = Cities::pluck('name','id')->all();
        $units = Units::orderBy('id','desc');

        //filter by status
        if (isset($_GET['status'])) {
            if ($_GET['status'] != 'available') {
                $ids = ClientUnit::where('status',$_GET['status'])->pluck('unit_id')->toArray();
                $units = $units->whereIn('id',$ids);
            } else {
                $ids = ClientUnit::pluck('unit_id')->toArray();
                $units = $units->whereNotIn('id',$ids);
            }
            if (count($ids) > 0) {
                foreach ($ids as $id) {
                    if (Units::find($id) == '') {
                        ClientUnit::where('unit_id',$id)->delete();
                    }
                }
                $the_ids = ClientUnit::whereIn('unit_id',$ids)->delete();
            }
        }
        //filter by project
        if (isset($_GET['owner_id'])) {
            if ($_GET['owner_id'] != '') {
                $units = $units->where('owner_id',$_GET['owner_id']);
            }
        }
        if (isset($_GET['broker_id'])) {
            if ($_GET['broker_id'] != '') {
                $units = $units->where('broker_id',$_GET['broker_id']);
            }
        }
        if (isset($_GET['type_id'])) {
            if ($_GET['type_id'] != '') {
                $units = $units->where('type_id',$_GET['type_id']);
            }
        }
        if (isset($_GET['governorate_id'])) {
            if ($_GET['governorate_id'] != '') {
                $units = $units->where('governorate_id',$_GET['governorate_id']);
            }
        }
        if (isset($_GET['city_id'])) {
            if ($_GET['city_id'] != '') {
                $units = $units->where('city_id',$_GET['city_id']);
            }
        }
        //filter by name
        // if (isset($_GET['name'])) {
        //     if ($_GET['name'] != '') {
        //         $units = $units->where('name','like','%' . $_GET['name'] . '%');
        //     }
        // }
        $units = $units->paginate(50);

        return view('AdminPanel.units.unit.index',[
            'active' => 'units',
            'title' => trans('common.units'),
            'units' => $units,
            'govs' => $govs,
            'cities' => $cities,

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
        if (!userCan('units_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token','images','accessories']);
        $data['UID'] = auth()->user()->id;
        $unit = units::create($data);
        foreach($request->accessories as $accessory)
        {
            $unitAccessory = UnitAccessory::create([
                'accessory_id' => $accessory,
                'unit_id' => $unit->id,
            ]);
        }

        if ($request->images != '') {
            $Files = [];
            if ($request->hasFile('images')) {
                foreach ($request->images as $file) {
                    $Files[] = upload_image_without_resize('units/'.$unit->id , $file );
                }
                $unit['files'] = base64_encode(serialize($Files));
                $unit->update();
            }
        }
        if ($unit) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function view($id)
    {
        //check if authenticated
        if (!userCan('units_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //select from DB
        $accessories = Accessory::get();
        $unitAccessories = UnitAccessory::where('unit_id',$id)->get();
        $unit = units::find($id);

        return view('AdminPanel.units.unit.view',[
            'active' => 'units',
            'title' => trans('common.units'),
            'unit' => $unit,
            'accessories' => $accessories,
            'unitAccessories' => $unitAccessories,
            'breadcrumbs' => [
                [
                    'url' => route('admin.units'),
                    'text' => trans('common.units')
                ],
                [
                    'url' => '',
                    'text' => trans('common.details').': '.$unit->name
                ]
            ]
        ]);
    }

    public function edit($id)
    {
        //check if authenticated
        if (!userCan('units_edit')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        //select from DB
        $unit = units::find($id);
        $govs = Governorates::pluck('name','id')->all();
        $cities = Cities::pluck('name','id')->all();
        $accessories = Accessory::get();
        $unitAccessories = UnitAccessory::where('unit_id',$id)->get();  
        return view('AdminPanel.units.unit.edit',[
            'active' => 'units',
            'title' => trans('common.units'),
            'unit' => $unit,
            'govs' => $govs,
            'cities' => $cities,
            'accessories' => $accessories,
            'unitAccessories' => $unitAccessories,
            'breadcrumbs' => [
                [
                    'url' => route('admin.units'),
                    'text' => trans('common.units')
                ],
                [
                    'url' => '',
                    'text' => trans('common.details').': '.$unit->name
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!userCan('units_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $project = units::find($id);

        $data = $request->except(['_token','files','accessories']);
        if ($request->images != '') {
            if ($project->images != '') {
                $Files = unserialize(base64_decode($project->images));
            } else {
                $Files = [];
            }
            if ($request->hasFile('images')) {
                foreach ($request->images as $file) {
                    $Files[] = upload_image_without_resize('units/'.$id , $file );
                }
                $data['images'] = base64_encode(serialize($Files));
            }
        }

        $update = units::find($id)->update($data);
        $unitAccessories = UnitAccessory::where('unit_id',$id)->delete();
        foreach($request->accessories as $accessory)
        {
            $unitAccessory = UnitAccessory::create([
                'accessory_id' => $accessory,
                'unit_id' => $id,
            ]);
        }
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
        if (!userCan('units_delete_photo')) {
            return Response::json("false");
        }
        $project = units::find($id);
        $Files = [];
        if ($project->images != '') {
            $Files = unserialize(base64_decode($project->images));
        }
        if (in_array($photo,$Files)) {
            delete_image('uploads/units/'.$id , $photo);
            if (($key = array_search($photo, $Files)) !== false) {
                unset($Files[$key]);
            }
            $project['images'] = base64_encode(serialize($Files));
            $project->update();
            return Response::json('photo_'.$X);
        }
        return Response::json("false");
    }

    public function delete($id)
    {
        if (!userCan('units_delete')) {
            return Response::json("false");
        }
        $unit = units::find($id);
        if ($unit->delete()) {
            $unit_client = ClientUnit::where('unit_id',$id)->count();
            if ($unit_client > 0) {
                $unit_client = ClientUnit::where('unit_id',$id)->delete();
            }
            delete_folder('uploads/units/'.$id);
            return Response::json($id);
        }
        return Response::json("false");
    }

    public function storeExcelUnit(Request $request)
    {
        $this->validate($request, [
            'excel' => 'required|mimes:xlsx'
        ]);
        $project_id = '';
        if (isset($request->project_id)) {
            $project_id = $request->project_id;
        }
        Excel::import(new UnitsImport($project_id), request()->file('excel'));

        $request->session()->put('PopSuccess', trans('Site.SavedSuccessfully'));
        return back();
    }

}
