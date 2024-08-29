<?php

namespace App\Http\Controllers\admin\projectsUnits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Projects;
use App\Models\Cities;
use Response;

class ProjectsController extends Controller
{
    public function index()
    {
        //check if authenticated
        if (!userCan('projects_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //select from DB
        $projects = Projects::orderBy('id','desc');
        //filter by location
        if (isset($_GET['location'])) {
            if ($_GET['location'] != 'all') {
                $projects = $projects->where('location',$_GET['location']);
            }
        }
        //filter by company
        if (isset($_GET['company'])) {
            if ($_GET['company'] != 'all') {
                $projects = $projects->where('company',$_GET['company']);
            }
        }
        //filter by city
        if (isset($_GET['city'])) {
            if ($_GET['city'] != 'all') {
                $projects = $projects->where('CityID',$_GET['city']);
            }
        }
        //filter by name
        if (isset($_GET['name'])) {
            if ($_GET['name'] != '') {
                $projects = $projects->where('name','like','%' . $_GET['name'] . '%');
            }
        }
        $projects = $projects->paginate(50);

        return view('AdminPanel.projectsUnits.projects.index',[
            'active' => 'projects',
            'title' => trans('common.projects'),
            'projects' => $projects,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.projects')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (!userCan('projects_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token','images']);

        $project = Projects::create($data);

        if ($request->images != '') {
            $Files = [];
            if ($request->hasFile('images')) {
                foreach ($request->images as $file) {
                    $Files[] = upload_image_without_resize('projects/'.$project->id , $file );
                }
                $project['images'] = base64_encode(serialize($Files));
                $project->update();
            }
        }
        if ($project) {
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
        if (!userCan('projects_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //select from DB
        $project = Projects::find($id);

        return view('AdminPanel.projectsUnits.projects.view',[
            'active' => 'projects',
            'title' => trans('common.projects'),
            'project' => $project,
            'breadcrumbs' => [
                [
                    'url' => route('admin.projects'),
                    'text' => trans('common.projects')
                ],
                [
                    'url' => '',
                    'text' => trans('common.details').': '.$project->name
                ]
            ]
        ]);
    }

    public function edit($id)
    {
        //check if authenticated
        if (!userCan('projects_edit')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        //select from DB
        $project = Projects::find($id);

        return view('AdminPanel.projectsUnits.projects.edit',[
            'active' => 'projects',
            'title' => trans('common.projects'),
            'project' => $project,
            'breadcrumbs' => [
                [
                    'url' => route('admin.projects'),
                    'text' => trans('common.projects')
                ],
                [
                    'url' => '',
                    'text' => trans('common.details').': '.$project->name
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!userCan('projects_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $project = Projects::find($id);
        $data = $request->except(['_token','images']);

        if ($request->images != '') {
            if ($project->images != '') {
                $Files = unserialize(base64_decode($project->images));
            } else {
                $Files = [];
            }
            if ($request->hasFile('images')) {
                foreach ($request->images as $file) {
                    $Files[] = upload_image_without_resize('projects/'.$id , $file );
                }
                $data['images'] = base64_encode(serialize($Files));
            }
        }

        $update = Projects::find($id)->update($data);
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
        if (!userCan('projects_delete_photo')) {
            return Response::json("false");
        }
        $project = Projects::find($id);
        $Files = [];
        if ($project->images != '') {
            $Files = unserialize(base64_decode($project->images));
        }
        if (in_array($photo,$Files)) {
            delete_image('uploads/projects/'.$id , $photo);
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
        if (!userCan('projects_delete')) {
            return Response::json("false");
        }
        $project = Projects::find($id);
        if ($project->delete()) {
            delete_folder('uploads/projects/'.$id);
            return Response::json($id);
        }
        return Response::json("false");
    }

}
