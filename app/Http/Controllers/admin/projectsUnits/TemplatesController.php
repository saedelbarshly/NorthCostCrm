<?php

namespace App\Http\Controllers\admin\projectsUnits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Templates;
use Response;

class TemplatesController extends Controller
{
    public function index()
    {
        if (!userCan('client_sources_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $templates = Templates::orderBy('id','desc')->paginate(25);
        return view('AdminPanel.templates.index',[
            'active' => 'templates',
            'title' => trans('common.templates'),
            'templates' => $templates,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.templates')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $Template = Templates::create($data);
        if ($Template) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function update(Request $request, $TemplateId)
    {
        $data = $request->except(['_token']);

        $update = Templates::find($TemplateId)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
        
    }

    public function delete($TemplateId)
    {
        $Template = Templates::find($TemplateId);
        if ($Template->delete()) {
            return Response::json($TemplateId);
        }
        return Response::json("false");
    }
}
