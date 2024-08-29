@extends('AdminPanel.layouts.master')
@section('content')
<?php
    $month = date('m');
    $year = date('Y');
    $source = '';
    $Name = '';
    $cellphone = '';
    $identity = '';
    $employee = 'all';
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
        if ($_GET['source'] != '') {
            $source = $_GET['source'];
        }
    }
    if (isset($_GET['Name'])) {
        if ($_GET['Name'] != '') {
            $Name = $_GET['Name'];
        }
    }
    if (isset($_GET['cellphone'])) {
        if ($_GET['cellphone'] != '') {
            $cellphone = $_GET['cellphone'];
        }
    }
    if (isset($_GET['identity'])) {
        if ($_GET['identity'] != '') {
            $identity = $_GET['identity'];
        }
    }
    $params = [
        'position' => ['sales','contractFollowUp'],
        'month' => $month,
        'year' => $year,
        'employee' => $employee,
        'source' => $source,
        'cellphone' => $cellphone,
        'Name' => $Name,
        'identity' => $identity
    ];
    if($active == 'clientsContracts') {
        $position = 'contracts';
    } else {
        $position = 'sales';
    }
?>
    <?php /*
    <!--/ Line Chart -->
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
                                <h4 class="fw-bolder mb-0">
                                    <a href="{{route('admin.possibleClients',['time'=>'today'])}}">
                                        {{homeStates($month, $year, $source, $Name, $cellphone, $identity, $employee, $position)['todayClients']}}
                                    </a>
                                </h4>
                                <p class="card-text font-small-3 mb-0">عملاء اليوم</p>
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
                                <h4 class="fw-bolder mb-0">{{homeStates($month, $year, $source, $Name, $cellphone, $identity, $employee)['monthClients']}}</h4>
                                <p class="card-text font-small-3 mb-0">{{trans('common.newClients')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                        <div class="d-flex flex-row">
                            <div class="avatar bg-light-warning me-1">
                                <div class="avatar-content">
                                    <i data-feather="user" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="my-auto">
                                <h4 class="fw-bolder mb-0">{{homeStates($month, $year, $source, $Name, $cellphone, $identity, $employee)['monthContractFollowUpClients']}}</h4>
                                <p class="card-text font-small-3 mb-0">متابعة التعاقد</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                        <div class="d-flex flex-row">
                            <div class="avatar bg-light-danger me-1">
                                <div class="avatar-content">
                                    <i data-feather="user" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="my-auto">
                                <h4 class="fw-bolder mb-0">{{homeStates($month, $year, $source, $Name, $cellphone, $identity, $employee)['monthLostClients']}}</h4>
                                <p class="card-text font-small-3 mb-0">تم خسارته</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                        <div class="d-flex flex-row">
                            <div class="avatar bg-light-success me-1">
                                <div class="avatar-content">
                                    <i data-feather="user" class="avatar-icon"></i>
                                </div>
                            </div>
                            <div class="my-auto">
                                <h4 class="fw-bolder mb-0">{{homeStates($month, $year, $source, $Name, $cellphone, $identity, $employee)['monthCurrentClients']}}</h4>
                                <p class="card-text font-small-3 mb-0">تم التعاقد</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    */ ?>


{{Form::open(['url'=>route('admin.noAgentClients.asignAgent'),'id'=>'assignClientsForm'])}}
    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{$title}}
                        @if(userCan('client_asignment'))
                            <br>
                            <small>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                    <label class="form-check-label" for="selectAll"> {{trans('common.Select All')}} </label>
                                </div>
                            </small>
                        @endif
                    </h4>
                    @if(userCan('client_asignment'))
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
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="AgentID">{{trans('common.agent')}}</label>
                                            {{Form::select('AgentID',[''=>trans('common.none')] + agentsListForSearch(),isset($_GET['AgentID']) ? $_GET['AgentID'] : '',['id'=>'AgentID', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="AgentID">{{trans('common.position')}}</label>
                                            <?php
                                          if (session()->get('Lang') == 'ar') {
                                                $positions = [
                                                    'call_center' => 'Call Center',
                                                    'reception' => 'الاستقبال',
                                                    'sales' => 'المبيعات',
                                                    'salesManger' => 'مدير المبيعات',
                                                    'archive' => 'الأرشيف',
                                                    'delete' => 'حذف نهائي',
                                                ];
                                            } else {
                                                $positions = [
                                                    'call_center' => 'Call Center',
                                                    'reception' => 'Reception',
                                                    'sales' => 'sales',
                                                    'salesManger' => 'Sales Manger',
                                                    'archive' => 'Archive',
                                                    'delete' => 'Delete Forever',
                                                ];
                                            }
                                            ?>
                                            {{Form::select('position',[''=>trans('common.none')] + $positions,'',['id'=>'position', 'class'=>'selectpicker'])}}
                                        </div>
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
                                <th>{{trans('common.name')}}</th>
                                <th>{{trans('common.status')}}</th>
                                <th>{{trans('common.identity')}} / {{trans('common.job')}}</th>
                                <th>{{trans('common.refferal')}}</th>
                                <th>{{trans('common.employee')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $followUps)
                                <?php $client = App\Models\Clients::find($followUps->ClientID);?>
                                @if ($client == null)
                                <?php $client = App\Models\Clients::find($followUps->client_id);?>
                                @endif
                            @if ($client != null)
                                <tr class="row_{{$client->id}}" style="border-top: solid #666;">
                                    @include('AdminPanel.clients.clients-table-row',['client_row'=>$client])
                                </tr>
                                <tr class="row_{{$client->id}}">
                                    <td colspan="3">{{$client->Notes}}</td>
                                </tr>
                                @endif
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
    @if(userCan('clients_create'))
        <a href="javascript:;" data-bs-target="#createClientModal" data-bs-toggle="modal" class="btn btn-sm btn-primary">
            {{trans('common.CreateNew')}}
        </a>
        @include('AdminPanel.clients.create',['position'=>'sales'])
        <a href="{{route('admin.clients.exportExcel',$params)}}" target="_blank" class="btn btn-sm btn-primary">
            {{trans('common.downloadExcel')}}
        </a>
    @endif
    @include('AdminPanel.clients.search')
@stop



@section('scripts')
<script src="{{asset('AdminAssets/app-assets/js/scripts/pages/modal-add-role.js')}}"></script>
@stop
