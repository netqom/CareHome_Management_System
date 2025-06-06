<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
    </div>
        <ul class="nav navbar-top-links navbar-right">
			<li>
				<span class="block m-t-xs font-bold">{{ Auth::user()->name }}</span>
				<span class="text-muted text-xs block">{{ Auth::user()->role_name->name }}</span>
			</li>
            <li>
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
					@php 
						$auth_img = asset('assets/img/profile-pic.jpg');
						if(!is_null(Auth::user()->profile_image)){
							$auth_img = asset(Auth::user()->profile_image);
						}
					 @endphp
					<img alt="image" class="rounded-circle" height="48px;" width="48px;" src="{{ $auth_img }}"/>
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu animated fadeInRight m-t-xs header-dropdown" style="right: 7px; margin-top: -11px;">
					<li><a class="dropdown-item" href="{{ route('profile.edit')}}">Profile</a></li>
					<li class="dropdown-divider"></li>
					<li>
						<a class="dropdown-item" href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout_form').submit();">{{ __('Log Out') }}</a>
						<form method="POST" action="{{ route('logout') }}" id="logout_form" class="d-none">
							@csrf
						</form>
					</li>
				</ul>
            </li>
        </ul>

</nav>

 
