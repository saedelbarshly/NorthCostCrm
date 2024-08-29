<?php

namespace App\Http\Controllers\admin\ClientsFollowUps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientSources;
use Response;

class ClientSourcesController extends Controller
{
    public function index()
    {

        if (!userCan('client_sources_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $clientSources = ClientSources::orderBy('name','desc')->paginate(25);
        return view('AdminPanel.clientSources.index',[
            'active' => 'clientSources',
            'title' => trans('common.clientSources'),
            'clientSources' => $clientSources,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.clientSources')
                ]
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $clientSource = ClientSources::create($data);
        if ($clientSource) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function update(Request $request, $clientSourceId)
    {
        $data = $request->except(['_token']);

        $update = ClientSources::find($clientSourceId)->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function delete($clientSourceId)
    {
        $clientSource = ClientSources::find($clientSourceId);
        if ($clientSource->delete()) {
            return Response::json($clientSourceId);
        }
        return Response::json("false");
    }
}
