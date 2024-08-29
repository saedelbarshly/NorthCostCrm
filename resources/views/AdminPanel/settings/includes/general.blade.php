<!-- form -->
<div class="row">
    <div class="col-md-3 text-center">
        <label class="form-label" for="logo">
            {{trans('common.logo')}}
        </label>
        {!! getSettingImageValue('logo') !!}
        <div class="file-loading"> 
            <input class="files" name="logo" type="file">
        </div>
    </div>
    <div class="col-md-3 text-center">
        <label class="form-label" for="logo_light">
            {{trans('common.logo_light')}}
        </label>
        {!! getSettingImageValue('logo_light') !!}
        <div class="file-loading"> 
            <input class="files" name="logo_light" type="file">
        </div>
    </div>
    <div class="col-md-3 text-center">
        <label class="form-label" for="fav">
            {{trans('common.fav')}}
        </label>
        {!! getSettingImageValue('fav') !!}
        <div class="file-loading"> 
            <input class="files" name="fav" type="file">
        </div>
    </div>
    <div class="col-12"></div>
    <div class="divider">
        <div class="divider-text">{{trans('common.salesSettings')}}</div>
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label" for="sales_agent_role_id">{{trans('common.sales_agent_role_id')}}</label>
        {{Form::select('sales_agent_role_id',getRolesList('ar','id'),getSettingValue('sales_agent_role_id'),['id'=>'sales_agent_role_id', 'class'=>'form-select'])}}
    </div>
</div>
<!--/ form -->