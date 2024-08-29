<!-- form -->
<div class="row">
    <div class="divider">
        <div class="divider-text">{{trans('common.Policies')}}</div>
    </div>
    <div class="col-12 col-md-12">
        <label class="form-label" for="targetPolicy">{{trans('common.targetPolicy')}}</label>
        {{Form::textarea('targetPolicy',getSettingValue('targetPolicy'),['rows'=>'3','id'=>'targetPolicy','class'=>'form-control editor_ar'])}}
    </div>
    <div class="col-12 col-md-12">
        <label class="form-label" for="commisionPolicy">{{trans('common.commisionPolicy')}}</label>
        {{Form::textarea('commisionPolicy',getSettingValue('commisionPolicy'),['rows'=>'3','id'=>'commisionPolicy','class'=>'form-control editor_ar'])}}
    </div>
    <div class="col-12 col-md-12">
        <label class="form-label" for="otherPolicy">{{trans('common.otherPolicy')}}</label>
        {{Form::textarea('otherPolicy',getSettingValue('otherPolicy'),['rows'=>'3','id'=>'otherPolicy','class'=>'form-control editor_ar'])}}
    </div>
    <div class="divider">
        <div class="divider-text">{{trans('common.lateDeductions')}}</div>
    </div>
    <div class="col-3 col-md-3">
        <label class="form-label" for="LateTimeDeduction">{{trans('common.LateTimeDeduction')}}</label>
        {{Form::text('LateTimeDeduction',getSettingValue('LateTimeDeduction'),['id'=>'LateTimeDeduction','class'=>'form-control'])}}
    </div>
    <div class="col-3 col-md-3">
        <label class="form-label" for="EmployeeLate15">{{trans('common.EmployeeLate15')}}</label>
        {{Form::text('EmployeeLate15',getSettingValue('EmployeeLate15'),['id'=>'EmployeeLate15','class'=>'form-control'])}}
    </div>
    <div class="col-3 col-md-3">
        <label class="form-label" for="MonthWorkingDays">{{trans('common.MonthWorkingDays')}}</label>
        {{Form::text('MonthWorkingDays',getSettingValue('MonthWorkingDays'),['id'=>'MonthWorkingDays','class'=>'form-control'])}}
    </div>
    <div class="col-3 col-md-3">
        <label class="form-label" for="WorkingHours">{{trans('common.WorkingHours')}}</label>
        {{Form::text('WorkingHours',getSettingValue('WorkingHours'),['id'=>'WorkingHours','class'=>'form-control'])}}
    </div>
    <div class="divider">
        <div class="divider-text">{{trans('common.weekEnd')}}</div>
    </div>
    <div class="col-12 col-md-2">
        <label class="form-label" for="WeekEndThu">{{trans('common.WeekEndThu')}}</label>
        <div class="form-check form-check-success form-switch">
            {{Form::checkbox('WeekEndThu','1',getSettingValue('WeekEndThu') == '1' ? true : false,['id'=>'WeekEndThu', 'class'=>'form-check-input'])}}
            <label class="form-check-label" for="WeekEndThu"></label>
        </div>
    </div>
    <div class="col-12 col-md-2">
        <label class="form-label" for="WeekEndFri">{{trans('common.WeekEndFri')}}</label>
        <div class="form-check form-check-success form-switch">
            {{Form::checkbox('WeekEndFri','1',getSettingValue('WeekEndFri') == '1' ? true : false,['id'=>'WeekEndFri', 'class'=>'form-check-input'])}}
            <label class="form-check-label" for="WeekEndFri"></label>
        </div>
    </div>
    <div class="col-12 col-md-2">
        <label class="form-label" for="WeekEndSat">{{trans('common.WeekEndSat')}}</label>
        <div class="form-check form-check-success form-switch">
            {{Form::checkbox('WeekEndSat','1',getSettingValue('WeekEndSat') == '1' ? true : false,['id'=>'WeekEndSat', 'class'=>'form-check-input'])}}
            <label class="form-check-label" for="WeekEndSat"></label>
        </div>
    </div>
</div>
<!--/ form -->