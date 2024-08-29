<table class="table table-bordered mb-2">
    <thead>
        <tr>
            <th>{{trans('common.name')}}</th>
            <th>{{trans('common.mobile')}}</th>
            <th>{{trans('common.supportHousing')}}</th>
            <th>{{trans('common.identity')}}</th>
            <th>{{trans('common.salary')}}</th>
            <th>{{trans('common.bank')}}</th>
            <th>{{trans('common.job')}}</th>
            <th>{{trans('common.Notes')}}</th>
            <th>{{trans('common.refferal')}}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clients as $client)
            <tr>
                <td>
                    {{$client['Name']}}
                </td>
                <td>
                    {{$client->cellphone}}
                </td>
                <td>
                    {{$client->worthyStatusTxt()['text'] ?? ''}}
                </td>
                <td>
                    {{$client->identity}}
                </td>
                <td>
                    {{$client->salary}}
                </td>
                <td>
                    {{$client->bank}}
                </td>
                <td>
                    {{$client->militaryTitle}}
                </td>
                <td>
                    {{$client->Notes}}
                </td>
                <td>
                    {{$client->sourceText() ?? ''}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="p-3 text-center ">
                    <h2>{{trans('common.nothingToView')}}</h2>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
