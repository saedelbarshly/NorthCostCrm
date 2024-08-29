@extends('AdminPanel.layouts.master')
@section('content')
    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">{{trans('common.name')}}/{{trans('common.code')}}</th>
                                <th class="text-center">{{trans('common.space')}}</th>
                                <th class="text-center">{{trans('common.landSpace')}}</th>
                                <th class="text-center">{{trans('common.price')}}</th>
                                <th class="text-center">{{trans('common.status')}}</th>
                                <th class="text-center">{{trans('common.project')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                                <tr id="row_{{$unit->id}}">
                                    <td>
                                        {{$unit['name']}}
                                    </td>
                                    <td class="text-center">
                                        {{$unit->space ?? '-'}}
                                    </td>
                                    <td class="text-center">
                                        {{$unit->landSpace ?? '-'}}
                                    </td>
                                    <td class="text-center">
                                        {{$unit->Price != '' ? number_format($unit->Price) : '-'}}
                                    </td>
                                    <td class="text-center">
                                        <span class="btn btn-sm btn-{{$unit->statusDes()['btnClass']}}">
                                            {{trans('common.'.$unit->statusDes()['status'])}}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{$unit->project->name ?? '-'}}
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <a href="{{route('admin.units.view',$unit->id)}}" class="btn btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.details')}}">
                                            <i data-feather='eye'></i>
                                        </a>

                                        @if(userCan('units_edit'))
                                            <a href="{{route('admin.units.edit',$unit->id)}}" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                                <i data-feather='edit'></i>
                                            </a>
                                        @endif
                                            
                                        @if(userCan('units_delete'))
                                            <?php $delete = route('admin.units.delete',['id'=>$unit->id]); ?>
                                            <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$unit->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
                                                <i data-feather='trash-2'></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-3 text-center ">
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $units->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
    <!-- Bordered table end -->

@stop

@section('page_buttons')
    @if(userCan('units_create'))
        @include('AdminPanel.projectsUnits.units.createExcel')
        @include('AdminPanel.projectsUnits.units.create')
    @endif
    <a href="javascript:;" data-bs-target="#searchunits" data-bs-toggle="modal" class="btn btn-primary btn-sm">
        {{trans('common.search')}}
    </a>

    <div class="modal fade text-md-start" id="searchunits" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-unit">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    {{Form::open(['id'=>'createunitForm', 'class'=>'row gy-1 pt-75', 'method'=>'GET'])}}
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-5">
                            <label class="form-label" for="name">{{trans('common.name')}} / {{trans('common.code')}}</label>
                            {{Form::text('name',isset($_GET['name']) ? $_GET['name'] : '',['id'=>'name', 'class'=>'form-control'])}}
                        </div> 
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="project">{{trans('common.project')}}</label>
                            {{Form::select('ProjectID',['' => trans('common.none')] + projectsList(),'',['id'=>'project', 'class'=>'selectpicker','data-live-search'=>'true','required'])}}
                        </div>

                        <div class="col-12 text-center mt-2 pt-50">
                            <button type="submit" class="btn btn-primary me-1">{{trans('common.search')}}</button>
                            <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                                {{trans('common.Cancel')}}
                            </button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop