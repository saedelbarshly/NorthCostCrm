<?php
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;

function daysForChart($month = null, $year = null)
{
    $thisMonthDaysChart = '';
    $thisMonthDaysCount = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    for ($i=1; $i <= $thisMonthDaysCount; $i++) {
        $thisMonthDaysChart .= $i;
        if ($i<$thisMonthDaysCount) {
            $thisMonthDaysChart .= ',';
        }
    }
    // dd($thisMonthDaysChart);
    return $thisMonthDaysChart;
}

// function todayForChart()
// {
//     $thisDayChart = '';
//     $thisMonthDaysCount = cal_days_in_month(CAL_GREGORIAN,$month,$year);
//     for ($i=1; $i <= $thisMonthDaysCount; $i++) {
//         $thisMonthDaysChart .= $i;
//         if ($i<$thisMonthDaysCount) {
//             $thisMonthDaysChart .= ',';
//         }
//     }
//     return $thisMonthDaysChart;
// }

function monthsForThisYear($year = null)
{
    $monthsArray = [];
    for ($i=1; $i <= 12; $i++) {
        $thisMonth = str_pad($i, 2, '0', STR_PAD_LEFT);
        if ($year == date('Y')) {
            if ($thisMonth <= date('m')) {
                $monthsArray[] = $thisMonth;
            }
        } else {
            $monthsArray[] = $thisMonth;
        }

    }
    return $monthsArray;
}
function allMonthsArray($year = null)
{
    $StartDate = strtotime("Dec 2020");
    $StopDate = strtotime(date('M Y'));
    $current = $StartDate;
    $ret = array();

    while( $current<$StopDate ){

        $next = date('Y-M-01', $current) . "+1 month";
        $current = strtotime($next);
        $ret[] = date('Y-M-01', $current);
    }

    return array_reverse($ret);
}


function expensesForChart($branch = 'all', $month = null, $year = null)
{
    $ExpensesChart = '';
    $thisMonthDaysCount = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    for ($i=1; $i <= $thisMonthDaysCount; $i++) {
        $day = $year.'-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT);
        if ($branch == 'all') {
            $dayExpenses = App\Models\Expenses::whereNotIn('Type',['transfeerToAnother'])->where('ExpenseDate',$day)->sum('Expense');
        } else {
            $dayExpenses = App\Models\Expenses::whereNotIn('Type',['transfeerToAnother'])->where('branch_id',$branch)->where('ExpenseDate',$day)->sum('Expense');
        }
        $ExpensesChart .= $dayExpenses;
        if ($i<$thisMonthDaysCount) {
            $ExpensesChart .= ',';
        }
    }
    return $ExpensesChart;
}

function revenueForChart($branch = 'all',$month = null, $year = null)
{
    $RevenueChart = '';
    $thisMonthDaysCount = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    for ($i=1; $i <= $thisMonthDaysCount; $i++) {
        $day = $year.'-'.$month.'-'.str_pad($i, 2, '0', STR_PAD_LEFT);
        if ($branch == 'all') {
            $dayRevenue = App\Models\Revenues::whereNotIn('Type',['transfeerFromAnother'])->where('Date',$day)->sum('amount');
        } else {
            $dayRevenue = App\Models\Revenues::whereNotIn('Type',['transfeerFromAnother'])->where('branch_id',$branch)->where('Date',$day)->sum('amount');
        }
        $RevenueChart .= $dayRevenue;
        if ($i<$thisMonthDaysCount) {
            $RevenueChart .= ',';
        }
    }
    return $RevenueChart;
}
function expensesTotals($branch = 'all',$month = null, $year = null)
{
    $totalManagmentExpenses = App\Models\Expenses::whereNotIn('Type',['withdrawal','transfeerToAnother'])
                                            ->where('month',$month)
                                            ->where('year',$year);
    $totalSalariesExpenses = App\Models\SalaryRequest::where('month',$month)
                                                ->where('year',$year);
    $totalWithdrawalExpenses = App\Models\Expenses::where('Type','withdrawal')
                                            ->where('month',$month)
                                            ->where('year',$year);

    $yearManagmentExpenses = App\Models\Expenses::whereNotIn('Type',['withdrawal','transfeerToAnother'])
                                            ->where('year',$year);
    $yearSalariesExpenses = App\Models\SalaryRequest::where('year',$year);
    $yearWithdrawalExpenses = App\Models\Expenses::where('Type','withdrawal')
                                            ->where('year',$year);

    if ($branch != 'all') {
        $totalManagmentExpenses = $totalManagmentExpenses->where('branch_id',$branch);
        $totalSalariesExpenses = $totalSalariesExpenses->where('branch_id',$branch);
        $totalWithdrawalExpenses = $totalWithdrawalExpenses->where('branch_id',$branch);

        $yearManagmentExpenses = $yearManagmentExpenses->where('branch_id',$branch);
        $yearSalariesExpenses = $yearSalariesExpenses->where('branch_id',$branch);
        $yearWithdrawalExpenses = $yearWithdrawalExpenses->where('branch_id',$branch);
    }

    $totalManagmentExpenses = $totalManagmentExpenses->sum('Expense');
    $totalSalariesExpenses = $totalSalariesExpenses->sum('DeliveredSalary');
    $totalWithdrawalExpenses = $totalWithdrawalExpenses->sum('Expense');

    $yearManagmentExpenses = $yearManagmentExpenses->sum('Expense');
    $yearSalariesExpenses = $yearSalariesExpenses->sum('DeliveredSalary');
    $yearWithdrawalExpenses = $yearWithdrawalExpenses->sum('Expense');

    return [
        'management' => $totalManagmentExpenses,
        'salaries' => $totalSalariesExpenses,
        'withdrawal' => $totalWithdrawalExpenses,
        'total' => $totalManagmentExpenses + $totalSalariesExpenses + $totalWithdrawalExpenses,
        'yearTotal' => $yearManagmentExpenses + $yearSalariesExpenses + $yearWithdrawalExpenses
    ];
}
function revenuesTotals($branch = 'all',$month = null, $year = null)
{
    $totalRevenues = App\Models\Revenues::whereNotIn('Type',['transfeerFromAnother'])
                                            ->where('month',$month)
                                            ->where('year',$year);
    $totalDeposits = App\Models\Revenues::where('Type','deposits')
                                            ->where('month',$month)
                                            ->where('year',$year);

    $yearRevenues = App\Models\Revenues::whereNotIn('Type',['transfeerFromAnother'])->where('year',$year);
    $yearDeposits = App\Models\Revenues::where('Type','deposits')->where('year',$year);

    if ($branch != 'all') {
        $totalRevenues = $totalRevenues->where('branch_id',$branch);
        $totalDeposits = $totalDeposits->where('branch_id',$branch);

        $yearRevenues = $yearRevenues->where('branch_id',$branch);
        $yearDeposits = $yearDeposits->where('branch_id',$branch);
    }

    $totalRevenues = $totalRevenues->sum('amount');
    $totalDeposits = $totalDeposits->sum('amount');

    $yearRevenues = $yearRevenues->sum('amount');
    $yearDeposits = $yearDeposits->sum('amount');

    return [
        'revenues' => $totalRevenues,
        'deposits' => $totalDeposits,
        'total' => $totalRevenues + $totalDeposits,
        'yearTotal' => $yearRevenues + $yearDeposits
    ];
}
function teamTodayFollwupsStats($teamMembers = [])
{
    $today = date('Y-m-d');
    $followUps = App\Models\ClientFollowUps::whereDate('created_at', $today)
    ->whereIn('UID', $teamMembers)
    ->get();
    $booking = App\Models\ClientUnit::where('status','booking')->whereDate('created_at',$today)->whereIn('agent_id',$teamMembers)->count();
    $contract = App\Models\ClientUnit::where('status','contract')
                                ->whereDate('created_at',$today)
                                ->whereIn('agent_id',$teamMembers)->count();
    $list = [
        'Call' => $followUps->where('contactingType','phone_call')->count(),
        'UnitVisit' => $followUps->where('contactingType','unit_visit')->count(),
        'Booking' => $booking,
        'Contract' => $contract
    ];
    // dd($list);
    return $list;
}
function teamFollowupsStats($teamMembers = [],$month = null, $year = null)
{
    $thisMonth = date('m');
    $thisYear = date('Y');
    if ($month != null) {
        $thisMonth = $month;
    }
    if ($year != null) {
        $thisYear = $year;
    }

    $followUps = App\Models\ClientFollowUps::where('month',$month)
                                ->where('year',$year)
                                ->whereIn('UID',$teamMembers)->get();

    $booking = App\Models\ClientUnit::where('status','booking')
                                ->whereMonth('created_at',$month)
                                ->whereYear('created_at',$year)
                                ->whereIn('agent_id',$teamMembers)->count();

    $contract = App\Models\ClientUnit::where('status','contract')
                                ->whereMonth('created_at',$month)
                                ->whereYear('created_at',$year)
                                ->whereIn('agent_id',$teamMembers)->count();

    $list = [
        'Call' => $followUps->where('contactingType','phone_call')->count(),
        'UnitVisit' => $followUps->where('contactingType','unit_visit')->count(),
        'Booking' => $booking,
        'Contract' => $contract
    ];

    return $list;
}
function teamMonthFollowupsStats($teamMembers = [],$month = null, $year = null)
{
    $numbers = [
        'Call' => '',
        'UnitVisit' => '',
        'Booking' => '',
        'Contract' => ''
    ];
    $thisMonthDaysCount = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    for ($i=1; $i <= $thisMonthDaysCount; $i++) {
        $day = $year.'-'.$month.'-'.$i;
        $followUps = App\Models\ClientFollowUps::where('status','Done')
                                    ->where('contactingDateTime',$day)
                                    ->where('year',$year)
                                    ->whereIn('UID',$teamMembers)->get();

        $Calls = $followUps->where('contactingType','Call')->count();
        $numbers['Call'] .= $Calls;
        if ($i<$thisMonthDaysCount) {
            $numbers['Call'] .= ',';
        }

        $UnitVisits = $followUps->where('contactingType','UnitVisit')->count();
        $numbers['UnitVisit'] .= $UnitVisits;
        if ($i<$thisMonthDaysCount) {
            $numbers['UnitVisit'] .= ',';
        }

        $booking = App\Models\ClientUnit::where('status','booking')
                                    // ->where('booking_day',$day)
                                    ->whereDay('updated_at', '=', $i)
                                    ->whereIn('agent_id',$teamMembers)->count();
        $numbers['Booking'] .= $booking;
        if ($i<$thisMonthDaysCount) {
            $numbers['Booking'] .= ',';
        }

        $Contract = App\Models\ClientUnit::where('status','contract')
                                    // ->where('contract_day',$day)
                                    ->whereDay('updated_at', '=', $i)
                                    ->whereIn('agent_id',$teamMembers)->count();
        $numbers['Contract'] .= $Contract;
        if ($i<$thisMonthDaysCount) {
            $numbers['Contract'] .= ',';
        }
    }
    return $numbers;
}
function branchFollowupsStats($branch,$month = null, $year = null)
{
    $thisMonth = date('m');
    $thisYear = date('Y');
    if ($month != null) {
        $thisMonth = $month;
    }
    if ($year != null) {
        $thisYear = $year;
    }
    if ($branch == 'all') {
        $followUps = App\Models\ClientFollowUps::where('status','Done')
                                    ->where('month',$month)
                                    ->where('year',$year)->get();
    } else {
        $branchMembers[] = $branch;
        $mybranch = App\Models\User::where('status','Active')->where('branch_id',$branch)->get();
        foreach ($mybranch as $mybranchKey => $mybranchV) {
            $branchMembers[] = $mybranchV['id'];
        }
        $followUps = App\Models\ClientFollowUps::where('status','Done')
                                    ->where('month',$month)
                                    ->where('year',$year)
                                    ->whereIn('UID',$branchMembers)->get();
    }

    $list = [
        'Mail' => $followUps->where('contactingType','Mail')->count(),
        'Call' => $followUps->where('contactingType','Call')->count(),
        'InVisit' => $followUps->where('contactingType','InVisit')->count(),
        'OutVisit' => $followUps->where('contactingType','OutVisit')->count(),
        'UnitVisit' => $followUps->where('contactingType','UnitVisit')->count()
    ];

    return $list;
}
function branchMonthFollowupsStats($branch,$month = null, $year = null)
{
    $numbers = [
        'Mail' => '',
        'Call' => '',
        'InVisit' => '',
        'OutVisit' => '',
        'UnitVisit' => ''
    ];
    $thisMonthDaysCount = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    if ($branch == 'all') {
        $mybranch = App\Models\User::where('status','Active')->get();
        foreach ($mybranch as $mybranchKey => $mybranchV) {
            $branchMembers[] = $mybranchV['id'];
        }
    }else {
        $branchMembers[] = $branch;
        $mybranch = App\Models\User::where('status','Active')->where('branch_id',$branch)->get();
        foreach ($mybranch as $mybranchKey => $mybranchV) {
            $branchMembers[] = $mybranchV['id'];
        }
    }
    for ($i=1; $i <= $thisMonthDaysCount; $i++) {
        $day = $year.'-'.$month.'-'.$i;
        $followUps = App\Models\ClientFollowUps::where('status','Done')
                                    ->where('contactingDateTime',$day)
                                    ->where('year',$year)
                                    ->whereIn('UID',$branchMembers)->get();
        $emails = $followUps->where('contactingType','Mail')->count();
        $numbers['Mail'] .= $emails;
        if ($i<$thisMonthDaysCount) {
            $numbers['Mail'] .= ',';
        }

        $Calls = $followUps->where('contactingType','Call')->count();
        $numbers['Call'] .= $Calls;
        if ($i<$thisMonthDaysCount) {
            $numbers['Call'] .= ',';
        }

        $InVisits = $followUps->where('contactingType','InVisit')->count();
        $numbers['InVisit'] .= $InVisits;
        if ($i<$thisMonthDaysCount) {
            $numbers['InVisit'] .= ',';
        }

        $OutVisits = $followUps->where('contactingType','OutVisit')->count();
        $numbers['OutVisit'] .= $OutVisits;
        if ($i<$thisMonthDaysCount) {
            $numbers['OutVisit'] .= ',';
        }

        $UnitVisits = $followUps->where('contactingType','UnitVisit')->count();
        $numbers['UnitVisit'] .= $UnitVisits;
        if ($i<$thisMonthDaysCount) {
            $numbers['UnitVisit'] .= ',';
        }
    }
    return $numbers;
}

function homeStates($month = null, $year = null, $source = null, $Name = null, $cellphone = null, $identity = null, $employee = null)
{
    $today = now()->startOfDay()->toDateString();
    $todayClients = App\Models\Clients::whereNotIn('position',['delete','archive'])->whereDate('created_at',$today);
    $monthClients = App\Models\Clients::whereNotIn('position',['delete','archive'])->whereMonth('created_at', Carbon::now()->month);
    $monthContractFollowUpClients = App\Models\Clients::whereNotIn('position',['delete','archive'])->where('position','contractFollowUp')->whereMonth('created_at', Carbon::now()->month);
    $monthLostClients = App\Models\Clients::whereNotIn('position',['delete','archive'])->where('status','archive')->whereMonth('created_at', Carbon::now()->month);
    $monthCurrentClients = App\Models\Clients::whereNotIn('position',['delete','archive'])->where('status','contracts')->whereMonth('created_at', Carbon::now()->month);
    if ($month != null) {
        if ($month == 'all') {
            $monthContractFollowUpClients = $todayClients->where('created_at','<',Carbon::now());
            $monthLostClients = $todayClients->where('created_at','<',Carbon::now());
            $monthCurrentClients = $todayClients->where('created_at','<',Carbon::now());
        } else {
            $monthContractFollowUpClients = $todayClients->whereMonth('created_at', $month);
            $monthLostClients = $todayClients->whereMonth('created_at', $month);
            $monthCurrentClients = $todayClients->whereMonth('created_at', $month);
        }
    }
    if ($source != null) {
        $todayClients = $todayClients->where('referral',$source);
        $monthClients = $todayClients->where('referral',$source);
        $monthContractFollowUpClients = $todayClients->where('referral',$source);
        $monthLostClients = $todayClients->where('referral',$source);
        $monthCurrentClients = $todayClients->where('referral',$source);
    }
    if ($Name != null) {
        $todayClients = $todayClients->where('Name','like','%'.$Name.'%');
        $monthClients = $todayClients->where('Name','like','%'.$Name.'%');
        $monthContractFollowUpClients = $todayClients->where('Name','like','%'.$Name.'%');
        $monthLostClients = $todayClients->where('Name','like','%'.$Name.'%');
        $monthCurrentClients = $todayClients->where('Name','like','%'.$Name.'%');
    }
    if ($cellphone != null) {
        $todayClients = $todayClients->where('cellphone',$cellphone);
        $monthClients = $todayClients->where('cellphone',$cellphone);
        $monthContractFollowUpClients = $todayClients->where('cellphone',$cellphone);
        $monthLostClients = $todayClients->where('cellphone',$cellphone);
        $monthCurrentClients = $todayClients->where('cellphone',$cellphone);
    }
    if ($identity != null) {
        $todayClients = $todayClients->where('identity',$identity);
        $monthClients = $todayClients->where('identity',$identity);
        $monthContractFollowUpClients = $todayClients->where('identity',$identity);
        $monthLostClients = $todayClients->where('identity',$identity);
        $monthCurrentClients = $todayClients->where('identity',$identity);
    }
    if ($employee != null) {
        $todayClients = $todayClients->where('UID',$employee);
        $monthClients = $todayClients->where('UID',$employee);
        $monthContractFollowUpClients = $todayClients->where('UID',$employee);
        $monthLostClients = $todayClients->where('UID',$employee);
        $monthCurrentClients = $todayClients->where('UID',$employee);
    }

    return [
        'todayClients' => $todayClients->count(),
        'monthClients' => $monthClients->count(),
        'monthContractFollowUpClients' => $monthContractFollowUpClients->count(),
        'monthLostClients' => $monthLostClients->count(),
        'monthCurrentClients' => $monthCurrentClients->count()
    ];
}

function clientsPageStats($month, $year,
                        $employee = 'all', $source = 'all',
                        $Name = null, $cellphone = null,
                        $identity = null, $position)
{
    $sourceClients = App\Models\Clients::where('position',$position);
    if ($month != 'all') {
        $sourceClients = $sourceClients->whereMonth('updated_at',$month);
    }
    if ($year != 'all') {
        $sourceClients = $sourceClients->whereYear('updated_at',$year);
    }

    if ($employee != 'all') {
        $sourceClients = $sourceClients->where('UID',$employee);
    }
    if ($source != null) {
        $sourceClients = $sourceClients->where('referral',$source);
    }
    // if ($Name != null) {
    //     $sourceClients = $sourceClients->where('Name',$Name);
    // }
    if ($cellphone != null) {
        $sourceClients = $sourceClients->where('cellphone',$cellphone);
    }
    if ($identity != null) {
        $sourceClients = $sourceClients->where('identity',$identity);
    }
    if(isset($_GET['supportHousing'])){
        if($_GET['supportHousing'] != ''){
            $sourceClients = $sourceClients->where('supportHousing',$_GET['supportHousing']);
        }
    }
    // $sourceClients = $sourceClients->get();
    foreach (clientStatusArray(session()->get('Lang')) as $key => $value) {
        $list[$key] = $sourceClients->where('status',$key)->count();
    }
    return $list;
}

function cotractsPageStats($month, $year, $agent = 'all', $client = 'all')
{
    $contracts = App\Models\ClientContracts::where('status','!=','cancel');
    if ($month != 'all') {
        $contracts = $contracts->where('month',$month);
    }
    if ($year != 'all') {
        $contracts = $contracts->where('year',$year);
    }
    if (isset($_GET['paymentStatus'])) {
        if ($_GET['paymentStatus'] == 'noPayment') {
            $contracts = $contracts->doesntHave('payments');
        }
        if ($_GET['paymentStatus'] == 'partialPayment') {
            $contracts = $contracts->whereColumn('paid','<', 'Total');
        }
        if ($_GET['paymentStatus'] == 'donePayment') {
            $contracts = $contracts->whereColumn('paid','=', 'Total');
        }
    }
    $contracts = $contracts->get();

    if ($agent != 'all') {
        $contracts = $contracts->where('AgentID',$agent);
    }
    if ($client != 'all') {
        $contracts = $contracts->where('ClientID',$client);
    }
    $list = [];
    $list['total'] = $contracts->count();
    $ids = $contracts->pluck('id')->toArray();
    $payments = App\Models\Revenues::whereIn('contract_id',$ids)->sum('amount');
    $list['payments'] = [
        'total' => number_format($contracts->sum('Total')),
        'paid' => number_format($payments),
        'rest' => number_format($contracts->sum('Total') - $payments)
    ];
    foreach (contractStatusList('ar') as $key => $value) {
        $list[$key] = $contracts->where('Status',$key)->count();
    }
    return $list;
}

function teamPerformance($users = [], $month = null, $year = null)
{
    $list = [
        'Call' => 0,
        'UnitVisit' => 0,
        'booking' => 0,
        'contract' => 0
    ];

    return $list;
}
