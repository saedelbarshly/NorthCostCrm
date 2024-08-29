<?php
$lang = app()->getLocale();
use Illuminate\Support\Carbon;
$arr = [
    [
        'position' => 'call_center',
        'date' => 'call_center_date',
        'period' => 7,
    ],
    [
        'position' => 'sales',
        'date' => 'sales_date',
        'period' => 14,
    ],
    [
        'position' => 'salesManger',
        'date' => 'sales_manger_date',
        'period' => 14,
    ],
];
$clients = collect();
foreach ($arr as $value) {
    $data = App\Models\Clients::where('position', $value['position'])->where($value['date'],'<=',Carbon::now()->subDays($value['period']))->get();
    $clients = $clients->merge($data);
}
?>
<li class="nav-item dropdown dropdown-notification me-25">
    <a class="nav-link" href="#" data-bs-toggle="dropdown">
        <i class="ficon text-danger" data-feather="bell"></i>
        <span class="badge rounded-pill bg-danger badge-up">
            {{ count($clients) > 99 ? '+99' : count($clients) }}
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
        <li class="dropdown-menu-header">
            <div class="dropdown-header d-flex">
                <h4 class="notification-title mb-0 me-auto">{{ trans('common.Notifications') }}</h4>
                @if (count($clients) > 0)
                    <div class="badge rounded-pill badge-light-primary">{{ count($clients) }} {{ trans('common.New') }}
                    </div>
                @endif
            </div>
        </li>
        <li class="scrollable-container media-list">
            @if (count($clients) > 0)
                @foreach ($clients as $value)

                        @if ($value->position == 'call_center')
                        <a class="d-flex" href="{{ route("admin.possibleClients", ['id' => $value->id]) }}">
                        @elseif ($value->position == 'sales')
                        <a class="d-flex" href="{{ route("admin.clients", ['id' => $value->id]) }}">
                        @elseif ($value->position == 'salesManger')
                        <a class="d-flex" href="{{ route("admin.salesManger", ['id' => $value->id]) }}">
                        @endif
                        <div class="list-item d-flex align-items-start">
                            {{-- <div class="list-item-body flex-grow-1">
                                <p class="media">
                                    {{ $value->Name }}
                                </p>
                                <small class="notification-text">{{ clientDuration($value->Name,$value->position,$value->cellphone,$lang) }}</small>
                            </div> --}}

                            <div class="list-item d-flex align-items-start">
                                <div class="me-1">
                                    <p class="media text-primary">
                                        {{ $value->Name }}
                                    </p>
                                </div>
                                <div class="list-item-body flex-grow-1">
                                    {{-- <p class="media-heading">
                                        {!!$notification['data']['text']!!}
                                    </p> --}}
                                    <small class="notification-text">{{ clientDuration($value->Name,$value->position,$value->cellphone,$lang) }}</small>
                                </div>
                            </div>

                        </div>
                    </a>
                @endforeach
            @else
                <div class="p-1 text-center">
                    <b>{{ trans('common.nothingToView') }}</b>
                </div>
            @endif
        </li>
        {{-- <li class="dropdown-menu-footer">
            <a class="btn btn-primary w-100"
                href="{{ route('admin.nextFollowups') }}">{{ trans('common.Read all notifications') }}</a>
        </li> --}}
    </ul>
</li>
