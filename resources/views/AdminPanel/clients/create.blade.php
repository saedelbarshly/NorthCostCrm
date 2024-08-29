
<div class="modal fade text-md-start" id="createClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                </div>
                {{Form::open(['url'=>route('admin.clients.store'), 'id'=>'createClientForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="Name">{{trans('common.name')}}</label>
                        {{Form::text('Name','',['id'=>'Name', 'class'=>'form-control','required'])}}
                        @if($errors->has('Name'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('Name') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="cellphone">{{trans('common.mobile')}}</label>
                        {{Form::text('cellphone','',['id'=>'cellphone', 'class'=>'form-control'])}}
                        @if($errors->has('cellphone'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('cellphone') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="supportHousing">{{trans('common.supportHousing')}}</label>
                        {{Form::select('supportHousing',supportHousingList(session()->get('Lang')),'',['id'=>'supportHousing', 'class'=>'selectpicker'])}}
                        @if($errors->has('supportHousing'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('supportHousing') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="identity">{{trans('common.identity')}}</label>
                        {{Form::text('identity','',['id'=>'identity', 'class'=>'form-control'])}}
                        @if($errors->has('identity'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('identity') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12"></div>
                    {{-- <div class="col-12 col-md-3">
                        <label class="form-label" for="salary">{{trans('common.salary')}}</label>
                        {{Form::number('salary','',['id'=>'salary', 'class'=>'form-control'])}}
                    </div> --}}

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="AgentID">{{ trans('common.salary') }}</label>
                        {{ Form::number('salary', '', ['id' => 'salary', 'class' => 'form-control']) }}
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="bank">{{trans('common.bank')}}</label>
                        {{Form::text('bank','',['id'=>'bank', 'class'=>'form-control'])}}
                        @if($errors->has('bank'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('bank') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="Job">{{trans('common.job')}}</label>
                        {{Form::select('Job',clientJobsList(session()->get('Lang')),'',['id'=>'Job', 'class'=>'form-control','onchange'=>'showMilitaryInput(this.value)','required'])}}
                    </div>
                    <div class="col-12 col-md-3" id="militaryTitle">
                        <label class="form-label" for="militaryTitleInput">{{trans('common.militaryTitle')}}</label>
                        {{Form::text('militaryTitle','',['id'=>'militaryTitleInput', 'class'=>'form-control'])}}
                        @if($errors->has('militaryTitle'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('militaryTitle') }}</b>
                            </span>
                        @endif
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label" for="district">{{trans('common.district')}}</label>
                        {{Form::text('district','',['id'=>'district', 'class'=>'form-control'])}}
                        @if($errors->has('district'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('district') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="commitments">{{trans('common.commitments')}}</label>
                        {{Form::text('commitments','',['id'=>'commitments', 'class'=>'form-control'])}}
                        @if($errors->has('commitments'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('commitments') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="dob">{{trans('common.dob')}}</label>
                        {{Form::date('dob','',['id'=>'dob', 'class'=>'form-control'])}}
                        @if($errors->has('dob'))
                            <span class="text-danger" role="alert">
                                <b>{{ $errors->first('dob') }}</b>
                            </span>
                        @endif
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-9">
                        <label class="form-label" for="Notes">{{trans('common.Notes')}}</label>
                        {{Form::text('Notes','',['id'=>'Notes', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="referral">{{trans('common.refferal')}}</label>
                        {{Form::select('referral',refferalList(session()->get('Lang')),'',['id'=>'referral', 'class'=>'form-control'])}}
                        {{Form::hidden('position',isset($position) ? $position : 'call_center')}}
                    </div>
                    {{-- @if(isset($position))
                        @if($position == 'sales')
                            <div class="col-12 col-md-3">
                                <label class="form-label" for="position">{{trans('common.position')}}</label>
                                {{Form::select('position',clientPositionsArray(session()->get('Lang'),'no'),'',['id'=>'position', 'class'=>'form-control'])}}
                            </div>
                            {{Form::hidden('UID',auth()->user()->id)}}
                        @endif
                    @endif --}}

                        <?php
                            $govs = App\Models\Governorates::pluck('name','id')->all();
                            $cities = App\Models\Cities::pluck('name','id')->all();
                        ?>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="governorate">{{trans('common.city')}}</label>
                            {{Form::select('governorate_id', [''=>'اختر']+$govs, '', ['id'=>'governorates', 'class'=>'form-select','onchange'=>'showElemet(this,this.value)'])}}
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="city">{{trans('common.zone')}}</label>
                            {{Form::select('city_id',[],'',['id'=>'cities','class'=>'form-control'])}}
                        </div>
                    </div>

                    {{-- add more section --}}
                    <div class="repeatableTempRest row mb-1">
                        <div class="col-12 col-md-6">
                            <label class="form-label font-medium-2" for="dept">{{ trans('common.dept') }}</label>
                            {{ Form::number('dept[]', '', ['id' => 'dept', 'class' => 'form-control ']) }}
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label font-medium-2" for="duration">{{ trans('common.duration') }}</label>
                            {{ Form::number('duration[]', '', ['id' => 'duration', 'class' => 'form-control ']) }}
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <span class="add_tempo_rest btn btn-sm btn-info">
                            {{trans('common.add more')}}
                        </span>
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
<script type="text/template" id="RepeatTempoRestTPL">
    <div class="More row mb-1">
            <h6>المزيد</h6>
            <div class="col-12 col-md-6">
                <label class="form-label font-medium-2" for="dept">{{ trans('common.dept') }}</label>
                {{ Form::number('dept[]', '', ['id' => 'dept', 'class' => 'form-control ']) }}
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label font-medium-2" for="duration">{{ trans('common.duration') }}</label>
                {{ Form::number('duration[]', '', ['id' => 'duration', 'class' => 'form-control ']) }}
            </div>

            <div class="col-1 col-md-2 mt-2">
                <span class="delete btn btn-icon btn-danger btn-block">
                    {{trans('common.delete')}}
                </span>
            </div>
        </div>
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var max_fields = 50;
            var ContractServices_wrapper = $(".repeatableTempRest");
            var add_tempo_rest = $(".add_tempo_rest");
            var RepeatTempoRestTPL = $("#RepeatTempoRestTPL").html();
            var x = 1;
            $(add_tempo_rest).click(function(e) {
                e.preventDefault();
                if (x < max_fields) {
                    x++;
                    $(ContractServices_wrapper).append(RepeatTempoRestTPL); //add input box
                } else {
                    alert('You Reached the limits')
                }
            });
            $(ContractServices_wrapper).on("click", ".delete", function(e) {
                e.preventDefault();
                $(this).closest('.More').remove();
                x--;
            });
        });
    </script>
    <script>
        function showElemet(elem,val)
        {
            var row = $(elem).parent().parent();
            console.log(row);
            var url = '<?php echo url("AdminPanel/clients/getZone"); ?>/'+val;
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




