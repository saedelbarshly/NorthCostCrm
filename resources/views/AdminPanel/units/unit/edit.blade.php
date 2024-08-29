@extends('AdminPanel.layouts.master')
@section('content')
<!-- Bordered table start -->
<div class="row" id="table-bordered">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                {{Form::open(['files'=>'true','url'=>route('admin.units.update',$unit->id), 'id'=>'createExpenseForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">{{trans('common.name')}} / {{trans('common.code')}}</label>
                        {{Form::text('name',$unit->name,['id'=>'name', 'class'=>'form-control','required'])}}
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label" for="district">{{trans('common.district')}}</label>
                        {{Form::text('district',$unit->district,['id'=>'district', 'class'=>'form-control','required'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="space">{{trans('common.space')}}</label>
                        {{Form::text('space',$unit->space,['id'=>'space', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="landSpace">{{trans('common.landSpace')}}</label>
                        {{Form::text('landSpace',$unit->landSpace,['id'=>'landSpace', 'class'=>'form-control'])}}
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label" for="Price">{{trans('common.price')}}</label>
                        {{Form::text('Price',$unit->Price,['id'=>'Price', 'class'=>'form-control'])}}
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label" for="room">{{trans('common.room')}}</label>
                        {{Form::text('room',$unit->room,['id'=>'room', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="bathroom">{{trans('common.bathroom')}}</label>
                        {{Form::text('bathroom',$unit->bathroom,['id'=>'bathroom', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="entre">{{trans('common.entre')}}</label>
                        {{Form::number('entre',$unit->entre,['id'=>'entre', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="type_id">{{trans('common.unitType')}}</label>
                        {{Form::select('type_id',unitsTypeList(),$unit->unitTypes->name, ['id'=>'type_id', 'class'=>'form-select','required'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="owner_id">{{trans('common.owner')}}</label>
                        {{Form::select('owner_id',ownersList(),$unit->owners->name, ['id'=>'owner_id', 'class'=>'form-select','required'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="broker_id">{{trans('common.broker')}}</label>
                        {{Form::select('broker_id',brokersList(),$unit->brokers->name, ['id'=>'broker_id', 'class'=>'form-select','required'])}}
                    </div>

                    <div class="mt-2">
                        <label>{{trans('common.accessory')}}</label>
                    <div class="d-flex mt-2">
                        @foreach($accessories as $accessory)
                            <div class="form-check me-3 me-lg-1">
                                <input class="form-check-input" type="checkbox" id="accessory{{$accessory['id']}}" name="accessories[]" value="{{$accessory['id']}}"
                                @foreach ($unitAccessories as $value )
                                    @if($accessory->id == $value->accessory_id) checked @endif
                                @endforeach
                                 />
                                <label class="form-check-label" for="permission{{$accessory['id']}}"> {{$accessory['name']}} </label>
                            </div>
                        @endforeach
                    </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="governorate">{{trans('common.city')}}</label>
                            {{Form::select('governorate_id', [''=>'اختر']+$govs,$unit->governorate_id, ['id'=>'governorates', 'class'=>'form-select','onchange'=>'showElemet(this,this.value)'])}}
                        </div>
                        <?php $this_city = App\Models\Cities::where('GovernorateID',$unit->governorate_id)->pluck('name','id')->all(); ?>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="city">{{trans('common.zone')}}</label>
                            {{Form::select('city_id',[]+$this_city,$unit->city_id,['id'=>'cities','class'=>'form-control'])}}
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="notes">{{trans('common.details')}}</label>
                        {{Form::textarea('notes',$unit->notes,['rows'=>'2','id'=>'notes', 'class'=>'form-control editor_ar'])}}
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

@section('scripts')
    <script>
        function showElemet(elem,val)
        {
            var row = $(elem).parent().parent();
            console.log(row);
            var url = '<?php echo url("AdminPanel/unit/getZone"); ?>/'+val;
            $.get(url, function(data, status){
                var options = '';
                for (index = 0; index < data.length; index++) {
                    options += '<option value="'+data[index]['id']+'">'+data[index]['name']+'</option>';
                }
                $(row).find('select#cities').empty().append(options);
            });
        }
    </script>

@stop
