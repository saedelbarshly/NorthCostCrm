<?php

namespace App\Http\Controllers\admin\projectsUnits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProjectCompanies;
use Response;

class CompaniesController extends Controller
{
    //
    public function index()
    {
        if (!userCan('companies_view')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }

        $companies = ProjectCompanies::orderBy('name','asc')->paginate(25);
        return view('AdminPanel.projectsUnits.companies.index',[
            'active' => 'companies',
            'title' => trans('common.companies'),
            'companies' => $companies,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.companies')
                ]
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        if (!userCan('companies_create')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $data = $request->except(['_token']);

        $branch = ProjectCompanies::create($data);
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
        if (!userCan('companies_edit')) {
            return redirect()->back()
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        $branch = ProjectCompanies::find($id);
        $data = $request->except(['_token']);

        $update = ProjectCompanies::find($id)->update($data);
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
        if (!userCan('companies_delete')) {
            return Response::json("false");
        }
        $branch = ProjectCompanies::find($id);
        if ($branch->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }
}
