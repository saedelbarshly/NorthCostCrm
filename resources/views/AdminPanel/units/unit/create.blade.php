<a href="javascript:;" data-bs-target="#createUnit" data-bs-toggle="modal" class="btn btn-sm btn-primary">
    {{trans('common.CreateNew')}}
</a>
<?php $accessories = App\Models\Accessory::get();?>
<div class="modal fade text-md-start" id="createUnit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                </div>
                {{Form::open(['files'=>true,'url'=>route('admin.units.store'), 'id'=>'createUnitForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="name">{{trans('common.name')}} / {{trans('common.code')}}</label>
                        {{Form::text('name','',['id'=>'name', 'class'=>'form-control','required'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="district">{{trans('common.district')}}</label>
                        {{Form::text('district','',['id'=>'district', 'class'=>'form-control','required'])}}
                    </div>
                    {{-- <div class="col-12 col-md-4">
                        <label class="form-label" for="project">{{trans('common.project')}}</label>
                        {{Form::select('ProjectID',projectsList(),'',['id'=>'project', 'class'=>'selectpicker','data-live-search'=>'true','required'])}}
                    </div> --}}
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="space">{{trans('common.space')}}</label>
                        {{Form::number('space','',['id'=>'space', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="landSpace">{{trans('common.landSpace')}}</label>
                        {{Form::number('landSpace','',['id'=>'landSpace', 'class'=>'form-control'])}}
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label" for="Price">{{trans('common.price')}}</label>
                        {{Form::number('Price','',['id'=>'Price', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="kitchen">{{trans('common.kitchen')}}</label>
                        {{Form::number('kitchen','',['id'=>'kitchen', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="room">{{trans('common.room')}}</label>
                        {{Form::number('room','',['id'=>'room', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="bathroom">{{trans('common.bathroom')}}</label>
                        {{Form::number('bathroom','',['id'=>'bathroom', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="entre">{{trans('common.entre')}}</label>
                        {{Form::number('entre','',['id'=>'entre', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="interface">{{trans('common.interface')}}</label>
                        {{Form::select('interface',interfaceList(), '', ['id'=>'interface', 'class'=>'form-select','placeholder' => 'نوع العقار'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="type_id">{{trans('common.unitType')}}</label>
                        {{Form::select('type_id',unitsTypeList(), '', ['id'=>'type_id', 'class'=>'form-select','placeholder' => 'نوع العقار'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="owner_id">{{trans('common.owner')}}</label>
                        {{Form::select('owner_id',ownersList(), '', ['id'=>'owner_id', 'class'=>'form-select' ,'placeholder' => 'مالك العقار'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="broker_id">{{trans('common.broker')}}</label>
                        {{Form::select('broker_id',brokersList(), '', ['id'=>'broker_id', 'class'=>'form-select' ,'placeholder' => 'وسيط العقار'])}}
                    </div>
                    {{-- @dd($governorates); --}}
                    <div class="row">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="governorate">{{trans('common.city')}}</label>
                        {{Form::select('governorate_id', [''=>'اختر']+$govs, '', ['id'=>'govs', 'class'=>'form-select','onchange'=>'showElemet(this,this.value)'])}}
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="city">{{trans('common.zone')}}</label>
                        {{Form::select('city_id',[],'',['id'=>'cities','class'=>'form-control'])}}
                    </div>

                    <div class="row mt-1">
                        <label>{{trans('common.accessory')}}</label>
                        <div class="mt-2 ">
                            <div class="row">
                                @foreach($accessories as $accessory)
                                <div class="col-4">
                                    <div class="form-check me-3 me-lg-1">
                                        <input class="form-check-input" type="checkbox" id="accessory{{$accessory['id']}}" name="accessories[]" value="{{$accessory['id']}}" />
                                        <label class="form-check-label" for="accessory{{$accessory['id']}}">
                                            {{$accessory['name']}}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12">
                        <label class="form-label" for="notes">{{trans('common.details')}}</label>
                        {{Form::textarea('notes','',['rows'=>'2','id'=>'notes', 'class'=>'form-control editor_ar'])}}
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
