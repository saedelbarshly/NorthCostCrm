<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Users\CreateUser;
use App\Http\Requests\Users\EditUser;
use App\Models\User;
use App\Models\EmploymentProfile;
use App\Models\EmployeeSalaryChanges;
use Response;

class AdminUsersController extends Controller
{
    //
    public function index()
    {
        $users = User::orderBy('id','desc');
        if (isset($_GET['role'])) {
            if ($_GET['role'] != '') {
                $users = $users->where('role',$_GET['role']);
            }
        }
        if (isset($_GET['status'])) {
            if ($_GET['status'] != '') {
                $users = $users->where('status',$_GET['status']);
            }
        } else {
            $users = $users->where('status','Active');
        }
        $users = $users->paginate(25);
        return view('AdminPanel.admins.index',[
            'active' => 'adminUsers',
            'title' => trans('common.users'),
            'users' => $users,
            'breadcrumbs' => [
                [
                    'url' => '',
                    'text' => trans('common.users')
                ]
            ]
        ]);
    }

    public function blockAction($id,$action)
    {
        $update = User::find($id)->update([
            'status' => $action == '1' ? 'Archive' : 'Active'
        ]);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }
    }

    public function create()
    {
        return view('AdminPanel.admins.create',[
            'active' => 'adminUsers',
            'title' => trans('common.users'),
            'breadcrumbs' => [
                                [
                                    'url' => route('admin.adminUsers'),
                                    'text' => trans('common.users')
                                ],
                                [
                                    'url' => '',
                                    'text' => trans('common.CreateNew')
                                ]
                            ]
        ]);
    }

    public function store(CreateUser $request)
    {
        $data = $request->except(['_token','password','hidden','attachments','profile_photo','identity','salary']);
        if ($request['password'] != '') {
            $data['password'] = bcrypt($request['password']);
        }
        $data['status'] = 'Active';

        $user = User::create($data);
        if ($request->profile_photo != '') {
            $user['profile_photo'] = upload_image_without_resize('users/'.$user->id , $request->profile_photo );
            $user->update();
        }
        if ($request->identity != '') {
            $user['identity'] = upload_image_without_resize('users/'.$user->id , $request->identity );
            $user->update();
        }
        if ($request->attachments != '') {
            $Files = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $file) {
                    $Files[] = upload_image_without_resize('users/'.$user->id , $file );
                }
                $user['files'] = base64_encode(serialize($Files));
                $user->update();
            }
        }
        //في حالة تغيير الراتب
        if ($request->salary > 0) {
            //تسجيل تاريخ الراتب بالشهر والسنة
            $update = EmployeeSalaryChanges::create([
                'UID' => auth()->user()->id,
                'EmployeeID' => $user->id,
                'Salary' => $request->salary,
                'fromMonth' => date('m'),
                'fromYear' => date('Y')
            ]);
        }

        if ($user) {
            return redirect()->route('admin.adminUsers')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('AdminPanel.admins.edit',[
            'active' => 'adminUsers',
            'title' => trans('common.users'),
            'user' => $user,
            'breadcrumbs' => [
                                [
                                    'url' => route('admin.adminUsers'),
                                    'text' => trans('common.users')
                                ],
                                [
                                    'url' => '',
                                    'text' => trans('common.edit').': '.$user->name
                                ]
                            ]
        ]);
    }

    public function update(EditUser $userRequest, $id)
    {
        $user = User::find($id);
        $data = $userRequest->except(['_token','password','hidden','attachments','profile_photo','identity','salary']);
        // return $data;
        if ($userRequest->unemployment_date != '') {
            $data['status'] = 'Archive';
        }
        if ($userRequest->photo != '') {
            if ($user->photo != '') {
                delete_image('users/'.$id , $user->photo);
            }
            $data['photo'] = upload_image_without_resize('users/'.$id , $userRequest->photo );
        }
        if ($userRequest['password'] != '') {
            $data['password'] = bcrypt($userRequest['password']);
        }
        if ($userRequest->attachments != '') {
            if ($user->files != '') {
                $Files = unserialize(base64_decode($user->files));
            } else {
                $Files = [];
            }
            if ($userRequest->hasFile('attachments')) {
                foreach ($userRequest->attachments as $file) {
                    $Files[] = upload_image_without_resize('users/'.$id , $file );
                }
                $data['files'] = base64_encode(serialize($Files));
            }
        }
        //في حالة تغيير الراتب
        if ($userRequest->salary != $user->salary) {
            //تسجيل تاريخ الراتب بالشهر والسنة
            $update = EmployeeSalaryChanges::create([
                'UID' => auth()->user()->id,
                'EmployeeID' => $id,
                'Salary' => $userRequest->salary,
                'fromMonth' => date('m'),
                'fromYear' => date('Y')
            ]);
        }

        $update = User::find($id)->update($data);
        if ($update) {
            return redirect()->route('admin.adminUsers')
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

    public function DeleteuserPhoto($id,$photo,$X)
    {
        if (!userCan('users_delete_photo')) {
            return Response::json("false");
        }
        $user = User::find($id);
        $Files = [];
        if ($user->files != '') {
            $Files = unserialize(base64_decode($user->files));
        }
        if (in_array($photo,$Files)) {
            delete_image('uploads/users/'.$id , $photo);
            if (($key = array_search($photo, $Files)) !== false) {
                unset($Files[$key]);
            }
            $user['files'] = base64_encode(serialize($Files));
            $user->update();
            return Response::json('photo_'.$X);
        }
        return Response::json("false");
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user->delete()) {
            return Response::json($id);
        }
        return Response::json("false");
    }


    public function hrProfile($id)
    {
        $user = User::find($id);
        // return $user->employmentProfile;
        return view('AdminPanel.admins.hrProfile',[
            'active' => 'adminUsers',
            'user' => $user,
            'title' => trans('common.users'),
            'breadcrumbs' => [
                                [
                                    'url' => route('admin.adminUsers'),
                                    'text' => trans('common.users')
                                ],
                                [
                                    'url' => '',
                                    'text' => trans('common.hrProfile').': '.$user->name
                                ]
                            ]
        ]);
    }

    public function updateHRProfile(Request $request,$id)
    {
        // return $request;
        $data = $request->except(['_token','hidden','qualifications',
                                'experience','chronic_internal_diseases','pandemic_viruses',
                                'medicine','hypersensitivity','disability','salary_type','attachments']);

        //qualifications
        if (count($request['qualifications']['Title']) > 0) {
            $qualifications = [];
            for ($i=0; $i < count($request['qualifications']['Title']); $i++) {
                $qualifications[] = [
                    'Title' => $request['qualifications']['Title'][$i],
                    'University' => $request['qualifications']['University'][$i],
                    'College' => $request['qualifications']['College'][$i],
                    'Year' => $request['qualifications']['Year'][$i],
                    'specialization' => $request['qualifications']['specialization'][$i]
                ];
            }
            if (count($qualifications) > 0) {
                $data['qualifications'] = base64_encode(serialize($qualifications));
            }
        }
        //experience
        if (count($request['experience']['Job']) > 0) {
            $experience = [];
            for ($i=0; $i < count($request['experience']['Job']); $i++) {
                $experience[] = [
                    'Job' => $request['experience']['Job'][$i],
                    'Company' => $request['experience']['Company'][$i],
                    'EmploymentDate' => $request['experience']['EmploymentDate'][$i],
                    'EndEmploymentDate' => $request['experience']['EndEmploymentDate'][$i]
                ];
            }
            if (count($experience) > 0) {
                $data['experience'] = base64_encode(serialize($experience));
            }
        }
        if (isset($request['chronic_internal_diseases']['status'])) {
            $data['chronic_internal_diseases'] = base64_encode(serialize($request['chronic_internal_diseases']));
        }
        if (isset($request['pandemic_viruses']['status'])) {
            $data['pandemic_viruses'] = base64_encode(serialize($request['pandemic_viruses']));
        }
        if (isset($request['medicine']['status'])) {
            $data['medicine'] = base64_encode(serialize($request['medicine']));
        }
        if (isset($request['hypersensitivity']['status'])) {
            $data['hypersensitivity'] = base64_encode(serialize($request['hypersensitivity']));
        }
        if (isset($request['disability']['status'])) {
            $data['disability'] = base64_encode(serialize($request['disability']));
        }
        if (isset($request['salary_type'])) {
            $data['salary_type'] = base64_encode(serialize($request['salary_type']));
        }
        $user = User::find($id);
        $profile = $user->employmentProfile;
        if ($profile == '') {
            $profile = EmploymentProfile::create(['EmployeeID'=>$id]);
        }
        if ($request->attachments != '') {
            if ($profile->attachments != '') {
                $attachments = unserialize(base64_decode($profile->attachments));
            } else {
                $attachments = [];
            }
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $file) {
                    $attachments[] = upload_image_without_resize('users/'.$id , $file );
                }
                $data['attachments'] = base64_encode(serialize($attachments));
            }
        }
        $update = $profile->update($data);
        if ($update) {
            return redirect()->back()
                            ->with('success',trans('common.successMessageText'));
        } else {
            return redirect()->back()
                            ->with('faild',trans('common.faildMessageText'));
        }

    }

}
