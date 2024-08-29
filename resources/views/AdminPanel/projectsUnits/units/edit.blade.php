@extends('AdminPanel.layouts.master')
@section('content')
<!-- Bordered table start -->
<div class="row" id="table-bordered">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{Form::open(['files'=>'true','url'=>route('admin.units.update',$project->id), 'id'=>'createExpenseForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">{{trans('common.name')}} / {{trans('common.code')}}</label>
                        {{Form::text('name',$project->name,['id'=>'name', 'class'=>'form-control','required'])}}
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label" for="project">{{trans('common.project')}}</label>
                        {{Form::select('ProjectID',projectsList(),$project->ProjectID,['id'=>'project', 'class'=>'selectpicker','data-live-search'=>'true','required'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="space">{{trans('common.space')}}</label>
                        {{Form::text('space',$project->space,['id'=>'space', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="landSpace">{{trans('common.landSpace')}}</label>
                        {{Form::text('landSpace',$project->landSpace,['id'=>'landSpace', 'class'=>'form-control'])}}
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label" for="Price">{{trans('common.price')}}</label>
                        {{Form::text('Price',$project->Price,['id'=>'Price', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="notes">{{trans('common.details')}}</label>
                        {{Form::textarea('notes',$project->notes,['rows'=>'2','id'=>'notes', 'class'=>'form-control editor_ar'])}}
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
<!-- Bordered table end -->

@stop