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
                        {{Form::text('name',$unit->name,['id'=>'name', 'class'=>'form-control','disabled'])}}
                    </div>
                <div class="col-12 col-md-6">
                        <label class="form-label" for="district">{{trans('common.name')}}</label>
                        {{Form::text('district',$unit->district,['id'=>'district', 'class'=>'form-control','disabled'])}}
                    </div>
                    {{-- <div class="col-12 col-md-4">
                        <label class="form-label" for="project">{{trans('common.project')}}</label>
                        {{Form::select('ProjectID',projectsList(),$project->ProjectID,['id'=>'project', 'class'=>'selectpicker','data-live-search'=>'true','disabled'])}}
                    </div> --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="interface">{{trans('common.interface')}}</label>
                        {{Form::select('interface',interfaceList(),$unit->interface,['id'=>'interface','class'=>'form-control','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="type_id">{{trans('common.unitType')}}</label>
                        {{Form::select('type_id',unitsTypeList(),$unit->type_id,['id'=>'type_id','class'=>'form-control','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="owner_id">{{trans('common.owner')}}</label>
                        {{Form::select('owner_id',ownersList(),$unit->owner_id,['id'=>'owner_id','class'=>'form-control','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="broker_id">{{trans('common.broker')}}</label>
                        {{Form::select('broker_id',brokersList(),$unit->broker_id,['id'=>'broker_id','class'=>'form-control','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="governorate_id">{{trans('common.governorate')}}</label>
                        {{Form::select('governorate_id',govList(),$unit->governorate_id,['id'=>'governorate_id','class'=>'form-control','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="city_id">{{trans('common.city')}}</label>
                        {{Form::select('city_id',cityList(),$unit->city_id,['id'=>'city_id','class'=>'form-control','data-live-search'=>'true','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="landSpace">{{trans('common.landSpace')}}</label>
                        {{Form::text('landSpace',$unit->landSpace,['id'=>'landSpace', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="space">{{trans('common.space')}}</label>
                        {{Form::text('space',$unit->space,['id'=>'space', 'class'=>'form-control','disabled'])}}
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label" for="Price">{{trans('common.price')}}</label>
                        {{Form::text('Price',$unit->Price,['id'=>'Price', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="kitchen">{{trans('common.kitchen')}}</label>
                        {{Form::text('kitchen',$unit->kitchen,['id'=>'kitchen', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="room">{{trans('common.room')}}</label>
                        {{Form::text('room',$unit->room,['id'=>'room', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="bathroom">{{trans('common.bathroom')}}</label>
                        {{Form::text('bathroom',$unit->bathroom,['id'=>'bathroom', 'class'=>'form-control','disabled'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="entre">{{trans('common.entre')}}</label>
                        {{Form::text('entre',$unit->entre,['id'=>'bathroom', 'class'=>'form-control','disabled'])}}
                    </div>

                    <div class="mt-2">
                        <label>{{trans('common.accessory')}}</label>
                        <div class="d-flex mt-2"">
                        @foreach($accessories as $accessory)
                            <div class="form-check me-3 me-lg-1">
                                <input disabled class="form-check-input" type="checkbox" id="accessory{{$accessory['id']}}"  value="{{$accessory['id']}}"
                                @foreach ($unitAccessories as $value )
                                    @if($accessory->id == $value->accessory_id) checked @endif
                                @endforeach

                                 />
                                <label class="form-check-label" for="permission{{$accessory['id']}}"> {{$accessory['name']}} </label>
                            </div>
                        @endforeach
                    </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <h3 class="form-label mb-1 mt-2"><b>{{trans('common.details')}}</b></h3>
                        <div class="col-12">
                            {!!$unit->notes!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bordered table end -->

@stop
