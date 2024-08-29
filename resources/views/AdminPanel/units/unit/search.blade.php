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
                {{Form::open(['id'=>'createunitForm', 'url'=>route('admin.units'),'class'=>'row gy-1 pt-75', 'method'=>'GET'])}}
                <div class="row justify-content-center">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="owner_id">{{trans('common.owner')}}</label>
                        {{Form::select ('owner_id',[''=>'بدون مصدر محدد'] + ownersList(),['id'=>'owner_id', 'class'=>'selectpicker'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="broker_id">{{trans('common.broker')}}</label>
                        {{Form::select('broker_id',[''=>'بدون مصدر محدد'] + brokersList(),['id'=>'broker_id', 'class'=>'form-select'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="type_id">{{trans('common.unitType')}}</label>
                        {{Form::select('type_id',[''=>'بدون مصدر محدد'] + unitsTypeList(),['id'=>'type_id', 'class'=>'selectpicker'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="governorate_id">{{trans('common.governorate')}}</label>
                        {{Form::select('governorate_id',[''=>'بدون مصدر محدد'] + govList(),['id'=>'governorate_id', 'class'=>'selectpicker'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="city_id">{{trans('common.city')}}</label>
                        {{Form::select('city_id',[''=>'بدون مصدر محدد'] + cityList(),['id'=>'city_id', 'class'=>'selectpicker'])}}
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
