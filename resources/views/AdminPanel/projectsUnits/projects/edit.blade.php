@extends('AdminPanel.layouts.master')
@section('content')
<!-- Bordered table start -->
<div class="row" id="table-bordered">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{Form::open(['files'=>'true','url'=>route('admin.projects.update',$project->id), 'id'=>'createExpenseForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-9">
                        <label class="form-label" for="name">{{trans('common.name')}}</label>
                        {{Form::text('name',$project->name,['id'=>'name', 'class'=>'form-control','required'])}}
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