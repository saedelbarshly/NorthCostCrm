<?php
function DayMonthOnly($your_date)
{
    $months = array("Jan" => "يناير",
                     "Feb" => "فبراير",
                     "Mar" => "مارس",
                     "Apr" => "أبريل",
                     "May" => "مايو",
                     "Jun" => "يونيو",
                     "Jul" => "يوليو",
                     "Aug" => "أغسطس",
                     "Sep" => "سبتمبر",
                     "Oct" => "أكتوبر",
                     "Nov" => "نوفمبر",
                     "Dec" => "ديسمبر");
    //$your_date = date('y-m-d'); // The Current Date
    $en_month = date("M", strtotime($your_date));
    foreach ($months as $en => $ar) {
        if ($en == $en_month) { $ar_month = $ar; }
    }

    $find = array ("Sat", "Sun", "Mon", "Tue", "Wed" , "Thu", "Fri");
    $replace = array ("السبت", "الأحد", "الإثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة");
    $ar_day_format = date("D", strtotime($your_date)); // The Current Day
    $ar_day = str_replace($find, $replace, $ar_day_format);

    header('Content-Type: text/html; charset=utf-8');
    $standard = array("0","1","2","3","4","5","6","7","8","9");
    $eastern_arabic_symbols = array("٠","١","٢","٣","٤","٥","٦","٧","٨","٩");
    $current_date = $ar_day.' '.date('d', strtotime($your_date)).' '.$ar_month.' '.date('Y', strtotime($your_date));
    $arabic_date = str_replace($standard , $eastern_arabic_symbols , $current_date);

    return $arabic_date;
}
function arabicMonth($your_date)
{
    $months = array("Jan" => "يناير",
                     "Feb" => "فبراير",
                     "Mar" => "مارس",
                     "Apr" => "أبريل",
                     "May" => "مايو",
                     "Jun" => "يونيو",
                     "Jul" => "يوليو",
                     "Aug" => "أغسطس",
                     "Sep" => "سبتمبر",
                     "Oct" => "أكتوبر",
                     "Nov" => "نوفمبر",
                     "Dec" => "ديسمبر");
    //$your_date = date('y-m-d'); // The Current Date
    $en_month = date("M", strtotime($your_date));
    foreach ($months as $en => $ar) {
        if ($en == $en_month) { $ar_month = $ar; }
    }
    return $ar_month;
}
function getTime($time)
{
    $time = '';
    $time .= date('H:m',strtotime($time));
    $time .= date('a',strtotime($time)) == 'am' ? ' ص ' : 'م';
    return $time;
}

function panelLangMenu()
{
    $list = [];
    $locales = Config::get('app.locales');

    if (Session::get('Lang') != 'ar') {
        $list[] = [
            'flag' => 'sa',
            'text' => trans('common.lang1Name'),
            'lang' => 'ar'
        ];
    } else {
        $selected = [
            'flag' => 'sa',
            'text' => trans('common.lang1Name'),
            'lang' => 'ar'
        ];
    }
    if (Session::get('Lang') != 'en') {
        $list[] = [
            'flag' => 'us',
            'text' => trans('common.lang2Name'),
            'lang' => 'en'
        ];
    } else {
        $selected = [
            'flag' => 'us',
            'text' => trans('common.lang2Name'),
            'lang' => 'en'
        ];
    }

    return [
        'selected' => $selected,
        'list' => $list
    ];
}

function getCssFolder()
{
    return trans('common.cssFile');
}

function contractsList()
{
    return App\Models\ClientContracts::orderBy('id','desc')->pluck('name','id')->all();
}

function projectsList()
{
    $projects = App\Models\Projects::orderBy('name','asc')->pluck('name','id')->all();
    return $projects;
}
function ownersList()
{
    $owners = App\Models\Owner::orderBy('name','asc')->pluck('name','id')->all();
    return  $owners;
}

function brokersList()
{
    $brokers = App\Models\Broker::orderBy('name','asc')->pluck('name','id')->all();
    return  $brokers;
}

function unitsTypeList()
{
    $unitType = App\Models\UnitType::orderBy('name','asc')->pluck('name','id')->all();
    return  $unitType;
}
function govList()
{
    $gove = App\Models\Governorates::orderBy('name','asc')->pluck('name','id')->all();
    return $gove;
}
function cityList()
{
    $city = App\Models\Cities::orderBy('name','asc')->pluck('name','id')->all();
    return $city;
}
function interfaceList()
{
    $list =[
        'north' => 'شمالية',
        'south' => 'جنوبية',
        'east' => 'شرقية',
        'west' => 'غربية'
    ];
    return $list;
}


function unitsList()
{
    $units = App\Models\Units::whereNull('ClientID')->orderBy('name','asc')->get();
    $list = [];
    foreach($units as $unit) {
        if($unit->bookings()->whereIn('status',['contract','booking'])->count() == 0) {
            $list[$unit->id] = $unit->name;
        }
    }
    return $list;
}
function templatesList()
{
    return App\Models\Templates::orderBy('id','desc')->pluck('name_'.session()->get('Lang'),'id')->all();
}
function getRolesList($lang,$value,$guard = null)
{
    $list = [];
    if ($guard == null) {
        $roles = App\Models\roles::orderBy('name_'.$lang,'asc')->get();
    } else {
        $roles = App\Models\roles::where('guard',$guard)->orderBy('name_'.$lang,'asc')->get();
    }
    foreach ($roles as $role) {
        $list[$role[$value]] = $role['name_'.$lang] != '' ? $role['name_'.$lang] : $role['name_ar'];
    }
    return $list;
}

function getSectionsList($lang)
{
    $list = [];
    $sections = App\Models\Sections::where('main_section','0')->orderBy('name_'.$lang,'asc')->get();
    foreach ($sections as $section) {
        $list[$section['id']] = $section['name_'.$lang];
        if ($section->subSections != '') {
            foreach ($section->subSections as $key => $value) {
                $list[$value['id']] = ' - '.$value['name_'.$lang];
            }
        }
    }
    return $list;
}

function getSettingValue($key)
{
    $value = '';
    $setting = App\Models\Settings::where('key',$key)->first();
    if ($setting != '') {
        $value = $setting['value'];
    }
    return $value;
}

function getSettingImageLink($key)
{
    $link = '';
    $setting = App\Models\Settings::where('key',$key)->first();
    if ($setting != '') {
        if ($setting['value'] != '') {
            $link = asset('uploads/settings/'.$setting['value']);
        }
    }
    return $link;
}

function getSettingImageValue($key)
{
    $value = '';
    if (getSettingImageLink($key) != '') {
        $value .= '<div class="row"><div class="col-12">';
        $value .= '<span class="avatar mb-2">';
        $value .= '<img class="round" src="'.getSettingImageLink($key).'" alt="avatar" height="90" width="90">';
        $value .= '</span>';
        $value .= '</div>';
        $value .= '<div class="col-12">';
        $value .= '<a href="'.route('admin.settings.deletePhoto',['key'=>$key]).'"';
        $value .= ' class="btn btn-danger btn-sm">'.trans("common.delete").'</a>';
        $value .= '</div></div>';
    }
    return $value;
}

function checkUserForApi($lang, $user_id)
{
    if ($lang == '') {
        $resArr = [
            'status' => 'faild',
            'message' => trans('api.pleaseSendLangCode'),
            'data' => []
        ];
        return response()->json($resArr);
    }
    $user = App\Models\User::find($user_id);
    if ($user == '') {
        return response()->json([
            'status' => 'faild',
            'message' => trans('api.thisUserDoesNotExist'),
            'data' => []
        ]);
    }

    return true;
}

function salesStatistics7Days()
{
    $date = \Carbon\Carbon::today()->subDays(7);
    $date7before = new \Carbon\Carbon($date);
    $date7before = $date7before->subDays(7);
    $ClientsCount = App\Models\User::where('role', '3')->where('created_at', '>=', $date)->count();

    return [
        'ClientsCount' => number_format($ClientsCount),
    ];
}

function branchesList()
{
    $branches = App\Models\Branches::orderBy('name','asc')->pluck('name','id')->all();
    return $branches;
}
function managementsList()
{
    $managements = App\Models\Managements::orderBy('name','asc')->pluck('name','id')->all();
    return $managements;
}
function jobsList()
{
    $jobs = App\Models\Jobs::orderBy('name','asc')->pluck('name','id')->all();
    return $jobs;
}

function safesList()
{
    $safes = [];
    if (userCan('expenses_view') || userCan('employees_account_pay_salary')) {
        $safes = App\Models\SafesBanks::orderBy('Title','asc')->pluck('Title','id')->all();
    } elseif (userCan('expenses_view_branch')) {
        $safes = App\Models\SafesBanks::where('branch_id',auth()->user()->branch_id)->orderBy('Title','asc')->pluck('Title','id')->all();
    }
    return $safes;
}
function agentsList()
{
    $agents = App\Models\User::where('status','Active')->orderBy('name','asc')->pluck('name','id')->all();
    return $agents;
}
function clientSourceList()
{
    $sources = App\Models\ClientSources::orderBy('name','asc')->pluck('name','id')->all();
    return $sources;
}
function servicesList()
{
    $services = App\Models\Services::orderBy('name','asc')->pluck('name','id')->all();
    return $services;
}
function serviceRenewalsList()
{
    $list = [
        '0' => 'تدفع مرة واحده',
        '1' => 'تجديد شهرياً',
        '3' => 'تجديد كل ثلاث أشهر',
        '6' => 'تجديد نصف سنوي',
        '12' => 'تجديد سنوي',
        '24' => 'تجديد كل سنتين'
    ];
    return $list;
}
function agentsListForSearch()
{
    $agents = App\Models\User::where('status','Active')->orderBy('name','asc')->pluck('name','id')->all();;
    // if (auth()->user()->role == '1') {
    //     $agents = App\Models\User::where('status','Active')->where('role',$role_id)->orderBy('name','asc')->pluck('name','id')->all();
    // }elseif (userCan('followups_view_branch')) {
    //     $agents = App\Models\User::where('status','Active')->where('role',$role_id)->where('branch_id',auth()->user()->id)
    //                                                 ->orderBy('name','asc')
    //                                                 ->pluck('name','id')
    //                                                 ->all();
    // }elseif (userCan('followups_view_team')) {
    //     $agents = [auth()->user()->id => auth()->user()->name] + App\Models\User::where('status','Active')->where('role',$role_id)
    //                                                                     ->where('leader',auth()->user()->id)
    //                                                                     ->orderBy('name','asc')
    //                                                                     ->pluck('name','id')
    //                                                                     ->all();
    // }
    return $agents;
}
function agentsVisitList()
{
    $agents = App\Models\User::where('status','Active')->orderBy('name','asc')->pluck('name','id')->all();
    if (userCan('clients_view_team')) {
        $agents = [auth()->user()->id => auth()->user()->name] + App\Models\User::where('status','Active')
                                                                        ->where('leader',auth()->user()->id)
                                                                        ->orderBy('name','asc')
                                                                        ->pluck('name','id')
                                                                        ->all();
    }
    if (userCan('clients_view_branch')) {
        $agents = App\Models\User::where('status','Active')->where('branch_id',auth()->user()->id)
                                                    ->orderBy('name','asc')
                                                    ->pluck('name','id')
                                                    ->all();
    }
    return $agents;
}
function clientsList()
{
    if (userCan('clients_view')) {
        $agents = App\Models\Clients::orderBy('Name','asc')->pluck('Name','id')->all();
    } else {
        $agents = App\Models\Clients::where('UID',auth()->user()->id)->orderBy('Name','asc')->pluck('Name','id')->all();
    }
    return $agents;
}
function clientSalesStatusArray($lang)
{
    $list = [
        'ar' => [
            'checkout_followup' => 'متابعه',
            'checkout_reject' => 'رفض',
            'booking_followup' => 'متابعه استكمال الاوراق',
            'booking_contract' => 'Booking/Contract Sign up'
        ],
        'en' => [
            'checkout_followup' => 'Checkout/Followup',
            'checkout_reject' => 'Checkout/Reject',
            'booking_followup' => 'Booking/Contract Followup',
            'booking_contract' => 'Booking/Contract Sign up'
        ]
    ];
    return $list[$lang];
}
function clientStatuslist($lang)
{
    $list = [
        'ar' => [
            'archive' => 'أرشيف',
            'current' => 'عميل حالي'
        ],
        'en' => [
            'archive' => 'archive',
            'current' => 'active'
        ]
    ];
    return $list[$lang];
}
function clientStatusArray($lang)
{
    $list = [
        'ar' => [
            'no_answer' => 'لا يرد',
            'call_back' => 'تواصل مرة أخرى',
            'no_interest' => 'غير مهتم',
            'interested' => 'مهتم',
            'wrong_number' => 'رقم خاطئ / لا تتصل',
            'checkout_followup' => 'متابعه',
            'checkout_reject' => 'رفض',
            'booking_followup' => 'متابعه استكمال الاوراق',
            'supported_cash' => 'كاش مدعوم',
            'none_supported_cash' => 'كاش غير مدعوم',
            'whatsapp_followup' => 'متابعة رسائل واتس',
            'date_booking' => 'تم حجز موعد',
            'date_done' => 'تم الحضور',
            'booking_done' => 'تم حجز فيلا',
            'booking_contract' => 'Booking/Contract Sign up'
        ],
        'en' => [
            'no_answer' => 'No Answer',
            'call_back' => 'Call Back',
            'no_interest' => 'No Interest',
            'interested' => 'interested',
            'wrong_number' => 'Wrong Number / don\'t call',
            'checkout_followup' => 'Checkout/Followup',
            'checkout_reject' => 'Checkout/Reject',
            'booking_followup' => 'Booking/Contract Followup',
            'supported_cash' => 'Supported Cash',
            'none_supported_cash' => 'None Supported Cash',
            'whatsapp_followup' => 'Whatsapp Followup',
            'date_booking' => 'Date Booking',
            'date_done' => 'Client Came Through',
            'booking_done' => 'Booking Done',
            'booking_contract' => 'Booking/Contract Sign up'
        ]
    ];
    return $list[$lang];
}
function clientPositionsArray($lang,$archive = 'yes')
{
    $list = [
        'ar' => [
            'call_center' => 'Call Center',
            'reception' => 'الاستقبال',
            'sales' => 'عميل حالي',
            'contract' => 'عميل متعاقد',
            'archive' => 'أرشيف',
            'salesTeam' => 'فريق المبيعات'
        ],
        'en' => [
            'call_center' => 'Call Center',
            'reception' => 'Reception',
            'sales' => 'Sales',
            'contract' => 'Contracted',
            'salesTeam' => 'Sales Team'
        ]
    ];
    if($archive == 'no'){
        unset($list['ar']['contract']);
        unset($list['ar']['archive']);
        unset($list['en']['contract']);
        unset($list['en']['archive']);
    }
    return $list[$lang];
}
function supportHousingList($lang)
{
    $list = [
        'ar' => [
            'worthy' => 'مستحق',
            'not_worthy' => 'غير مستحق'
        ],
        'en' => [
            'worthy' => 'Worthy',
            'not_worthy' => 'Not Worthy'
        ]
    ];
    return $list[$lang];
}
function clientJobsList($lang)
{
    $list = [
        'ar' => [
            'military' => 'عسكري',
            'governmental_civil' => 'حكومي/مدني',
            'private_sector' => 'قطاع خاص',
            'retired' => 'متقاعد',
            'deposits' => 'الامانات',
            'Organizations' => 'الهيئات',
            'health' => 'الصحة',
            'education' => 'التعليم'
        ],
        'en' => [
            'military' => 'Military',
            'governmental_civil' => 'Governmental/Civil',
            'private_sector' => 'Private Sector',
            'retired' => 'Retired',
            'deposits' => 'Deposits',
            'Organizations' => 'Organizations',
            'health' => 'Health',
            'education' => 'Education'
        ]
    ];
    return $list[$lang];
}

function callCenterRejectionCauses($lang)
{
    $list = [
        'ar' => [
            'callCenter_financial_ability' => 'القدرة المالية',
            'callCenter_project_location' => 'موقع المشروع',
            'callCenter_prices' => 'الاسعار',
            'callCenter_design' => 'التصميم',
            'callCenter_not_qualified_for_support' => 'غير مستحق للدعم',
            'callCenter_supported_before' => 'استفاد سابقاً من الدعم',
            'callCenter_want_ready_unit' => 'يريد عقار جاهز'
        ],
        'en' => [
            'callCenter_financial_ability' => 'Financial Ability',
            'callCenter_project_location' => 'Project Location',
            'callCenter_prices' => 'Prices',
            'callCenter_design' => 'Design',
            'callCenter_not_qualified_for_support' => 'Not Qualified For Support',
            'callCenter_supported_before' => 'Supported Before',
            'callCenter_want_ready_unit' => 'Want Ready Unit'
        ]
    ];
    return $list[$lang];
}

function salesRejectionCauses($lang)
{
    $list = [
        'ar' => [
            'sales_financial_ability' => 'القدرة المالية',
            'sales_project_location' => 'موقع المشروع',
            'sales_prices' => 'الاسعار',
            'sales_design' => 'التصميم',
            'sales_bank_profit_margin' => 'هامش ربح البنك',
            'sales_want_ready_unit' => 'يريد عقار جاهز',
            'sales_not_decided' => 'لم يقرر'
        ],
        'en' => [
            'sales_financial_ability' => 'Financial Ability',
            'sales_project_location' => 'Project Location',
            'sales_prices' => 'Prices',
            'sales_design' => 'Design',
            'sales_bank_profit_margin' => 'Bank Profit Margin',
            'sales_want_ready_unit' => 'Want Ready Unit',
            'sales_not_decided' => 'Not Decided'
        ]
    ];
    return $list[$lang];
}

function unitsTypesList($lang)
{
    $list = [
        'ar' => [
            'Land' => 'أرض',
            'Floor' => 'شقة',
            'House' => 'منزل',
            'Villa' => 'فيلا',
            'Shop' => 'محل',
            'Studio' => 'ستديو',
            'Shalie' => 'شاليه'
        ],
        'en' => [
            'Land' => 'أرض',
            'Floor' => 'شقة',
            'House' => 'منزل',
            'Villa' => 'فيلا',
            'Shop' => 'محل',
            'Studio' => 'ستديو',
            'Shalie' => 'شاليه'
        ]
    ];
    return $list[$lang];
}

function systemMainSections()
{
    $systemMainSections = [
        'settings' => 'settings',
        'users' => 'users',
        'roles' => 'roles',
        'managements' => 'managements',
        'jobs' => 'jobs',
        'userAccounts' => 'userAccounts',
        'attendance' => 'attendance',
        'projects' => 'projects',
        'units' => 'units',
        'clients' => 'clients',
        'owners' => 'owners',
        'brokers' => 'brokers',
        'contracts' => 'contracts',
        'followups' => 'followups',
        'reports' => 'reports',
        'homeStats' => 'homeStats',
    ];
    return $systemMainSections;
}


function getPermissions($role = null)
{

    $roleData = '';
    if ($role != null) {
        $roleData = App\Models\roles::find($role);
    }

    $permissionsArr = [];
    foreach (systemMainSections() as $section) {
        $permissionsArr[$section] = [
            'name' => trans('common.'.$section),
            'permissions' => []
        ];
        $settingPermissions = App\Models\permissions::where('group',$section)->get();
        foreach ($settingPermissions as $permission) {
            $hasIt = 0;
            if ($roleData != '') {
                if ($roleData->hasPermission($permission['id']) == 1) {
                    $hasIt = 1;
                }
            }
            $permissionsArr[$section]['permissions'][] = [
                'id' => $permission['id'],
                'can' => $permission['can'],
                'name' => $permission['name_'.session()->get('Lang')],
                'hasIt' => $hasIt
            ];
        }
    }
    return $permissionsArr;
}

function monthArray($lang)
{
    $arr = [
        'ar' => [
            '01' => '01 يناير',
            '02' => '02 فبراير',
            '03' => '03 مارس',
            '04' => '04 أبريل',
            '05' => '05 مايو',
            '06' => '06 يونيو',
            '07' => '07 يوليو',
            '08' => '08 أغسطس',
            '09' => '09 سبتمبر',
            '10' => '10 أكتوبر',
            '11' => '11 نوفمبر',
            '12' => '12 ديسمبر',
        ],
        'en' => [
            '01' => '01 يناير',
            '02' => '02 فبراير',
            '03' => '03 مارس',
            '04' => '04 أبريل',
            '05' => '05 مايو',
            '06' => '06 يونيو',
            '07' => '07 يوليو',
            '08' => '08 أغسطس',
            '09' => '09 سبتمبر',
            '10' => '10 أكتوبر',
            '11' => '11 نوفمبر',
            '12' => '12 ديسمبر',
        ]
    ];
    return $arr[$lang];
}
function yearArray()
{
    $cunrrentYear = date('Y');
    $firstYear = 2020;
    $arr = [];
    for ($i=$cunrrentYear; $i >= $firstYear; $i--) {
        $arr[$i] = $i;
    }
    return $arr;
}
function employeeStatusArray($lang)
{
    $arr = [
        'ar' => [
            'Active' => 'موظف مفعل',
            'Archive' => 'موظف معطل'
        ]
    ];
    return $arr[$lang];
}
function safeTypes($lang)
{
    $list = [
        'ar' => [
            'safe' => 'خزينة نقدية',
            'bank' => 'حساب بنكي',
            'wallet' => 'محفظة إلكترونية'
        ],
        'en' => [
            'safe' => 'Cash Safe',
            'bank' => 'Banck Account',
            'wallet' => 'Electronic Wallet'
        ]
    ];

    return $list[$lang];
}
function expensesTypes($lang)
{
    $list = [
        'ar' => [
            'withdrawal' => 'مسحوبات',
            'transfeerToAnother' => 'نقل إلى خزينة',
            'contract' => 'مصروفات تعاقد'
        ],
        'en' => [
            'withdrawal' => 'Withdrawal',
            'transfeerToAnother' => 'نقل إلى خزينة',
            'contract' => 'مصروفات تعاقد'
        ]
    ];
    $types = App\Models\ExpensesTypes::orderBy('name','asc')->pluck('name','id')->all();
    return $list[$lang]+$types;
}
function revenuesTypes($lang)
{
    $list = [
        'ar' => [
            'revenues' => 'إيرادات',
            'contract' => 'تعاقد',
            'deposits' => 'إيداعات',
            'contract_inAdvance' => 'مقدم تعاقد',
            'contract_payment' => 'دفعة مالية',
            'transfeerFromAnother' => 'نقل من خزينة'
        ],
        'en' => [
            'revenues' => 'إيرادات',
            'contract' => 'contract',
            'deposits' => 'إيداعات',
            'contract_inAdvance' => 'مقدم تعاقد',
            'contract_payment' => 'دفعة مالية',
            'transfeerFromAnother' => 'نقل من خزينة'
        ]
    ];
    return $list[$lang];
}

function refferalList($lang)
{
    $list = App\Models\ClientSources::orderBy('name','asc')->pluck('name','id')->all();
    return $list;
}

function contactingTypeList($lang)
{
    $list = [
        'ar' => [
            'phone_call' => 'مكالمة هاتفيه',
            'company_visit' => 'زيارة بالشركة',
            'unit_visit' => 'معاينة للوحدة'
        ],
        'en' => [
            'phone_call' => 'Phone Call',
            'company_visit' => 'Company Visit',
            'unit_visit' => 'Unit Visit'
        ]
    ];
    return $list[$lang];
}

function followUpTypeList($lang)
{
    $list = [
        'ar' => [
            'call_center' => 'Call Center',
            'sales_coordenator' => 'متابعة منسق المبيعات'
        ],
        'en' => [
            'call_center' => 'Call Center',
            'sales_coordenator' => 'Sales Co-ordenator'
        ]
    ];
    return $list[$lang];
}
function whoIsContactingList($lang)
{
    $list = [
        'ar' => [
            'Company' => 'الشركة',
            'Client' => 'العميل'
        ],
        'en' => [
            'Company' => 'Company',
            'Client' => 'Client'
        ]
    ];
    return $list[$lang];
}
function contractStatusList($lang = 'ar')
{
    $list = [
        'ar' => [
            'new' => 'جديد',
            'inProgress' => 'جاري العمل',
            'done' => 'تم الإنتهاء والتسليم',
            'cancel' => 'تم الإلغاء',
            'waitingDeliver' => 'فى انتظار التسليم',
            'onHold' => 'موقوف مؤقتاً'
        ],
        'en' => [
            'new' => 'جديد',
            'inProgress' => 'جاري العمل',
            'done' => 'تم الإنتهاء والتسليم',
            'cancel' => 'تم الإلغاء',
            'waitingDeliver' => 'فى انتظار التسليم',
            'onHold' => 'موقوف مؤقتاً'
        ]
    ];
    return $list[$lang];
}
function contractPaymentStatusList($lang)
{
    $list = [
        'ar' => [
            'noPayment' => 'بدون أي مدفوعات',
            'partialPayment' => 'مدفوع جزئياً',
            'donePayment' => 'مدفوع كاملاً'
        ],
        'en' => [
            'noPayment' => 'بدون أي مدفوعات',
            'partialPayment' => 'مدفوع جزئياً',
            'donePayment' => 'مدفوع كاملاً'
        ]
    ];
    return $list[$lang];
}


function themeModeClasses()
{
    if (session()->get('theme_mode') == 'light') {
        $arr = [
            'html' => 'semi-dark-layout',
            'navbar' => 'navbar-light',
            'icon' => 'moon',
            'menu' => 'menu-dark'
        ];
    } else {
        $arr = [
            'html' => 'dark-layout',
            'navbar' => 'navbar-dark',
            'icon' => 'sun',
            'menu' => 'menu-dark'
        ];
    }
    return $arr;
}
