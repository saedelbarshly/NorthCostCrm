@extends('AdminPanel.layouts.master')
@section('content')
<?php
    $month = date('m');
    $year = date('Y');
    $branch = 'all';
    if (isset($_GET['month'])) {
        if ($_GET['month'] != '') {
            $month = $_GET['month'];
        }
    }
    if (isset($_GET['year'])) {
        if ($_GET['year'] != '') {
            $year = $_GET['year'];
        }
    }
    if (isset($_GET['branch_id'])) {
        if ($_GET['branch_id'] != '') {
            $branch = $_GET['branch_id'];
        }
    }
?>

    <div class="divider">
        <div class="divider-text">{{trans('common.moneyTransfeers')}}</div>
    </div>
    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>{{trans('common.date')}}</th>
                                <th class="text-center">{{trans('common.from')}}</th>
                                <th class="text-center">{{trans('common.to')}}</th>
                                <th class="text-center">{{trans('common.amount')}}</th>
                                <th class="text-center">{{trans('common.details')}}</th>
                                @if(userCan('money_transfeers_edit') || userCan('money_transfeers_delete'))
                                    <th class="text-center">{{trans('common.actions')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transfeers as $transfeer)
                                <tr id="row_{{$transfeer->id}}">
                                    <td>
                                        {{$transfeer['date']}}
                                    </td>
                                    <td class="text-center">
                                        {{$transfeer->fromSafe->Title ?? 'خزينة محذوفة'}}
                                    </td>
                                    <td class="text-center">
                                        {{$transfeer->toSafe->Title ?? 'خزينة محذوفة'}}
                                    </td>
                                    <td class="text-center">
                                        {{$transfeer->amount}}
                                    </td>
                                    <td class="text-center">
                                        {!!$transfeer->notes!!}
                                    </td>
                                    @if(userCan('money_transfeers_edit') || userCan('money_transfeers_delete'))
                                        <td class="text-center">
                                            @if(userCan('money_transfeers_edit'))
                                                <a href="javascript:;" data-bs-target="#edittransfeer{{$transfeer->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                                    <i data-feather='edit'></i>
                                                </a>
                                            @endif
                                                
                                            @if(userCan('money_transfeers_delete'))
                                                <?php $delete = route('admin.moneyTransfeers.delete',['id'=>$transfeer->id]); ?>
                                                <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$transfeer->id}}')">
                                                    <i data-feather='trash-2'></i>
                                                </button>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-3 text-center ">
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Bordered table end -->

@if(userCan('money_transfeers_edit'))

    @foreach($transfeers as $transfeer)
        <div class="modal fade text-md-start" id="edittransfeer{{$transfeer->id}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-transfeer">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pb-5 px-sm-5 pt-50">
                        <div class="text-center mb-2">
                            <h1 class="mb-1">{{trans('common.edit')}}: {{$transfeer['name_'.session()->get('Lang')]}}</h1>
                        </div>
                        {{Form::open(['files'=>'true','url'=>route('admin.moneyTransfeers.update',$transfeer->id), 'id'=>'edittransfeerForm'.$transfeer->id, 'class'=>'row gy-1 pt-75'])}}
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="date">{{trans('common.date')}}</label>
                                {{Form::date('date',$transfeer->date,['id'=>'date', 'class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-2">
                                <label class="form-label" for="amount">{{trans('common.amount')}}</label>
                                {{Form::number('amount',$transfeer->amount,['step'=>'.01','id'=>'amount', 'class'=>'form-control','required'])}}
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="from_safe_id">{{trans('common.from')}}</label>
                                {{Form::select('from_safe_id',safesList(),$transfeer->from_safe_id,['id'=>'from_safe_id', 'class'=>'form-select','required'])}}
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="to_safe_id">{{trans('common.to')}}</label>
                                {{Form::select('to_safe_id',safesList(),$transfeer->to_safe_id,['id'=>'to_safe_id', 'class'=>'form-select','required'])}}
                            </div>
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="notes">{{trans('common.details')}}</label>
                                {{Form::textarea('notes',$transfeer->notes,['rows'=>'2','id'=>'notes', 'class'=>'form-control','required'])}}
                            </div>
                            <div class="divider">
                                <div class="divider-text">{{trans('common.files')}}</div>
                            </div>

                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12 text-center">
                                    <label class="form-label" for="attachment">{{trans('common.attachment')}}</label>
                                    @if($transfeer->Attachments != '')
                                        <div class="row mb-2">
                                            {!!$transfeer->attachmentsHtml()!!}
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
    @if(userCan('money_transfeers_create'))
        @include('AdminPanel.accounts.transfeers.create')
    @endif

    <a href="javascript:;" data-bs-target="#search" data-bs-toggle="modal" class="btn btn-sm btn-primary">
        {{trans('common.search')}}
    </a>
    <div class="modal fade text-md-start" id="search" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-transfeer">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    {{Form::open(['files'=>'true','id'=>'createtransfeerForm', 'class'=>'row gy-1 pt-75', 'method'=>'GET'])}}
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="month">{{trans('common.month')}}</label>
                            {{Form::select('month',monthArray(session()->get('Lang')),isset($_GET['month']) ? $_GET['month'] : date('m'),['id'=>'month', 'class'=>'form-select'])}}
                        </div> 
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="year">{{trans('common.year')}}</label>
                            {{Form::select('year',yearArray(),isset($_GET['year']) ? $_GET['year'] : date('Y'),['id'=>'year', 'class'=>'form-select'])}}
                        </div>
                        @if(userCan('money_transfeers_view_branch'))
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="branch_id">{{trans('common.branch')}}</label>
                                <?php $branchesList = App\Branches::orderBy('name','asc')->pluck('name','id')->all(); ?>
                                {{Form::select('branch_id',['all'=>'الجميع'] + $branchesList,isset($_GET['branch_id']) ? $_GET['branch_id'] : '',['id'=>'branch_id', 'class'=>'form-select'])}}
                            </div>
                        @endif
                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">{{trans('common.search')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                {{trans('common.Cancel')}}
                            </button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop