<a href="javascript:;" data-bs-target="#createFollowUp" data-bs-toggle="modal" class="btn btn-sm btn-primary">
    {{trans('common.CreateNewFollowUp')}}
</a>

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
                        {{Form::select('ClientID',clientsList(),isset($theClient) ? $theClient : '',['id'=>'ClientID', 'class'=>'form-select'])}}
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
                        {{Form::select('status',clientStatusArray(session()->get('Lang')),'',['id'=>'status', 'class'=>'form-control','onchange'=>'changeStatus(this)'])}}
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="followupDuration">{{ trans('common.followupDuration') }}</label>
                        {{Form::time('followupDuration','',['id'=>'followupDuration', 'class'=>'form-control'])}}
                    </div>

                    <div class="col-12 col-md-3" style="display:none;">
                        <label class="form-label" for="rejictionCause">{{trans('common.rejictionCauses')}}</label>
                        {{Form::select('rejictionCause',['' => trans('common.none')] + callCenterRejectionCauses(session()->get('Lang')),'',['id'=>'rejictionCause', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextFollowUpType">{{trans('common.nextFollowUpType')}}</label>
                        {{Form::select('nextFollowUpType',[''=>'بدون متابعة'] + followUpTypeList(session()->get('Lang')),'',['id'=>'nextFollowUpType', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextContactingDateTime">{{trans('common.nextContactingDate')}}</label>
                        {{Form::date('nextContactingDateTime','',['id'=>'nextContactingDateTime', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextContactingTime">الوقت</label>
                        {{Form::time('nextContactingTime','',['id'=>'nextContactingTime', 'class'=>'form-control'])}}
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
