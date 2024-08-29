<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            @if (getSettingValue('logo_light') != '')
                <img src="{{ getSettingImageLink('logo_light') }}" height="55">
            @endif
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="@if (isset($active) && $active == 'panelHome') active @endif nav-item">
                <a class="d-flex align-items-center" href="{{ route('admin.index') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="{{ trans('common.PanelHome') }}">
                        {{ trans('common.PanelHome') }}
                    </span>
                </a>
            </li>
            @if (userCan('view_settings'))
                <li class="nav-item @if (isset($active) && $active == 'setting') active @endif">
                    <a class="d-flex align-items-center" href="{{ route('admin.settings.general') }}">
                        <i data-feather='settings'></i>
                        <span class="menu-title text-truncate" data-i18n="{{ trans('common.setting') }}">
                            {{ trans('common.setting') }}
                        </span>
                    </a>
                </li>
            @endif
            @if (userCan('users_view') || userCan('roles_view'))
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="shield"></i>
                        <span class="menu-title text-truncate" data-i18n="{{ trans('common.UsersManagment') }}">
                            {{ trans('common.UsersManagment') }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        @if (userCan('users_view'))
                            <li @if (isset($active) && $active == 'adminUsers') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.adminUsers') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.AdminUsers') }}">
                                        {{ trans('common.users') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('roles_view'))
                            <li @if (isset($active) && $active == 'roles') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.roles') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.Roles') }}">
                                        {{ trans('common.Roles') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (userCan('managements_view') ||
                    userCan('jobs_view') ||
                    userCan('employees_account_view') ||
                    userCan('attendance_view'))
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='file-text'></i>
                        <span class="menu-title text-truncate" data-i18n="{{ trans('common.HrDep') }}">
                            {{ trans('common.HrDep') }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        @if (userCan('managements_view'))
                            <li @if (isset($active) && $active == 'managements') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.managements') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.managements') }}">
                                        {{ trans('common.managements') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('jobs_view'))
                            <li @if (isset($active) && $active == 'jobs') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.jobs') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.jobs') }}">
                                        {{ trans('common.jobs') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('employees_account_view'))
                            <li @if (isset($active) && $active == 'salaries') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.salaries') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.salaries') }}">
                                        {{ trans('common.salaries') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('attendance_view'))
                            <li @if (isset($active) && $active == 'attendance') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.attendance') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.attendance') }}">
                                        {{ trans('common.attendance') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='users'></i>
                    <span class="menu-title text-truncate" data-i18n="{{ trans('common.broker&owner') }}">
                        {{ trans('common.broker&owner') }}
                    </span>
                </a>

                <ul class="menu-content">
                    <li @if (isset($active) && $active == 'owner') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.owner') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.owner') }}">
                                {{ trans('common.owner') }}
                            </span>
                        </a>
                    </li>
                </ul>

                <ul class="menu-content">
                    <li @if (isset($active) && $active == 'broker') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.broker') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.broker') }}">
                                {{ trans('common.broker') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='home'></i>
                    <span class="menu-title text-truncate" data-i18n="{{ trans('common.cityAndRegion') }}">
                        {{ trans('common.cityAndRegion') }}
                    </span>
                </a>
                <ul class="menu-content">
                    <li @if (isset($active) && $active == 'cities') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.governorates') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.governorates') }}">
                                {{ trans('common.governorates') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='briefcase'></i>
                    <span class="menu-title text-truncate" data-i18n="{{ trans('common.projects&units') }}">
                        {{ trans('common.projects&units') }}
                    </span>
                </a>
                <ul class="menu-content">
                    <li @if (isset($active) && $active == 'unitType') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.unitType') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.unitType') }}">
                                {{ trans('common.unitType') }}
                            </span>
                        </a>
                    </li>
                    <li @if (isset($active) && $active == 'units') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.units') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.units') }}">
                                {{ trans('common.units') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- <li class=" nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='briefcase'></i>
                    <span class="menu-title text-truncate" data-i18n="{{ trans('common.projects&units') }}">
                        {{ trans('common.projects&units') }}
                    </span>
                </a>
                <ul class="menu-content">
                    <li @if (isset($active) && $active == 'projects') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.projects') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.projects') }}">
                                {{ trans('common.projects') }}
                            </span>
                        </a>
                    </li>
                    <li @if (isset($active) && $active == 'units') class="active" @endif>
                        <a class="d-flex align-items-center" href="{{ route('admin.units') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate" data-i18n="{{ trans('common.units') }}">
                                {{ trans('common.units') }}
                            </span>
                        </a>
                    </li>
                </ul>
            </li> --}}



            <?php /*
            @if(userCan('contracts_view') || userCan('contracts_view_branch') || userCan('contracts_view_mine'))
                <li class="nav-item @if(isset($active) && $active == 'contracts') active @endif">
                    <a class="d-flex align-items-center" href="{{route('admin.contracts.index')}}">
                        <i data-feather='list'></i>
                        <span class="menu-title text-truncate" data-i18n="{{trans('common.contracts')}}">
                            {{trans('common.contracts')}}
                        </span>
                    </a>
                </li>
            @endif
            */
            ?>
            @if (userCan('clients_view') ||
                    userCan('clients_view_team') ||
                    userCan('clients_view_mine_only') ||
                    userCan('followups_view') ||
                    userCan('followups_view_team') ||
                    userCan('followups_view_mine_only'))
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='users'></i>
                        <span class="menu-title text-truncate" data-i18n="{{ trans('common.clients') }}">
                            {{ trans('common.clients&FollowUps') }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        @if (userCan('client_sources_view'))
                            <li @if (isset($active) && $active == 'clientSources') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.clients.sources') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.clients.sources') }}">
                                        {{ trans('common.clientSources') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('clients_view') || userCan('clients_view_call_center'))
                            <li @if (isset($active) && $active == 'possibleClients') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.possibleClients') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.possibleClients') }}">
                                        {{ trans('common.callCenter') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('clients_view') || userCan('clients_view_reception'))
                            <li @if (isset($active) && $active == 'receptionClients') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.receptionClients') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.receptionClients') }}">
                                        {{ trans('common.receptionClients') }}
                                    </span>
                                </a>
                            </li>
                        @endif

                        @if (userCan('clients_view') || userCan('clients_view_reception'))
                            <li @if (isset($active) && $active == 'salesMangerClients') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.salesManger') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.salesMangerClients') }}">
                                        {{ trans('common.salesMangerClients') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('clients_view') || userCan('clients_view_current') || userCan('clients_view_mine_only'))
                            <li @if (isset($active) && $active == 'clients') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.clients') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.clients') }}">
                                        {{ trans('common.currentClients') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('clients_view') || userCan('clients_view_contracts') || userCan('clients_view_mine_only'))
                            <li @if (isset($active) && $active == 'clientsContracts') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.clients.contracts') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.clientsContracts') }}">
                                        {{ trans('common.clientsContracts') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('clients_view') || userCan('clients_view_archive'))
                            <li @if (isset($active) && $active == 'clientsArchive') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.clients.archive') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.clientsArchive') }}">
                                        {{ trans('common.clientsArchive') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (userCan('reports_rejectionCauses') || userCan('reports_teamPerformance') || userCan('reports_clients'))
                <li class=" nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='layers'></i>
                        <span class="menu-title text-truncate" data-i18n="{{ trans('common.reports') }}">
                            {{ trans('common.reports') }}
                        </span>
                    </a>
                    <ul class="menu-content">
                        @if (userCan('reports_rejectionCauses'))
                            <li @if (isset($active) && $active == 'rejectionCauses') class="active" @endif>
                                <a class="d-flex align-items-center"
                                    href="{{ route('admin.reports.rejectionCauses') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.rejectionCauses') }}">
                                        {{ trans('common.rejectionCauses') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('reports_teamPerformance'))
                            <li @if (isset($active) && $active == 'teamPerformance') class="active" @endif>
                                <a class="d-flex align-items-center"
                                    href="{{ route('admin.reports.teamPerformance') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate"
                                        data-i18n="{{ trans('common.teamPerformance') }}">
                                        {{ trans('common.teamPerformance') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('reports_clients'))
                            <li @if (isset($active) && $active == 'reports_clients') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.reports.clients') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.clients') }}">
                                        {{ trans('common.clients') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                        @if (userCan('reports_units'))
                            <li @if (isset($active) && $active == 'reports_units') class="active" @endif>
                                <a class="d-flex align-items-center" href="{{ route('admin.reports.units') }}">
                                    <i data-feather='circle'></i>
                                    <span class="menu-item text-truncate" data-i18n="{{ trans('common.units') }}">
                                        {{ trans('common.units') }}
                                    </span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
