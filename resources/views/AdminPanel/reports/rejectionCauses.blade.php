@extends('AdminPanel.layouts.master')
@section('content')
    <!--/ Line Chart -->
    <div class="col-lg-12 col-12">
        <div class="card card-statistics">
            <div class="card-header">
                <h4 class="card-title">{{trans('common.callCenter')}}</h4>
            </div>
            <div class="card-body statistics-body">
                <div class="row justify-content-center">
                    @foreach(callCenterRejectionCauses(auth()->user()->language) as $key => $rejection)
                        <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0">
                            <div class="d-flex flex-row" style="align-items: flex-start;">
                                <div class="avatar bg-light-primary me-1">
                                    <div class="avatar-content">
                                        <a href="{{route('admin.clients.archive',['rejictionCause'=>$key])}}">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <a href="{{route('admin.clients.archive',['rejictionCause'=>$key])}}">
                                        <h4 class="fw-bolder mb-0">
                                            {{App\Models\Clients::where('rejictionCause',$key)->where('status','archive')->count()}}
                                        </h4>
                                        <p class="card-text font-small-3 mb-0">{{$rejection}}</p>
                                    </a>
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
            <div class="card-header">
                <h4 class="card-title">فريق المبيعات</h4>
            </div>
            <div class="card-body statistics-body">
                <div class="row justify-content-center">
                    @foreach(salesRejectionCauses(auth()->user()->language) as $key => $rejection)
                        <div class="col-md-2 col-sm-6 col-12 mb-2 mb-md-0" style="align-items: flex-start;">
                            <div class="d-flex flex-row">
                                <div class="avatar bg-light-primary me-1">
                                    <div class="avatar-content">
                                        <a href="{{route('admin.clients.archive',['rejictionCause'=>$key])}}">
                                            <i data-feather="user" class="avatar-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="my-auto">
                                    <a href="{{route('admin.clients.archive',['rejictionCause'=>$key])}}">
                                        <h4 class="fw-bolder mb-0">
                                            {{App\Models\Clients::where('rejictionCause',$key)->where('status','archive')->count()}}
                                        </h4>
                                        <p class="card-text font-small-3 mb-0">{{$rejection}}</p>
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
