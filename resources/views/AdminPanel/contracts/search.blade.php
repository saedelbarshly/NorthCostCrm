<a href="javascript:;" data-bs-target="#searchContracts" data-bs-toggle="modal" class="btn btn-primary btn-sm">
    {{trans('common.search')}}
</a>

<div class="modal fade text-md-start" id="searchContracts" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                {{Form::open(['id'=>'searchContractsForm', 'class'=>'row gy-1 pt-75', 'method'=>'GET'])}}
                    @if(userCan('contrcts_view'))
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="branch_id">{{trans('common.branch')}}</label>
                            <?php $branchesList = App\Branches::orderBy('name','asc')->pluck('name','id')->all(); ?>
                            {{Form::select('branch_id',['all'=>'الجميع'] + $branchesList,isset($_GET['branch_id']) ? $_GET['branch_id'] : '',['id'=>'branch_id', 'class'=>'form-select'])}}
                        </div>
                    @endif
                    @if(userCan('contrcts_view') || userCan('contrcts_view_branch'))
                        <div class="col-12 col-md-3">
                            <label class="form-label" for="agent_id">{{trans('common.agent')}}</label>
                            {{Form::select('agent_id',['all' => 'الجميع'] + agentsListForSearch(getSettingValue('sales_agent_role_id')),isset($_GET['AgentID']) ? $_GET['AgentID'] : '',['id'=>'AgentID', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                        </div>
                    @endif
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="client_id">{{trans('common.client')}}</label>
                        {{Form::select('client_id',['all'=>'الجميع'] + clientsList(session()->get('Lang')),isset($_GET['client_id']) ? $_GET['client_id'] : $client,['id'=>'client_id', 'class'=>'form-select selectpicker','data-live-search'=>'true'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="status">{{trans('common.status')}}</label>
                        {{Form::select('status',['all'=>'الجميع'] + contractStatusList(session()->get('Lang')),isset($_GET['status']) ? $_GET['status'] : $status,['id'=>'status', 'class'=>'form-select'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="paymentStatus">{{trans('common.paymentStatus')}}</label>
                        {{Form::select('paymentStatus',['all'=>'الجميع'] + contractPaymentStatusList(session()->get('Lang')),isset($_GET['paymentStatus']) ? $_GET['paymentStatus'] : 'all',['id'=>'status', 'class'=>'form-select'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="month">{{trans('common.month')}}</label>
                        {{Form::select('month',['all' => 'كل الشهور'] + monthArray(session()->get('Lang')),isset($_GET['month']) ? $_GET['month'] : $month,['id'=>'month', 'class'=>'form-select'])}}
                    </div> 
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="year">{{trans('common.year')}}</label>
                        {{Form::select('year',['all' => 'كل السنين'] + yearArray(),isset($_GET['year']) ? $_GET['year'] : $year,['id'=>'year', 'class'=>'form-select'])}}
                    </div> 
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">{{trans('common.search')}}</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            {{trans('common.Cancel')}}
                        </button>
                    </div>
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
