@extends('AdminPanel.layouts.master')
@section('content')
<?php
    $month = date('m');
    $year = date('Y');
    $source = 'all';
    $service = 'all';
    $status = 'all';
    if (userCan('clients_view')) {
        $employee = 'all';
        if (isset($_GET['employee'])) {
            if ($_GET['employee'] != 'all') {
                $employee = $_GET['employee'];
            }
        }
    } else {
        $employee = auth()->user()->id;
    }
    if (isset($_GET['month'])) {
        $month = $_GET['month'];
    }
    if (isset($_GET['year'])) {
        $year = $_GET['year'];
    }
    if (isset($_GET['source'])) {
        if ($_GET['source'] != 'all') {
            $source = $_GET['source'];
        }
    }
    if (isset($_GET['service'])) {
        if ($_GET['service'] != 'all') {
            $service = $_GET['service'];
        }
    }
    if (isset($_GET['status'])) {
        if ($_GET['status'] != 'all') {
            $status = $_GET['status'];
        }
    }
    $params = [
        'month' => $month,
        'year' => $year,
        'employee' => $employee,
        'source' => $source,
        'service' => $service
    ];
?>
    {{Form::open(['url'=>route('admin.noAgentClients.asignAgent'),'id'=>'assignClientsForm'])}}

        <div class="row">
            @foreach(clientStatusArray(session()->get('Lang')) as $index => $type)
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card text-center @if($status == 'open') bg-primary @endif">
                        <div class="card-body">
                            <a href="{{route('admin.possibleClients',$params + ['status'=>'open'])}}" @if($status == 'open') class="text-white" @endif>
                                <h2 class="fw-bolder @if($status == 'open') text-white @endif">
                                    {{clientsPageStats($month,$year,$employee,$source)[$index]}}
                                </h2>
                                <p class="card-text">{{$type}}</p>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Bordered table start -->
        <div class="row" id="table-bordered">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {{$title}}
                            @if(userCan('reports_accounts_view'))
                                <br>
                                <small>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll" />
                                        <label class="form-check-label" for="selectAll"> {{trans('common.Select All')}} </label>
                                    </div>
                                </small>
                            @endif
                        </h4>
                        @if(userCan('reports_accounts_view'))
                            <a href="javascript:;" data-bs-target="#transferClients" data-bs-toggle="modal" class="btn btn-primary btn-sm">
                                {{trans('common.transferClients')}}
                            </a>

                            <div class="modal fade text-md-start" id="transferClients" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered modal-edit-user">
                                    <div class="modal-content">
                                        <div class="modal-header bg-transparent">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pb-5 px-sm-5 pt-50">
                                            @if(userCan('clients_view') || userCan('clients_view_branch') || userCan('clients_view_team'))
                                                <div class="col-12 col-md-12">
                                                    <label class="form-label" for="AgentID">{{trans('common.agent')}}</label>
                                                    {{Form::select('AgentID',agentsListForSearch(getSettingValue('sales_agent_role_id')),isset($_GET['AgentID']) ? $_GET['AgentID'] : '',['id'=>'AgentID', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                                                </div>
                                            @endif
                                            <div class="col-12 text-center mt-2 pt-50">
                                                <button type="submit" class="btn btn-primary me-1">{{trans('common.Save Changes')}}</button>
                                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                                    {{trans('common.Cancel')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-2">
                            <thead>
                                <tr>
                                    <th>
                                        {{trans('common.client')}}
                                    </th>
                                    <th>{{trans('common.lastContactDetails')}}</th>
                                    <th class="text-center">{{trans('common.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $client)
                                    <tr id="row_{{$client->id}}">
                                        <td>
                                            @if(userCan('reports_accounts_view'))
                                                <div class="form-check me-3 me-lg-1">
                                                    <input class="form-check-input" type="checkbox" id="client{{$client->id}}" name="clients[]" value="{{$client->id}}" />
                                                    <label class="form-check-label" for="client{{$client->id}}">
                                                        {{$client->Name}}
                                                        <small>({{refferalList(session()->get('Lang'))[$client->referral] ?? '-'}})</small>
                                                    </label>
                                                </div>
                                            @else
                                                {{$client->Name}}
                                            @endif
                                            
                                            <div class="col-12"></div>
                                            @if($client->cellphone != '')
                                                <span class="btn btn-sm btn-primary mb-1">
                                                    <b><i data-feather='phone'></i></b>
                                                    <a href="call:{{$client->cellphone}}" class="text-white">
                                                        {{$client->cellphone ?? '-'}}
                                                    </a>
                                                </span>
                                                <div class="col-12"></div>
                                            @endif
                                            @if($client->email != '')
                                                <span class="btn btn-sm btn-primary mb-1">
                                                    <b>{{trans('common.email')}}: </b>
                                                    <a href="mailto:{{$client->email}}" class="text-white">
                                                        {{$client->email ?? '-'}}
                                                    </a>
                                                </span>
                                                <div class="col-12"></div>
                                            @endif
                                            @if($client->whatsapp != '')
                                                <span class="btn btn-sm btn-success">
                                                    <b><i data-feather='message-square'></i></b>
                                                    <a href="https://wa.me/{{$client->whatsapp}}" class="text-white">
                                                        {{$client->whatsapp ?? '-'}}
                                                    </a>
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($client->lastFollowUp() != '')
                                                {{$client->lastFollowUp()->contactingDateTime}} - {{$client->lastFollowUp()->agent->name}}
                                                <br>
                                                {{$client->lastFollowUp()->offerDetails}}
                                                <br>
                                                {{$client->lastFollowUp()->offerResponse}}
                                                <br>
                                                {!!$client->projectDetails()!!}
                                                <br>
                                                {!!$client->offerDetails()!!}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-nowrap text-center">
                                            <a href="{{route('admin.followups',['client_id'=>$client->id])}}" class="btn btn-icon btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.followups')}}">
                                                <i data-feather='list'></i>
                                            </a>
                                            <a href="javascript:;" data-bs-target="#editclient{{$client->id}}" data-bs-toggle="modal" class="btn btn-icon btn-sm btn-info">
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                                    <i data-feather='edit'></i>
                                                </span>
                                            </a>
                                            <?php $delete = route('admin.clients.delete',['id'=>$client->id]); ?>
                                            <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="confirmDelete('{{$delete}}','{{$client->id}}')">
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                                    <i data-feather='trash-2'></i>
                                                </span>
                                            </button>
                                        </td>
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

                    {{ $clients->links('vendor.pagination.default') }}


                </div>
            </div>
        </div>
        <!-- Bordered table end -->
    {{Form::close()}}
    


    @foreach($clients as $client)
        @include('AdminPanel.clients.edit',['client'=>$client])
        @include('AdminPanel.clients.createFollowUp',['client'=>$client])
        @include('AdminPanel.contracts.create',['clientData'=>$client])
    @endforeach


@stop

@section('page_buttons')
    @include('AdminPanel.clients.search')
@stop
