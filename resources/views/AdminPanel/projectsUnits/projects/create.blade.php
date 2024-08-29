<a href="javascript:;" data-bs-target="#createExpense" data-bs-toggle="modal" class="btn btn-sm btn-primary">
    {{trans('common.CreateNew')}}
</a>

<div class="modal fade text-md-start" id="createExpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                </div>
                {{Form::open(['files'=>'true','url'=>route('admin.projects.store'), 'id'=>'createExpenseForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-9">
                        <label class="form-label" for="name">{{trans('common.name')}}</label>
                        {{Form::text('name','',['id'=>'name', 'class'=>'form-control','required'])}}
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
