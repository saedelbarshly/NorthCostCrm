<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\roles;
use App\Models\permissions;
use Response;

class RolesController extends Controller
{
    //
    public function index()
    {
        $roles = roles::orderBy('id','desc')->paginate(25);
        return view('AdminPanel.roles.index',[     
            'active' => 'roles',
            'title' => trans('common.Roles'),
            'roles' => $roles,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.Roles')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token','permissions']);
        if ($request->permissions == '') {
            return redirect()->back()
                            ->with('faild',trans('common.youHaveToAssignOnePermissionAtLeast'));
        }
        $role = roles::create($data);
        if ($role) {
            foreach ($request->permissions as $value) {
                $role->permissions()->attach($value);
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
        $data = $request->except(['_token','permissions']);
        if ($request->permissions == '') {
            return redirect()->back()
                            ->with('faild',trans('common.youHaveToAssignOnePermissionAtLeast'));
        }
        $role = roles::find($id);
        $role->permissions()->detach();
        $role->update($data);
        foreach ($request->permissions as $value) {
            $role->permissions()->attach($value);
        }
        if ($role) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function delete($id)
    {
        $role = roles::find($id);
        $role->permissions()->detach();
        if ($role->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }




    public function CreatePermission(Request $request)
    {
        $data = $request->except(['_token']);
        $permission = permissions::create($data);
        if ($permission) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }


}
