@extends('AdminPanel.layouts.master')
@section('content')


    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{$title}}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-2">
                        <thead>
                            <tr>
                                <th>{{trans('common.date')}}</th>
                                <th>{{trans('common.user')}}</th>
                                @if(!isset($_GET['client_id']))
                                    <th>{{trans('common.client')}}</th>
                                @else
                                    <th>تفاصيل المحادثة</th>
                                @endif
                                <th class="text-center">
                                    {{trans('common.actions')}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($followups as $followup)
                            <tr id="row_{{$followup->id}}">
                                <td>
                                    {{$followup->contactingDateTime}}
                                </td>
                                <td>
                                    {{$followup->agent->name ?? '-'}}
                                </td>
                                @if(!isset($_GET['client_id']))
                                    <td>
                                        {{$followup->client->Name ?? '-'}}
                                        <div class="col-12"></div>
                                        @if($followup && $followup->client && $followup->client->cellphone !== null)
                                            <span class="btn btn-sm btn-primary mb-25">
                                                <b>{{trans('common.mobile')}}: </b>
                                                <a href="call:{{$followup->client->cellphone}}" class="text-white">
                                                    {{$followup->client->cellphone ?? '-'}}
                                                </a>
                                            </span>
                                        @endif
                                        <div class="col-12"></div>
                                        @if($followup && $followup->client && $followup->client->whatsapp !== null)
                                            <span class="btn btn-sm btn-danger">
                                                <b>{{trans('common.whatsapp')}}: </b>
                                                <a href="https://api.whatsapp.com/send?phone={{$followup->client->whatsapp}}" class="text-white">
                                                    {{$followup->client->whatsapp ?? '-'}}
                                                </a>
                                            </span>
                                        @endif
                                    </td>
                                @else
                                    <td>
                                        {{$followup->offerDetails}}
                                    </td>
                                @endif
                                <td class="text-nowrap">
                                    <a href="javascript:;" data-bs-target="#editfollowup{{$followup->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                        <i data-feather='edit'></i>
                                    </a>
                                    <?php $delete = route('admin.followups.delete',['id'=>$followup->id]); ?>
                                    <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$followup->id}}')">
                                        <i data-feather='trash-2'></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-3 text-center ">
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $followups->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->


@foreach($followups as $followup)

    <div class="modal fade text-md-start" id="editfollowup{{$followup->id}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.edit')}}: {{$followup['name_'.session()->get('Lang')]}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.followups.update',$followup->id), 'id'=>'editfollowupForm'.$followup->id, 'class'=>'row gy-1 pt-75'])}}
                        <div class="row">
                            <div class="col-md-4">
                                <b>{{trans('common.agent')}}: </b>
                                {{$followup->agent->name ?? '-'}}
                            </div>
                            <div class="col-md-4">
                                <b>{{trans('common.date')}}: </b>
                                {{$followup->contactingDateTime}}
                            </div>
                            <div class="col-md-4">
                                <b>{{trans('common.time')}}: </b>
                                {{date('H:i:s')}}
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="ClientID">{{trans('common.client')}}</label>
                            @if($followup && $followup->client && $followup->client->Name !== null)
                            <p>{{$followup->client->Name}}</p>
                            @endif
                            {{Form::hidden('ClientID',$followup->client->id)}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="contactingType">{{trans('common.followUpType')}}</label>
                            {{Form::select('contactingType',contactingTypeList(session()->get('Lang')),$followup->contactingType,['id'=>'contactingType', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="whoIsContacting">{{trans('common.whoIsContacting')}}</label>
                            {{Form::select('whoIsContacting',whoIsContactingList(session()->get('Lang')),$followup->whoIsContacting,['id'=>'whoIsContacting', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12"></div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="UnitID">{{trans('common.unit')}}</label>
                            {{Form::select('UnitID',['All' => 'كل الوحدات'] + unitsList(),$followup->UnitID,['id'=>'UnitID', 'class'=>'selectpicker' ,'data-live-search'=>'true'])}}
                        </div>
                        @if($followup->client->UID != '')
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="status">{{trans('common.statusChanger')}}</label>
                                {{Form::select('status',['' => trans('common.Select')] + clientStatusArray(session()->get('Lang')),$followup->status,['id'=>'status', 'class'=>'form-control','onchange'=>'changeStatus(this)','required'])}}
                            </div>
                        @else
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="status">{{trans('common.statusChanger')}}</label>
                                {{Form::select('status',['' => trans('common.Select')] + clientStatusArray(session()->get('Lang')),$followup->status,['id'=>'status', 'class'=>'form-control','onchange'=>'changeStatus(this)','required'])}}
                            </div>
                        @endif
                        @if($followup->client->UID != '')
                            <div class="col-12 col-md-3" style="display:none;">
                                <label class="form-label" for="rejictionCause">{{trans('common.rejictionCauses')}}</label>
                                {{Form::select('rejictionCause',['' => trans('common.none')] + salesRejectionCauses(session()->get('Lang')),$followup->rejictionCause,['id'=>'rejictionCause', 'class'=>'form-control'])}}
                            </div>
                        @else
                            <div class="col-12 col-md-3" style="display:none;">
                                <label class="form-label" for="rejictionCause">{{trans('common.rejictionCauses')}}</label>
                                {{Form::select('rejictionCause',['' => trans('common.none')] + callCenterRejectionCauses(session()->get('Lang')),$followup->rejictionCause,['id'=>'rejictionCause', 'class'=>'form-control'])}}
                            </div>
                        @endif
                        <div class="col-12"></div>
                        {{Form::hidden('nextFollowUpType','call_center')}}
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="nextContactingDateTime">{{trans('common.nextContactingDate')}}</label>
                            {{Form::date('nextContactingDateTime',$followup->nextContactingDateTime,['id'=>'nextContactingDateTime', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="nextContactingTime">الوقت</label>
                            {{Form::time('nextContactingTime',$followup->nextContactingTime,['id'=>'nextContactingTime', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="offerDetails">تفاصيل المحادثة</label>
                            {{Form::textarea('offerDetails',$followup->offerDetails,['id'=>'offerDetails', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1" @if($followup->status == 'Done' && !userCan('followups_edit') ) disabled @endif>{{trans('common.Save changes')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                {{trans('common.Cancel')}}
                            </button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>

@endforeach


@stop

@section('page_buttons')
    <?php /*
    @if(userCan('followups_create'))
        @include('AdminPanel.followups.create')
    @endif
    */ ?>

    <a href="javascript:;" data-bs-target="#searchfollowups" data-bs-toggle="modal" class="btn btn-primary btn-sm">
        {{trans('common.search')}}
    </a>

    <div class="modal fade text-md-start" id="searchfollowups" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    {{Form::open(['id'=>'searchfollowupsForm', 'class'=>'row gy-1 pt-75', 'method'=>'GET'])}}
                        @if(userCan('followups_view') || userCan('followups_view_team'))
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="AgentID">{{trans('common.agent')}}</label>
                                {{Form::select('AgentID',['all' => 'الجميع'] + agentsListForSearch(getSettingValue('sales_agent_role_id')),isset($_GET['AgentID']) ? $_GET['AgentID'] : '',['id'=>'AgentID', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                            </div>
                        @endif
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="status">{{trans('common.status')}}</label>
                            {{Form::select('status',['all'=>'الجميع'] + clientstatusArray(session()->get('Lang')),isset($_GET['status']) ? $_GET['status'] : date('Y'),['id'=>'status', 'class'=>'form-select'])}}
                        </div>
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">{{trans('common.search')}}</button>
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
