<div class="table-responsive">
    <table class="table table-bordered mb-2">
        <thead class="table-dark">
            <tr>
                <th>{{trans('common.month')}}</th>
                <th>{{trans('common.amount')}}</th>
                <th>{{trans('common.responsible')}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user->salaryChanges as $theSalary)
                <tr id="row_{{ $theSalary->id }}">
                    <td>
                        {{ $theSalary->fromMonth }} - {{ $theSalary->fromYear }}
                    </td>
                    <td class="text-center">
                        {{ $theSalary->Salary }}
                    </td>
                    <td>{{$theSalary->responsible->name ?? '-'}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>