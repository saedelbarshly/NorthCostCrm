<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Expenses;
use App\Models\Revenues;
use App\Models\ClientUnit;
use Illuminate\Http\Request;
use App\Models\ClientFollowUps;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ReportsController extends Controller
{
    //
    public function rejectionCauses()
    {
        //check if authenticated
        if (!userCan('reports_rejectionCauses')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        return view('AdminPanel.reports.rejectionCauses',[
            'active' => 'rejectionCauses',
            'title' => trans('common.rejectionCauses'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.rejectionCauses')
                ]
            ]
        ]);
    }
    public function teamPerformance()
    {
        //check if authenticated
        if (!userCan('reports_teamPerformance')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        return view('AdminPanel.reports.teamPerformance',[
            'active' => 'teamPerformance',
            'title' => trans('common.teamPerformance'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.reports')
                ],
                [
                    'url' => '',
                    'text' => trans('common.teamPerformance')
                ]
            ]
        ]);
    }
    public function clients()
    {
        //check if authenticated
        if (!userCan('reports_clients')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        return view('AdminPanel.reports.clients',[
            'active' => 'reports_clients',
            'title' => trans('common.clients'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.reports')
                ],
                [
                    'url' => '',
                    'text' => trans('common.clients')
                ]
            ]
        ]);
    }
    public function units()
    {
        //check if authenticated
        if (!userCan('reports_units')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        return view('AdminPanel.reports.units',[
            'active' => 'reports_units',
            'title' => trans('common.units'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.reports')
                ],
                [
                    'url' => '',
                    'text' => trans('common.units')
                ]
            ]
        ]);
    }
    public function accountsReport()
    {
        //check if authenticated
        if (!userCan('reports_accounts_view') && !userCan('reports_accounts_view_branch')) {
            return redirect()->route('admin.index')
                                ->with('faild',trans('common.youAreNotAuthorized'));
        }
        return view('AdminPanel.reports.accounts',[
            'active' => 'accountsReport',
            'title' => trans('common.accountsReport'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.accountsReport')
                ]
            ]
        ]);
    }

    public function clientTodayReport()
    {
        $today = date('Y-m-d');
        $teamMembers = [];
        if (isset($_GET['status'])) {
            if ($_GET['status'] != '') {
                $status = $_GET['status'];
            }
        }
        if (isset($_GET['users'])) {
            if ($_GET['users'] != '') {
                $teamMembers = $_GET['users'];
            }
        }
        $followUps = ClientFollowUps::whereDate('created_at', $today)
        ->whereIn('UID', $teamMembers);
        $booking = ClientUnit::where('status', 'booking')->whereDate('created_at', $today)->whereIn('agent_id', $teamMembers);
        $contract = ClientUnit::where('status', 'contract')
        ->whereDate('created_at', $today)
            ->whereIn('agent_id', $teamMembers);
        if($status == 'Call')
        {
           $clients = $followUps->where('contactingType', 'phone_call')->orderBy('id','desc')->paginate(25);

        }elseif($status == 'UnitVisit'){
            $clients = $followUps->where('contactingType', 'unit_visit')->orderBy('id','desc')->paginate(25);
        }elseif($status == 'Booking'){
            $clients = $booking->orderBy('id','desc')->paginate(25);
        }elseif($status = 'Contract'){
            $clients = $contract->orderBy('id','desc')->paginate(25);
        }
        return view('AdminPanel.reports.index', [
            'active' => 'teamPerformance',
            'clients' => $clients,
            'title' => trans('common.teamPerformance'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.teamPerformance')
                ]
            ]
        ]);
    }

    public function clientMonthReport()
    {
        $today = date('Y-m-d');
        $teamMembers = [];
        if (isset($_GET['status'])) {
            if ($_GET['status'] != '') {
                $status = $_GET['status'];
            }
        }
        if (isset($_GET['users'])) {
            if ($_GET['users'] != '') {
                $teamMembers = $_GET['users'];
            }
        }
        if (isset($_GET['month'])) {
            if ($_GET['month'] != '') {
                $month = $_GET['month'];
            }
        }else{
            $month = date('m');
        }
        if (isset($_GET['year'])) {
            if ($_GET['year'] != '') {
                $year = $_GET['year'];
            }
        }else{
            $year = date('Y');
        }
        $followUps = ClientFollowUps::where('month', $month)
        ->where('year', $year)
        ->whereIn('UID', $teamMembers);
        if ($status == 'Call')
        {
            $clients = $followUps->where('contactingType', 'phone_call')->orderBy('id','desc')->paginate(25);
        } elseif ($status == 'UnitVisit')
        {
            $clients = $followUps->where('contactingType', 'unit_visit')->orderBy('id','desc')->paginate(25);
        } elseif ($status == 'Booking')
        {
            $clients = ClientUnit::where('status', 'booking')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereIn('agent_id', $teamMembers)->orderBy('id','desc')->paginate(25);
        } elseif ($status = 'Contract')
        {
            $clients = ClientUnit::where('status', 'contract')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereIn('agent_id', $teamMembers)->orderBy('id','desc')->paginate(25);
        }
        return view('AdminPanel.reports.index', [
            'active' => 'teamPerformance',
            'clients' => $clients,
            'title' => trans('common.teamPerformance'),
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.teamPerformance')
                ]
            ]
        ]);
    }

}
