<!-- form -->
<div class="row">
    <div class="divider">
        <div class="divider-text">{{trans('common.booksPublishing')}}</div>
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label" for="stopAllPublishers">{{trans('common.stopAllPublishers')}}</label>
        <div class="form-check form-check-success form-switch">
            {{Form::checkbox('stopAllPublishers','1',getSettingValue('stopAllPublishers') == '1' ? true : false,['id'=>'stopAllPublishers', 'class'=>'form-check-input'])}}
            <label class="form-check-label" for="stopAllPublishers"></label>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label" for="autoBooksPublishing">{{trans('common.autoBooksPublishing')}}</label>
        <div class="form-check form-check-success form-switch">
            {{Form::checkbox('autoBooksPublishing','1',getSettingValue('autoBooksPublishing') == '1' ? true : false,['id'=>'autoBooksPublishing', 'class'=>'form-check-input'])}}
            <label class="form-check-label" for="autoBooksPublishing"></label>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <label class="form-label" for="publisherControlPublishStatus">{{trans('common.publisherControlPublishStatus')}}</label>
        <div class="form-check form-check-success form-switch">
            {{Form::checkbox('publisherControlPublishStatus','1',getSettingValue('publisherControlPublishStatus') == '1' ? true : false,['id'=>'publisherControlPublishStatus', 'class'=>'form-check-input'])}}
            <label class="form-check-label" for="publisherControlPublishStatus"></label>
        </div>
    </div>
    <div class="divider">
        <div class="divider-text">{{trans('common.publishersSetting')}}</div>
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label" for="publisherShare">{{trans('common.publisherShare')}}</label>
        {{Form::number('publisherShare',getSettingValue('publisherShare'),['step'=>'.01','id'=>'publisherShare','class'=>'form-control'])}}
    </div>
    <div class="col-12 col-md-4">
        <label class="form-label" for="MinimumForTransfeerMoney">{{trans('common.MinimumForTransfeerMoney')}}</label>
        {{Form::number('MinimumForTransfeerMoney',getSettingValue('MinimumForTransfeerMoney'),['step'=>'.01','id'=>'MinimumForTransfeerMoney','class'=>'form-control'])}}
    </div>
</div>
<!--/ form -->