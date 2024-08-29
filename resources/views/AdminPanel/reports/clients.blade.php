@extends('AdminPanel.layouts.master')
@section('content')
<?php

    $positions = [
        'call_center',
        'reception',
        'sales',
        'contract',
        'contracts',
        'coordinator',
        'contractFollowUp',
        // 'archive'
    ];
?>

     <!--/ Line Chart -->
    <div class="col-lg-12 col-12">
        <div class="card card-statistics">
            <div class="card-title text-center p-2 pb-0">
                إجماليات
            </div>
            <div class="card-body statistics-body">
                <div class="row justify-content-center">
                    @foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
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
                                    @if ($key == 'checkout_reject')
                                    <a href="{{route('admin.clients.archive',['position[]'=>$positions])}}">
                                        <h4 class="fw-bolder mb-0">
                                            {{App\Models\Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count()}}
                                        </h4>
                                        <p class="card-text font-small-3 mb-0">
                                            {{$status}}
                                        </p>
                                    </a>
                                    @else
                                        <a href="{{route('admin.possibleClients',['status'=>$key,'position[]'=>$positions])}}">
                                            <h4 class="fw-bolder mb-0">
                                                {{App\Models\Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count()}}
                                            </h4>
                                            <p class="card-text font-small-3 mb-0">
                                                {{$status}}
                                            </p>
                                        </a>
                                    @endif
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
@stop
