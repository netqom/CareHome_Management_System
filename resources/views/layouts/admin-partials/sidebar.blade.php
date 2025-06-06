<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    {{-- <img alt="image" class="" src="{{ asset('dashboard-logo.png') }}"/> --}}
                    <a href="{{ route('home') }}">
                        {{-- <img alt="image" class="" src="{{ asset('logo-backend.png') }}" /> --}}
                        <img alt="image" class="" src="{{ asset('logo.svg') }}" style="width:50px; height:50px;"/>
                    </a>
                </div>
                <div class="logo-element">
                    DOCRYT
                </div>
            </li>
            <li class="{{ checkactivepage('dashboard', 1, 'active') }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="{{ checkactivepage('homes', 1, 'active') }}">
                <a href="{{ route('homes.index') }}">
                    <img src="{{ asset('assets/img/icons/carehome-icon.png') }}" class="manual-icon">
                    <span class="nav-label">Care Homes</span>
                </a>
            </li>
            @if (Auth::user()->role_id == 1)
                <li class="{{ checkactivepage('care-home-admin', 1, 'active') }}">
                    <a href="{{ route('care-home-admin') }}">
                        <img src="{{ asset('assets/img/icons/carehome-icon.png') }}" class="manual-icon">
                        <span class="nav-label">Care Homes Admin</span>
                    </a>
                </li>
                <li class="{{ checkactivepage('subscription-plans', 1, 'active') }}">
                    <a href="{{ route('subscription_plans.index') }}">
                        <i class="fa fa-diamond"></i> <span class="nav-label">Subscription Plans</span>
                    </a>
                </li>
                <li class="{{ checkactivepage('discounts', 1, 'active') }}">
                    <a href="{{ route('discounts.index') }}">
                        <i class="fa fa-percent"></i> <span class="nav-label">Discounts Managment</span>
                    </a>
                </li>
                <li class="{{ checkactivepage('coupons', 1, 'active') }}">
                    <a href="{{ route('coupons.index') }}">
                        <img src="{{ asset('assets/img/icons/coupon.png') }}" class="manual-icon"> <span
                            class="nav-label">Coupons Managment</span>
                    </a>
                </li>
            @endif
            <li class="{{ checkactivepage('users', 1, 'active') }}">
                <a href="{{ route('users.index') }}">
                    <i class="fa fa-users"></i> <span class="nav-label">
                        @if (Auth::user()->role_id == 1)
                            Users
                        @else
                            Staff
                        @endif Management
                    </span>
                </a>
            </li>
            @if (Auth::user()->role_id == 2)
                <li class="{{ checkactivepage('patients', 1, 'active') }}">
                    <a href="{{ route('patients.index') }}">
                        <i class="fa fa-users"></i> <span class="nav-label">Patient Management</span>
                    </a>
                </li>
            @endif
            <li class="{{ checkactivepage('revenues', 1, 'active') }}">
                <a href="{{ route('revenues.index') }}">
                    {{-- <i class="fa fa-usd"></i>  --}}
                    <img src="{{ asset('assets/img/icons/revenue-icon.png') }}" class="manual-icon">
                    <span class="nav-label">Revenue Management</span>
                </a>
            </li>
            @if (Auth::user()->role_id == 2)
                <li class="{{ checkactivepage('expenses', 1, 'active') }}">
                    <a href="{{ route('expenses.index') }}">
                        <img src="{{ asset('assets/img/icons/expenses-icon.png') }}" class="manual-icon"> 
						<span class="nav-label">Expenses Management</span>
                    </a>
                </li>
            @endif
            <li class="{{ checkactivepage('chat', 1, 'active') }}">
                <a href="{{ route('chat.index') }}">
                    <i class="fa fa-commenting fs-16"></i><span class="nav-label">Chat  <span id="chat-count"></span> {!! chatMessageUserCount() > 0 ? '<i class="fa fa-circle position-absolute" style="font-size: 7px; color: #1ab394 !important;"></i>' : '' !!}</span>
                </a>
            </li>

            @if (Auth::user()->role_id == 1)
                <li class="{{ checkactivepage('push-notification', 1, 'active') }}">
                    <a href="{{ route('push-notification') }}">
                        <i class="fa fa-bell fs-16"></i><span class="nav-label">Push Notification</span>
                    </a>
                </li>
            @endif

            {{-- @if (Auth::user()->role_id == 1) --}}
            <li class="{{ checkactivepage('activities', 1, 'active') }}">
                <a href="{{ route('activities.index') }}">
                    <i class="fa fa-list"></i> <span class="nav-label">Activity Management</span>
                </a>
            </li>

            @if (Auth::user()->role_id == 1)
                <li class="{{ checkactivepage('faqs', 1, 'active') }}">
                    <a href="{{ route('faqs.index') }}">
                        <i class="fa fa-question-circle"></i> <span class="nav-label">Faq Management</span>
                    </a>
                </li>
                <li class="{{ checkactivepage('pages', 1, 'active') }}">
                    <a href="{{ route('pages.index') }}">
                        <i class="fa fa-file-text"></i> <span class="nav-label">Pages Management</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->role_id == 2)
                <li class="{{ checkactivepage('training-course-list', 1, 'active') }}">
                    <a href="{{ route('training-course-list') }}">
                        <i class="fa fa-list-alt fs-16" aria-hidden="true"></i><span class="nav-label">Training
                            Course</span>
                    </a>
                </li>
                <li class="{{ checkactivepage('tasks-list', 1, 'active') }}">
                    <a href="{{ route('tasks-list') }}">
                        <i class="fa fa-list-alt fs-16" aria-hidden="true"></i><span class="nav-label">Tasks
                            Management</span>
                    </a>
                </li>
                <li class="{{ checkactivepage('staff-activity-history-list', 1, 'active') }}">
                    <a href="{{ route('staff-activity-history-list') }}">
                        <i class="fa fa-list-alt fs-16" aria-hidden="true"></i><span class="nav-label">Client & Activity
                            History</span>
                    </a>
                </li>
            @endif
            <li class="{{ checkactivepage('incident-reports-list', 1, 'active') }}">
                <a href="{{ route('incident-reports-list') }}">
                    <i class="fa fa-list-alt fs-16" aria-hidden="true"></i><span class="nav-label">Incident
                        Reports</span>
                </a>
            </li>

            @if (Auth::user()->role_id == 1)
                <li class="{{ checkactivepage('email-logs', 1, 'active') }}">
                    <a href="{{ route('email-logs.index') }}">
                        <i class="fa fa-envelope"></i> <span class="nav-label">Email Logs</span>
                    </a>
                </li>
            @endif
        </ul>

    </div>
</nav>
