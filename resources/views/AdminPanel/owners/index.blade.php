@extends('AdminPanel.layouts.master')
@section('content')
    <!-- Bordered table start -->
    <div class="row" id="table-bordered">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{$title}}</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-2">
                        <thead>
                            <tr>
                                <th>{{trans('common.name')}}</th>
                                <th>{{trans('common.phone')}}</th>
                                <th>{{trans('common.email')}}</th>
                                <th>{{trans('common.identity')}}</th>
                                <th>{{trans('common.governorate')}}</th>
                                <th>{{trans('common.city')}}</th>
                                <th>{{trans('common.dept')}}</th>
                                <th>{{trans('common.duration')}}</th>
                                <th class="text-center">{{trans('common.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($owners as $value)
                            <tr id="row_{{$value->id}}">
                                @if ($value->name != null)
                                      <td>
                                    {{$value->name}}
                                </td>
                                @endif
                              
                                <td>
                                    {{$value['phone']}}
                                </td>
                                <td>
                                    {{$value['email']}}
                                </td>
                                <td>
                                    {{$value['identity']}}
                                </td>
                                <td>
                                    <?php $gov = App\Models\Governorates::find($value->governorate_id);?>
                                    @if ($gov  != null)
                                    {{$gov->name}}
                                    @endif
                                </td>
                                <td>
                                    <?php $city = App\Models\Cities::find($value->city_id);?>
                                    @if ($city != null)
                                    {{$city->name}}
                                    @endif
                                </td>
                                <td>
                                    @foreach ($value->installments as $dept)
                                    {{ $dept->dept }} <br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($value->installments as $duration)
                                    {{ $duration->duration }} <br>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" data-bs-target="#editCity{{$value->id}}" data-bs-toggle="modal" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.edit')}}">
                                        <i data-feather='edit'></i>
                                    </a>
                                    <?php $delete = route('admin.owner.delete',$value->id); ?>
                                         <button type="button" class="btn btn-icon btn-danger" onclick="confirmDelete('{{$delete}}','{{$value->id}}')" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.delete')}}">
                                        <i data-feather='trash-2'></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-3 text-center ">
                                        <h2>{{trans('common.nothingToView')}}</h2>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @foreach($owners as $value)
                    <div class="modal fade text-md-start" id="editCity{{$value->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pb-5 px-sm-5 pt-50">
                                    <div class="text-center mb-2">
                                        <h1 class="mb-1">{{trans('common.edit')}}</h1>
                                    </div>
                                    {{Form::open(['url'=>route('admin.owner.update',$value->id), 'class'=>'row gy-1 pt-75'])}}
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="name">{{trans('common.name')}}</label>
                                        {{Form::text('name',$value->name,['id'=>'name', 'class'=>'form-control','required'])}}
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="phone">{{trans('common.phone')}}</label>
                                        {{Form::text('phone',$value->phone,['id'=>'phone', 'class'=>'form-control','required'])}}
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="email">{{trans('common.email')}}</label>
                                        {{Form::text('email',$value->email,['id'=>'email', 'class'=>'form-control','required'])}}
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label" for="identity">{{trans('common.identity')}}</label>
                                        {{Form::text('identity',$value->identity,['id'=>'identity', 'class'=>'form-control','required'])}}
                                    </div>
                                    {{-- <div class="col-12 col-md-6">
                                        <label class="form-label font-medium-2" for="commission">{{ trans('common.commission') }}</label>
                                        {{ Form::number('commission', $value->commission, ['id' => 'commission', 'class' => 'form-control ', 'required','step' => '0.0001']) }}
                                    </div> --}}

                                    {{-- @dd($governorates); --}}
                                    <?php
                                        $gov = App\Models\Governorates::find($value->governorate_id);
                                        $city = App\Models\Cities::find($value->city_id);
                                    ?>
                                    {{-- @dd($governorates); --}}
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="governorate">{{trans('common.city')}}</label>
                                            {{Form::select('governorate_id', [''=>'اختر']+$governorates,$value->governorate_id, ['id'=>'governorates', 'class'=>'form-select','required','onchange'=>'showElemet(this,this.value)'])}}
                                        </div>
                                        <?php $this_city = App\Models\Cities::where('GovernorateID',$value->governorate_id)->pluck('name','id')->all(); ?>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="city">{{trans('common.zone')}}</label>
                                            {{Form::select('city_id',[]+$this_city,$value->city_id,['id'=>'cities','class'=>'form-control','required'])}}
                                        </div>
                                    </div>

                                    <div class="repeatableTempRest">
                                        @foreach ($value->installments as $key)
                                        <div class="col-12 col-md-12">
                                            <label class="form-label font-medium-2" for="dept">{{ trans('common.dept') }}</label>
                                            {{ Form::number('dept[]', $key->dept, ['id' => 'dept', 'class' => 'form-control ', 'required']) }}
                                        </div>
                                        <div class="col-12 col-md-12">
                                            <label class="form-label font-medium-2" for="duration">{{ trans('common.duration') }}</label>
                                            {{ Form::number('duration[]', $key->duration, ['id' => 'duration', 'class' => 'form-control ', 'required']) }}
                                        </div>
                                        @endforeach
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
                @endforeach
                {{ $owners->links('vendor.pagination.default') }}
            </div>
        </div>
    </div>
    <!-- Bordered table end -->



@stop

@section('page_buttons')
    <a href="javascript:;" data-bs-target="#createCity" data-bs-toggle="modal" class="btn btn-primary">
        {{trans('common.CreateNew')}}
    </a>

    <div class="modal fade text-md-start" id="createCity" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">{{trans('common.CreateNew')}}</h1>
                    </div>
                    {{Form::open(['url'=>route('admin.owner.store'), 'class'=>'row gy-1 pt-75'])}}
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="name">{{trans('common.name')}}</label>
                            {{Form::text('name','',['id'=>'name', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="phone">{{trans('common.phone')}}</label>
                            {{Form::text('phone','',['id'=>'phone', 'class'=>'form-control','required'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="email">{{trans('common.email')}}</label>
                            {{Form::text('email','',['id'=>'email', 'class'=>'form-control'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="identity">{{trans('common.identity')}}</label>
                            {{Form::text('identity','',['id'=>'identity', 'class'=>'form-control'])}}
                        </div>
                        {{-- @dd($governorates); --}}
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="governorate">{{trans('common.city')}}</label>
                                {{Form::select('governorate_id', [''=>'اختر']+$governorates, '', ['id'=>'governorates', 'class'=>'form-select','onchange'=>'showElemet(this,this.value)'])}}
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="city">{{trans('common.zone')}}</label>
                                {{Form::select('city_id',[],'',['id'=>'cities','class'=>'form-control'])}}
                            </div>
                        </div>

                                {{-- add more section --}}
                        <div class="repeatableTempRest">
                            <div class="col-12 col-md-12">
                                <label class="form-label font-medium-2" for="dept">{{ trans('common.dept') }}</label>
                                {{ Form::number('dept[]', '', ['id' => 'dept', 'class' => 'form-control ']) }}
                            </div>
                            <div class="col-12 col-md-12">
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
@stop

@section('scripts')
    <script type="text/template" id="RepeatTempoRestTPL">
    <div class="More row mb-1">
            <h6>المزيد</h6>
            <div class="col-12 col-md-12">
                <label class="form-label font-medium-2" for="dept">{{ trans('common.dept') }}</label>
                {{ Form::number('dept[]', '', ['id' => 'dept', 'class' => 'form-control ']) }}
            </div>
            <div class="col-12 col-md-12">
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
            var url = '<?php echo url("AdminPanel/owner/getZone"); ?>/'+val;
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
