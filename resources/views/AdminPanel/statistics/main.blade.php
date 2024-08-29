<?php
// $array = [];
// $booked = App\Models\ClientUnit::where('status', 'booking')->count();
// $contracted = App\Models\ClientUnit::where('status', 'contract')->count();
// $units = App\Models\Units::count();
// $available = $units - ($booked + $contracted);
// $todayClients = number_format(homeStates()['todayClients']);
// $monthClients = number_format(homeStates()['monthClients']);
// $array = [$todayClients,$monthClients,$booked, $contracted, $available];
// $statistics = "'متاح للبيع','تم التعاقد','تم الحجز','عملاء الشهر','عملاء اليوم'";
?>
<!-- Donut Chart -->
<div class="row">
<div class="col-md-6 col-6 mb-4">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">{{trans('common.Statistics')}}</h5>
            </div>
            <div class="dropdown d-none d-sm-flex">
                {{-- <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i data-feather='menu'></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['today' => now()->startOfDay()->toDateString() ,'key' =>'statistic']) }}">
                            Today
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['yesterday' => now()->subDays(1)->startOfDay()->toDateString() ,'key' =>'statistic']) }}">
                            Yesterday
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['yesterday' => now()->subDays(7)->startOfDay()->toDateString() ,'key' =>'statistic']) }}">
                            Week
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['month' => now()->subDays(30)->startOfDay()->toDateString() ,'key' =>'statistic']) }}">
                            Last Month
                        </a>
                    </li>
                </ul> --}}
            </div>
        </div>
        <div class="card-body">
            <div id="donutChartMain"></div>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div class="row match-height">
        <div class="col-12">
            <div class="divider mt-0">
                <div class="divider-text">{{trans('common.ShortCut')}}</div>
            </div>
        </div>
        @if(userCan('clients_create'))
            <div class="col-md-4 col-xl-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <a href="javascript:;" data-bs-target="#createClientModal" data-bs-toggle="modal" class="text-white">
                            {{trans('common.clients')}} - {{trans('common.CreateNew')}}
                        </a>
                        @include('AdminPanel.clients.create',['position'=>'sales'])
                    </div>
                </div>
            </div>
        @endif
        @if(userCan('clients_create_excel'))
            <div class="col-md-4 col-xl-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <a href="{{route('admin.clients')}}" class="text-white">

                        </a>
                        <a href="javascript:;" data-bs-target="#createExcelClient" data-bs-toggle="modal" class="text-white">
                            {{trans('common.clients')}} - {{trans('common.uploadExcel')}}
                        </a>
                    </div>
                </div>
            </div>
            @include('AdminPanel.clients.createExcel')
        @endif
        <div class="col-md-4 col-xl-4">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <a href="javascript:;" data-bs-target="#createFollowUp" data-bs-toggle="modal" class="text-white">
                        {{trans('common.CreateNewFollowUp')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12"></div>
        <div class="col-12">
            <div class="divider mt-0">
                <div class="divider-text">{{trans('common.reports')}}</div>
            </div>
        </div>
        @if(userCan('reports_rejectionCauses'))
            <div class="col-md-4 col-xl-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <a class="text-white" href="{{route('admin.reports.rejectionCauses')}}">
                            {{trans('common.rejectionCauses')}}
                        </a>
                    </div>
                </div>
            </div>
        @endif
        @if(userCan('reports_teamPerformance'))
            <div class="col-md-4 col-xl-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <a class="text-white" href="{{route('admin.reports.teamPerformance')}}">
                            {{trans('common.teamPerformance')}}
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
<!-- Greetings Card ends -->
</div>

<div class="modal fade text-md-start" id="createFollowUp" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">{{trans('common.CreateNewFollowUp')}}</h1>
                </div>
                {{Form::open(['url'=>route('admin.followups.store'), 'id'=>'createFollowUpForm', 'class'=>'row gy-1 pt-75'])}}
                    <div class="row">
                        <div class="col-md-4">
                            <b>{{trans('common.agent')}}: </b>
                            {{auth()->user()->name}}
                        </div>
                        <div class="col-md-4">
                            <b>{{trans('common.date')}}: </b>
                            {{date('Y-m-d')}}
                        </div>
                        <div class="col-md-4">
                            <b>{{trans('common.time')}}: </b>
                            {{date('H:i:s')}}
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="ClientID">{{trans('common.client')}}</label>
                        {{Form::select('ClientID',clientsList(session()->get('Lang')),'',['id'=>'ClientID', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="contactingType">{{trans('common.followUpType')}}</label>
                        {{Form::select('contactingType',contactingTypeList(session()->get('Lang')),'',['id'=>'contactingType', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="whoIsContacting">{{trans('common.whoIsContacting')}}</label>
                        {{Form::select('whoIsContacting',whoIsContactingList(session()->get('Lang')),'',['id'=>'whoIsContacting', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="UnitID">{{trans('common.unit')}}</label>
                        {{Form::select('UnitID',['All' => 'كل الوحدات'] + unitsList(),'',['id'=>'UnitID', 'class'=>'selectpicker' ,'data-live-search'=>'true'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="status">{{trans('common.statusChanger')}}</label>
                        {{Form::select('status',['' => trans('common.Select')] + clientStatusArray(session()->get('Lang')),'',['id'=>'status', 'class'=>'form-control','onchange'=>'changeStatus(this)','required'])}}
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextContactingDateTime">{{trans('common.nextContactingDate')}}</label>
                        {{Form::date('nextContactingDateTime','',['id'=>'nextContactingDateTime', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="nextContactingTime">الوقت</label>
                        {{Form::time('nextContactingTime','',['id'=>'nextContactingTime', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="nextContactingType">{{trans('common.followUpType')}}</label>
                        {{Form::select('nextContactingType',[''=>trans('common.none')] + contactingTypeList(session()->get('Lang')),'',['id'=>'nextContactingType', 'class'=>'form-control'])}}
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="offerDetails">تفاصيل المحادثة</label>
                        {{Form::textarea('offerDetails','',['id'=>'offerDetails', 'class'=>'form-control'])}}
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

{{-- @stop --}}
<!-- /Donut Chart -->
{{-- @section('scripts')
<script>
    (function() {
        let cardColor, headingColor, labelColor, borderColor, legendColor;
        const chartColors = {
            column: {
                series1: '#826af9',
                series2: '#d2b0ff',
                bg: '#f8d3ff'
            },
            donut: {
                series1: '#fee802',
                series2: '#3fd0bd',
                series3: '#826bf8',
                series4: '#2b9bf4',
                series5: '#A25772',
            },
            area: {
                series1: '#29dac7',
                series2: '#60f2ca',
                series3: '#a5f8cd'
            }
        };

        // Donut Chart
        // --------------------------------------------------------------------
        const donutChartEl = document.querySelector('#donutChartMain'),
            donutChartConfig = {
                chart: {
                    height: 390,
                    type: 'donut'
                },
                labels: [{!! $statistics !!}],
                series: [{{ implode(',', $array) }}],
                colors: [
                    chartColors.donut.series1,
                    chartColors.donut.series5,
                    chartColors.donut.series4,
                    chartColors.donut.series3,
                    chartColors.donut.series2
                ],
                stroke: {
                    show: false,
                    curve: 'straight'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opt) {
                        return parseInt(val, 10) + '%';
                    }
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    markers: {
                        offsetX: -3
                    },
                    itemMargin: {
                        vertical: 3,
                        horizontal: 10
                    },
                    labels: {
                        colors: legendColor,
                        useSeriesColors: false
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: {
                                    fontSize: '2rem',
                                    fontFamily: 'Public Sans'
                                },
                                value: {
                                    fontSize: '1.2rem',
                                    color: legendColor,
                                    fontFamily: 'Public Sans',
                                    formatter: function(val) {
                                        return parseInt(val, 10) + '%';
                                    }
                                },
                                total: {
                                    show: true,
                                    fontSize: '1.5rem',
                                    color: headingColor,
                                    label: 'Operational',
                                    formatter: function(w) {
                                        return '%';
                                    }
                                }
                            }
                        }
                    }
                },
                responsive: [{
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 380
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    colors: legendColor,
                                    useSeriesColors: false
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 320
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        labels: {
                                            show: true,
                                            name: {
                                                fontSize: '1.5rem'
                                            },
                                            value: {
                                                fontSize: '1rem'
                                            },
                                            total: {
                                                fontSize: '1.5rem'
                                            }
                                        }
                                    }
                                }
                            },
                            legend: {
                                position: 'bottom',
                                labels: {
                                    colors: legendColor,
                                    useSeriesColors: false
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 420,
                        options: {
                            chart: {
                                height: 280
                            },
                            legend: {
                                show: false
                            }
                        }
                    },
                    {
                        breakpoint: 360,
                        options: {
                            chart: {
                                height: 250
                            },
                            legend: {
                                show: false
                            }
                        }
                    }
                ]
            };
        if (typeof donutChartEl !== undefined && donutChartEl !== null) {
            const donutChart = new ApexCharts(donutChartEl, donutChartConfig);
            donutChart.render();
        }
    })();

    function changeStatus(elem) {
            var rejectionList = elem.parentNode.parentNode.querySelector('#rejictionCause');
            if (elem.value != 'no_interest' && elem.value != 'checkout_reject') {
                rejectionList.parentNode.style.display = "none";
            } else {
                rejectionList.parentNode.style.display = "block";
            }
        }
</script>
@stop --}}
