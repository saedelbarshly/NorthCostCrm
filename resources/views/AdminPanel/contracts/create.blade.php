
<div class="modal fade text-md-start" id="createContract" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.CreateNewContract')}}</h1>
                </div>
                {{Form::open(['url'=>route('admin.contracts.store'), 'id'=>'createContractForm', 'class'=>'row gy-1 pt-75','files'=>'true'])}}
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="name">اسم المشروع</label>
                        {{Form::text('name','',['id'=>'name', 'class'=>'form-control','required'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="contractDate">تاريخ التوقيع</label>
                        {{Form::date('contractDate',date('Y-m-d'),['id'=>'contractDate', 'class'=>'form-control','required'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="ClientID">العميل</label>
                        {{Form::select('ClientID',[''=>'اختر العميل'] + clientsList(),isset($clientData) ? $clientData->id : '',['id'=>'ClientID', 'class'=>'form-control selectpicker','data-live-search'=>'true','required'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="AgentID">موظف المبيعات</label>
                        {{Form::select('AgentID',agentsList(),auth()->user()->id,['id'=>'AgentID', 'class'=>'form-control selectpicker','data-live-search'=>'true'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="contractWorkDays">أيام العمل</label>
                        {{Form::number('contractWorkDays','',['id'=>'contractWorkDays', 'class'=>'form-control','required'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="contractEndDate">ميعاد التسليم</label>
                        {{Form::date('contractEndDate','',['id'=>'contractEndDate', 'class'=>'form-control','required'])}}
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="Notes">تفاصيل</label>
                        {{Form::textarea('Notes','',['id'=>'Notes', 'class'=>'form-control editor_ar'])}}
                    </div>
                    <div class="divider">
                        <div class="divider-text">{{trans('common.files')}}</div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <div class="col-md-12 text-center">
                            <label class="form-label" for="Attachments">{{trans('common.attachment')}}</label>
                            <div class="file-loading"> 
                                <input class="files" name="Files[]" type="file" id="Attachments" multiple>
                            </div>
                        </div>
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