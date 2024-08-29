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
                                {{-- <th class="text-center">{{trans('common.district')}}</th>
                                <th class="text-center">{{trans('common.spaces')}}</th> --}}
                                <th class="text-center">{{trans('common.owner')}}</th>
                                <th class="text-center">{{trans('common.broker')}}</th>
                                <th class="text-center">{{trans('common.unitType')}}</th>
                                <th class="text-center">{{trans('common.title')}}</th>
                                <th class="text-center">{{trans('common.price')}}</th>
                                <th class="text-center">{{trans('common.status')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($units as $unit)
                                <tr id="row_{{$unit->id}}">
                                    <td>
                                        {{$unit['name']}}
                                    </td>
                                    {{-- <td>
                                        {{$unit['district']}}
                                    </td>
                                    <td class="text-center">
                                        {{trans('common.space')}}: {{$unit->space ?? '-'}} <br>
                                        {{trans('common.landSpace')}} : {{$unit->landSpace ?? '-'}} <br>
                                    </td> --}}
                                    <td>
                                        <?php $owner = App\Models\Owner::find($unit->owner_id);?>
                                        {{ $owner->name ?? '-' }}
                                    </td>
                                    <td>
                                        <?php $broker = App\Models\Broker::find($unit->broker_id);?>
                                        {{ $broker->name ?? '-' }}
                                    </td>
                                    <td>
                                        <?php $unitType = App\Models\UnitType::find($unit->type_id);?>
                                        {{ $unitType->name ?? '-' }}
                                    </td>
                                    <td>
                                        <?php  $gov = App\Models\Governorates::find($unit->governorate_id);?>
                                        <?php  $city = App\Models\Cities::find($unit->city_id);?>
                                         {{ $gov->name ?? '-' }} <br>
                                         {{ $city->name ?? '-' }} <br>
                                    </td>
                                    <td class="text-center">
                                        {{$unit->Price != '' ? number_format($unit->Price) : '-'}}
                                    </td>
                                    <td class="text-center">
                                        <span class="btn btn-sm btn-{{$unit->statusDes()['btnClass']}}">
                                            {{trans('common.'.$unit->statusDes()['status'])}}
                                        </span>
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
        @include('AdminPanel.units.unit.search')
        @include('AdminPanel.units.unit.createExcel')
        @include('AdminPanel.units.unit.create')
        {{-- <a href="javascript:;" data-bs-target="#searchClients" data-bs-toggle="modal" class="btn btn-primary btn-sm">
            {{trans('common.search')}}
        </a> --}}
    @endif
@stop
