@extends('AdminPanel.layouts.master')
@section('content')
<?php
    $month = 'all';
    $year = 'all';
    $client = 'all';
    $agent = 'all';
    $status = 'all';
    if (userCan('contracts_view') || userCan('contracts_view_branch')) {
        $agent = 'all';
        if (isset($_GET['agent_id'])) {
            if ($_GET['agent_id'] != 'all') {
                $agent = $_GET['agent_id'];
            }
        }
    } else {
        $agent = auth()->user()->id;
    }
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
    }
    if (isset($_GET['year'])) {
        $year = $_GET['year'];
    }
    if (isset($_GET['client_id'])) {
        if ($_GET['client_id'] != 'all') {
            $client = $_GET['client_id'];
        }
    }
    $params = [
        'month' => $month,
        'year' => $year,
        'agent' => $agent,
        'client' => $client,
        'status' => $status
    ];
?>

    <div class="row">
        @foreach(contractStatusList('ar') as $typeKey => $typeValue)
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card text-center @if($status == $typeKey) bg-primary @endif">
                    <div class="card-body">
                        <?php $params['status'] = $typeKey; ?>
                        <a href="{{route('admin.contracts.index',$params)}}" @if($status == $typeKey) class="text-white" @endif>
                            <h2 class="fw-bolder @if($status == $typeKey) text-white @endif">
                                {{cotractsPageStats($month,$year,$agent,$client,$status)[$typeKey]}}
                            </h2>
                            <p class="card-text">{{$typeValue}}</p>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <a href="{{route('admin.contracts.index',$params + ['status'=>'inProgress'])}}">
                        <h2 class="fw-bolder">
                            {{cotractsPageStats($month,$year,$agent,$client,'all')['total']}}
                        </h2>
                        <p class="card-text">إجمالي</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12"></div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">
                        {{cotractsPageStats($month,$year,$agent,$client,'all')['payments']['total']}}
                    </h2>
                    <p class="card-text">قيمة الإجمالي</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">
                        {{cotractsPageStats($month,$year,$agent,$client,'all')['payments']['paid']}}
                    </h2>
                    <p class="card-text">المدفوع</p>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="fw-bolder">
                        {{cotractsPageStats($month,$year,$agent,$client,'all')['payments']['rest']}}
                    </h2>
                    <p class="card-text">المتبقى</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{$title}}</h4>
                </div>
                <div class="table-responsive p-1">
                    <table class="table table-bordered mb-2">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">{{trans('common.date')}}</th>
                                <th class="text-center">{{trans('common.project')}}</th>
                                <th class="text-center">{{trans('common.client')}}</th>
                                <th class="text-center">{{trans('common.user')}}</th>
                                <th class="text-center">{{trans('common.agent')}}</th>
                                <th class="text-center">{{trans('common.totals')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contracts as $index => $contract)
                                <tr id="row_{{$contract->id}}">
                                    <td>
                                        <b>
                                            {{$index+1}}
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            {{$contract['contractDate']}}
                                        </b>
                                    </td>
                                    <td>
                                        <b>
                                            {{$contract['name']}}
                                        </b>
                                    </td>
                                    <td class="text-right">
                                        <b>
                                            {{$contract->client['Name'] ?? ''}}
                                        </b>
                                    </td>
                                    <td class="text-right text-nowrap">
                                        <small>
                                            {{$contract->user['name'] ?? ''}}
                                        </small>
                                    </td>
                                    <td class="text-right text-nowrap">
                                        <small>
                                            {{$contract->agent['name'] ?? ''}}
                                        </small>
                                    </td>
                                    <td class="text-right text-nowrap">
                                        <small>
                                            <b>{{trans('common.total')}}:</b> {{$contract->Total}}<br>
                                            <b>{{trans('common.paid')}}:</b> {{$contract->totals()['paid']}}<br>
                                            <b>{{trans('common.rest')}}:</b> {{$contract->totals()['rest']}}<br>
                                            <b>{{trans('common.expenses')}}:</b> {{$contract->totals()['expenses']}}<br>
                                            <b>{{trans('common.net')}}:</b> {{$contract->totals()['net']}}
                                        </small>
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{route('admin.payments',$contract->id)}}" class="btn btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.payments')}}">
                                            <i data-feather='list'></i>
                                        </a>
                                        <a href="{{route('admin.contracts.edit',$contract->id)}}" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                            <i data-feather='edit'></i>
                                        </a>
                                        <?php $delete = route('admin.contracts.delete',['id'=>$contract->id]); ?>
                                        <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$contract->id}}')">
                                            <i data-feather='trash-2'></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-3 text-center ">
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $contracts->links('vendor.pagination.default') }}


            </div>
        </div>
    </div>
    <!-- Bordered table end -->

@stop

@section('page_buttons')
    @if(userCan('contracts_create'))
        <a href="javascript:;" data-bs-target="#createContract" data-bs-toggle="modal" class="btn btn-sm btn-primary">
            {{trans('common.CreateNewContract')}}
        </a>

        @include('AdminPanel.contracts.create')
    @endif
    @include('AdminPanel.contracts.search')
@stop