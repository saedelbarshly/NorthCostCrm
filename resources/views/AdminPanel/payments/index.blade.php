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
                                <th>{{trans('common.safe')}}</th>
                                <th class="text-center">{{trans('common.user')}}</th>
                                <th class="text-center">{{trans('common.amount')}}</th>
                                <th class="text-center">{{trans('common.details')}}</th>
                                @if(userCan('revenues_edit') || userCan('revenues_delete'))
                                    <th class="text-center">{{trans('common.actions')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr id="row_{{$payment->id}}">
                                    <td>
                                        {{$payment['Date']}}
                                    </td>
                                    <td>{{$payment->safe->Title ?? ''}}</td>
                                    <td class="text-center">
                                        {{$payment->responsible->name ?? '-'}}
                                    </td>
                                    <td class="text-center">
                                        {{$payment->amount}}
                                    </td>
                                    <td class="text-center">
                                        {{$payment->Notes}}
                                    </td>
                                    @if(userCan('revenues_edit') || userCan('revenues_delete'))
                                        <td class="text-center">
                                            @if(userCan('revenues_edit'))
                                                <a href="javascript:;" data-bs-target="#editRevenue{{$payment->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                                    <i data-feather='edit'></i>
                                                </a>
                                            @endif
                                                
                                            @if(userCan('revenues_delete'))
                                                <?php $delete = route('admin.revenues.delete',['id'=>$payment->id]); ?>
                                                <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$payment->id}}')">
                                                    <i data-feather='trash-2'></i>
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    @if(userCan('revenues_edit') || userCan('revenues_delete'))
                                        <td colspan="5" class="p-3 text-center ">
                                    @else
                                        <td colspan="4" class="p-3 text-center ">
                                    @endif
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                            <tr>
                                <td colspan="2" class="text-center">
                                    {{trans('common.total')}}
                                </td>
                                <td class="text-center">{{$contract->payments()->sum('amount')}}</td>
                                @if(userCan('revenues_edit') || userCan('revenues_delete'))
                                    <td colspan="2" class="text-center">
                                @else
                                    <td class="text-center">
                                @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
    <!-- Bordered table end -->

@if(userCan('revenues_edit'))

    @foreach($payments as $payment)
        <div class="modal fade text-md-start" id="editRevenue{{$payment->id}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-Revenue">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-5 px-sm-5 pt-50">
                        <div class="text-center mb-2">
                            <h1 class="mb-1">{{trans('common.edit')}}: {{$payment['name_'.session()->get('Lang')]}}</h1>
                        </div>
                        {{Form::open(['files'=>'true','url'=>route('admin.payments.update',['id'=>$payment->contract_id,'pid'=>$payment->id]), 'id'=>'editRevenueForm'.$payment->id, 'class'=>'row gy-1 pt-75'])}}
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="Date">{{trans('common.date')}}</label>
                                {{Form::date('Date',$payment->Date,['id'=>'Date', 'class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="amount">{{trans('common.amount')}}</label>
                                {{Form::number('amount',$payment->amount,['step'=>'.01','id'=>'amount', 'class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="Type">{{trans('common.type')}}</label>
                                {{Form::select('Type',revenuesTypes(session()->get('Lang')),$payment->Type,['id'=>'Type', 'class'=>'form-select','required'])}}
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="safe_id">{{trans('common.safe')}}</label>
                                {{Form::select('safe_id',safesList(),$payment->safe_id,['id'=>'safe_id', 'class'=>'form-select','required'])}}
                            </div>
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="Notes">{{trans('common.details')}}</label>
                                {{Form::textarea('Notes',$payment->Notes,['rows'=>'2','id'=>'Notes', 'class'=>'form-control'])}}
                            </div>

                            <div class="divider">
                                <div class="divider-text">{{trans('common.files')}}</div>
                            </div>

                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12 text-center">
                                    <label class="form-label" for="attachment">{{trans('common.attachment')}}</label>
                                    @if($payment->Files != '')
                                        <div class="row mb-2">
                                            {!!$payment->attachmentsHtml()!!}
                                        </div>
                                    @endif
                                    <div class="file-loading"> 
                                        <input class="files" name="Attachments[]" type="file" multiple>
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
    @endforeach

@endif

@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#createPayment" data-bs-toggle="modal" class="btn btn-sm btn-primary">
        {{trans('common.CreateNew')}}
    </a>

    <div class="modal fade text-md-start" id="createPayment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                    </div>
                    {{Form::open(['files'=>'true','url'=>route('admin.payments.store',$contract->id), 'id'=>'createPaymentForm', 'class'=>'row gy-1 pt-75'])}}
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="Date">{{trans('common.date')}}</label>
                            {{Form::date('Date',date('Y-m-d'),['id'=>'Date', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-2">
                            <label class="form-label" for="amount">{{trans('common.amount')}}</label>
                            {{Form::number('amount','',['step'=>'.01','id'=>'amount', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="safe_id">{{trans('common.safe')}}</label>
                            {{Form::select('safe_id',safesList(),'',['id'=>'safe_id', 'class'=>'form-select','required'])}}
                        </div>
                        <div class="col-12 col-md-12">
                            <label class="form-label" for="Notes">{{trans('common.details')}}</label>
                            {{Form::textarea('Notes','',['rows'=>'2','id'=>'Notes', 'class'=>'form-control'])}}
                        </div>

                        <div class="divider">
                            <div class="divider-text">{{trans('common.files')}}</div>
                        </div>

                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12 text-center">
                                <label class="form-label" for="Attachments">{{trans('common.attachment')}}</label>
                                <div class="file-loading"> 
                                    <input class="files" name="Attachments[]" type="file" multiple>
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
