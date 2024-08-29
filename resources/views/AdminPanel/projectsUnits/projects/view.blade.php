@extends('AdminPanel.layouts.master')
@section('content')
<!-- Bordered table start -->
<div class="row" id="table-bordered">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-9">
                        <label class="form-label mb-1 mt-2" for="name"><b>{{trans('common.name')}}</b></label>
                        {{Form::text('name',$project->name,['id'=>'name', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label mb-1 mt-2" for="excel"><b>{{trans('common.excelLink')}}: </b></label>
                        @if($project->excel != '')
                            <a href="{{$project->excel}}" target="_blank">
                                اضغط هنا
                            </a>
                        @else
                            -
                        @endif
                    </div>

                    <div class="col-12 col-md-12">
                        <label class="form-label mb-1 mt-2" for="details"><b>{{trans('common.details')}}</b></label>
                        <div class="col-12">
                            {!!$project->details!!}
                        </div>
                    </div>

                    <div class="divider">
                        <div class="divider-text"><b>{{trans('common.files')}}</div>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <div class="col-md-12 text-center">
                            @if($project->images != '')
                                <div class="row mb-2">
                                    {!!$project->imagesHtml('view')!!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bordered table end -->

@stop