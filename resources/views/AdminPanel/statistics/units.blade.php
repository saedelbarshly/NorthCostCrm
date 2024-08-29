<?php
// $arr = [];
// $book = App\Models\ClientUnit::where('status', 'booking')->count();
// $contract = App\Models\ClientUnit::where('status', 'contract')->count();
// $units = App\Models\Units::count();
// $ava = $units - ($book + $contract);
// $arr = [$book, $contract, $ava];
// $unit = "'تم الحجز','تم التعاقد ','متاح البيع'";
?>
<!-- Donut Chart -->
<div class="col-md-12 col-12 mb-4">
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h5 class="card-title mb-0">الوحدات</h5>
            </div>
            <div class="dropdown d-none d-sm-flex">
                <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i data-feather='menu'></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['today' => now()->startOfDay()->toDateString() ,'key' =>'unit']) }}">
                            Today
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['yesterday' => now()->subDays(1)->startOfDay()->toDateString() ,'key' =>'unit']) }}">
                            Yesterday
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['yesterday' => now()->subDays(7)->startOfDay()->toDateString() ,'key' =>'unit']) }}">
                            Week
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.index', ['month' => now()->subDays(30)->startOfDay()->toDateString() ,'key' =>'unit']) }}">
                            Last Month
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div id="donutChartUnit"></div>
        </div>
    </div>
</div>
<!-- /Donut Chart -->
<?php
// $numberOfStatus = [];
// // $clientStatus = '';
// foreach(clientStatusArray(session()->get('Lang')) as $key => $status)
// {
//     // $clientStatus .=$status;
//   $numberOfStatus[] = App\Models\Clients::where('status',$key)->whereNotIn('position',['delete','archive'])->count();
// }
// $clientStatus = "'لا يرد',
// 'تواصل مرة أخرى',
// 'غير مهتم',
// 'رقم خاطئ/لاتتصل',
// 'متابعة',
// 'رفض',
// 'متابعه استكمال الاوراق',
// 'كاش مدعوم',
// 'كاش غير مدعوم',
// 'متابعة رسائل واتس',
// 'تم حجز موعد',
// 'تم الحضور',
// 'تم حجز فيلا',
// 'Booking/Contract Sign up'
// ";
?>
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
            },
            area: {
                series1: '#29dac7',
                series2: '#60f2ca',
                series3: '#a5f8cd'
            }
        };

        // Donut Chart
        // --------------------------------------------------------------------
        const donutChartEl = document.querySelector('#donutChartUnit'),
            donutChartConfig = {
                chart: {
                    height: 390,
                    type: 'donut'
                },
                labels: [{!! $unit !!}],
                series: [{{ implode(',', $arr) }}],
                colors: [
                    chartColors.donut.series1,
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
</script>
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
                series6: '#22092C',
                series7: '#5B0888',
                series8: '#618264',
                series9: '#94A684',
                series10: '#2E4374',
                series11: '#9400FF',
                series12: '#EDB7ED',
                series13: '#FFBB5C',
                series14: '#79AC78',
                series15: '#952323',
            },
            area: {
                series1: '#29dac7',
                series2: '#60f2ca',
                series3: '#a5f8cd'
            }
        };

        // Donut Chart
        // --------------------------------------------------------------------
        const donutChartEl = document.querySelector('#donutChartTotal'),
            donutChartConfig = {
                chart: {
                    height: 390,
                    type: 'donut'
                },
                labels: [{!! $clientStatus !!}],
                series: [{{ implode(',', $numberOfStatus) }}],
                colors: [
                    chartColors.donut.series1,
                    chartColors.donut.series15,
                    chartColors.donut.series14,
                    chartColors.donut.series13,
                    chartColors.donut.series12,
                    chartColors.donut.series11,
                    chartColors.donut.series10,
                    chartColors.donut.series9,
                    chartColors.donut.series8,
                    chartColors.donut.series7,
                    chartColors.donut.series6,
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
</script>
@stop --}}
