<td>
    @if (userCan('client_asignment'))
        <div class="form-check me-3 me-lg-1">
            <input class="form-check-input" type="checkbox" id="client{{ $client_row->id }}" name="clients[]"
                value="{{ $client_row->id }}" />
            <label class="form-check-label" for="client{{ $client_row->id }}">
                {{ $client_row->Name }}
            </label>
        </div>
        <div class="col-12 mb-1"></div>
        <a href="{{ route('admin.clients.udpateSupporting', $client_row->id) }}">
            <span class="btn btn-sm btn-{{ $client_row->worthyStatusTxt()['color'] }}">
                {{ $client_row->worthyStatusTxt()['text'] }}
            </span>
        </a>
    @else
        {{ $client_row->Name }}
    @endif

    <div class="col-12 mb-1"></div>
    @if ($client_row->cellphone != '')
        <span class="btn btn-sm btn-primary">
            <b><i data-feather='phone'></i></b>
            <a href="call:{{ $client_row->cellphone }}" class="text-white">
                {{ $client_row->cellphone ?? '-' }}
            </a>
        </span>
    @endif
    <br><small>{{ date('Y-m-d', strtotime($client_row->created_at)) }}</small>
</td>
<td>
    @if ($client_row->statusText() != '')
        <span class="btn btn-sm text-light" style="background-color:#A25772">
            {{ $client_row->statusText() }}
        </span>
    @endif
</td>
<td>
    {{ $client_row->identity }}
    <br>
    {{ clientJobsList(session()->get('Lang'))[$client_row->Job] ?? '' }}
    @if ($client_row->Job == 'military')
        <small>({{ $client_row->militaryTitle }})</small>
    @endif
    <br>
    {{ trans('common.salary') }} : {{ $client_row->salary ?? '' }}
        
</td>
<td>
    {{ $client_row->sourceText() }}
</td>

<td>
    @if ($client->lastFollowUp() != '')
        @if ($client->lastFollowUp()->agent != null)
            <span class="avatar position-relative test">
                <img class="round" src="{{ $client->lastFollowUp()->agent->photolink() }}" alt="avatar"
                    height="40" width="40">
                <span class="test2">{{ $client->lastFollowUp()->agent->name }}</span>
                {{-- <span class="avatar-status-online"></span> --}}
            </span>
        @endif
        {{-- {{ $client->lastFollowUp()->agent != '' ? $client->lastFollowUp()->agent->profile_photo : 'موظف محذوف' }} --}}
    @else
            -
    @endif
</td>
<td class="text-nowrap text-center">
    <a href="javascript:;" data-bs-target="#createFollowUp{{ $client_row->id }}" data-bs-toggle="modal"
        class="btn btn-sm btn-primary mb-1">
        {{ trans('common.CreateNewFollowUp') }}
    </a>
    <div class="col-12"></div>
    <a href="{{ route('admin.followups', ['client_id' => $client_row->id]) }}" class="btn btn-icon btn-sm btn-success"
        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ trans('common.followups') }}">
        <i data-feather='list'></i>
    </a>
    @if (userCan('clients_edit_call_center'))
        <a href="javascript:;" data-bs-target="#editclient{{ $client_row->id }}" data-bs-toggle="modal"
            class="btn btn-icon btn-sm btn-info">
            <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ trans('common.edit') }}">
                <i data-feather='edit'></i>
            </span>
        </a>
    @endif
    <?php $delete = route('admin.clients.delete', ['id' => $client_row->id]); ?>
    <button type="button" class="btn btn-icon btn-sm btn-danger"
        onclick="confirmDelete('{{ $delete }}','{{ $client_row->id }}')">
        <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="{{ trans('common.edit') }}">
            <i data-feather='trash-2'></i>
        </span>
    </button>
</td>
<style>
    .test2 {
        position: absolute;
        bottom: -30px;
        color: black;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: 0.4s;
    }

    .test:hover .test2 {
        opacity: 1;
    }
</style>
