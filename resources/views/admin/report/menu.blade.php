<aside class="main-sidebar sidebar-dark-primary elevation-4 overflow-y-scroll-auto" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">
            {{ config('app.name', 'LMS') }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (auth()->user()->is_superadmin)
                    <li>
                        <select class="searchable-field form-control">

                        </select>
                    </li>
                    <li class="mt-2 mb-2">
                        <select class="form-control mt-2" multiple name="__global_clients_filter"
                            id="__global_clients_filter">
                            @foreach ($__global_clients_drpdwn as $id => $name)
                                <option value="{{ $id }}" @if (!empty($__global_clients_filter) && in_array($id, $__global_clients_filter)) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                        href="{{ route('admin.home') }}">
                        <i class="fas fa-fw fa-tachometer-alt nav-icon">
                        </i>
                        <p>
                            {{ trans('global.dashboard') }}
                        </p>
                    </a>
                </li>
                @if (auth()->user()->checkPermission('webhook'))
                    <li
                        class="nav-item has-treeview {{ request()->routeIs('admin.webhook.new.lead.log') ? 'menu-open' : '' }} {{ request()->routeIs('admin.webhook.lead.activities.log') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->routeIs('admin.webhook.new.lead.log') ? 'active' : '' }} {{ request()->routeIs('admin.webhook.lead.activities.log') ? 'active' : '' }}"
                            href="#">
                            <i class="fas fa-satellite-dish nav-icon fa-fw"></i>
                            <p>
                                {{ trans('messages.webhook') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon ml-3"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.webhook.new.lead.log') ? 'active' : '' }}"
                                    href="{{ route('admin.webhook.new.lead.log') }}">
                                    <i class="fas fa-handshake nav-icon fa-fw"></i>
                                    <p>
                                        @lang('messages.new_lead')
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.webhook.lead.activities.log') ? 'active' : '' }}"
                                    href="{{ route('admin.webhook.lead.activities.log') }}">
                                    <i class="fas fa-history nav-icon fa-fw"></i>
                                    <p>
                                        @lang('messages.lead_activities')
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('document_view') ||
                        auth()->user()->checkPermission('document_send'))
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/documents*') ? 'menu-open' : '' }} {{ request()->routeIs('admin.get.documents.log') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/documents*') ? 'active' : '' }} {{ request()->routeIs('admin.get.documents.log') ? 'active' : '' }}"
                            href="#">
                            <i class=" fa-fw nav-icon fas fa-folder-open"></i>
                            <p>
                                {{ trans('messages.documents_management') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon ml-3"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->checkPermission('document_view'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"
                                        href="{{ route('admin.documents.index') }}">
                                        <i class="fas fa-question-circle nav-icon fa-fw"></i>
                                        <p>
                                            {{ trans('messages.documents') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->checkPermission('document_send'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.get.documents.log') ? 'active' : '' }}"
                                        href="{{ route('admin.get.documents.log') }}">
                                        <i class="fas fa-history nav-icon fa-fw"></i>
                                        <p>
                                            {{ trans('messages.document_logs') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('user_view') ||
                        auth()->user()->checkPermission('audit_log_view'))
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }} {{ request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }} {{ request()->is('admin/audit-logs*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->checkPermission('user_view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-user">

                                        </i>
                                        <p>
                                            {{ trans('cruds.user.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->checkPermission('audit_log_view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">

                                        </i>
                                        <p>
                                            {{ trans('cruds.auditLog.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('client_view'))
                    <li class="nav-item has-treeview {{ request()->is('admin/clients*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/clients*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-briefcase">

                            </i>
                            <p>
                                {{ trans('cruds.clientManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.clients.index') }}"
                                    class="nav-link {{ request()->is('admin/clients') || request()->is('admin/clients/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-briefcase">

                                    </i>
                                    <p>
                                        {{ trans('cruds.client.title') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('agency_view'))
                    <li class="nav-item has-treeview {{ request()->is('admin/agencies*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/agencies*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-tv">

                            </i>
                            <p>
                                {{ trans('cruds.agencyManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.agencies.index') }}"
                                    class="nav-link {{ request()->is('admin/agencies') || request()->is('admin/agencies/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-tv">

                                    </i>
                                    <p>
                                        {{ trans('cruds.agency.title') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('project_view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.projects.index') }}"
                            class="nav-link {{ request()->is('admin/projects') || request()->is('admin/projects/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-project-diagram">

                            </i>
                            <p>
                                {{ trans('cruds.project.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('campaign_view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.campaigns.index') }}"
                            class="nav-link {{ request()->is('admin/campaigns') || request()->is('admin/campaigns/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-bullhorn">

                            </i>
                            <p>
                                {{ trans('cruds.campaign.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('source_view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.sources.index') }}"
                            class="nav-link {{ request()->is('admin/sources') || request()->is('admin/sources/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-external-link-alt"></i>
                            <p>
                                {{ trans('cruds.source.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('lead_view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.leads.index', ['view' => 'list']) }}" id="lead_menu_link"
                            class="nav-link {{ request()->is('admin/leads') || request()->is('admin/leads/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-handshake">

                            </i>
                            <p>
                                {{ trans('cruds.lead.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('eoi_view'))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.eoi.*') ? 'active' : '' }}"
                            href="{{ route('admin.eoi.index') }}">
                            <i class="fas fa-wind nav-icon"></i>
                            <p>
                                {{ trans('messages.expression_of_interest') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->checkPermission('merlom_view'))
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.merlom-leads.*') ? 'active' : '' }}"
                            href="{{ route('admin.merlom-leads.index') }}">
                            <i class="fas fa-wind nav-icon"></i>
                            <p>
                                Merlom Leads
                            </p>
                        </a>
                    </li> --}}
                @endif
                @if (auth()->user()->checkPermission('eoi_view'))
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
                            href="{{ route('admin.bookings.index') }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>
                                Booking Form
                            </p>
                        </a>
                    </li> --}}

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.booking.*') ? 'active' : '' }}"
                            href="{{ route('admin.booking.index') }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <p>
                                Booking Form
                            </p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('admin.aztec.index') }}"
                            class="nav-link {{ request()->is('admin/enquiry-form') || request()->is('admin/enquiry-form/*') ? 'active' : '' }}">
                            <i class="fas fa-stream nav-icon">

                            </i>
                            <p>
                                Walkin Form
                            </p>
                        </a>
                    </li> --}}
                @endif
                @if(auth()->user()->checkPermission('project_view'))
                <li class="nav-item">
                    <a href="{{ route("admin.selldo.index") }}" class="nav-link {{ request()->is("admin/selldo") || request()->is("admin/selldo/*") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-project-diagram">

                        </i>
                        <p>
                        Sell Do Fields
                        </p>
                    </a>
                </li>
            @endif
                @if (auth()->user()->checkPermission('calendar'))
                    <li class="nav-item">
                        <a href="{{ route('admin.systemCalendar') }}"
                            class="nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-calendar nav-icon">

                            </i>
                            <p>
                                {{ trans('global.systemCalendar') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    @if (auth()->user()->checkPermission('profile'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                                href="{{ route('profile.password.edit') }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>
                                    {{ trans('messages.profile') }}
                                </p>
                            </a>
                        </li>
                    @endif
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
