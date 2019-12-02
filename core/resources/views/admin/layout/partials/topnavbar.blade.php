<header class="app-header"><a target="_blank" class="app-header__logo" href="">{{$gs->website_title}}</a>
  <!-- Sidebar toggle button--><a style="padding-top: 14px;" class="app-sidebar__toggle fas fa-bars" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
  <!-- Navbar Right Menu-->
  <ul class="app-nav">
    @include('notification_bell')
    <!-- User Menu-->
    <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">{{Auth::guard('admin')->user()->first_name.' '.Auth::guard('admin')->user()->last_name}} <i class="fas fa-caret-down"></i></a>
      <ul class="dropdown-menu settings-menu dropdown-menu-right">
        <li><a class="dropdown-item" href="{{route('admin.editProfile', Auth::guard('admin')->user()->id)}}"><i class="icon-pencil icons"></i> Edit Profile</a></li>
        <li><a class="dropdown-item" href="{{route('admin.changePass')}}"><i class="icon-lock icons"></i> Change Password</a></li>
        <li><a class="dropdown-item" href="{{route('admin.logout')}}"><i class="icon-logout icons "></i> Logout</a></li>
      </ul>
    </li>
  </ul>
</header>
