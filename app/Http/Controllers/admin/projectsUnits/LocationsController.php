<?php

namespace App\Http\Controllers\admin\projectsUnits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectLocations;
use Response;

class LocationsController extends Controller
{
    public function index()
    {
        if (!userCan('locations_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        $locations = ProjectLocations::orderBy('name','asc')->paginate(25);
        return view('AdminPanel.projectsUnits.locations.index',[
            'active' => 'locations',
            'title' => trans('common.locations'),
            'locations' => $locations,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.locations')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (!userCan('locations_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token']);

        $branch = ProjectLocations::create($data);
        if ($branch) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function update(Request $request, $id)
    {
        if (!userCan('locations_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $branch = ProjectLocations::find($id);
        $data = $request->except(['_token']);

        $update = ProjectLocations::find($id)->update($data);
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
        if (!userCan('locations_delete')) {
            return Response::json("false");
        }
        $branch = ProjectLocations::find($id);
        if ($branch->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
