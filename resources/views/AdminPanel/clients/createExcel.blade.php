

<div class="modal fade text-md-start" id="createExcelClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.uploadExcel')}}</h1>
                </div>
                {{Form::open(['files'=>'true','url'=>route('admin.clients.storeExcelClient'), 'id'=>'createExcelClientForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="file"><b>{{trans('common.file')}}</b> <small>يسمح برفع ملفات xlsx فقط</small></label>
                        <input class="form-control" id="file" name="excel" type="file">
                        @if($errors->has('excel'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('excel') }}</b>
                            </span>
                        @endif
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
