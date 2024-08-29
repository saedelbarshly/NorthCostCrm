@extends('AdminPanel.layouts.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- profile -->
            <div class="card">
                <div class="card-body py-2 my-25">
                    {{Form::open(['files'=>'true','class'=>'validate-form','url'=>route('admin.adminUsers.store')])}}
                        <input autocomplete="false" name="hidden" type="text" style="display:none;">

                        <div class="divider">
                            <div class="divider-text">{{trans('common.MainProfileData')}}</div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="name">{{trans('common.name')}}</label>
                                {{Form::text('name','',['id'=>'name','class'=>'form-control','required'])}}
                                @if($errors->has('name'))
                                    <span class="text-danger" role="alert">
                                        <b>{{ $errors->first('name') }}</b>
                                    </span>
                                @endif
                            </div>
                            <?php /*
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="title">{{trans('common.job')}}</label>
                                {{Form::select('title',jobTitles(),'',['id'=>'title','class'=>'form-select','required'])}}
                            </div>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="leader">{{trans('common.TeamWork')}}</label>
                                {{Form::select('leader',[''=>'بدون فريق عمل'] + leadersList(),'',['id'=>'leader','class'=>'form-select'])}}
                            </div>
                            */ ?>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="another_phone">{{trans('common.phone')}}</label>
                                {{Form::text('another_phone','',['id'=>'another_phone','class'=>'form-control'])}}
                                @if($errors->has('another_phone'))
                                    <span class="text-danger" role="alert">
                                        <b>{{ $errors->first('another_phone') }}</b>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 col-sm-12 mb-1">
                                <label class="form-label" for="address">{{trans('common.address')}}</label>
                                {{Form::text('address','',['id'=>'address','class'=>'form-control'])}}
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">{{trans('common.loginData')}}</div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="username">{{trans('common.username')}}</label>
                                {{Form::text('username','',['id'=>'username','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="email">{{trans('common.email')}}</label>
                                {{Form::email('email','',['id'=>'email','class'=>'form-control'])}}
                                @if($errors->has('email'))
                                    <span class="text-danger" role="alert">
                                        <b>{{ $errors->first('email') }}</b>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="phone">{{trans('common.personalPhone')}}</label>
                                {{Form::text('phone','',['id'=>'phone','class'=>'form-control'])}}
                                @if($errors->has('phone'))
                                    <span class="text-danger" role="alert">
                                        <b>{{ $errors->first('phone') }}</b>
                                    </span>
                                @endif
                            </div>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="phone">{{trans('common.password')}}</label>
                                {{Form::password('password',['id'=>'phone','class'=>'form-control','autoComplete'=>'new-password'])}}
                            </div>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="role">{{trans('common.role')}}</label>
                                {{Form::select('role',getRolesList(session()->get('Lang'),'id','admin'),'',['id'=>'role','class'=>'form-select selectpicker','data-live-search'=>'true'])}}
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">{{trans('common.employmentDetails')}}</div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 col-sm-2 mb-1">
                                <label class="form-label" for="salary">{{trans('common.salary')}}</label>
                                {{Form::number('salary',0,['id'=>'salary','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-sm-2 mb-1">
                                <label class="form-label" for="commission">{{trans('common.commission')}}</label>
                                {{Form::number('commission',0,['id'=>'commission','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-sm-3 mb-1">
                                <label class="form-label" for="employment_date">{{trans('common.employmentDate')}}</label>
                                {{Form::date('employment_date','',['id'=>'employment_date','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-sm-2 mb-1">
                                <label class="form-label" for="zk_id">{{trans('common.zk_id')}}</label>
                                {{Form::number('zk_id','',['id'=>'zk_id','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-sm-2 mb-1">
                                <label class="form-label" for="WorkFrom">{{trans('common.WorkFrom')}}</label>
                                {{Form::time('WorkFrom','09:00',['id'=>'WorkFrom','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-sm-2 mb-1">
                                <label class="form-label" for="WorkTo">{{trans('common.WorkTo')}}</label>
                                {{Form::time('WorkTo','17:00',['id'=>'WorkTo','class'=>'form-control'])}}
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="countLateAttendance">{{trans('common.countLateAttendance')}}</label>
                                <div class="form-check form-check-success form-switch">
                                    {{Form::checkbox('countLateAttendance','1',true,['id'=>'countLateAttendance', 'class'=>'form-check-input'])}}
                                    <label class="form-check-label" for="countLateAttendance"></label>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="countOverTime">{{trans('common.countOverTime')}}</label>
                                <div class="form-check form-check-success form-switch">
                                    {{Form::checkbox('countOverTime','1',true,['id'=>'countOverTime', 'class'=>'form-check-input'])}}
                                    <label class="form-check-label" for="countOverTime"></label>
                                </div>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">{{trans('common.files')}}</div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <div class="col-md-3 text-center">
                                <label class="form-label" for="profile_photo">{{trans('common.profilePhoto')}}</label>
                                <div class="file-loading"> 
                                    <input class="files" name="profile_photo" type="file">
                                </div>
                                @if($errors->has('profile_photo'))
                                    <span class="text-danger" role="alert">
                                        <b>{{ $errors->first('profile_photo') }}</b>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3 text-center">
                                <label class="form-label" for="identity">{{trans('common.identity')}}</label>
                                <div class="file-loading"> 
                                    <input class="files" name="identity" type="file" multiple>
                                </div>
                                @if($errors->has('identity'))
                                    <span class="text-danger" role="alert">
                                        <b>{{ $errors->first('identity') }}</b>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-12 text-center">
                                <label class="form-label" for="files">{{trans('common.files')}}</label>
                                <div class="file-loading"> 
                                    <input class="files" name="attachments[]" type="file" multiple>
                                </div>
                            </div>

                        </div>

                        <!-- form -->
                        <div class="row pt-3">

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary mt-1 me-1">{{trans('common.Save changes')}}</button>
                            </div>
                        </div>
                        <!--/ form -->
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop