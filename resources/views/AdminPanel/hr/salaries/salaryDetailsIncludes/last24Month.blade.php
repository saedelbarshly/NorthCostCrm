
<div class="table-responsive">
    <table class="table table-responsive-sm">
        <thead class="thead-dark">
            <tr>
                <th class="text-center">{{trans('common.month')}}</th>
                <th class="text-center">{{trans('common.salary')}}</th>
                <th class="text-center">{{trans('common.addons')}}</th>
                <th class="text-center">{{trans('common.deductions')}}</th>
                <th class="text-center">{{trans('common.receviedSalary')}}</th>
                <th class="text-center">{{trans('common.net')}}</th>
                <th class="text-center">{{trans('common.actions')}}</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $last24Months = [];
                $start = new \DateTime;
                $start->setDate($start->format('Y'), $start->format('n'), 1); // Normalize the day to 1
                $start->setTime(0, 0, 0); // Normalize time to midnight
                $start->sub(new \DateInterval('P24M'));
                $interval = new \DateInterval('P1M');
                $recurrences = 24;
                foreach (new \DatePeriod($start, $interval, $recurrences, true) as $date) {
                    // echo $date->format('F, Y'), "\n"; // attempting to make it more clear to read here
                    if (strtotime($user['employment_date']) <= strtotime($date->format('Y-m').'-28')
                        && strtotime(date('Y-m-d')) > strtotime($date->format('Y-m').'-28')) {
                        $last24Months[] = [
                            'year' => $date->format('Y'),
                            'month' => $date->format('m'),
                        ];
                    }
                }
                $last24Months = array_reverse($last24Months);
                $TotalNet = 0;
            ?>
            @foreach($last24Months as $month)
                    <tr>
                        <td class="text-center">
                            {{$month['month'].' - '.$month['year']}}
                        </td>
                        <td class="text-center">
                            {{$user->monthSalary($month['month'],$month['year'])['basic']}}
                        </td>
                        <td class="text-center">
                            {{$user->monthSalary($month['month'],$month['year'])['plus']}}
                        </td>
                        <td class="text-center">
                            {{$user->monthSalary($month['month'],$month['year'])['minus']}}
                        </td>
                        <td class="text-center">
                            {{$user->monthSalary($month['month'],$month['year'])['delivered']}}
                        </td>
                        <td class="text-center">
                            {{$user->monthSalary($month['month'],$month['year'])['net']}}
                            <?php $TotalNet += $user->monthSalary($month['month'],$month['year'])['net']; ?>
                        </td>
                        <td class="text-center text-nowrap">
                            <a href="{{route('admin.EmployeeSalary',$user->id)}}" class="btn btn-icon btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{trans('common.view')}}">
                                <i data-feather='eye'></i>
                            </a>
                        </td>
                    </tr>
            @endforeach
            <tr style="background-color:#64a8e2;font-weight:bold;">
                <td colspan="6" class="text-center">إجمالي مستحقات عن الشهور السابقة</td>
                <td class="text-center">{{$TotalNet}}</td>
            </tr>
        </tbody>
    </table>
</div>