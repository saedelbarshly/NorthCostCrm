@extends('AdminPanel.layouts.master')
@section('content')
<?php
    $booked = App\Models\ClientUnit::where('status','booking')->count();
    $contracted = App\Models\ClientUnit::where('status','contract')->count();
    $units = App\Models\Units::count();
?>
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
                            <a href="{{route('admin.units',['status'=>'booking'])}}">
                                <h4 class="fw-bolder mb-0">
                                    {{$booked}}
                                </h4>
                                <p class="card-text font-small-3 mb-0">
                                    {{trans('common.booked_up')}}
                                </p>
                            </a>
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
                            <a href="{{route('admin.units',['status'=>'contract'])}}">
                                <h4 class="fw-bolder mb-0">
                                    {{$contracted}}
                                </h4>
                                <p class="card-text font-small-3 mb-0">
                                    {{trans('common.contracted')}}
                                </p>
                            </a>
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
                            <a href="{{route('admin.units',['status'=>'available'])}}">
                                <h4 class="fw-bolder mb-0">
                                    {{$units - ($booked + $contracted)}}
                                </h4>
                                <p class="card-text font-small-3 mb-0">
                                    {{trans('common.available')}}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
