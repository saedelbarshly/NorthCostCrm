@extends('AdminPanel.layouts.master')

@section('content')
<?php
    $booked = App\Models\ClientUnit::where('status','booking')->count();
    $contracted = App\Models\ClientUnit::where('status','contract')->count();
    $units = App\Models\Units::count();

    $positions = [
        'call_center',
        'reception',
        'sales',
        'contract',
        'contracts',
        'coordinator',
        'contractFollowUp',
        'archive'
    ];
?>
    <!-- Dashboard Analytics Start -->
    <section id="dashboard-analytics">
        <div class="row">
            @if(userCan('view_home_stats'))

                <?php
                    $booked = App\Models\ClientUnit::where('status','booking')->count();
                    $contracted = App\Models\ClientUnit::where('status','contract')->count();
                    $units = App\Models\Units::count();
                ?>
                <div class="col-lg-12 col-12">
                    <div class="card card-statistics">
                        <div class="card-body statistics-body">
                            <div class="row justify-content-center">
                                <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-1">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{route('admin.units',['status'=>'booking'])}}">
                                                <h4 class="fw-bolder mb-0">
                                                    {{$booked}}
                                                </h4>
                                                <p class="card-text font-small-3 mb-0">
                                                    {{trans('common.booked_up')}}
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-1">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{route('admin.units',['status'=>'contract'])}}">
                                                <h4 class="fw-bolder mb-0">
                                                    {{$contracted}}
                                                </h4>
                                                <p class="card-text font-small-3 mb-0">
                                                    {{trans('common.contracted')}}
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-1">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <a href="{{route('admin.units',['status'=>'available'])}}">
                                                <h4 class="fw-bolder mb-0">
                                                    {{$units - ($booked + $contracted)}}
                                                </h4>
                                                <p class="card-text font-small-3 mb-0">
                                                    {{trans('common.available')}}
                                                </p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--/ Line Chart -->
                <div class="col-lg-12 col-12">
                    <div class="card card-statistics">
                        <div class="card-title text-center p-2 pb-0">
                            إجماليات
                        </div>
                        <div class="card-body statistics-body">
                            <div class="row justify-content-center">
                                @foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
                                {{-- @dd(clientStatusArray(session()->get('Lang'))) --}}
                                    <div class="col-md-3 col-sm-6 col-12 mb-1">
                                        <div class="d-flex flex-row" style="align-items: flex-start;">
                                            <div class="avatar bg-light-primary me-1">
                                                <div class="avatar-content">
                                                    <a href="{{route('admin.possibleClients',['status'=>$key,'position[]'=>$positions])}}">
                                                        <i data-feather="user" class="avatar-icon"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{route('admin.possibleClients',['status'=>$key,'position[]'=>$positions])}}">
                                                    <h4 class="fw-bolder mb-0">
                                                        {{App\Models\Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count()}}
                                                    </h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                        {{$status}}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Line Chart -->
                <div class="col-lg-12 col-12">
                    <div class="card card-statistics">
                        <div class="card-title text-center p-2 pb-0">
                            {{trans('common.clientSources')}}
                        </div>
                        <div class="card-body statistics-body">
                            <div class="row justify-content-center">
                                @foreach(clientSourceList() as $key => $source)
                                    <div class="col-md-3 col-sm-6 col-12 mb-1">
                                        <div class="d-flex flex-row" style="align-items: flex-start;">
                                            <div class="avatar bg-light-primary me-1">
                                                <div class="avatar-content">
                                                    <a href="{{route('admin.possibleClients',['source_id'=>$key,'position[]'=>$positions])}}">
                                                        <i data-feather="user" class="avatar-icon"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{route('admin.possibleClients',['source_id'=>$key,'position[]'=>$positions])}}">
                                                    <h4 class="fw-bolder mb-0">
                                                        {{App\Models\Clients::where('referral',$key)->whereNotIn('position',['delete','archive'])->count()}}
                                                    </h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                        {{$source}}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>


                <!--/ Line Chart -->
                <div class="col-lg-6">
                    <div class="card card-statistics">
                        <div class="card-header">
                            <h4 class="card-title">{{trans('common.Statistics')}}</h4>
                        </div>
                        <div class="card-body statistics-body">
                            <div class="row justify-content-center">
                                <div class="col-md-5 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-1">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">
                                                <a href="{{route('admin.possibleClients',['time'=>'today','position[]'=>$positions])}}">
                                                    {{number_format(homeStates()['todayClients'])}}
                                                </a>
                                            </h4>
                                            <p class="card-text font-small-3 mb-0">عملاء اليوم</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-6 col-12 mb-2 mb-md-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-1">
                                            <div class="avatar-content">
                                                <i data-feather="user" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{number_format(homeStates()['monthClients'])}}</h4>
                                            <p class="card-text font-small-3 mb-0">عملاء الشهر</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-1">
                                    <div class="col-md-4 col-sm-6 col-12 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{route('admin.units',['status'=>'booking'])}}">
                                                    <h4 class="fw-bolder mb-0">
                                                        {{$booked}}
                                                    </h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                        {{trans('common.booked_up')}}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{route('admin.units',['status'=>'contract'])}}">
                                                    <h4 class="fw-bolder mb-0">
                                                        {{$contracted}}
                                                    </h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                        {{trans('common.contracted')}}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-12 mb-2 mb-md-0">
                                        <div class="d-flex flex-row">
                                            <div class="avatar bg-light-primary me-1">
                                                <div class="avatar-content">
                                                    <i data-feather="user" class="avatar-icon"></i>
                                                </div>
                                            </div>
                                            <div class="my-auto">
                                                <a href="{{route('admin.units',['status'=>'available'])}}">
                                                    <h4 class="fw-bolder mb-0">
                                                        {{$units - ($booked + $contracted)}}
                                                    </h4>
                                                    <p class="card-text font-small-3 mb-0">
                                                        {{trans('common.available')}}
                                                    </p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Greetings Card starts -->
            <div class="col-lg-6">
                <div class="row match-height">
                    <div class="col-12">
                        <div class="divider mt-0">
                            <div class="divider-text">{{trans('common.ShortCut')}}</div>
                        </div>
                    </div>
                    @if(userCan('clients_create'))
                        <div class="col-md-4 col-xl-4">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <a href="javascript:;" data-bs-target="#createClientModal" data-bs-toggle="modal" class="text-white">
                                        {{trans('common.clients')}} - {{trans('common.CreateNew')}}
                                    </a>
                                    @include('AdminPanel.clients.create',['position'=>'sales'])
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(userCan('clients_create_excel'))
                        <div class="col-md-4 col-xl-4">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <a href="{{route('admin.clients')}}" class="text-white">

                                    </a>
                                    <a href="javascript:;" data-bs-target="#createExcelClient" data-bs-toggle="modal" class="text-white">
                                        {{trans('common.clients')}} - {{trans('common.uploadExcel')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @include('AdminPanel.clients.createExcel')
                    @endif
                    <div class="col-md-4 col-xl-4">
                        <div class="card bg-primary text-white text-center">
                            <div class="card-body">
                                <a href="javascript:;" data-bs-target="#createFollowUp" data-bs-toggle="modal" class="text-white">
                                    {{trans('common.CreateNewFollowUp')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12">
                        <div class="divider mt-0">
                            <div class="divider-text">{{trans('common.reports')}}</div>
                        </div>
                    </div>
                    @if(userCan('reports_rejectionCauses'))
                        <div class="col-md-4 col-xl-4">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <a class="text-white" href="{{route('admin.reports.rejectionCauses')}}">
                                        {{trans('common.rejectionCauses')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(userCan('reports_teamPerformance'))
                        <div class="col-md-4 col-xl-4">
                            <div class="card bg-primary text-white text-center">
                                <div class="card-body">
                                    <a class="text-white" href="{{route('admin.reports.teamPerformance')}}">
                                        {{trans('common.teamPerformance')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
            <!-- Greetings Card ends -->

        </div>
    </section>
    <!-- Dashboard Analytics end -->
    <div class="modal fade text-md-start" id="createFollowUp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.CreateNewFollowUp')}}</h1>
                </div>
                {{Form::open(['url'=>route('admin.followups.store'), 'id'=>'createFollowUpForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="row">
                        <div class="col-md-4">
                            <b>{{trans('common.agent')}}: </b>
                            {{auth()->user()->name}}
                        </div>
                        <div class="col-md-4">
                            <b>{{trans('common.date')}}: </b>
                            {{date('Y-m-d')}}
                        </div>
                        <div class="col-md-4">
                            <b>{{trans('common.time')}}: </b>
                            {{date('H:i:s')}}
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="ClientID">{{trans('common.client')}}</label>
                        {{Form::select('ClientID',clientsList(session()->get('Lang')),'',['id'=>'ClientID', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="contactingType">{{trans('common.followUpType')}}</label>
                        {{Form::select('contactingType',contactingTypeList(session()->get('Lang')),'',['id'=>'contactingType', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="whoIsContacting">{{trans('common.whoIsContacting')}}</label>
                        {{Form::select('whoIsContacting',whoIsContactingList(session()->get('Lang')),'',['id'=>'whoIsContacting', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="UnitID">{{trans('common.unit')}}</label>
                        {{Form::select('UnitID',['All' => 'كل الوحدات'] + unitsList(),'',['id'=>'UnitID', 'class'=>'selectpicker' ,'data-live-search'=>'true'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="status">{{trans('common.statusChanger')}}</label>
                        {{Form::select('status',['' => trans('common.Select')] + clientStatusArray(session()->get('Lang')),'',['id'=>'status', 'class'=>'form-control','onchange'=>'changeStatus(this)','required'])}}
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextContactingDateTime">{{trans('common.nextContactingDate')}}</label>
                        {{Form::date('nextContactingDateTime','',['id'=>'nextContactingDateTime', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextContactingTime">الوقت</label>
                        {{Form::time('nextContactingTime','',['id'=>'nextContactingTime', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="nextContactingType">{{trans('common.followUpType')}}</label>
                        {{Form::select('nextContactingType',[''=>trans('common.none')] + contactingTypeList(session()->get('Lang')),'',['id'=>'nextContactingType', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="offerDetails">تفاصيل المحادثة</label>
                        {{Form::textarea('offerDetails','',['id'=>'offerDetails', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">{{trans('common.Save changes')}}</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            {{trans('common.Cancel')}}
                        </button>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
    <script>
        function changeStatus(elem) {
            var rejectionList = elem.parentNode.parentNode.querySelector('#rejictionCause');
            if (elem.value != 'no_interest' && elem.value != 'checkout_reject') {
                rejectionList.parentNode.style.display = "none";
            } else {
                rejectionList.parentNode.style.display = "block";
            }
        }
    </script>
@stop
