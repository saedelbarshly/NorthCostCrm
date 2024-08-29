@extends('AdminPanel.layouts.master')
@section('content')
<!-- Bordered table start -->
<div class="row" id="table-bordered">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                <div class="col-12 col-md-6">
                        <label class="form-label" for="name">{{trans('common.name')}}</label>
                        {{Form::text('name',$project->name,['id'=>'name', 'class'=>'form-control','disabled'])}}
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label" for="project">{{trans('common.project')}}</label>
                        {{Form::select('ProjectID',projectsList(),$project->ProjectID,['id'=>'project', 'class'=>'selectpicker','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="landSpace">{{trans('common.landSpace')}}</label>
                        {{Form::text('landSpace',$project->landSpace,['id'=>'landSpace', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="space">{{trans('common.space')}}</label>
                        {{Form::text('space',$project->space,['id'=>'space', 'class'=>'form-control','disabled'])}}
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label" for="Price">{{trans('common.price')}}</label>
                        {{Form::text('Price',$project->Price,['id'=>'Price', 'class'=>'form-control','disabled'])}}
                    </div>

                    <div class="col-12 col-md-12">
                        <label class="form-label mb-1 mt-2" for="details"><b>{{trans('common.details')}}</b></label>
                        <div class="col-12">
                            {!!$project->notes!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bordered table end -->

@stop