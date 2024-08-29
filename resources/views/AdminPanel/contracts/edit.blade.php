@extends('AdminPanel.layouts.master')
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{Form::open(['url'=>route('admin.contracts.update',$contract->id), 'id'=>'createContractForm', 'class'=>'row gy-1 pt-75','files'=>'true'])}}
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="name">اسم المشروع</label>
                            {{Form::text('name',$contract->name,['id'=>'name', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="contractDate">تاريخ التوقيع</label>
                            {{Form::date('contractDate',$contract->contractDate,['id'=>'contractDate', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="ClientID">العميل</label>
                            {{Form::select('ClientID',clientsList(),$contract->ClientID,['id'=>'ClientID', 'class'=>'form-control selectpicker','data-live-search'=>'true','required'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="AgentID">موظف المبيعات</label>
                            {{Form::select('AgentID',agentsList(),$contract->AgentID,['id'=>'AgentID', 'class'=>'form-control selectpicker','data-live-search'=>'true'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="contractWorkDays">أيام العمل</label>
                            {{Form::number('contractWorkDays',$contract->contractWorkDays,['id'=>'contractWorkDays', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="contractEndDate">ميعاد التسليم</label>
                            {{Form::date('contractEndDate',$contract->contractEndDate,['id'=>'contractEndDate', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="Status">الحالة</label>
                            {{Form::select('Status',contractStatusList(),$contract->Status,['id'=>'Status', 'class'=>'form-control selectpicker'])}}
                        </div>
                        
                        <div class="row pt-1">
                            <div class="col-12">
                                <div class="divider">
                                    <div class="divider-text">خدمات التعاقد</div>
                                </div>
                            </div>
                            <div class="repeatableContractServicess col-sm-12">
                                @forelse($contract->servicesList as $i => $service)
                                    <div class="row mb-1 More">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label" for="service_id">الخدمة</label>
                                            {{Form::select('service_id[]',servicesList(),$service->service_id,['id'=>'service_id','class'=>'form-select','required'])}}
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label" for="service_price">السعر</label>
                                            {{Form::number('service_price[]',$service->price,['id'=>'service_price', 'onkeyup'=>'calculateTotal()', 'class'=>'form-control','required'])}}
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label class="form-label" for="service_renewal">دورة الدفع</label>
                                            {{Form::select('service_renewal[]',serviceRenewalsList(),$service->service_renewal,['id'=>'service_renewal', 'class'=>'form-control','required'])}}
                                        </div>
                                        @if($i > 0)
                                            <div class="col-1 col-md-2 mt-2">
                                                <span class="delete btn btn-icon btn-danger btn-block">
                                                    {{trans('common.delete')}}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="row mb-1">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label" for="service_id">الخدمة</label>
                                            {{Form::select('service_id[]',servicesList(),'',['id'=>'service_id','class'=>'form-select','required'])}}
                                        </div>

                                        <div class="col-12 col-md-3">
                                            <label class="form-label" for="service_price">السعر</label>
                                            {{Form::number('service_price[]','',['id'=>'service_price', 'onkeyup'=>'calculateTotal()', 'class'=>'form-control','required'])}}
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <label class="form-label" for="service_renewal">دورة الدفع</label>
                                            {{Form::select('service_renewal[]',serviceRenewalsList(),'',['id'=>'service_renewal', 'class'=>'form-control','required'])}}
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="col-12 mt-2">
                                <span class="add_ContractServices btn btn-sm btn-info">خدمة إضافية</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="divider">
                                    <div class="divider-text">الدفعات المالية</div>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="Total">إجمالي التعاقد</label>
                                {{Form::number('Total',$contract->Total,['id'=>'Total', 'class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="paid">المدفوع</label>
                                {{$contract->totals()['paid']}}
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="paid">المتبقى</label>
                                {{$contract->totals()['rest']}}
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="Notes">تفاصيل</label>
                            {{Form::textarea('Notes',$contract->name,['id'=>'Notes', 'class'=>'form-control editor_ar'])}}
                        </div>
                        <div class="divider">
                            <div class="divider-text">{{trans('common.files')}}</div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12 text-center">
                                <label class="form-label" for="Attachments">{{trans('common.attachment')}}</label>
                                @if($contract->Files != '')
                                    <div class="row mb-2">
                                        {!!$contract->FilesHtml()!!}
                                    </div>
                                @endif
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

@stop


@section('scripts')
    <script type="text/template" id="RepeatContractServicesTPL">
        <div class="More row mb-1">
            <div class="col-12 col-sm-3">
                <label class="form-label" for="service_id">الخدمة</label>
                {{Form::select('service_id[]',servicesList(),'',['id'=>'service_id','class'=>'form-select','required'])}}
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label" for="service_price">السعر</label>
                {{Form::number('service_price[]','',['id'=>'service_price', 'onkeyup'=>'calculateTotal()', 'class'=>'form-control','required'])}}
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label" for="service_renewal">دورة الدفع</label>
                {{Form::select('service_renewal[]',serviceRenewalsList(),'',['id'=>'service_renewal', 'class'=>'form-control','required'])}}
            </div>
            <div class="col-1 col-md-2 mt-2">
                <span class="delete btn btn-icon btn-danger btn-block">
                    {{trans('common.delete')}}
                </span>
            </div>
        </div>
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            var max_fields              = 50;
            var ContractServices_wrapper   = $(".repeatableContractServicess");
            var add_ContractServices       = $(".add_ContractServices");
            var RepeatContractServicesTPL  = $("#RepeatContractServicesTPL").html();


            var x = 1;
            $(add_ContractServices).click(function(e){
                e.preventDefault();
                if(x < max_fields){
                    x++;
                    $(ContractServices_wrapper).append(RepeatContractServicesTPL); //add input box
                }else{
                    alert('You Reached the limits')
                }
            });

            $(ContractServices_wrapper).on("click",".delete", function(e){
                e.preventDefault(); $(this).closest('.More').remove(); x--;
                calculateTotal();
            });


        });

        function calculateTotal() {
            var arr = document.getElementsByName('service_price[]');
            var tot=0;
            for(var i=0;i<arr.length;i++){
                if(parseInt(arr[i].value))
                    tot += parseInt(arr[i].value);
            }
            if (tot > 0) {
                document.getElementById('Total').value = tot;
            } else {
                document.getElementById('Total').value = 0;
            }
        }
    </script>
@stop
