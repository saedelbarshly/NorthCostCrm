<?php

namespace App\Http\Controllers\admin;

use Auth;
use App\Models\User;
use App\Models\Units;
use App\Models\Clients;
use App\Models\ClientUnit;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\users\UpdateUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Notifications\DatabaseNotification;

class AdminPanelController extends Controller
{
    //
    public function index()
    {
        return view('AdminPanel.index',[
            'active' => 'panelHome',
            'title' => trans('common.Admin Panel')
        ]);
    }
    public function graf()
    {
        $currentDate = now();
        if (isset($_GET['key'])) {
            if ($_GET['key'] != '') {
                $key = $_GET['key'];
            }

            if($key == 'unit')
            {
                $book = ClientUnit::where('status', 'booking');
                $contract = ClientUnit::where('status', 'contract');
                if (isset($_GET['today'])) {
                    if ($_GET['today'] != '') {
                        $today = $_GET['today'];
                        $book = ClientUnit::whereDate('created_at',$today)->where('status', 'booking');
                        $contract = ClientUnit::whereDate('created_at',$today)->where('status', 'contract');
                    }
                }
                if (isset($_GET['yesterday'])) {
                    if ($_GET['yesterday'] != '') {
                        $yesterday = $_GET['yesterday'];
                        $book = ClientUnit::whereDate('created_at',$yesterday)->where('status', 'booking');
                        $contract = ClientUnit::whereDate('created_at',$yesterday)->where('status', 'contract');

                    }
                }
                if (isset($_GET['week'])) {
                    if ($_GET['week'] != '') {
                        $week = $_GET['week'];
                        $book = ClientUnit::whereBetween('created_at',[$week,$currentDate])->where('status', 'booking');
                        $contract = ClientUnit::whereBetween('created_at',[$week,$currentDate])->where('status', 'contract');
                    }
                }
                if (isset($_GET['month'])) {
                    if ($_GET['month'] != '') {
                        $month = $_GET['month'];
                        $book = ClientUnit::whereBetween('created_at',[$month,$currentDate])->where('status', 'booking');
                        $contract = ClientUnit::whereBetween('created_at',[$month,$currentDate])->where('status', 'contract');
                    }
                }

                $unit = [];
                $book = $book->count();
                $contract = $contract->count();
                $units = Units::count();
                $ava = $units - ($book + $contract);
                $unit = [$book, $contract, $ava];


                $numberOfStatus = [];
                foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                {
                $numberOfStatus[] = Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count();
                }

                $sourceNum = [];
                foreach(clientSourceList() as $key => $source)
                {
                    $sourceNum[] = Clients::where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
                }

                $statistic = [];
                $booked = ClientUnit::where('status', 'booking')->count();
                $contracted = ClientUnit::where('status', 'contract')->count();
                $units = Units::count();
                $available = $units - ($booked + $contracted);
                $todayClients = number_format(homeStates()['todayClients']);
                $monthClients = number_format(homeStates()['monthClients']);
                $statistic = [$todayClients,$monthClients,$booked, $contracted, $available];

            }

            if($key == 'client')
            {
                if (isset($_GET['today'])) {
                    if ($_GET['today'] != '') {
                        $today = $_GET['today'];

                        $numberOfStatus = [];
                        foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                        {
                        $numberOfStatus[] = Clients::whereDate('created_at',$today)->where('status',$key)->whereNotIn('position',['delete','archive'])->count();
                        }

                    }
                }
                if (isset($_GET['yesterday'])) {
                    if ($_GET['yesterday'] != '') {
                        $yesterday = $_GET['yesterday'];
                        $numberOfStatus = [];
                        foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                        {
                        $numberOfStatus[] = Clients::whereDate('created_at',$yesterday)->where('status',$key)->whereNotIn('position',['delete','archive'])->count();
                        }
                    }
                }
                if (isset($_GET['week'])) {
                    if ($_GET['week'] != '') {
                        $week = $_GET['week'];
                        $numberOfStatus = [];
                        foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                        {
                        $numberOfStatus[] = Clients::whereBetween('created_at',[$week,$currentDate])->where('status',$key)->whereNotIn('position',['delete','archive'])->count();
                        }

                    }
                }
                if (isset($_GET['month'])) {
                    if ($_GET['month'] != '') {
                        $month = $_GET['month'];
                        $numberOfStatus = [];
                        foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                        {
                        $numberOfStatus[] = Clients::whereBetween('created_at',[$month,$currentDate])->where('status',$key)->whereNotIn('position',['delete','archive'])->count();
                        }

                    }
                }

                $unit = [];
                $book = ClientUnit::where('status', 'booking')->count();
                $contract = ClientUnit::where('status', 'contract')->count();
                $units = Units::count();
                $ava = $units - ($book + $contract);
                $unit = [$book, $contract, $ava];

                $sourceNum = [];
                foreach(clientSourceList() as $key => $source)
                {
                    $sourceNum[] = Clients::where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
                }

                $statistic = [];
                $booked = ClientUnit::where('status', 'booking')->count();
                $contracted = ClientUnit::where('status', 'contract')->count();
                $units = Units::count();
                $available = $units - ($booked + $contracted);
                $todayClients = number_format(homeStates()['todayClients']);
                $monthClients = number_format(homeStates()['monthClients']);
                $statistic = [$todayClients,$monthClients,$booked, $contracted, $available];

            }

            if($key == 'source')
            {
                if (isset($_GET['today'])) {
                    if ($_GET['today'] != '') {
                        $today = $_GET['today'];

                        $sourceNum = [];
                        foreach(clientSourceList() as $key => $source)
                        {
                            $sourceNum[] = Clients::whereDate('created_at',$today)->where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
                        }
                    }
                }
                if (isset($_GET['yesterday'])) {
                    if ($_GET['yesterday'] != '') {
                        $yesterday = $_GET['yesterday'];
                        $sourceNum = [];
                        foreach(clientSourceList() as $key => $source)
                        {
                            $sourceNum[] = Clients::whereDate('created_at',$yesterday)->where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
                        }
                    }
                }
                if (isset($_GET['week'])) {
                    if ($_GET['week'] != '') {
                        $week = $_GET['week'];
                        $sourceNum = [];
                    foreach(clientSourceList() as $key => $source)
                    {
                        $sourceNum[] = Clients::whereBetween('created_at',[$week,$currentDate])->where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
                    }

                    }
                }
                if (isset($_GET['month'])) {
                    if ($_GET['month'] != '') {
                        $month = $_GET['month'];
                        $sourceNum = [];
                        foreach(clientSourceList() as $key => $source)
                        {
                            $sourceNum[] = Clients::whereBetween('created_at',[$month,$currentDate])->where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
                        }
                    }
                }

                $unit = [];
                $book = ClientUnit::where('status', 'booking')->count();
                $contract = ClientUnit::where('status', 'contract')->count();
                $units = Units::count();
                $ava = $units - ($book + $contract);
                $unit = [$book, $contract, $ava];


                $numberOfStatus = [];
                foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                {
                $numberOfStatus[] = Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count();
                }

                $statistic = [];
                $booked = ClientUnit::where('status', 'booking')->count();
                $contracted = ClientUnit::where('status', 'contract')->count();
                $units = Units::count();
                $available = $units - ($booked + $contracted);
                $todayClients = number_format(homeStates()['todayClients']);
                $monthClients = number_format(homeStates()['monthClients']);
                $statistic = [$todayClients,$monthClients,$booked, $contracted, $available];
            }

            // if($key == 'statistic')
            // {

            //     $booked = ClientUnit::where('status', 'booking');
            //     $contracted = ClientUnit::where('status', 'contract');
            //     if (isset($_GET['today'])) {
            //         if ($_GET['today'] != '') {
            //             $today = $_GET['today'];
            //             $booked = ClientUnit::whereDate('created_at',$today)->where('status', 'booking');
            //             $contracted = ClientUnit::whereDate('created_at',$today)->where('status', 'contract');
            //         }
            //     }
            //     if (isset($_GET['yesterday'])) {
            //         if ($_GET['yesterday'] != '') {
            //             $yesterday = $_GET['yesterday'];
            //             $booked = ClientUnit::whereDate('created_at',$yesterday)->where('status', 'booking');
            //             $contracted = ClientUnit::whereDate('created_at',$yesterday)->where('status', 'contract');
            //         }
            //     }
            //     if (isset($_GET['week'])) {
            //         if ($_GET['week'] != '') {
            //             $week = $_GET['week'];
            //             $booked = ClientUnit::whereBetween('created_at',[$week,$currentDate])->where('status', 'booking');
            //             $contracted = ClientUnit::whereBetween('created_at',[$week,$currentDate])->where('status', 'contract');
            //         }
            //     }
            //     if (isset($_GET['month'])) {
            //         if ($_GET['month'] != '') {
            //             $month = $_GET['month'];
            //             $booked = ClientUnit::whereBetween('created_at',[$month,$currentDate])->where('status', 'booking');
            //             $contracted = ClientUnit::whereBetween('created_at',[$month,$currentDate])->where('status', 'contract');
            //         }
            //     }

            //     $statistic = [];
            //     $booked = $booked->count();
            //     $contracted =  $contracted->count();
            //     $units = Units::count();
            //     $available = $units - ($booked + $contracted);
            //     $todayClients = number_format(homeStates()['todayClients']);
            //     $monthClients = number_format(homeStates()['monthClients']);
            //     $statistic = [$todayClients,$monthClients,$booked, $contracted, $available];

            //     $unit = [];
            //     $book = ClientUnit::where('status', 'booking')->count();
            //     $contract = ClientUnit::where('status', 'contract')->count();
            //     $units = Units::count();
            //     $ava = $units - ($book + $contract);
            //     $unit = [$book, $contract, $ava];


            //     $numberOfStatus = [];
            //     foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
            //     {
            //     $numberOfStatus[] = Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count();
            //     }

            //     $sourceNum = [];
            //     foreach(clientSourceList() as $key => $source)
            //     {
            //         $sourceNum[] = Clients::where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
            //     }

            // }
        }else{
            $unit = [];
            $book = ClientUnit::where('status', 'booking')->count();
            $contract = ClientUnit::where('status', 'contract')->count();
            $units = Units::count();
            $ava = $units - ($book + $contract);
            $unit = [$book, $contract, $ava];

            $numberOfStatus = [];
            foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
            {
            $numberOfStatus[] = Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count();
            }

            $sourceNum = [];
            foreach(clientSourceList() as $key => $source)
            {
                $sourceNum[] = Clients::where('referral',$key)->whereNotIn('position',['delete','archive'])->count();
            }

            $statistic = [];
            $booked = ClientUnit::where('status', 'booking')->count();
            $contracted = ClientUnit::where('status', 'contract')->count();
            $units = Units::count();
            $available = $units - ($booked + $contracted);
            $todayClients = number_format(homeStates()['todayClients']);
            $monthClients = number_format(homeStates()['monthClients']);
            $statistic = [$todayClients,$monthClients];
        }

        return view('AdminPanel.graf',[
            'active' => 'panelHome',
            'unit' =>$unit,
            'numberOfStatus' =>$numberOfStatus,
            'sourceNum' =>$sourceNum,
            'statistic' =>$statistic,
            'title' => trans('common.Admin Panel')
        ]);
    }

    public function EditProfile()
    {
        return view('AdminPanel.loggedinUser.my-profile',[
            'active' => 'my-profile',
            'title' => trans('common.Profile'),
            'breadcrumbs' => [
                                [
                                    'url' => '',
                                    'text' => trans('common.Account')
                                ]
                            ]
        ]);
    }


    public function EditPassword()
    {
        return view('AdminPanel.loggedinUser.my-password',[
            'active' => 'my-password',
            'title' => trans('common.password'),
            'breadcrumbs' => [
                                [
                                    'url' => '',
                                    'text' => trans('common.Security')
                                ]
                            ]
        ]);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->except(['_token','password_confirmation']);

        $rules = [
                    'password' => 'required|confirmed',
                ];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return redirect()->back()
                            ->withErrors($validator)
                            ->with('faild',trans('common.faildMessageText'));
        }
        $data['password'] = bcrypt($request['password']);

        $update = User::find(auth()->user()->id)->update($data);

        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function UpdateProfile(Request $request)
    {
        $data = $request->except(['_token','photo']);
        // return $data;
        if ($request->photo != '') {
            if (auth()->user()->profile_photo != '') {
                delete_image('users/'.auth()->user()->id , auth()->user()->profile_photo);
            }
            $data['profile_photo'] = upload_image_without_resize('users/'.auth()->user()->id , $request->photo );
        }

        $update = User::find(auth()->user()->id)->update($data);
        if ($update) {
            return redirect()->route('admin.myProfile')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function notificationDetails($id)
    {
        $Notification = DatabaseNotification::find($id);
        $Notification->markAsRead();

        if (in_array($Notification['data']['type'], ['newPublisher'])) {
            return redirect()->route('admin.publisherUsers.edit',['id'=>$Notification['data']['linked_id']]);
        }
        if (in_array($Notification['data']['type'], ['newPublisherMessage'])) {
            return redirect()->route('admin.contactmessages.details',['id'=>$Notification['data']['linked_id']]);
        }

        return redirect()->back();
    }

    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function mySalary()
    {
        $month = date('m');
        $year = date('Y');
        if (isset($_GET['month']) && isset($_GET['year'])) {
            if ($_GET['month'] != '' && $_GET['year'] != '') {
                $month = $_GET['month'];
                $year = $_GET['year'];
            }
        }

        $user = User::find(auth()->user()->id);
        return view('AdminPanel.hr.salaries.view',[
            'active' => 'mySalary',
            'title' => trans('common.mySalary'),
            'user' => $user,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.mySalary')
                ]
            ]
        ]);
    }


    public function changeTheme()
    {
        $theme_mode = auth()->user()->theme_mode == 'light' ? 'dark' : 'light';
        $user = auth()->user()->update(['theme_mode'=>$theme_mode]);
    }
}
