@extends('AdminPanel.layouts.master')
@section('content')
<?php
    $month = date('m');
    $year = date('Y');
    $users = App\Models\User::pluck('id')->toArray();
    $activeChartTodayTab = '';
    $activeChartTab = 'active';
    $activeYearTableTab = '';
    if (isset($_GET['month'])) {
        if ($_GET['month'] != '') {
            $month = $_GET['month'];
        }
    }
    if (isset($_GET['year'])) {
        if ($_GET['year'] != '') {
            $year = $_GET['year'];
        }
    }
    if (isset($_GET['agent_id'])) {
        if ($_GET['agent_id'] != '') {
            $users = [$_GET['agent_id']];
        }
    }

    if (isset($_GET['tab'])) {
        if ($_GET['tab'] == 'yearTable') {
            $activeChartTodayTab = '';
            $activeChartTab = '';
            $activeYearTableTab = 'active';
        }
        if ($_GET['tab'] == 'chart') {
            $activeChartTodayTab ='';
            $activeChartTab = 'active';
            $activeYearTableTab = '';
        }
        if ($_GET['tab'] == 'today') {
            $activeChartTodayTab = 'active';
            $activeChartTab = '';
            $activeYearTableTab = '';
        }
    }
?>

<!-- Bordered table start -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{$activeChartTodayTab}}" id="today-tab" data-bs-toggle="tab" href="#today" aria-controls="today" role="tab" aria-selected="{{$activeChartTodayTab == 'active' ? 'true' : 'false'}}">
                            <i data-feather='bar-chart-2'></i> {{trans('common.todayChart')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$activeChartTab}}" id="chart-tab" data-bs-toggle="tab" href="#chart" aria-controls="chart" role="tab" aria-selected="{{$activeChartTab == 'active' ? 'true' : 'false'}}">
                            <i data-feather='bar-chart-2'></i> {{trans('common.monthChart')}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{$activeYearTableTab}}" id="yearTable-tab" data-bs-toggle="tab" href="#yearTable" aria-controls="yearTable" role="tab" aria-selected="{{$activeYearTableTab == 'active' ? 'true' : 'false'}}">
                            <i data-feather='grid'></i> {{trans('common.yearTable')}}
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane {{$activeChartTodayTab}}" id="today" aria-labelledby="today-tab" role="tabpanel">
                        <div class="d-flex justify-content-center mb-3 text-center">
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.PhoneCall')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.today.reports',['status'=>'Call','users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamTodayFollwupsStats($users)['Call'])}}</span>
                                    </a>
                                </h3>
                            </div>
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.UnitVisit')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.today.reports',['status'=>'UnitVisit','users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamTodayFollwupsStats($users)['UnitVisit'])}}</span>
                                    </a>
                                </h3>
                            </div>
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.booking')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.today.reports',['status'=>'Booking','users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamTodayFollwupsStats($users)['Booking'])}}</span>
                                </a>
                                </h3>
                            </div>
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.contract')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.today.reports',['status'=>'Contract','users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamTodayFollwupsStats($users)['Contract'])}}</span>
                                    </a>
                                </h3>
                            </div>
                        </div>
                        <div id="today-chart"></div>
                    </div>

                    <div class="tab-pane {{$activeChartTab}}" id="chart" aria-labelledby="chart-tab" role="tabpanel">
                        <div class="d-flex justify-content-center mb-3 text-center">
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.PhoneCall')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.month.reports',['status'=>'Call','month' => $month,'year' =>$year,'users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamFollowupsStats($users,$month,$year)['Call'])}}</span>
                                </a>
                                </h3>
                            </div>
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.UnitVisit')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.month.reports',['status'=>'UnitVisit','month' => $month,'year' =>$year,'users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamFollowupsStats($users,$month,$year)['UnitVisit'])}}</span>
                                    </a>
                                </h3>
                            </div>
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.booking')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.month.reports',['status'=>'Booking','month' => $month,'year' =>$year,'users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamFollowupsStats($users,$month,$year)['Booking'])}}</span>
                                </a>
                                </h3>
                            </div>
                            <div class="me-2">
                                <p class="card-text mb-50">{{trans('common.contract')}}</p>
                                <h3 class="fw-bolder">
                                    <a href="{{route('admin.month.reports',['status'=>'Contract','month' => $month,'year' =>$year,'users'=>$users])}}">
                                    <span class="text-info">{{number_format(teamFollowupsStats($users,$month,$year)['Contract'])}}</span>
                                    </a>
                                </h3>
                            </div>
                        </div>
                        <div id="month-chart"></div>
                    </div>
                    <div class="tab-pane {{$activeYearTableTab}}" id="yearTable" aria-labelledby="yearTable-tab" role="tabpanel">
                        <table class="table table-bordered mb-2">
                            <thead class="dark-table">
                                <tr>
                                    <th>{{trans('common.month')}}</th>
                                    <th class="text-center">{{trans('common.PhoneCall')}}</th>
                                    <th class="text-center">{{trans('common.UnitVisit')}}</th>
                                    <th class="text-center">{{trans('common.booking')}}</th>
                                    <th class="text-center">{{trans('common.contract')}}</th>
                                    <th class="text-center">{{trans('common.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(monthsForThisYear($year) as $singleMonth)
                                    <tr>
                                        <td>
                                            {{arabicMonth($year.'-'.$singleMonth.'-1').', '.$year}}
                                        </td>
                                        <td class="text-center">
                                            {{number_format(teamFollowupsStats($users,$singleMonth,$year)['Call'])}}
                                        </td>
                                        <td class="text-center">
                                            {{number_format(teamFollowupsStats($users,$singleMonth,$year)['UnitVisit'])}}
                                        </td>
                                        <td class="text-center">
                                            {{number_format(teamFollowupsStats($users,$singleMonth,$year)['Booking'])}}
                                        </td>
                                        <td class="text-center">
                                            {{number_format(teamFollowupsStats($users,$singleMonth,$year)['Contract'])}}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('admin.reports.teamPerformance',['month'=>$singleMonth, 'year'=>$year, 'agent_id'=>$_GET['agent_id'] ?? ''])}}" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.view')}}">
                                                <i data-feather='eye'></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bordered table end -->

@stop

@section('page_buttons')
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
                    {{Form::open(['id'=>'createunitForm', 'class'=>'row gy-1 pt-75', 'method'=>'GET'])}}
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="month">{{trans('common.month')}}</label>
                            {{Form::select('month',monthArray(session()->get('Lang')),$month,['id'=>'month', 'class'=>'form-select'])}}
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="year">{{trans('common.year')}}</label>
                            {{Form::select('year',yearArray(),$year,['id'=>'year', 'class'=>'form-select'])}}
                        </div>
                        @if(userCan('followups_view') && userCan('followups_view_branch') && userCan('followups_view_team'))
                            <div class="col-12 col-md-4">
                                <label class="form-label" for="agent_id">{{trans('common.agent')}}</label>
                                {{Form::select('agent_id',['' => trans('common.Select')] + agentsListForSearch(),$_GET['agent_id'] ?? '',['id'=>'agent_id', 'class'=>'selectpicker','data-live-search'=>'true'])}}
                            </div>
                        @endif


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
@stop

@section('scripts')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('AdminAssets/app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
    <!-- END: Page Vendor JS-->
    <script>
        $(window).on('load', function () {
            'use strict';
            var $textHeadingColor = '#5e5873';
            var $strokeColor = '#ebe9f1';
            var $labelColor = '#e7eef7';
            var $avgSessionStrokeColor2 = '#ebf0f7';
            var $budgetStrokeColor2 = '#dcdae3';
            var $goalStrokeColor2 = '#51e5a8';
            var $revenueStrokeColor2 = '#d0ccff';
            var $textMutedColor = '#b9b9c3';
            var $salesStrokeColor2 = '#df87f2';
            var $white = '#fff';
            var $earningsStrokeColor2 = '#28c76f66';
            var $earningsStrokeColor3 = '#28c76f33';

            var revenueChartOptions;
            var revenueChart;
            var $revenueChart = document.querySelector('#today-chart');

            revenueChartOptions = {
                chart: {
                    height: 240,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    type: 'line',
                    offsetX: -10
                },
                stroke: {
                    curve: 'smooth', //straight
                    dashArray: [0, 0, 0, 0, 0],
                    width: [2, 2, 2, 2, 2]
                },
                grid: {
                    borderColor: $labelColor
                },
                legend: {
                    show: false
                },
                colors: ['#036','#025','#014','#789','#069'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        inverseColors: false,
                        gradientToColors: ['#036','#025','#014','#789','#069'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    }
                },
                markers: {
                    size: 0,
                    hover: {
                        size: 5
                    }
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: $textMutedColor,
                            fontSize: '1rem'
                        }
                    },
                    axisTicks: {
                        show: false
                    },
                    categories: [{{daysForChart($month, $year)}}],
                    axisBorder: {
                        show: false
                    },
                    tickPlacement: 'on'
                },
                yaxis: {
                    tickAmount: 5,
                    labels: {
                        style: {
                        colors: $textMutedColor,
                        fontSize: '1rem'
                        },
                        formatter: function (val) {
                            return val > 999 ? (val / 1000).toFixed(0) + '{{trans("common.thousandChart")}}' : val;
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -20,
                        bottom: -10,
                        left: 20
                    }
                },
                tooltip: {
                    x: { show: false }
                },
                series: [
                    {
                        name: '{{trans("common.PhoneCall")}}',
                        data: [{{teamTodayFollwupsStats($users)["Call"]}}]
                    },
                    {
                        name: '{{trans("common.UnitVisit")}}',
                        data: [{{teamTodayFollwupsStats($users)["UnitVisit"]}}]
                    },
                    {
                        name: '{{trans("common.booking")}}',
                        data: [{{teamTodayFollwupsStats($users)["Booking"]}}]
                    },
                    {
                        name: '{{trans("common.contract")}}',
                        data: [{{teamTodayFollwupsStats($users)["Contract"]}}]
                    }
                ]
            };
            revenueChart = new ApexCharts($revenueChart, revenueChartOptions);
            revenueChart.render();
        });
    </script>
    <script>
        $(window).on('load', function () {
            'use strict';
            var $textHeadingColor = '#5e5873';
            var $strokeColor = '#ebe9f1';
            var $labelColor = '#e7eef7';
            var $avgSessionStrokeColor2 = '#ebf0f7';
            var $budgetStrokeColor2 = '#dcdae3';
            var $goalStrokeColor2 = '#51e5a8';
            var $revenueStrokeColor2 = '#d0ccff';
            var $textMutedColor = '#b9b9c3';
            var $salesStrokeColor2 = '#df87f2';
            var $white = '#fff';
            var $earningsStrokeColor2 = '#28c76f66';
            var $earningsStrokeColor3 = '#28c76f33';

            var revenueChartOptions;
            var revenueChart;
            var $revenueChart = document.querySelector('#month-chart');

            revenueChartOptions = {
                chart: {
                    height: 240,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    type: 'line',
                    offsetX: -10
                },
                stroke: {
                    curve: 'smooth', //straight
                    dashArray: [0, 0, 0, 0, 0],
                    width: [2, 2, 2, 2, 2]
                },
                grid: {
                    borderColor: $labelColor
                },
                legend: {
                    show: false
                },
                colors: ['#036','#025','#014','#789','#069'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        inverseColors: false,
                        gradientToColors: ['#036','#025','#014','#789','#069'],
                        shadeIntensity: 1,
                        type: 'horizontal',
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100, 100, 100]
                    }
                },
                markers: {
                    size: 0,
                    hover: {
                        size: 5
                    }
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: $textMutedColor,
                            fontSize: '1rem'
                        }
                    },
                    axisTicks: {
                        show: false
                    },
                    categories: [{{daysForChart($month, $year)}}],
                    axisBorder: {
                        show: false
                    },
                    tickPlacement: 'on'
                },
                yaxis: {
                    tickAmount: 5,
                    labels: {
                        style: {
                        colors: $textMutedColor,
                        fontSize: '1rem'
                        },
                        formatter: function (val) {
                            return val > 999 ? (val / 1000).toFixed(0) + '{{trans("common.thousandChart")}}' : val;
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -20,
                        bottom: -10,
                        left: 20
                    }
                },
                tooltip: {
                    x: { show: false }
                },
                series: [
                    {
                        name: '{{trans("common.PhoneCall")}}',
                        data: [{{teamMonthFollowupsStats($users,$month, $year)["Call"]}}]
                    },
                    {
                        name: '{{trans("common.UnitVisit")}}',
                        data: [{{teamMonthFollowupsStats($users,$month, $year)["UnitVisit"]}}]
                    },
                    {
                        name: '{{trans("common.booking")}}',
                        data: [{{teamMonthFollowupsStats($users,$month, $year)["Booking"]}}]
                    },
                    {
                        name: '{{trans("common.contract")}}',
                        data: [{{teamMonthFollowupsStats($users,$month, $year)["Contract"]}}]
                    }
                ]
            };
            revenueChart = new ApexCharts($revenueChart, revenueChartOptions);
            revenueChart.render();
        });
    </script>
@stop
